CREATE EXTENSION IF NOT EXISTS postgis;

CREATE TABLE IF NOT EXISTS sandbox.w_
(
    id integer ,
    geom geometry(Geometry,2154),
    fid bigint,
    uuid text primary key,
    commentaire character varying COLLATE pg_catalog."default",
    date_saisie date,
    action character varying COLLATE pg_catalog."default",
    operateur character varying COLLATE pg_catalog."default",
    etat character varying COLLATE pg_catalog."default",
    projet character varying COLLATE pg_catalog."default",
    element character varying COLLATE pg_catalog."default",
    import character varying COLLATE pg_catalog."default",
    nom_site character varying COLLATE pg_catalog."default",
    id_site character varying COLLATE pg_catalog."default",
    surface_longueur integer,
    date_previsionnel_debut date,
    date_previsionnel_fin date,
    courriel character varying COLLATE pg_catalog."default",
    uuid_nx text
)

TABLESPACE pg_default;
