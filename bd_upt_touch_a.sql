CREATE DATABASE bd_upt_touch_a;

USE bd_upt_touch_a;

CREATE TABLE servicios (
	IdServicio INT(10) NOT NULL AUTO_INCREMENT,
	NombreServicio VARCHAR(250) NOT NULL,
	PRIMARY KEY (IdServicio)
);

CREATE TABLE ubicacion (
	IdUbicacion INT(10)NOT NULL AUTO_INCREMENT,
	Latitud varchar(50),
	Longitud varchar (50),
	Ciudad varchar(50),
	Direccion varchar(250),
	PRIMARY KEY (IdUbicacion)
);

CREATE TABLE usuarios (
	IdUsuario INT(10) NOT NULL AUTO_INCREMENT,
	Contrasena VARCHAR(40) NOT NULL,
	Nombres VARCHAR(250) NOT NULL,
	EstadoUsuario ENUM('activo','inactivo') NOT NULL,
	Email VARCHAR(100) NOT NULL,
	Foto LONGBLOB NULL,
	NivelUsuario ENUM('cliente','empleado','administrador') NOT NULL,
	Celular VARCHAR(9) NOT NULL,
	PRIMARY KEY (IdUsuario)
);

CREATE TABLE solicitudes (
	IdSolicitud INT(10)NOT NULL AUTO_INCREMENT,
	EstadoSolicitud ENUM('aceptado','rechazado') NOT NULL,
	IdUsuario INT(10)NOT NULL,
	IdServicio INT(10)NOT NULL,
	PRIMARY KEY (IdSolicitud),
	FOREIGN KEY (IdUsuario) REFERENCES usuarios (IdUsuario),
	FOREIGN KEY (IdServicio) REFERENCES servicios (IdServicio)
);


CREATE TABLE asignaciones(
	IdAsignados INT(10) NOT NULL AUTO_INCREMENT,
	IdUbicacion INT(10) NOT NULL,
	IdUsuario INT(10) NOT NULL,
	PRIMARY KEY (IdAsignados),
	FOREIGN KEY (IdUsuario) REFERENCES usuarios (IdUsuario),
	FOREIGN KEY (IdUbicacion) REFERENCES ubicacion (IdUbicacion)
);

CREATE TABLE datosempleado(
	IdDatosEmpleado INT(10) NOT NULL AUTO_INCREMENT,
	IdUsuario INT(10) NOT NULL,
	PRIMARY KEY (IdDatosEmpleado),
	FOREIGN KEY (IdUsuario) REFERENCES usuarios (IdUsuario)
);

CREATE TABLE opiniones (
	IdOpinion INT(10) NOT NULL AUTO_INCREMENT,
	IdUsuario INT(10) NOT NULL,
	Descripcion varchar(250) NOT NULL,
	ValorOpinion varchar(50) NOT NULL,
	FechaOpinion date,
	PRIMARY KEY (IdOpinion),
	FOREIGN KEY (IdUsuario) REFERENCES usuarios (IdUsuario)
);

CREATE TABLE detalleservicios (
	IdDetalleServicio INT(10) NOT NULL AUTO_INCREMENT,
	NivelServicio ENUM('alto','bajo')  NOT NULL,
	FechaServicio date,
	EstadoServicio ENUM('realizado','incompleto') NOT NULL,
	IdServicio INT(10) NOT NULL,
	IdUsuario INT(10) NOT NULL,
	IdUbicacion INT(10) NOT NULL,
	PRIMARY KEY (IdDetalleServicio),
	FOREIGN KEY (IdServicio) REFERENCES servicios (IdServicio),
	FOREIGN KEY (IdUsuario) REFERENCES usuarios (IdUsuario),
	FOREIGN KEY (IdUbicacion) REFERENCES ubicacion (IdUbicacion)
);

CREATE TABLE reportes (
	IdReportes INT(10) NOT NULL AUTO_INCREMENT,
	IdOpinion INT(10) NOT NULL,
	IdAsignados INT(10) NOT NULL,
	IdSolicitud INT(10)NOT NULL,
	IdServicio INT(10) NOT NULL,
	PRIMARY KEY (IdReportes),
	FOREIGN KEY (IdOpinion) REFERENCES opiniones (IdOpinion),
	FOREIGN KEY (IdServicio) REFERENCES servicios (IdServicio),
	FOREIGN KEY (IdSolicitud) REFERENCES solicitudes (IdSolicitud),
	FOREIGN KEY (IdAsignados) REFERENCES asignaciones (IdAsignados)
);