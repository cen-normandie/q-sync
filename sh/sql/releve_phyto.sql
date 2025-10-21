DROP FUNCTION IF EXISTS sh.ind_eco_releve_phyto(text[], text[], text);
CREATE OR REPLACE FUNCTION sh.ind_eco_releve_phyto(
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
            WHEN SUM(r.coef_relative) > 0 THEN
                -SUM(r.coef_relative * LOG(r.coef_relative))
            ELSE NULL
        END AS indice_shannon,
        CASE
            WHEN COUNT(DISTINCT r.taxon) > 1 THEN
                (-SUM(r.coef_relative * LOG(r.coef_relative))) / LOG(COUNT(DISTINCT r.taxon))
            ELSE NULL
        END AS equitabilite
    FROM (
        SELECT
            rp.annee,
            rp.id_releve,
            rp.site,
            rp.taxon,
            SUM(rp.coefficient_int)::INTEGER AS coef_total,
            SUM(rp.coefficient_int)::FLOAT / SUM(SUM(rp.coefficient_int)) OVER (PARTITION BY rp.annee, rp.id_releve, rp.site) AS coef_relative
        FROM sh.releve_phyto rp
        WHERE rp.annee = ANY(annee_param)
          AND rp.id_releve = ANY(id_releve_param)
          AND rp.site = site_param
        GROUP BY rp.annee, rp.id_releve, rp.site, rp.taxon
    ) r
    GROUP BY r.annee, r.id_releve, r.site;
END;
$$;


SELECT * FROM sh.ind_eco_releve_phyto(
    ARRAY['2005','2009'],                 -- années
    ARRAY['1A__11', '6H__11'],               -- plots
    '27GAI'                     -- site
);
