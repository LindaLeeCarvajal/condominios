<?php
	require "Conexion.php";
//creacion de la clase persona
	class Persona{





		public function __construct(){
		}


// vamos hacer la consulta si existe nuestro clientes
public function cliente_existe($nombre,$num_documento){
		global $conexion;
		$sql="select idpersona from persona where nombre='$nombre' and num_documento ='$num_documento'";
	$query = $conexion->query($sql);
return $query;





}




		public function Registrar($tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion_departamento,$direccion_provincia,$direccion_distrito,$direccion_calle,$telefono,$email,$numero_cuenta,$estado){
			global $conexion;
			$sql = "INSERT INTO persona(tipo_persona,nombre,tipo_documento,num_documento,direccion_departamento,direccion_provincia,direccion_distrito,direccion_calle,telefono,email,numero_cuenta,estado)
						VALUES('$tipo_persona','$nombre','$tipo_documento','$num_documento','$direccion_departamento','$direccion_provincia','$direccion_distrito','$direccion_calle','$telefono','$email','$numero_cuenta','$estado')";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Modificar($idpersona,$tipo_persona,$nombre,$tipo_documento,$num_documento,$direccion_departamento,$direccion_provincia,$direccion_distrito,$direccion_calle,$telefono,$email,$numero_cuenta,$estado){
			global $conexion;
			$sql = "UPDATE persona set tipo_persona = '$tipo_persona',nombre = '$nombre',tipo_documento='$tipo_documento',num_documento='$num_documento', direccion_departamento = '$direccion_departamento',direccion_provincia='$direccion_provincia',direccion_distrito='$direccion_distrito',direccion_calle='$direccion_calle' ,telefono='$telefono',email='$email',numero_cuenta='$numero_cuenta',
					estado='$estado'
						WHERE idpersona = $idpersona";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Eliminar($idpersona){
			global $conexion;
			$sql = "UPDATE persona SET estado='C' WHERE idpersona = $idpersona";
			$query = $conexion->query($sql);
			return $query;
		}
		public function Listar(){
			global $conexion;
			$sql = "SELECT * FROM persona where estado = 'A' order by idpersona desc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ListarProveedor(){
			global $conexion;
			$sql = "SELECT * FROM persona where tipo_persona='Proveedor' and estado = 'A' order by idpersona desc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ReporteProveedor(){
			global $conexion;
			$sql = "SELECT * FROM persona where tipo_persona='Proveedor' order by nombre asc";
			$query = $conexion->query($sql);
			return $query;
		}

		public function ReporteCliente(){
			global $conexion;
			$sql = "SELECT * FROM persona where tipo_persona='Cliente' order by nombre asc";
			$query = $conexion->query($sql);
			return $query;
		}
//funcion de listar clientes 
		public function ListarCliente(){
			global $conexion;
			$sql = "SELECT * FROM persona where tipo_persona='Cliente' and estado = 'A' order by idpersona desc";
			$query = $conexion->query($sql);
			return $query;
		}



	}
