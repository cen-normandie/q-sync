DROP FUNCTION IF EXISTS sh.calcul_appartenance_phytosocio(text[], text[], text);

CREATE OR REPLACE FUNCTION sh.calcul_appartenance_phytosocio(
    annee_param text[],
    id_releve_param text[],
    site_param text)
RETURNS TABLE(
    annee text,
    id_releve text,
    site text,
    nom_taxon TEXT,
    association TEXT,
    alliance TEXT,
    ordre TEXT,
    classe TEXT
) 
LANGUAGE plpgsql
COST 100
VOLATILE PARALLEL UNSAFE
ROWS 1000
AS $BODY$
BEGIN
    RETURN QUERY
        SELECT
        t.id_releve,
        t.nom_taxon,
        p.association,
        p.alliance,
        p.ordre,
        p.classe
    FROM
        taxons_observes t
    JOIN
        releves r ON t.id_releve = r.id_releve
    LEFT JOIN
        phytosocio_ref p ON LOWER(t.nom_taxon) = LOWER(p.nom_taxon)
    WHERE
        (_annees IS NULL OR r.annee::TEXT = ANY(_annees))
        AND (_id_releves IS NULL OR t.id_releve = ANY(_id_releves));
    GROUP BY r.annee, r.id_releve, r.site;
END;
$BODY$;
SELECT * FROM sh.calcul_appartenance_phytosocio(
    ARRAY['2021', '2022'],         -- années
    ARRAY['R1__11', 'R2__11'],     -- plots
    '0077_061'                     -- site
);

