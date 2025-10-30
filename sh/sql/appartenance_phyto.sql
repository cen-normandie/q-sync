DROP FUNCTION IF EXISTS sh.calcul_appartenance_carre_contact(text[], text[], text);

CREATE OR REPLACE FUNCTION sh.calcul_appartenance_carre_contact(
	annee_param text[],
	id_releve_param text[],
	site_param text)
    RETURNS TABLE(annee text, appartenance_phyto text, pourcentage numeric, color_etea text) 
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE PARALLEL UNSAFE
    ROWS 1000

AS $BODY$
BEGIN
    RETURN QUERY
    WITH taxons AS (
        SELECT DISTINCT r.annee AS annee_taxon,
                        r.id_releve AS id_releve_taxon,
                        r.cd_nom AS cd_nom_taxon
        FROM sh.carre_contact AS r
        WHERE (annee_param IS NULL OR r.annee::text = ANY(annee_param))
          AND (id_releve_param IS NULL OR r.id_releve = ANY(id_releve_param))
          AND (site_param IS NULL OR r.site = site_param)
    ),
    mapping AS (
        SELECT t.annee_taxon AS annee_mapping,
               COALESCE(s.appartenance_phyto, 'Indéterminé') AS appartenance_mapping,
               COALESCE(s.color_etea, '#CCCCCC') AS color_mapping
        FROM taxons AS t
        LEFT JOIN sh._appartenance AS a ON a.id_taxon = t.cd_nom_taxon::int
        LEFT JOIN sh._synsystematique AS s ON s.id = a.id_synsystematique
    )
    SELECT
        mapping.annee_mapping AS annee,
        mapping.appartenance_mapping AS appartenance_phyto,
        ROUND(COUNT(*)::numeric / SUM(COUNT(*)) OVER (PARTITION BY mapping.annee_mapping) * 100, 1) AS pourcentage,
        mapping.color_mapping AS color_etea
    FROM mapping
    GROUP BY mapping.annee_mapping, mapping.appartenance_mapping, mapping.color_mapping
    ORDER BY mapping.annee_mapping, pourcentage DESC;
END;
$BODY$;



DROP FUNCTION IF EXISTS sh.calcul_appartenance_releve_phyto(text[], text[], text);

CREATE OR REPLACE FUNCTION sh.calcul_appartenance_releve_phyto(
	annee_param text[],
	id_releve_param text[],
	site_param text)
    RETURNS TABLE(annee text, appartenance_phyto text, pourcentage numeric, color_etea text) 
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE PARALLEL UNSAFE
    ROWS 1000

AS $BODY$
BEGIN
    RETURN QUERY
    WITH taxons AS (
        SELECT DISTINCT r.annee AS annee_taxon,
                        r.id_releve AS id_releve_taxon,
                        r.cd_nom AS cd_nom_taxon
        FROM sh.releve_phyto AS r
        WHERE (annee_param IS NULL OR r.annee::text = ANY(annee_param))
          AND (id_releve_param IS NULL OR r.id_releve = ANY(id_releve_param))
          AND (site_param IS NULL OR r.site = site_param)
    ),
    mapping AS (
        SELECT t.annee_taxon AS annee_mapping,
               COALESCE(s.appartenance_phyto, 'Indéterminé') AS appartenance_mapping,
               COALESCE(s.color_etea, '#CCCCCC') AS color_mapping
        FROM taxons AS t
        LEFT JOIN sh._appartenance AS a ON a.id_taxon = t.cd_nom_taxon::int
        LEFT JOIN sh._synsystematique AS s ON s.id = a.id_synsystematique
    )
    SELECT
        mapping.annee_mapping AS annee,
        mapping.appartenance_mapping AS appartenance_phyto,
        ROUND(COUNT(*)::numeric / SUM(COUNT(*)) OVER (PARTITION BY mapping.annee_mapping) * 100, 1) AS pourcentage,
        mapping.color_mapping AS color_etea
    FROM mapping
    GROUP BY mapping.annee_mapping, mapping.appartenance_mapping, mapping.color_mapping
    ORDER BY mapping.annee_mapping, pourcentage DESC;
END;
$BODY$;




-- Exemple d'appel de la fonction
SELECT * FROM sh.calcul_appartenance_releve_phyto(
    ARRAY['2005','2009'],                 -- années
    ARRAY['1A__11', '6H__11'],               -- plots
    '27GAI'                     -- site
);

-- Exemple d'appel de la fonction
SELECT * FROM sh.calcul_appartenance_carre_contact(
    ARRAY['2021', '2022'],         -- années
    ARRAY['R1__11', 'R2__11'],     -- plots
    '0077_061'                     -- site
);