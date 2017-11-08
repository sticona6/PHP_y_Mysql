CREATE DATABASE bd_upt_touch_a;

USE bd_upt_touch_a;

CREATE TABLE `servicios` (
	`IdServicio` INT(10) NOT NULL AUTO_INCREMENT,
	`NombreServicio` VARCHAR(250) NOT NULL,
	PRIMARY KEY (`IdServicio`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=2

CREATE TABLE `detalleservicios` (
	`IdDetalleServicio` INT(10) NOT NULL AUTO_INCREMENT,
	`FechaServicio` VARCHAR(250) NOT NULL,
	`EstadoServicio` ENUM('realizado','incompleto') NOT NULL,
	`IdServicio` INT(10) NOT NULL,
	`IdUsuario` INT(10) NOT NULL,
	`IdUbicacion` INT(10) NOT NULL,
	PRIMARY KEY (`IdDetalleServicio`),
	INDEX `IdServicio` (`IdServicio`),
	INDEX `IdUsuario` (`IdUsuario`),
	INDEX `IdUbicacion` (`IdUbicacion`),
	CONSTRAINT `detalleservicios_ibfk_1` FOREIGN KEY (`IdServicio`) REFERENCES `servicios` (`IdServicio`),
	CONSTRAINT `detalleservicios_ibfk_2` FOREIGN KEY (`IdUsuario`) REFERENCES `usuarios` (`IdUsuario`),
	CONSTRAINT `detalleservicios_ibfk_3` FOREIGN KEY (`IdUbicacion`) REFERENCES `ubicacion` (`IdUbicacion`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=2
;

CREATE TABLE `usuarios` (
	`IdUsuario` INT(10) NOT NULL AUTO_INCREMENT,
	`Contrasena` VARCHAR(40) NOT NULL,
	`Nombres` VARCHAR(250) NOT NULL,
	`EstadoUsuario` ENUM('activo','inactivo') NOT NULL,
	`Email` VARCHAR(100) NOT NULL,
	`Foto` LONGBLOB NULL,
	`NivelUsuario` ENUM('cliente','empleado','administrador') NOT NULL,
	`Celular` VARCHAR(9) NOT NULL,
	PRIMARY KEY (`IdUsuario`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=7
;

CREATE TABLE `ubicacion` (
	`IdUbicacion` INT(10) NOT NULL AUTO_INCREMENT,
	`Latitud` VARCHAR(40) NOT NULL,
	`Longitud` VARCHAR(40) NOT NULL,
	`IdUsuario` INT(10) NOT NULL,
	`Ciudad` VARCHAR(10) NOT NULL,
	`Direccion` VARCHAR(250) NOT NULL,
	PRIMARY KEY (`IdUbicacion`),
	INDEX `IdUsuario` (`IdUsuario`),
	CONSTRAINT `ubicacion_ibfk_1` FOREIGN KEY (`IdUsuario`) REFERENCES `usuarios` (`IdUsuario`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=2
;

CREATE TABLE `asignados`(
	`IdAsignados` INT(10) NOT NULL AUTO_INCREMENT,
	PRIMARY KEY (`IdAsignado`)
);

CREATE TABLE `detalleAsignados`(
	`IdDetalleAsignados` INT(10) NOT NULL AUTO_INCREMENT,
	`IdAsignados` INT(10) NOT NULL,
	`IdUbicacion` INT(10) NOT NULL,
	`IdUsuario` INT(10) NOT NULL,
	PRIMARY KEY (`IdDetalleAsignados`)
);

CREATE TABLE `opinion` (
	`IdOpinion` INT(10) NOT NULL AUTO_INCREMENT,
	`IdUsuario` INT() NOT NULL,
	`Descripcion` varchar(250) NOT NULL,
	`ValorOpinion` varchar(50) NOT NULL,
	`ValorOpinion` date,
	FOREIGN KEY (`IdUsuario`) REFERENCES `usuarios` (`IdUsuario`)
)