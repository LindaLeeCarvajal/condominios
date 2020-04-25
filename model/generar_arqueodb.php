<?php

require_once "Conexion.php";


/**

 *

 */

class generar_arqueo

{

  function get_info_tb_mc_ingresos ($idusuario,$idsucursal) {

      global $conexion;
      $today =  date("Y-m-d");

      $consulta = "SELECT motivo, monto from movimiento_caja where idusuario=$idusuario and idsucursal=$idsucursal and fecha='$today' and tipo_operacion = 'INGRESO'";

      $query = $conexion->query($consulta);

      return $query;
  }


  function get_total_ingresos ($idusuario,$idsucursal) {

    	global $conexion;
      $today = date("Y-m-d");

      $consulta = "SELECT SUM(monto) AS total_ingreso from movimiento_caja where idusuario=$idusuario and idsucursal=$idsucursal and fecha='$today' and tipo_operacion = 'INGRESO'";

      $query = $conexion->query($consulta);

      return $query;
  }

  function get_info_tb_ventas_ingresos ($idusuario,$idsucursal) {

      global $conexion;
      $today = date("Y-m-d");

      $sql = "SELECT tipo_venta,tipo_pago,total,recibi FROM venta WHERE idusuario=$idusuario and idsucursal=$idsucursal and fecha='$today' and estado = 'A'  ";
/*
      $sql = "SELECT venta.total , detalle_pedido.cantidad ,detalle_pedido.precio_venta,articulo.nombre,venta.tipo_venta,venta.recibi FROM venta INNER join  detalle_pedido on venta.idventa=detalle_pedido.idventa INNER JOIN detalle_ingreso on detalle_ingreso.iddetalle_ingreso=detalle_pedido.iddetalle_ingreso INNER join articulo on articulo.idarticulo =detalle_ingreso.idarticulo WHERE venta.idusuario=$idusuario and venta.idsucursal=$idsucursal and venta.fecha='$today' and  venta.estado = 'A'  ";
*/
      $query = $conexion->query($sql);

      return $query;
  }


  function get_total_ventas_ingresos_tipo_pago ($idusuario,$idsucursal,$tipo_pago,$tipo_venta) {

      global $conexion;
      $today = date("Y-m-d");

      if ($tipo_venta=='credito') {
          $sql = "SELECT sum(total) as total_venta FROM venta where idusuario = $idusuario AND idsucursal = $idsucursal and fecha='$today' and estado = 'A' and tipo_venta='$tipo_venta'"  ;
      } else {
          $sql = "SELECT sum(total) as total_venta FROM venta where idusuario = $idusuario AND idsucursal = $idsucursal and fecha='$today' and estado = 'A' and tipo_pago='$tipo_pago' and tipo_venta='$tipo_venta'"  ;
      }

      $query = $conexion->query($sql);

      return $query;
  }

  function get_total_ventas_ingresos_credito ($idusuario,$idsucursal) {

      global $conexion;
      $today = date("Y-m-d");

      $sql = "SELECT sum(total) as total_recibido FROM venta where idusuario = $idusuario AND idsucursal = $idsucursal and fecha='$today' and venta.estado = 'A' and venta.tipo_venta='credito'"  ;

      $query = $conexion->query($sql);

      return $query;
  }

  function get_total_ventas_ingresos ($idusuario,$idsucursal) {

      global $conexion;
      $today = date("Y-m-d");

      $sql = "SELECT sum(total) as total_venta FROM venta where idusuario = $idusuario AND idsucursal = $idsucursal and fecha='$today' and estado = 'A' "  ;

      $query = $conexion->query($sql);

      return $query;
  }

  function get_info_tb_mc_salidas ($idusuario,$idsucursal) {

    	global $conexion;
      $today = date("Y-m-d");

      $consulta = "SELECT motivo , monto  from movimiento_caja where idusuario=$idusuario and idsucursal=$idsucursal and fecha='$today' and tipo_operacion = 'EGRESO'";

      $query = $conexion->query($consulta);

      return $query;
  }

  function get_total_salida_ma ($idusuario,$idsucursal) {

      global $conexion;
      $today = date("Y-m-d");

      $sql = "SELECT  SUM(monto)  AS total_salida from movimiento_caja where idusuario=$idusuario and  idsucursal= $idsucursal and fecha='$today' and tipo_operacion = 'EGRESO'";

      $query = $conexion->query($sql);

      return $query;

  }

}
