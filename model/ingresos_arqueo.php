<?php

	require "Conexion.php";

	require "get_date.php";



	class DocSucursal{

	public function MontoCajaAbierta($idsucursal,$idusuario) {
		global $conexion;
		$today = date("Y-m-d");

		$sql = "select monto from movimiento_caja where idsucursal= $idsucursal and idusuario = $idusuario and tipo_operacion='APERTURA' and fecha=curdate()";

		$query = $conexion->query($sql);

		$monto = 0;

		while ($regCajaAbierta = $query->fetch_object()) {
			$monto = $regCajaAbierta->monto;
		}

		return $monto;

	}


	public function RegistrarApertura($id_sucursal,$motivo,$monto,$id_usuario,$tipo_transaccion) {

		global $conexion;
		$today = date("Y-m-d");

		$sw = true;
		$sql_detalle = "INSERT INTO movimiento_caja(motivo,monto, idusuario,idsucursal,fecha,tipo_operacion)
						VALUES('$motivo','$monto' , '$id_usuario', '$id_sucursal',curdate(),'$tipo_transaccion')";
		$conexion->query($sql_detalle) or $sw = false;
		return $sw;
	}

	public function ModificarApertura($idsucursal, $idusuario, $monto) {
		global $conexion;
		$today = date("Y-m-d");

		$sql = "UPDATE movimiento_caja SET monto = $monto
				WHERE idsucursal = $idsucursal  AND  idusuario = $idusuario AND fecha=curdate() and tipo_operacion='APERTURA'" ;
		$query = $conexion->query($sql);
		return $query;
	}




		public function Registrar($detalle,$id_sucursal,$motivo,$monto,$id_usuario,$tipo_transaccion){

			global $conexion;
			$today = date("Y-m-d");

			$sw = true;

			//0 = idsucursal

			//1 = tipo Documento

			//3= monto

			//4 = razon



				foreach($detalle as $indice => $valor){



					$sql_detalle = "INSERT INTO movimiento_caja(idingresocaja,motivo,monto, idusuario,idsucursal,fecha,tipo_operacion)

											VALUES(null,'$valor[3]','$valor[4]' , '$id_usuario', '$id_sucursal',curdate(),'$tipo_transaccion')";



					$conexion->query($sql_detalle) or $sw = false;

				}





		return $sw;

		}













		public function Modificar($iddetalle_documento_sucursal, $idsucursal, $motivo, $monto,$usuario, $transaccion){

			global $conexion;
			$today = date("Y-m-d");

			$sql = "UPDATE movimiento_caja set motivo = '$motivo',

						monto = $monto, fecha = curdate() , tipo_operacion ='$transaccion'

						where idingresocaja = $iddetalle_documento_sucursal AND idsucursal = $idsucursal  AND  idusuario = $usuario " ;

			$query = $conexion->query($sql);

			return $query;

		}



		public function Eliminar($iddetalle_documento_sucursal){

			global $conexion;

			$sql = "DELETE FROM movimiento_caja

						where idingresocaja = $iddetalle_documento_sucursal";

			$query = $conexion->query($sql);

			return $query;

		}



		public function ListarTipoDocumento(){

			global $conexion;

			$sql = "select * from tipo_documento where operacion = 'Operacion' and nombre<>'APERTURA'";

			$query = $conexion->query($sql);

			return $query;

		}

		public function Listar_tipo_movimiento($nombre_operacion){

			global $conexion;

			$sql = "select idtipo_documento from tipo_documento where nombre = '$nombre_operacion'";

			$query = $conexion->query($sql);

			return $query;

		}









		public function ListarDetalleDocSuc($idsucursal,$idusuario){

/*$obj = new get_date();

$fecha_servidor = $obj->get_fecha();

$x= (string)$fecha_servidor;

*/

$fecha = date("Y-m-d");

$fecha2 = date("Y-m-d");



			global $conexion;

			$sql = "select  idingresocaja,motivo,monto,idusuario,idsucursal,fecha,tipo_operacion from movimiento_caja where idsucursal= $idsucursal and idusuario = $idusuario and tipo_operacion<>'APERTURA'";

			$query = $conexion->query($sql);

			return $query;

		}



	}
