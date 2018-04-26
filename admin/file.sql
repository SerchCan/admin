
 * -- ****************** SqlDBM: MySQL ******************;
-- ***************************************************;




-- ************************************** `cat_paises`

CREATE TABLE `cat_paises`
(
 `id_pais` INT NOT NULL ,
 `nombre`  VARCHAR(45) NOT NULL ,

PRIMARY KEY (`id_pais`)
);





-- ************************************** `usuario`

CREATE TABLE `usuario`
(
 `id_usuario`   INT NOT NULL ,
 `nombre`       VARCHAR(45) NOT NULL ,
 `correo`       VARCHAR(45) NOT NULL ,
 `username`     VARCHAR(45) NOT NULL ,
 `password`     VARCHAR(254) NOT NULL ,
 `estatus`      ENUM('ACTIVO', 'INACTIVO') NOT NULL ,
 `tipo`         ENUM('CLIENTE', 'EJECUTIVO', 'ADMINISTRADOR') NOT NULL ,
 `fecha_alta`   DATETIME NOT NULL ,
 `direccion`    TEXT NOT NULL ,
 `sexo`         ENUM('MASCULINO', 'FEMENINO') NOT NULL ,
 `rfc`          VARCHAR(45) NOT NULL ,
 `curp`         VARCHAR(45) NOT NULL ,
 `usuario_alta` INT NOT NULL ,
 `id_pais`      INT NOT NULL ,

PRIMARY KEY (`id_usuario`),
KEY `fkIdx_383` (`id_pais`),
CONSTRAINT `FK_383` FOREIGN KEY `fkIdx_383` (`id_pais`) REFERENCES `cat_paises` (`id_pais`)
);





-- ************************************** `telefono`

CREATE TABLE `telefono`
(
 `id_telefono` INT NOT NULL ,
 `numero`      VARCHAR(45) NOT NULL ,
 `tipo`        ENUM('MOVIL,FIJO') NOT NULL ,
 `id_usuario`  INT NOT NULL ,

PRIMARY KEY (`id_telefono`),
KEY `fkIdx_373` (`id_usuario`),
CONSTRAINT `FK_373` FOREIGN KEY `fkIdx_373` (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
);





-- ************************************** `empleado`

CREATE TABLE `empleado`
(
 `id_empleado`           INT NOT NULL ,
 `contrato_fecha_inicio` DATE NOT NULL ,
 `contrato_fecha_fin`    DATE NOT NULL ,
 `sueldo`                FLOAT NOT NULL ,
 `id_usuario`            INT NOT NULL ,

PRIMARY KEY (`id_empleado`),
KEY `fkIdx_340` (`id_usuario`),
CONSTRAINT `FK_340` FOREIGN KEY `fkIdx_340` (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
);





-- ************************************** `cuenta`

CREATE TABLE `cuenta`
(
 `id_cuenta`  INT NOT NULL ,
 `numero`     VARCHAR(10) NOT NULL ,
 `balance`    FLOAT NOT NULL ,
 `detalles`   TEXT NOT NULL ,
 `fecha_alta` DATETIME NOT NULL ,
 `estatus`    ENUM('ACTIVA', 'INACTIVA') NOT NULL ,
 `tipo`       ENUM('CHEQUES', 'AHORROS') NOT NULL ,
 `id_usuario` INT NOT NULL ,

PRIMARY KEY (`id_cuenta`),
KEY `fkIdx_379` (`id_usuario`),
CONSTRAINT `FK_379` FOREIGN KEY `fkIdx_379` (`id_usuario`) REFERENCES `usuario` (`id_usuario`)
);





-- ************************************** `caja`

CREATE TABLE `caja`
(
 `id_caja`             INT NOT NULL ,
 `estatus`             ENUM('ACTIVO', 'INACTIVO') NOT NULL ,
 `fecha_alta`          DATETIME NOT NULL ,
 `id_usuario_asignado` INT NOT NULL ,

PRIMARY KEY (`id_caja`),
KEY `fkIdx_331` (`id_usuario_asignado`),
CONSTRAINT `FK_331` FOREIGN KEY `fkIdx_331` (`id_usuario_asignado`) REFERENCES `usuario` (`id_usuario`)
);





-- ************************************** `tarjeta`

CREATE TABLE `tarjeta`
(
 `id_tarjeta`           INT NOT NULL ,
 `numero`               VARCHAR(16) NOT NULL ,
 `pin`                  VARCHAR(4) NOT NULL ,
 `codigo_seguridad`     VARCHAR(3) NOT NULL ,
 `fecha_validez_inicio` DATE NOT NULL ,
 `fecha_validez_fin`    DATE NOT NULL ,
 `estatus`              ENUM('ACTIVO', 'INACTIVO') NOT NULL ,
 `id_cuenta`            INT NOT NULL ,

PRIMARY KEY (`id_tarjeta`),
KEY `fkIdx_231` (`id_cuenta`),
CONSTRAINT `FK_231` FOREIGN KEY `fkIdx_231` (`id_cuenta`) REFERENCES `cuenta` (`id_cuenta`)
);





-- ************************************** `transaccion`

CREATE TABLE `transaccion`
(
 `id_transaccion` INT NOT NULL ,
 `folio`          VARCHAR(16) NOT NULL ,
 `fecha`          DATETIME NOT NULL ,
 `monto`          FLOAT NOT NULL ,
 `detalles`       TEXT NOT NULL ,
 `tipo`           ENUM('DEPOSITO', 'RETIRO', 'PAGO') NOT NULL ,
 `id_caja`        INT NOT NULL ,
 `id_tarjeta`     INT NOT NULL ,

PRIMARY KEY (`id_transaccion`),
KEY `fkIdx_162` (`id_caja`),
CONSTRAINT `FK_162` FOREIGN KEY `fkIdx_162` (`id_caja`) REFERENCES `caja` (`id_caja`),
KEY `fkIdx_235` (`id_tarjeta`),
CONSTRAINT `FK_235` FOREIGN KEY `fkIdx_235` (`id_tarjeta`) REFERENCES `tarjeta` (`id_tarjeta`)
);





 * 
 * 