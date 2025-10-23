CREATE OR REPLACE FUNCTION shcalcul_appartenance_phytosocio(
    _annees TEXT[],
    _id_releves TEXT[]
)
RETURNS TABLE (
    id_releve TEXT,
    nom_taxon TEXT,
    association TEXT,
    alliance TEXT,
    ordre TEXT,
    classe TEXT
) AS $$
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
END;
$$ LANGUAGE plpgsql;

SELECT * FROM sh.calcul_appartenance_phytosocio(
    ARRAY['2020', '2006', '2003'],
    ARRAY['T1__R1', 'T1__R2']
);
``