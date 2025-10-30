
--declaration de la source carre_contact dans la table gn_synthese.t_sources
INSERT INTO gn_synthese.t_sources (name_source, desc_source)
VALUES ('carre_contact_custom', 'Import manuel depuis sh.carre_contact');

--suppression des triggers
DROP TRIGGER IF EXISTS trg_insert_synthese_carre_contact_custom;
DROP TRIGGER IF EXISTS trg_update_synthese_carre_contact_custom;

--fonction d'insertion des données de sh.carre_contact dans gn_synthese.synthese
DROP FUNCTION IF EXISTS sh.insert_in_synthese_from_carre_contact_custom(text);
CREATE OR REPLACE FUNCTION sh.insert_in_synthese_from_carre_contact_custom(z_id_releve text)
RETURNS void LANGUAGE plpgsql AS $$
DECLARE
  rec RECORD;
  id_source INTEGER;
BEGIN
  SELECT id_source INTO id_source
  FROM gn_synthese.t_sources
  WHERE name_source = 'carre_contact';

  FOR rec IN
    SELECT *
    FROM sh.carre_contact
    WHERE id_releve = z_id_releve
  LOOP
    INSERT INTO gn_synthese.synthese (
      unique_id_sinp,
      entity_source_pk_value,
      id_source,
      cd_nom,
      observers,
      date_min,
      date_max,
      the_geom_point,
      comment_context,
      last_action
    ) VALUES (
      rec.uuid_contact,
      rec.id::text,
      id_source,
      rec.cd_nom,
      rec.observateur,
      rec.date_,
      rec.date_,
      NULL, -- à remplacer si tu ajoutes une géométrie
      rec.commentaire,
      'I'
    );
  END LOOP;
END;
$$;

--fonction de mise à jour des données de gn_synthese.synthese depuis sh.carre_contact
DROP FUNCTION IF EXISTS sh.update_synthese_from_carre_contact_custom(text);
CREATE OR REPLACE FUNCTION sh.update_synthese_from_carre_contact_custom(z_id_releve text)
RETURNS void LANGUAGE plpgsql AS $$
DECLARE
  rec RECORD;
BEGIN
  FOR rec IN
    SELECT *
    FROM sh.carre_contact
    WHERE id_releve = z_id_releve
  LOOP
    UPDATE gn_synthese.synthese
    SET
      cd_nom = rec.cd_nom,
      observers = rec.observateur,
      date_min = rec.date_,
      date_max = rec.date_,
      comment_context = rec.commentaire,
      last_action = 'U'
    WHERE entity_source_pk_value = rec.id::text
      AND unique_id_sinp = rec.uuid_contact;
  END LOOP;
END;
$$;

-- Insertion
CREATE TRIGGER trg_insert_synthese_carre_contact_custom
AFTER INSERT ON sh.carre_contact
FOR EACH ROW
EXECUTE FUNCTION sh.insert_in_synthese_from_carre_contact_custom(NEW.id_releve);

-- Mise à jour
CREATE TRIGGER trg_update_synthese_carre_contact_custom
AFTER UPDATE ON sh.carre_contact
FOR EACH ROW
EXECUTE FUNCTION sh.update_synthese_from_carre_contact_custom(NEW.id_releve);


-- création de la vue gn_synthese.v_synthese_carre_contact_custom
DROP VIEW IF EXISTS gn_synthese.v_synthese_carre_contact_custom;
CREATE OR REPLACE VIEW gn_synthese.v_synthese_carre_contact_custom AS
SELECT *
FROM gn_synthese.synthese
WHERE id_source = (
  SELECT id_source
  FROM gn_synthese.t_sources
  WHERE name_source = 'carre_contact_custom'
);

-- test de la vue
SELECT * FROM gn_synthese.v_synthese_carre_contact_custom WHERE date_min >= '2025-01-01';