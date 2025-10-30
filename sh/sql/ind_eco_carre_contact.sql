DROP FUNCTION IF EXISTS sh.ind_eco_carre_contact(text[], text[], text);

CREATE OR REPLACE FUNCTION sh.ind_eco_carre_contact(
    annee_param text[],
    id_releve_param text[],
    site_param text)
RETURNS TABLE(
    annee text,
    id_releve text,
    site text,
    richesse_specifique integer,
    indice_shannon double precision,
    equitabilite double precision
) 
LANGUAGE plpgsql
COST 100
VOLATILE PARALLEL UNSAFE
ROWS 1000
AS $BODY$
BEGIN
    RETURN QUERY
    SELECT
        r.annee,
        r.id_releve,
        r.site,
        COUNT(DISTINCT r.taxon)::INTEGER AS richesse_specifique,
        CASE
            WHEN SUM(CASE WHEN r.freq_relative > 0 THEN r.freq_relative ELSE 0 END) > 0 THEN
                -SUM(CASE WHEN r.freq_relative > 0 THEN r.freq_relative * LOG(r.freq_relative) ELSE 0 END)
            ELSE NULL
        END AS indice_shannon,
        CASE
            WHEN COUNT(DISTINCT r.taxon) > 1 THEN
                (-SUM(CASE WHEN r.freq_relative > 0 THEN r.freq_relative * LOG(r.freq_relative) ELSE 0 END)) / LOG(COUNT(DISTINCT r.taxon))
            ELSE NULL
        END AS equitabilite
    FROM (
        SELECT
            rc.annee,
            rc.id_releve,
            rc.site,
            rc.taxon,
            COUNT(*)::INTEGER AS freq,
            COUNT(*)::FLOAT / SUM(COUNT(*)) OVER (PARTITION BY rc.annee, rc.id_releve, rc.site) AS freq_relative
        FROM sh.carre_contact rc
        WHERE rc.annee = ANY(annee_param)
          AND rc.id_releve = ANY(id_releve_param)
          AND rc.site = site_param
        GROUP BY rc.annee, rc.id_releve, rc.site, rc.taxon
    ) r
    GROUP BY r.annee, r.id_releve, r.site;
END;
$BODY$;
SELECT * FROM sh.ind_eco_carre_contact(
    ARRAY['2021', '2022'],         -- années
    ARRAY['R1__11', 'R2__11'],     -- plots
    '0077_061'                     -- site
);
