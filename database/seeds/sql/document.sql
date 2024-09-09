-- create table operasional.cara_bayar
CREATE SEQUENCE operasional.cara_bayar_seq;
CREATE TABLE operasional.cara_bayar (
    ID_CR_BYR_O integer NOT NULL DEFAULT nextval('operasional.cara_bayar_seq'),
	NM_CR_BYR_O varchar(64),
	  IS_AKTIF BOOLEAN,
	  UPDATED_at timestamp,
	  ID_USER integer,
	  GR_CR_BAYAR integer DEFAULT 1
);
ALTER SEQUENCE operasional.cara_bayar_seq
OWNED BY operasional.cara_bayar.ID_CR_BYR_O;
ALTER TABLE operasional.cara_bayar ADD PRIMARY KEY (ID_CR_BYR_O);

-- create table master.m_marketing
CREATE SEQUENCE master.marketing_seq;
CREATE TABLE master.m_marketing (
  ID_MARKETING integer NOT NULL DEFAULT nextval('master.marketing_seq'),
  ID_PERUSH integer,
  NM_MARKETING varchar(64),
  NO_HP VarCHAR(16),
created_at timestamp,
  UPDATED_at timestamp,
  ID_USER integer,
  IS_AKTIF BOOLEAN
);
ALTER SEQUENCE master.marketing_seq
OWNED BY master.m_marketing.ID_MARKETING;
ALTER TABLE master.m_marketing ADD PRIMARY KEY (ID_MARKETING);

-- create table operasional.m_packing
CREATE SEQUENCE operasional.packing_seq;
CREATE TABLE operasional.m_packing (
  ID_PACKING integer NOT NULL DEFAULT nextval('operasional.packing_seq'),
  NM_PACKING varchar(64),
  created_at timestamp,
  UPDATED_at timestamp,
  ID_USER integer
);

ALTER SEQUENCE operasional.packing_seq
OWNED BY operasional.m_packing.ID_PACKING;
ALTER TABLE operasional.m_packing ADD PRIMARY KEY (id_packing);

-- create table master.m_group_vendor
CREATE SEQUENCE master.group_ven_seq;
CREATE TABLE master.m_group_vendor (
  ID_GRUP_VEN integer NOT NULL DEFAULT nextval('master.group_ven_seq'),
  NM_GRUP_VEN varchar(32),
  IS_AKTIF BOOLEAN,
  created_at timestamp,
  UPDATED_at timestamp,
  ID_USER integer
);

ALTER SEQUENCE master.group_ven_seq
OWNED BY master.m_group_vendor.ID_GRUP_VEN;
ALTER TABLE master.m_group_vendor ADD PRIMARY KEY (ID_GRUP_VEN);

-- create table master.m_vendor
CREATE SEQUENCE master.vendor_seq;
CREATE TABLE master.M_VENDOR (
  id_ven integer NOT NULL DEFAULT nextval('master.vendor_seq'),
  ID_PERUSH integer,
  ID_GRUP_VEN integer,
  nm_ven varchar(64),
  ALM_VEN varchar(128),
  id_wil varchar(11),
  TELP_VEN varchar(32),
  FAX_VEN varchar(32),
  EMAIL_VEN varchar(64),
  NPWP_VEN varchar(32),
  IS_AKTIF BOOLEAN DEFAULT 'true',
  KONTAK_VEN varchar(64),
  KONTAK_HP varchar(32),
  UPDATED_at timestamp,
	created_at timestamp,
  ID_USER integer,
  D_AC4_BIY varchar(16),
  D_AC4_PPN varchar(16),
  D_AC4_MAT varchar(16),
  D_AC4_HUT varchar(16),
  LAMA_KIRIM integer DEFAULT 0,
  CARA_BAYAR integer,
  HARI_INV integer DEFAULT 0
);


ALTER SEQUENCE master.vendor_seq
OWNED BY master.m_vendor.id_ven;
ALTER TABLE master.m_vendor ADD PRIMARY KEY (id_ven);

ALTER TABLE master.M_VENDOR ADD CONSTRAINT FK_M_VENDOR_1 FOREIGN KEY (ID_GRUP_VEN) REFERENCES master.m_group_vendor(ID_GRUP_VEN) ON UPDATE CASCADE;
ALTER TABLE master.M_VENDOR ADD CONSTRAINT FK_M_VENDOR_2 FOREIGN KEY (ID_PERUSH) REFERENCES master.m_perusahaan(ID_PERUSH) ON UPDATE CASCADE;

