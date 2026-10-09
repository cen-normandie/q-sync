CREATE TRIGGER IF not exists trg_sites_insert
AFTER INSERT ON sites
BEGIN

INSERT INTO buffer_1km (
    id_site,
    geom
)
VALUES (
    NEW.id_site,
    ST_Buffer(NEW.geom, 1000)
);

INSERT INTO buffer_10km (
    id_site,
    geom
)
VALUES (
    NEW.id_site,
    ST_Buffer(NEW.geom, 10000)
);

INSERT INTO buffer_20km (
    id_site,
    geom
)
VALUES (
    NEW.id_site,
    ST_Buffer(NEW.geom, 20000)
);

END;

CREATE TRIGGER if not exists trg_sites_update
AFTER UPDATE OF geom ON sites
BEGIN

UPDATE buffer_1km
SET
    geom = ST_Buffer(NEW.geom, 1000)
WHERE id_site = NEW.id_site;

UPDATE buffer_10km
SET
    geom = ST_Buffer(NEW.geom, 10000)
WHERE id_site = NEW.id_site;

UPDATE buffer_20km
SET
    geom = ST_Buffer(NEW.geom, 20000)
WHERE id_site = NEW.id_site;

END;

CREATE TRIGGER if not exists trg_sites_delete
AFTER DELETE ON sites
BEGIN

DELETE FROM buffer_1km
WHERE id_site = OLD.id_site;

DELETE FROM buffer_10km
WHERE id_site = OLD.id_site;

DELETE FROM buffer_20km
WHERE id_site = OLD.id_site;

END;