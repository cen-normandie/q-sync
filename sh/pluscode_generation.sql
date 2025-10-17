--alphabet [2,3,4,5,6,7,8,9,C,F,G,H,J,M,P,Q,R,V,W,X]
DROP TABLE IF EXISTS sh.pluscode;
CREATE TABLE IF NOT EXISTS sh.pluscode
(
    lat_lo numeric,
    lng_lo numeric,
    lat_hi numeric,
    lng_hi numeric,
    code_length numeric,
    lat_center numeric,
    lng_center numeric,
    pluscode text COLLATE pg_catalog."default" NOT NULL,
    wkt_geom text COLLATE pg_catalog."default",
    geom geometry,
    CONSTRAINT pkey_pc PRIMARY KEY (pluscode)
);


--11 digits pluscode area
--2
INSERT INTO sh.pluscode(
	lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C2'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C2') as a ;
--3
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C3'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C3') as a ;
--4
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C4'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C4') as a ;
--5
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C5'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C5') as a ;
--6
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C6'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C6') as a ;
--7
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C7'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C7') as a ;
--8
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C8'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C8') as a ;
--9
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C9'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C9') as a ;
--C
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8CC'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8CC') as a ;
--F
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8CF'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8CF') as a ;
--G
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8CG'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8CG') as a ;
--H
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8CH'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8CH') as a ;
--J
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8CJ'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8CJ') as a ;
--M
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8CM'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8CM') as a ;
--P
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8CP'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8CP') as a ;
--Q
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)    
select a.*, '8FX2CF4G+8CQ'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8CQ') as a ;
--R
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8CR'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8CR') as a ;
--V
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8CV'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8CV') as a ;
--W
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)    
select a.*, '8FX2CF4G+8CW'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8CW') as a ;
--X
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8CX'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8CX') as a ;

--12 digits pluscode area
--2
INSERT INTO sh.pluscode(
	lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C22'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C22') as a ;
--3
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C23'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C23') as a ;
--4
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C24'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C24') as a ;
--5
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C25'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C25') as a ;
--6
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C26'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C26') as a ;
--7
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C27'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C27') as a ;
--8
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C28'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C28') as a ;
--9
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C29'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C29') as a ;
--C
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C2C'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C2C') as a ;
--F
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C2F'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C2F') as a ;
--G
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C2G'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C2G') as a ;
--H
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C2H'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C2H') as a ;
--J
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C2J'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C2J') as a ;
--M
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C2M'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C2M') as a ;
--P
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C2P'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C2P') as a ;
--Q
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C2Q'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C2Q') as a ;
--R
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C2R'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C2R') as a ;
--V
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C2V'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C2V') as a ;
--W
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C2W'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C2W') as a ;
--X
INSERT INTO sh.pluscode(
    lat_lo, lng_lo, lat_hi, lng_hi, code_length, lat_center, lng_center, pluscode, wkt_geom)
select a.*, '8FX2CF4G+8C2X'::text as pluscode, ''::text as wkt_geom from pluscode_decode('8FX2CF4G+8C2X') as a ;


update sh.pluscode set geom= st_setsrid(
 st_geomfromtext('POLYGON(('||lng_lo||' '||lat_lo||','||lng_lo||' '||lat_hi||','||lng_hi||' '||lat_hi||','||lng_hi||' '||lat_lo||','||lng_lo||' '||lat_lo||'))')
 ,4326)
;

