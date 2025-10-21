DROP FUNCTION IF EXISTS sh.ind_eco_carre_contact(text[], text[], text);
CREATE OR REPLACE FUNCTION sh.ind_eco_carre_contact(
    annee_param TEXT[],
    id_releve_param TEXT[],
    site_param TEXT
)
RETURNS TABLE (
    annee TEXT,
    id_releve TEXT,
    site TEXT,
    richesse_specifique INTEGER,
    indice_shannon DOUBLE PRECISION,
    equitabilite DOUBLE PRECISION
)
LANGUAGE plpgsql AS
$$
BEGIN
    RETURN QUERY
    SELECT
        r.annee,
        r.id_releve,
        r.site,
        COUNT(DISTINCT r.taxon)::INTEGER AS richesse_specifique,
        CASE
            WHEN SUM(r.freq_relative) > 0 THEN
                -SUM(r.freq_relative * LOG(r.freq_relative))
            ELSE NULL
        END AS indice_shannon,
        CASE
            WHEN COUNT(DISTINCT r.taxon) > 1 THEN
                (-SUM(r.freq_relative * LOG(r.freq_relative))) / LOG(COUNT(DISTINCT r.taxon))
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
$$;
SELECT * FROM sh.ind_eco_carre_contact(
    ARRAY['2021', '2022'],         -- années
    ARRAY['R1__11', 'R2__11'],     -- plots
    '0077_061'                     -- site
);
