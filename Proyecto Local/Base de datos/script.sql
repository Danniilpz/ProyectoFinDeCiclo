DROP DATABASE IF EXISTS loopz;

CREATE DATABASE IF NOT EXISTS loopz
	DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE       utf8mb4_unicode_ci;;
USE loopz;

CREATE TABLE sitios (
  direccion VARCHAR(191) NOT NULL,
  fecha_exp datetime NOT NULL,
  bloqueado tinyint NOT NULL,
  CONSTRAINT sitios_pk PRIMARY KEY (direccion)
);

CREATE TABLE direcciones (
  direccion VARCHAR(191) NOT NULL,
  titulo VARCHAR(191) NOT NULL,
  sitio  VARCHAR(191) NOT NULL,
  CONSTRAINT direcciones_pk PRIMARY KEY (direccion),
  CONSTRAINT sitio_fk FOREIGN KEY (sitio)
    REFERENCES sitios(direccion)
    ON DELETE CASCADE
    ON UPDATE CASCADE
); 

CREATE TABLE keywords (
keyword VARCHAR(191) NOT NULL,
direccion VARCHAR(191) NOT NULL,
prioridad int NOT NULL,
CONSTRAINT keywords_pk PRIMARY KEY (keyword,direccion),
CONSTRAINT direccion_fk FOREIGN KEY (direccion)
  REFERENCES direcciones(direccion)
  ON DELETE CASCADE
  ON UPDATE CASCADE,
INDEX idx_keyword (keyword,direccion)
);

CREATE TABLE historial (
id_registro int auto_increment,
busqueda VARCHAR(191) NOT NULL,
tipo VARCHAR(20) NOT NULL,
usuario int,
fecha datetime NOT NULL,
CONSTRAINT historial_pk PRIMARY KEY (id_registro)
) ;

CREATE TABLE imagenes (
imagen VARCHAR(191) NOT NULL,
direccion VARCHAR(191) NOT NULL,
CONSTRAINT imagenes_pk PRIMARY KEY (imagen,direccion),
CONSTRAINT direccionimagen_fk FOREIGN KEY (direccion)
  REFERENCES direcciones(direccion)
  ON DELETE CASCADE
  ON UPDATE CASCADE
);