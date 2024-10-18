--
-- Database schema with catalogs and functions
--

CREATE EXTENSION IF NOT EXISTS pgcrypto WITH SCHEMA public;

CREATE OR REPLACE FUNCTION lo_tri_tra(varchar) RETURNS varchar AS
$$
SELECT lower(trim(translate($1,E'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÐÒÓÔÕÖØÙÚÛÜÝßàáâãäåçèéêëìíîïòóôõöøùúûüýÿĀāĂăĄąĆćĈĉĊċČčĎďĐđĒēĔĕĖėĘęĚěĜĝĞğĠġĢģĤĥĦħĨĩĪīĬĭĮįİıĴĵĶķĹĺĻļĽľĿŀŁłÑñŃńŅņŇňŉŌōŎŏŐőŔŕŖŗŘřŚśŜŝŞşŠšŢţŤťŦŧŨũŪūŬŭŮůŰűŲųŴŵŶŷŸŹźŻżŽžſƒƠơƯưǍǎǏǐǑǒǓǔǕǖǗǘǙǚǛǜǺǻǾǿ\ń ','AAAAAACEEEEIIIIDOOOOOOUUUUYsaaaaaaceeeeiiiioooooouuuuyyAaAaAaCcCcCcCcDdDdEeEeEeEeEeGgGgGgGgHhHhIiIiIiIiIiJjKkLlLlLlLlllNnNnNnNnnOoOoOoRrRrRrSsSsSsSsTtTtTtUuUuUuUuUuUuWwYyYZzZzZzsfOoUuAaIiOoUuUuUuUuUuAaOo ')));
$$
LANGUAGE SQL;

CREATE OR REPLACE FUNCTION lo_tri_tra(text) RETURNS text AS
$$
SELECT lower(trim(translate($1,E'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÐÒÓÔÕÖØÙÚÛÜÝßàáâãäåçèéêëìíîïòóôõöøùúûüýÿĀāĂăĄąĆćĈĉĊċČčĎďĐđĒēĔĕĖėĘęĚěĜĝĞğĠġĢģĤĥĦħĨĩĪīĬĭĮįİıĴĵĶķĹĺĻļĽľĿŀŁłÑñŃńŅņŇňŉŌōŎŏŐőŔŕŖŗŘřŚśŜŝŞşŠšŢţŤťŦŧŨũŪūŬŭŮůŰűŲųŴŵŶŷŸŹźŻżŽžſƒƠơƯưǍǎǏǐǑǒǓǔǕǖǗǘǙǚǛǜǺǻǾǿ\ń ','AAAAAACEEEEIIIIDOOOOOOUUUUYsaaaaaaceeeeiiiioooooouuuuyyAaAaAaCcCcCcCcDdDdEeEeEeEeEeGgGgGgGgHhHhIiIiIiIiIiJjKkLlLlLlLlllNnNnNnNnnOoOoOoRrRrRrSsSsSsSsTtTtTtUuUuUuUuUuUuWwYyYZzZzZzsfOoUuAaIiOoUuUuUuUuUuAaOo ')));
$$
LANGUAGE SQL;



--
-- Personas
--
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

--
-- Usuarios
--
CREATE TABLE usrsys (
    id_usrsys serial NOT NULL PRIMARY KEY,
    uname character varying(100) NOT NULL,
    upass character varying,
    email character varying(250) NOT NULL,
    paterno character varying(100),
    materno character varying(100),
    nombre character varying(100) NOT NULL,
    cel_movil character varying(20),
    activo boolean NOT NULL DEFAULT true,
    f_activo timestamp without time zone NOT NULL DEFAULT now(),
    f_inactivo timestamp without time zone,
    f_vigencia timestamp without time zone,
    validado boolean NOT NULL DEFAULT false,
    persona_id integer,
    f_reg timestamp without time zone NOT NULL DEFAULT now()
);
CREATE UNIQUE INDEX ON usrsys (lo_tri_tra(uname));
CREATE UNIQUE INDEX ON usrsys (lo_tri_tra(email));
-- Insert Admin/q1w2e3r4t5
INSERT INTO usrsys (uname, upass, email, nombre) VALUES ('admin','$2y$10$o5RzOUs9MlAyyfNdiyotaex2SnKD5h4Rg.jNIjsJ7Q4/rUjTpmeMu','admin@okasu.net','Admin');

--
-- Bitacora / Logs
--

-- DROP TABLE cat_logs;
CREATE TABLE cat_logs(
    id_cat_log integer NOT NULL UNIQUE PRIMARY KEY,
    log_action character varying(250),
    f_reg timestamp without time zone NOT NULL DEFAULT now()
);
INSERT INTO cat_logs (id_cat_log, log_action) VALUES
(1,'Add new user'),
(2,'Log in application');

--
-- Bitacora / Log : users
--
-- DROP TABLE log_users;
CREATE TABLE log_users(
    id_log_user serial NOT NULL PRIMARY KEY,
	fk_id_usrsys integer NOT NULL, 
    fk_id_cat_log integer NOT NULL,
    log_time timestamp without time zone NOT NULL DEFAULT now(),
    log_desc character varying(250),
    ip_address character varying(100)
);

--
-- Bitacora / Log : users
--

-- DROP TABLE sys_app_mod;
CREATE TABLE sys_app_mod (
    id_sys_am serial NOT NULL PRIMARY KEY,
    app_mod character varying(200) NOT NULL,
    app_mod_lbl character varying NOT NULL,
    parent_id_sys_am integer,
    icon character varying,
    order_mod integer,
    active boolean NOT NULL DEFAULT TRUE,
    f_act timestamp without time zone NOT NULL DEFAULT now(),
    f_inactive timestamp without time zone,
    f_reg timestamp without time zone NOT NULL DEFAULT now()
);

-- Insert modulo inicial
INSERT INTO sys_app_mod(app_mod, app_mod_lbl, parent_id_sys_am, icon) VALUES 
('#', 'Administrador', NULL, 'bi bi-brilliance'), -- 1
('ad-users',  'Usuarios' ,1,'bi bi-people-fill'),
('ad-mod-user','Permisos',1,'bi bi-ui-checks');

--
-- users - modules
--
-- DROP TABLE usr_sys_am;
CREATE TABLE usr_sys_am (
    id_usr_sys_am serial NOT NULL PRIMARY KEY,
    fk_id_usrsys integer NOT NULL REFERENCES usrsys(id_usrsys),
    fk_id_sys_am integer NOT NULL REFERENCES sys_app_mod(id_sys_am),
    f_reg timestamp without time zone NOT NULL DEFAULT now()
);
CREATE UNIQUE INDEX ON usr_sys_am (fk_id_usrsys, fk_id_sys_am);
INSERT INTO usr_sys_am(fk_id_usrsys, fk_id_sys_am) VALUES (1,1),(1,2),(1,3);


