

CREATE TABLE personas (
    id_persona serial NOT NULL PRIMARY KEY,
    paterno character varying(100),
    materno character varying(100),
    nombre character varying(100) NOT NULL,
    sexo character varying(1),
    fecha_nac date,
    estado_id_nac integer,
    curp character varying(18),
    curp_verificado boolean NOT NULL DEFAULT false,
    f_reg_persona timestamp without time zone NOT NULL DEFAULT now()
);

CREATE TABLE usrsys (
    id_usrsys serial NOT NULL PRIMARY KEY,
    uname character varying(100) NOT NULL,
    upass character varying,
    email character varying(250) NOT NULL,
    paterno character varying(100),
    materno character varying(100),
    nombre character varying(100) NOT NULL,
    tel_movil character varying(20),
    activo boolean NOT NULL DEFAULT true,
    f_activo timestamp without time zone NOT NULL DEFAULT now(),
    f_inactivo timestamp without time zone,
    f_vigencia timestamp without time zone,
    validado boolean NOT NULL DEFAULT false,
    persona_id integer,
    f_reg timestamp without time zone NOT NULL DEFAULT now()
);


