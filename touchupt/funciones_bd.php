<?php
//header('Content-Type: text/html; UTF-8');
class funciones_BD {
    private $db;
    // constructor
    function __construct() {
        require_once 'connectbd.php';
        // connecting to database
        $this->db = new DB_Connect();
        $this->db->connect();
    }
    // destructor
    function __destruct() {
    }
 
    /*
----------------AGREGAR PERSONA
     */
    public function adduser($dni,$nombre,$apellidos,$tipo,$foto,$email,$direccion,$celular,$telefono,$celularReferenci) {

        //por seguridad colocar or die(mysql_error())
        //INSERT INTO PERSONA VALUES ('1','41004216','Cristhiam Roby','PEREIRA HUAYLLA','1','','cristhiam20@hotmail.com','CALLE TARATA 624 URB. BACIGALUPO','95226428','4563558','');

    $result = mysql_query("INSERT INTO PERSONA(DNI,Nombre,Apellidos,Tipo,Foto,Email,Direccion,Celular,Telefono,CelularReferencia) 
        VALUES('$dni','$nombre','$apellidos','$tipo','$foto','$email','$direccion','$celular','$telefono','$celularReferenci')" );
        // check for successful store

        if ($result) {

            return true;

        } else {

            return false;
        }

    }
 
 
     /*
--------------Verificar si el PERSONA ya existe por el username
     */

    public function isuserexist($dni) {

        $result = mysql_query("SELECT DNI from PERSONA WHERE DNI = '$dni'");

        $num_rows = mysql_num_rows($result); //numero de filas retornadas

        if ($num_rows > 0) {

            // el usuario existe 
            return true;
        } else {
            // no existe
            return false;
        }
    }
 
   /*
-------------------LOGIN*/
	public function login($user,$passw){

        $con = mysqli_connect("localhost","root","","bd_upt_touch_a");

       // $passwX = SHA1($passw);
//  $result=mysql_query("SELECT COUNT(*) FROM usuarios WHERE username='$user' AND passw='$passw' "); 
    	$result = mysqli_query($con,"SELECT 
        IdUsuario
        , Contrasena
        , Nombre
        , Apellido
        , EstadoUsuario
        , Email
        , Foto
        , NivelUsuario
        , Celular

        FROM usuarios

        WHERE Email = '$user' AND Contrasena = '$passw'") or die(mysqli_error($con));

        /*$num_rows = mysql_num_rows($result);
        if ($num_rows!= NULL) {    
            $rows = array();
            $r = mysql_fetch_assoc($result); 
            $rows[] = array_map('htmlentities',$r);
            return $rows;
        } else {
            return false;
        }*/

        $num_rows = mysqli_num_rows($result);
        if ($num_rows!= NULL) {    
            $rows = array();
            $r = mysqli_fetch_assoc($result);
             $xx = array('IdUsuario' => $r['IdUsuario']
                ,'Nombre' => $r['Nombre']
                ,'Apellido' => $r['Apellido']
                ,'EstadoUsuario' => $r['EstadoUsuario']
                ,'Email' => $r['Email']
                ,'Foto' => base64_encode($r['Foto'])
                ,'NivelUsuario' => $r['NivelUsuario']
                ,'Celular' => $r['Celular']
                );
             //$rows[] = array('id' => $r['id'], 'nombre' => $r['nombre'], 'apellidos' => $r['apellidos'], 'foto' => base64_encode($r['foto']));
            $rows[] = array_map('htmlentities',$xx);
            return $rows;
        } else {
            return false;
        }


	}


    /*agregar nuevo Curso
     */
    public function addCurso($codcur, $nomcur, $credcur) {
        //por seguridad colocar or die(mysql_error())
        //INSERT INTO CURSO VALUES ('','SI652','Calculo 2','4');
        $result = mysql_query("INSERT INTO CURSO(CodigoCurso,NombreCurso,CreditoCurso) VALUES('$codcur', '$nomcur', '$credcur')" );
        // check for successful store
        if ($result) {
            return true;

        } else {

            return false;
        }

    }
     /**
     * Verificar si el usuario ya existe por el username
     */

    public function ValidarCurso($cursocod,$cursonom) {

        $result = mysql_query("SELECT CodigoCurso from CURSO WHERE CodigoCurso = '$cursocod' or NombreCurso = '$cursonom'");

        $num_rows = mysql_num_rows($result); //numero de filas retornadas

        if ($num_rows > 0) {

            // el usuario existe 

            return true;
        } else {
            // no existe
            return false;
        }
    }
    /*BUSCAR CURSO*/
    public function BuscarCursoCodigo($curcod) {
        //CodigoCurso,NombreCurso,CreditoCurso
        $result = mysql_query("SELECT * from CURSO WHERE CodigoCurso = '$curcod'");
        $num_rows = mysql_num_rows($result); //numero de filas retornadas
        if ($num_rows > 0) {
            //return true;
            $rows = array();
            while($r = mysql_fetch_assoc($result)) {
              $rows[] = $r;
            }
            //return json_encode($rows);
            return $rows;
        } else {
            return false;
        }
    }
    /*
--------------------LISTA MIEMBROS DE UN CURSO X*/
    public function ListarMiembrosCurso($idcurso) {
        $result = mysql_query("SELECT 
            u.idPersona id,
            b.CodigoDocente  codigo,
            u.DNI dni,
            concat_ws(' ',u.Nombre,u.Apellidos) nombre,
            u.Tipo tipo,
            /*u.Foto foto,*/
            u.Email email,
            /*u.Direccion direccion,
            u.Celular celular,
            u.Telefono telefono,
            u.CelularReferencia celularreferencia,
            ub.FacebookUsuario facebook,
            ub.TwiterUsuario twitter,*/
            ub.EstadoUsuario estado
        FROM Usuario ub
        INNER JOIN PERSONA u ON ub.IdUsuario = u.IdPersona
        INNER JOIN DOCENTE b ON ub.IdUsuario = b.IdDocente
        INNER JOIN CURSO_DOCENTE e ON ub.IdUsuario = e.IdDocente
        INNER JOIN CURSO c ON e.idCurso = c.idCurso
        WHERE c.CodigoCurso = '$idcurso' AND e.Estado = 'activo'
        UNION ALL
        SELECT 
            u.idPersona id,
            b.CodigoAlumno  codigo,
            u.DNI dni,
            concat_ws(' ',u.Nombre,u.Apellidos) nombre,
            u.Tipo tipo,
            /*u.Foto foto,*/
            u.Email email,
            /*u.Direccion direccion,
            u.Celular celular,
            u.Telefono telefono,
            u.CelularReferencia celularreferencia,
            ub.FacebookUsuario facebook,
            ub.TwiterUsuario twitter,*/
            ub.EstadoUsuario estado
        FROM Usuario ub
        INNER JOIN PERSONA u ON ub.IdUsuario = u.IdPersona
        INNER JOIN ALUMNO b ON ub.IdUsuario = b.IdAlumno
        INNER JOIN CURSO_ALUMNO e ON ub.IdUsuario = e.IdAlumno
        INNER JOIN CURSO c ON e.idCurso = c.idCurso
        WHERE c.CodigoCurso = '$idcurso' AND e.Estado = 'activo'
        
        ");
        $num_rows = mysql_num_rows($result); 
        if ($num_rows > 0) {
            $rows = array();
            while($r = mysql_fetch_assoc($result)) {
              //$rows[] = $r;
             $xx = array('id' => $r['id']
                ,'codigo' => $r['codigo']
                ,'dni' => $r['dni']
                ,'nombre' => $r['nombre']
                ,'tipo' => $r['tipo']
                /*,'foto' => base64_encode($r['foto'])*/
                ,'email' => $r['email']
                /*,'direccion' => $r['direccion']
                ,'celular' => $r['celular']
                ,'telefono' => $r['telefono']
                ,'celularreferencia' => $r['celularreferencia']
                ,'facebook' => $r['facebook']
                ,'twitter' => $r['twitter']*/
                ,'estado' => $r['estado']
                );
            $rows[] = array_map('htmlentities',$xx);
              
            }
            return $rows;
        } else {
            return false;
        }
    }
/*---------------------------LISTAR MIS CURSOS*/
    public function ListarMisCursos($IdUsuario) {
        $result = mysql_query("SELECT 
            c.idCurso id,
            c.CodigoCurso codigo,
            c.NombreCurso nombre,
            c.CreditoCurso creditos
        FROM CURSO_ALUMNO ub
        INNER JOIN CURSO c ON ub.idCurso = c.IdCurso
        INNER JOIN USUARIO u ON ub.idAlumno = u.IdUsuario
        WHERE ub.idAlumno = '$IdUsuario'
        UNION ALL
        SELECT 
            c.idCurso id,
            c.CodigoCurso codigo,
            c.NombreCurso nombre,
            c.CreditoCurso creditos
        FROM CURSO_DOCENTE ub
        INNER JOIN CURSO c ON ub.idCurso = c.IdCurso
        INNER JOIN USUARIO u ON ub.idDocente = u.IdUsuario
        WHERE ub.idDocente = '$IdUsuario'");
        $num_rows = mysql_num_rows($result); 

        if ($num_rows > 0) {
            $rows = array();
            while($r = mysql_fetch_assoc($result)) {
              //$rows[] = $r;
              $rows[] = array_map('htmlentities',$r);
            }
            return $rows;
        } else {
            return false;
        }
    }
/*------------------------LISTAR CPNTACTOS*/
    public function ListarContactos($idcurso) {
        $result = mysql_query("SELECT 
            u.idPersona id,
            concat_ws(' ',u.Nombre,u.Apellidos) nombre,
            u.Tipo tipo,
            u.Foto foto,
            u.Email email,
            u.Direccion direccion,
            u.Celular celular,
            u.Telefono telefono,
            u.CelularReferencia celularreferencia
        FROM CONTACTO ub
        INNER JOIN USUARIO a ON ub.IdUsuario = a.IdUsuario
        INNER JOIN PERSONA u ON ub.CodigoContacto = u.IdPersona
        WHERE a.IdUsuario = '$idcurso'");
        $num_rows = mysql_num_rows($result); 
        if ($num_rows > 0) {
            $rows = array();
            while($r = mysql_fetch_assoc($result)) {
              //$rows[] = $r;
             $xx = array('id' => $r['id']
                ,'nombre' => $r['nombre']
                ,'tipo' => $r['tipo']
                ,'foto' => base64_encode($r['foto'])
                ,'email' => $r['email']
                ,'direccion' => $r['direccion']
                ,'celular' => $r['celular']
                ,'telefono' => $r['telefono']
                ,'celularreferencia' => $r['celularreferencia']
                );
            $rows[] = array_map('htmlentities',$xx);
              
            }
            return $rows;
        } else {
            return false;
        }
    }
//-------------- VALIDA CONTACTO YA AGREGADO 
    public function ValidarContacto($idUsuario,$idContacto){
        $result = mysql_query("SELECT COUNT(*) estado FROM CONTACTO WHERE IdUsuario='$idUsuario' AND CodigoContacto='$idContacto'");
        $count = mysql_fetch_row($result);
        if ($count[0]==0){
            return false;
        }else{
            return true;
        }
    }
/*----------------AGREGAR CONTACTO*/
    public function addContacto($IdUsuario,$idContacto) {
        //INSERT INTO CONTACTO VALUES ('','3','41004216'); 
        //IDContacto IdUsuario CodigoContacto
    $result = mysql_query("INSERT INTO CONTACTO(IdUsuario,CodigoContacto) VALUES('$IdUsuario','$idContacto')" );
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
//-----------------ELIMINAR CONTACTO
    public function ValidarDelete($idUsuario,$idContacto){
        $result = mysql_query("SELECT COUNT(*) estado FROM CONTACTO WHERE IdUsuario='$idUsuario' and CodigoContacto = '$idContacto'");
        $count = mysql_fetch_row($result);
        if ($count[0]==0){
            return true;//Ya fue eliminado
        }else{
            return false;//Eliminado correctamente
        }
    }
    public function deleteContacto($idUsuario,$idContacto) {
    $result = mysql_query("DELETE FROM CONTACTO WHERE IdUsuario='$idUsuario' and CodigoContacto = '$idContacto'");
        if ($result) {
            return true;//eliminado
        } else {
            return false;//no eiminado
        }
    }
//-----------------ACTUALIZAR INFORMACION
    public function ActualizarPerfil($idUsuario,$facebook,$twitter) {
    $validar =  mysql_query("SELECT COUNT(*) estado from USUARIO WHERE idUsuario='$idUsuario'");
    $result = mysql_query("UPDATE Usuario SET FacebookUsuario = '$facebook', TwiterUsuario='$twitter' WHERE IdUsuario = '$idUsuario'");
    $count = mysql_fetch_row($validar);
        if ($count[0]==0){
            return false;
        }else{
            return true;
        }
    }

//------------------CAMBIAR CONTRASEÑA 
    public function CambiarPass($idUsuario,$passactual,$password){
        $validar = mysql_query("SELECT COUNT(*) estado FROM USUARIO WHERE idUsuario='$idUsuario'AND Contrasena='$passactual'");
        $result = mysql_query("UPDATE Usuario SET Contrasena = '$password' WHERE idUsuario='$idUsuario'AND Contrasena='$passactual'");
        $count = mysql_fetch_row($validar);
        if ($count[0]==0){
            return false;
        }else{
            return true;
        }
    }
//-------------- VER DETALLE CONTACTO 
    public function VerDetalleContacto($idUsuario, $idContacto){
        $result = mysql_query("SELECT 
            u.idPersona id,
            concat_ws(' ',u.Nombre ,u.Apellidos) nombre,
            u.Tipo tipo,
            u.Foto foto,
            u.Email email,
            u.Direccion direccion,
            u.Celular celular,
            u.Telefono telefono,
            u.CelularReferencia celularreferencia,
            ub.FacebookUsuario facebook,
            ub.TwiterUsuario twitter,
            ub.EstadoUsuario estado       
        FROM Usuario ub
        INNER JOIN PERSONA u ON ub.IdUsuario = u.IdPersona
        INNER JOIN ALUMNO b ON ub.IdUsuario = b.IdAlumno
        WHERE ub.IdUsuario = '$idContacto'
        UNION ALL
        SELECT 
            u.idPersona id,
            concat_ws(' ',u.Nombre ,u.Apellidos) nombre,
            u.Tipo tipo,
            u.Foto foto,
            u.Email email,
            u.Direccion direccion,
            u.Celular celular,
            u.Telefono telefono,
            u.CelularReferencia celularreferencia,
            ub.FacebookUsuario facebook,
            ub.TwiterUsuario twitter,
            ub.EstadoUsuario estado
        FROM Usuario ub
        INNER JOIN PERSONA u ON ub.IdUsuario = u.IdPersona
        INNER JOIN DOCENTE b ON ub.IdUsuario = b.IdDocente
        WHERE ub.IdUsuario = '$idContacto'") or die(mysql_error());

        $validar = mysql_query("SELECT COUNT(*) estado FROM CONTACTO WHERE IdUsuario='$idUsuario' AND CodigoContacto='$idContacto'");
        $count = mysql_fetch_row($validar);
        if ($count[0]==0){
            $rows = array();
            $r = mysql_fetch_assoc($result);
             $xx = array(
                'boton' => 'agregar'
                ,'id' => $r['id']
                ,'nombre' => $r['nombre']
                ,'tipo' => $r['tipo']
                ,'foto' => base64_encode($r['foto'])
                ,'email' => $r['email']
                ,'direccion' => $r['direccion']
                ,'celular' => $r['celular']
                ,'telefono' => $r['telefono']
                ,'celularreferencia' => $r['celularreferencia']
                ,'facebook' => $r['facebook']
                ,'twitter' => $r['twitter']
                ,'estado' => $r['estado']
                );
            $rows[] = array_map('htmlentities',$xx);
            return $rows;
        }else{
            $rows = array();
            $r = mysql_fetch_assoc($result);
             $xx = array(
                'boton' => 'eliminar'
                ,'id' => $r['id']
                ,'nombre' => $r['nombre']
                ,'tipo' => $r['tipo']
                ,'foto' => base64_encode($r['foto'])
                ,'email' => $r['email']
                ,'direccion' => $r['direccion']
                ,'celular' => $r['celular']
                ,'telefono' => $r['telefono']
                ,'celularreferencia' => $r['celularreferencia']
                ,'facebook' => $r['facebook']
                ,'twitter' => $r['twitter']
                ,'estado' => $r['estado']
                );
            $rows[] = array_map('htmlentities',$xx);
            return $rows;
        }
        /*$num_rows = mysql_num_rows($result);
        if ($num_rows!= NULL) {   

            $rows = array();
            $r = mysql_fetch_assoc($result);
             $xx = array('id' => $r['id']
                ,'nombre' => $r['nombre']
                ,'apellidos' => $r['apellidos']
                ,'tipo' => $r['tipo']
                ,'foto' => base64_encode($r['foto'])
                ,'email' => $r['email']
                ,'direccion' => $r['direccion']
                ,'celular' => $r['celular']
                ,'telefono' => $r['telefono']
                ,'celularreferencia' => $r['celularreferencia']
                ,'facebook' => $r['facebook']
                ,'twitter' => $r['twitter']
                ,'estado' => $r['estado']
                );
            $rows[] = array_map('htmlentities',$xx);
            return $rows;
        } else {
            return false;

        }*/
    }

    //-------------- VER DETALLE CONTACTO 
    public function DetallarUsuario($idUsuario){
        $result = mysql_query("SELECT 
            u.idPersona id,
            concat_ws(' ',u.Nombre ,u.Apellidos) nombre,
            u.Tipo tipo,
            u.Foto foto,
            ub.Email email,
            ub.Direccion direccion,
            ub.Celular celular,
            ub.Telefono telefono,
            ub.EstadoUsuario estado       
        FROM Usuario ub
        INNER JOIN PERSONA u ON ub.IdUsuario = u.IdPersona
        INNER JOIN ALUMNO b ON ub.IdUsuario = b.IdAlumno
        WHERE ub.IdUsuario = '$idUsuario'
        UNION ALL
        SELECT 
            u.idPersona id,
            concat_ws(' ',u.Nombre ,u.Apellidos) nombre,
            u.Tipo tipo,
            u.Foto foto,
            ub.Email email,
            ub.Direccion direccion,
            ub.Celular celular,
            ub.Telefono telefono,
            ub.EstadoUsuario estado
        FROM Usuario ub
        INNER JOIN PERSONA u ON ub.IdUsuario = u.IdPersona
        INNER JOIN DOCENTE b ON ub.IdUsuario = b.IdDocente
        WHERE ub.IdUsuario = '$idUsuario'") or die(mysql_error());

        $num_rows = mysql_num_rows($result);
        if ($num_rows!= NULL) {  
            $rows = array();
            $r = mysql_fetch_assoc($result);
             $xx = array(
                'id' => $r['id']
                ,'nombre' => $r['nombre']
                ,'tipo' => $r['tipo']
                ,'foto' => base64_encode($r['foto'])
                ,'email' => $r['email']
                ,'direccion' => $r['direccion']
                ,'celular' => $r['celular']
                ,'telefono' => $r['telefono']
                ,'estado' => $r['estado']
                );
            $rows[] = array_map('htmlentities',$xx);
            return $rows;
        }else{
            $rows = array();
            $r = mysql_fetch_assoc($result);
             $xx = array(
                'id' => 'null'
                ,'nombre' => 'null'
                ,'tipo' => 'null'
                ,'foto' => 'null'
                ,'email' => 'null'
                ,'direccion' => 'null'
                ,'celular' => 'null'
                ,'telefono' => 'null'
                ,'estado' => 'null'
                );
            $rows[] = array_map('htmlentities',$xx);
            return $rows;
        }
    }
    /*---------------------------LISTAR MIS CURSOS*/
    public function ListarSocial($IdUsuario) {
        $result = mysql_query("SELECT * FROM SOCIAL WHERE IdUsuario  = '$IdUsuario' AND estado = 'activo'");
        $num_rows = mysql_num_rows($result); 

        if ($num_rows > 0) {
            $rows = array();
            while($r = mysql_fetch_assoc($result)) {
              //$rows[] = $r;
              $rows[] = array_map('htmlentities',$r);
            }
            return $rows;
        } else {
            return false;
        }
    }
    //---AGREGAR RED SOCIAL
     public function AgregarSocial($idUsuario,$tipo,$url) {
      $result = mysql_query("INSERT INTO SOCIAL(IdUsuario,tipo,url,estado) VALUES('$idUsuario','$tipo','$url','1')"  );
        if ($result) {
            return true;
        } else {
            return false;
        }

    }
    //---ACTUALIZAR RED SOCIAL
    public function ActualizarSocial($idSocial,$tipo,$url) {
    $validar =  mysql_query("SELECT COUNT(*) estado from SOCIAL WHERE IdSocial='$idSocial'");
    $result = mysql_query("UPDATE SOCIAL SET tipo = '$tipo' , url = '$url' WHERE IdSocial = '$idSocial'");
    $count = mysql_fetch_row($validar);
        if ($count[0]==0){
            return false;
        }else{
            return true;
        }
    }
    //---DESCARTAR SOCIAL
     public function DescartarSocial($idSocial) {
      $validar =  mysql_query("SELECT COUNT(*) estado from SOCIAL WHERE IdSocial='$idSocial'");
      $result = mysql_query("UPDATE SOCIAL SET estado = '2' WHERE IdSocial = '$idSocial'");
        $count = mysql_fetch_row($validar);
        if ($count[0]==0){
            return false;
        }else{
            return true;
        }
    }

    public function VerMiPerfil($idUsuario) {
      $result = mysql_query("SELECT 
            u.Email email,
            u.Direccion direccion,
            u.Celular celular,
            u.Telefono telefono,
            u.EstadoUsuario estado

            FROM USUARIO u WHERE IdUsuario  = '$idUsuario'");
        $num_rows = mysql_num_rows($result); //numero de filas retornadas
        if ($num_rows > 0) {
            //return true;
            $rows = array();
            while($r = mysql_fetch_assoc($result)) {
              $rows[] = $r;
            }
            //return json_encode($rows);
            return $rows;
        } else {
            return false;
        }

    }
 
 //------Actualizar perfil
    //-----------------ACTUALIZAR INFORMACION
    public function ActualizarMiPerfil($idusu,$email,$dir,$cel,$fono) {
    $validar =  mysql_query("SELECT COUNT(*) estado from USUARIO WHERE idUsuario='$idusu'");
    $result = mysql_query("UPDATE Usuario SET  Email='$email' , Direccion = '$dir' , Celular='$cel' , Telefono = '$fono' WHERE IdUsuario = '$idusu'");
    $count = mysql_fetch_row($validar);
        if ($count[0]==0){
            return false;
        }else{
            return true;
        }
    }

    //-----AVATAR
    public function addAvatar($idusu,$foto) {
    $validar =  mysql_query("SELECT COUNT(*) estado from PERSONA WHERE idPersona='$idusu'");
    $result = mysql_query("UPDATE PERSONA SET  foto = '$foto' WHERE idPersona = '$idusu'");
    $count = mysql_fetch_row($validar);
        if ($count[0]==0){
            return false;
        }else{
            return true;
        }
    }

    //----LISTAR ALUMNOS __ DELEGADO
    public function ListarDelegado($codCurso) {
        $result = mysql_query("SELECT 
                u.idPersona id
                ,concat_ws(' ',u.Nombre ,u.Apellidos) nombre
                ,ub.EstadoUsuario estado
                ,e.delegado delegado
            FROM Usuario ub
            INNER JOIN PERSONA u ON ub.IdUsuario = u.IdPersona
            INNER JOIN ALUMNO b ON ub.IdUsuario = b.IdAlumno
            INNER JOIN CURSO_ALUMNO e ON ub.IdUsuario = e.IdAlumno
            INNER JOIN CURSO c ON e.idCurso = c.idCurso
            WHERE c.CodigoCurso = '$codCurso' AND e.Estado = 'activo'");
        $num_rows = mysql_num_rows($result); 

        if ($num_rows > 0) {
            $rows = array();
            while($r = mysql_fetch_assoc($result)) {
              //$rows[] = $r;
              $rows[] = array_map('htmlentities',$r);
            }
            return $rows;
        } else {
            return false;
        }
    }
    //-----ACTUALIZAR DELEGADO
    public function ActualizarDelegado($idcurso,$idalumno) {
        /*
UPDATE CURSO_ALUMNO SET delegado='2' WHERE IdCurso='1' ;
UPDATE CURSO_ALUMNO SET Delegado='1' WHERE IdCurso='1' AND IdAlumno ='4'
        */
    $validar =  mysql_query("SELECT COUNT(*) estado from CURSO_ALUMNO WHERE IdCurso='$idcurso'");
    $result = mysql_query("UPDATE CURSO_ALUMNO SET  Delegado='2'  WHERE IdCurso = '$idcurso'");
    $result = mysql_query("UPDATE CURSO_ALUMNO SET Delegado='1' WHERE IdCurso='$idcurso' AND IdAlumno ='$idalumno'");
    $count = mysql_fetch_row($validar);
        if ($count[0]==0){
            return false;
        }else{
            return true;
        }
    }
    //----Mostrar Delegado
    public function VerDelegado($codCurso) {
        $result = mysql_query("SELECT 
                u.idPersona id
                ,concat_ws(' ',u.Nombre ,u.Apellidos) nombre
                ,ub.EstadoUsuario estado
                ,e.delegado delegado
            FROM Usuario ub
            INNER JOIN PERSONA u ON ub.IdUsuario = u.IdPersona
            INNER JOIN ALUMNO b ON ub.IdUsuario = b.IdAlumno
            INNER JOIN CURSO_ALUMNO e ON ub.IdUsuario = e.IdAlumno
            INNER JOIN CURSO c ON e.idCurso = c.idCurso
            WHERE c.CodigoCurso = '$codCurso' AND e.Estado = 'activo' AND delegado='si'");
        $num_rows = mysql_num_rows($result); 

        if ($num_rows > 0) {
            $rows = array();
            while($r = mysql_fetch_assoc($result)) {
              //$rows[] = $r;
              $rows[] = array_map('htmlentities',$r);
            }
            return $rows;
        } else {
            return false;
        }
    }
    //==================MENSAJE LISTAR
    public function ListarMensaje($idServicio) {
        $con = mysqli_connect("localhost","root","","bd_upt_touch_a");
        $result = mysqli_query($con,"SELECT 
            ub.IdServicio id
            ,ub.NombreServicio
            FROM Servicios ub
            INNER JOIN detalleServicios c ON ub.IdServicio = c.IdServicio
            WHERE ub.IdServicio = '$idServicio' --AND estado='activo'
            ORDER BY c.fechaServicio DESC");
            if($result){
                $num_rows = mysqli_num_rows($result);

                mysqli_free_result($result);
            }
        

        if ($num_rows = 0) {
            $rows = array();
            while($r = mysqli_fetch_assoc($con,$result)) {
              //$rows[] = $r;
              $rows[] = array_map('htmlentities',$r);
            }
            return $rows;
        } else {
            return false;
        }
    }
    //---DESCARTAR Mensaje
     public function DescartarMensaje($idMensaje) {
      $validar =  mysql_query("SELECT COUNT(*) estado from MENSAJE WHERE IdMensaje='$idMensaje'");
      $result = mysql_query("UPDATE MENSAJE SET estado = '2' WHERE IdMensaje = '$idMensaje'");
        $count = mysql_fetch_row($validar);
        if ($count[0]==0){
            return false;
        }else{
            return true;
        }
    }
    //---AGREGAR Mensaje
     public function AgregarMensaje($IdCurso,$IdDocente,$titulo,$detalle,$fecha) {

      $result = mysql_query("INSERT INTO MENSAJE (IdCurso,IdDocente,titulo,detalle,fecha,estado) VALUES('$IdCurso','$IdDocente','$titulo','$detalle','$fecha','1')"  );
        if ($result) {
            return true;
        } else {
            return false;
        }

    }
    //---ACTUALIZAR mensaje
    public function ActualizarMensaje($idMensaje,$titulo,$detalle,$fecha) {
    $validar =  mysql_query("SELECT COUNT(*) estado from MENSAJE WHERE IdMensaje='$idMensaje'");
    $result = mysql_query("UPDATE MENSAJE SET titulo = '$titulo' , detalle = '$detalle',fecha ='$fecha' WHERE IdMensaje = '$idMensaje'");
    $count = mysql_fetch_row($validar);
        if ($count[0]==0){
            return false;
        }else{
            return true;
        }
    }

    //----

}
?>