select * from master.m_vendor;

select * from operasional.cara_bayar;

-- create table tipe_kirim
CREATE TABLE operasional.tipe_kirim
(
    id_tipe_kirim character varying(16) COLLATE pg_catalog."default" NOT NULL,
    nm_tipe_kirim character varying(32) COLLATE pg_catalog."default",
    is_aktif boolean,
    updated_at timestamp without time zone,
    created_at timestamp without time zone,
    id_user integer,
    CONSTRAINT tipe_kirim_pkey PRIMARY KEY (id_tipe_kirim)
);

-- create table stt
CREATE TABLE operasional.op_stt
(	
    id_stt character varying(16) COLLATE pg_catalog."default" NOT NULL,
    tgl_masuk date,
	tgl_keluar date,
	no_awb character varying(32) COLLATE pg_catalog."default",
	id_plgn integer,
    id_perush_asal integer,
	id_perush_tujuan integer,
    pengirim_perush character varying(64) COLLATE pg_catalog."default",
    pengirim_nm character varying(64) COLLATE pg_catalog."default",
	pengirim_telp character varying(16) COLLATE pg_catalog."default",
    pengirim_alm character varying(128) COLLATE pg_catalog."default",
    pengirim_id_region character varying(11) COLLATE pg_catalog."default",
    pengirim_kodepos character varying(8) COLLATE pg_catalog."default",
    penerima_perush character varying(64) COLLATE pg_catalog."default",
    penerima_nm character varying(64) COLLATE pg_catalog."default",
    penerima_telp character varying(16) COLLATE pg_catalog."default",
	penerima_id_region character varying(11) COLLATE pg_catalog."default",
	penerima_alm character varying(128) COLLATE pg_catalog."default",
    penerima_kodepos character varying(11) COLLATE pg_catalog."default",
    id_layanan integer,
	id_cr_byr_o integer,
    id_tarif integer,
	kode_tarif smallint,
    id_ven integer,
	id_user integer,
	id_marketing integer,
    id_tipe_kirim character varying(11) COLLATE pg_catalog."default",
    id_packing integer,
	tgl_tempo date,
    n_berat double precision,
    n_volume double precision,
    n_koli integer,
    n_tarif_brt double precision,
    n_tarif_vol double precision,
    n_tarif_koli double precision,
    n_hrg_bruto double precision,
    n_hrg_terusan double precision,
    n_terusan double precision,
	is_ppn boolean,
    n_ppn double precision,
	id_asuransi integer,
    n_asuransi double precision,
    n_diskon double precision,
    n_materai double precision,
	c_total double precision,
    info_kirim character varying(600) COLLATE pg_catalog."default",
    instruksi_kirim character varying(600) COLLATE pg_catalog."default",
    is_lunas boolean,
	is_aktif boolean,
    x_n_bayar double precision,
    x_n_piut double precision,
    c_id_stt_stat integer,
    c_id_inv_st integer,
    c_id_stt_st integer,
    c_n_rata2_koli double precision,
    c_ac4_pend character varying(16) COLLATE pg_catalog."default",
    c_ac4_disc character varying(16) COLLATE pg_catalog."default",
    c_ac4_ppn character varying(16) COLLATE pg_catalog."default",
    c_ac4_mat character varying(16) COLLATE pg_catalog."default",
    c_ac4_piut character varying(16) COLLATE pg_catalog."default",
    c_total_1 double precision,
    c_ac4_asur double precision,
    approve_user integer,
    approve_date date,
    created_at timestamp without time zone,
    updated_at timestamp without time zone,
    CONSTRAINT op_stt_pkey PRIMARY KEY (id_stt)
);

-- create table generate id stt
CREATE SEQUENCE operasional.gen_id_stt_eq;
CREATE TABLE operasional.gen_id_stt (
    id_gen integer NOT NULL DEFAULT nextval('operasional.gen_id_stt_eq'),
	id_stt varchar(16),
	date_origin varchar(10),
	created_at timestamp,
	updated_at timestamp
);

ALTER SEQUENCE operasional.gen_id_stt_eq
OWNED BY operasional.gen_id_stt.id_gen;
ALTER TABLE operasional.gen_id_stt ADD PRIMARY KEY (id_gen);

select * from operasional.op_stt;

update operasional.op_stt set is_aktif='1';

