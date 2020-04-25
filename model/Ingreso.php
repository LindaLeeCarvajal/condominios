<?php
require "Conexion.php";

class Ingreso
{

    public function Registrar($idusuario, $idsucursal, $idproveedor, $tipo_comprobante, $numero_factura, $codigo_control, $tipo_compra, $numero_autorizacion, $impuesto, $total, $total_descuento, $detalle)
    {
        global $conexion;
        $sw = true;
        try {


            $sql = "INSERT INTO ingreso(idusuario, idsucursal, idproveedor, tipo_comprobante, serie_comprobante, codigo_control, tipo_ingreso, numero_autorizacion,fecha, impuesto,
						total, estado, total_descuento)
						VALUES($idusuario, $idsucursal, $idproveedor, '$tipo_comprobante', '$numero_factura','$codigo_control','$tipo_compra', '$numero_autorizacion',curdate(), $impuesto, $total, 'A', $total_descuento)";
            //var_dump($sql);
            $conexion->query($sql);
            $idingreso = $conexion->insert_id;

            $conexion->autocommit(true);

            require_once "../model/Articulo.php";

            $objArticulo = new Articulo();

            foreach ($detalle as $indice => $valor) {

                $query_Tipo = $objArticulo->GetStock($idsucursal, $valor[0]);
                if ($reg = $query_Tipo->fetch_object()) {
                    $stockActual = $reg->stock_actual;
                } else {
                    $stockActual = 0;
                }


                $suma = $stockActual + $valor[4];

                $sql_detalle = "INSERT INTO detalle_ingreso(idingreso, idarticulo, codigo, serie, descripcion, stock_ingreso, stock_ingreso2, stock_actual, precio_compra, precio_ventadistribuidor, precio_ventapublico)
											VALUES($idingreso, " . $valor[0] . ", '" . $valor[1] . "', '" . $valor[2] . "', '" . $valor[3] . "', '" . $valor[4] . "', '" . $valor[4] . "', '" . $suma . "', '" . $valor[6] . "', '" . $valor[7] . "', '" . $valor[8] . "')";
                $conexion->query($sql_detalle) or $sw = false;

                $conexion->autocommit(true);

                $sql_cantidad = "update articulo set P_compra = '" . $valor[6] . "', P_venta = '" . $valor[8] . "', P_mayor= '" . $valor[7] . "', P_distribuidor='" . $valor[1] . "',	P_auspicio= '" . $valor[2] . "',	cantidad= '" . $suma . "'
										 where idarticulo = " . $valor[0] . " ";
                $conexion->query($sql_cantidad);

            }
            if ($conexion != null) {
                $conexion->close();
            }

        } catch (Exception $e) {
            $conexion->rollback();

        }
        return $sw;
    }

    public function GetIdIngreso()
    {
        global $conexion;
        $sql = "select max(idingreso) as id from ingreso";
        $query = $conexion->query($sql);
        return $query;
    }

    public function RegistrarC($idingreso, $fecha_pago, $total_pago)
    {
        global $conexion;
        $sql = "INSERT INTO creditos(idventa, idingreso,fecha_pago, total_pago, tipo_credito)
						VALUES( 0, $idingreso, curdate(), $total_pago, 'Compra')";
        $query = $conexion->query($sql);
        return $query;
    }

    public function ModificarC($idcredito, $idventa, $fecha_pago, $total_pago)
    {
        global $conexion;
        $sql = "UPDATE creditos set idventa = '$idventa',fecha_pago='$fecha_pago', total_pago = $total_pago
						WHERE idcredito = $idcredito";
        $query = $conexion->query($sql);
        return $query;
    }

    public function Listar($idsucursal)
    {
        global $conexion;
        $sql = "select i.*, d.idarticulo, p.nombre as proveedor
			from ingreso as i
			left join persona as p on i.idproveedor = p.idpersona
			left join detalle_ingreso as d on i.idingreso = d.idingreso
			where i.idingreso<>'1'
            and i.idsucursal = $idsucursal
            order by i.idingreso desc
            limit 0,2999";
        $query = $conexion->query($sql);
        return $query;
    }

    public function CambiarEstado($idingreso, $idArticulo)
    {
        global $conexion;

        $idsucursal = $_SESSION["idsucursal"];
        require_once "../model/Articulo.php";

        $objArticulo = new Articulo();





        $objArticulo = new Articulo();

        $sqlIngreso = "select * from detalle_ingreso where idingreso = $idingreso";
        $queryIngreso = $conexion->query($sqlIngreso);


        $sqlIngresoUltimo = "select max(idingreso) as ingreso from detalle_ingreso";
        $queryIngresoUltimo = $conexion->query($sqlIngresoUltimo);
        $reIngresoUtl = $queryIngresoUltimo->fetch_assoc();
      //  var_dump(  $reIngresoUtl);
        while ($reg = $queryIngreso->fetch_assoc()) {
            // $resta = $reg["stock_ingreso"] - $reg["stock_actual"];
            /*$sql_cantidad = "update articulo set cantidad = cantidad - '" . $reg["stock_ingreso"] . "'
                                          where idarticulo = " . $reg['idarticulo'] . " ";
            $conexion->query($sql_cantidad);*/
            $sql_detalle_cantidad = "UPDATE detalle_ingreso, ingreso
             SET detalle_ingreso.stock_actual = detalle_ingreso.stock_actual - '" . $reg["stock_ingreso"] . "'
             WHERE ingreso.idsucursal = $idsucursal
             AND  detalle_ingreso.idingreso='" . $reIngresoUtl['ingreso']. "'
             AND detalle_ingreso.idarticulo = '" . $reg["idarticulo"] . "'";
             $conexion->query($sql_detalle_cantidad);
              printf("%s",$conexion->error);

            /*$sql_detalle_cantidad = "update detalle_ingreso set stock_actual = $resta
                                          where idarticulo = " . $reg['idarticulo'] . "";
            $conexion->query($sql_detalle_cantidad);*/
        }

        $sql = "UPDATE ingreso set estado = 'C'
						WHERE idingreso = $idingreso";
        $query = $conexion->query($sql);


        return $query;


    }


    public function GetDetalleArticulo($idingreso)
    {
        global $conexion;
        $sql = "select a.nombre as articulo, a.numero, a.codigo_interno as color, di.precio_compra as P_compra, di.precio_ventapublico as P_venta, di.precio_ventadistribuidor as P_mayor, di.codigo as P_distribuidor, di.serie as P_auspicio,
			di.*, (di.stock_ingreso * di.precio_compra) as sub_total, (select nombre from unidad_medida where unidad_medida.idunidad_medida=a.idunidad_medida)as marca
			from detalle_ingreso di inner join articulo a on di.idarticulo = a.idarticulo where di.idingreso =$idingreso";
        $query = $conexion->query($sql);
        return $query;
    }

    public function GetProveedorSucursalIngreso($idingreso)
    {
        global $conexion;
        $sql = "select p.*, ped.fecha,ped.tipo_comprobante, ped.num_comprobante, ped.serie_comprobante, s.razon_social, s.tipo_documento as documento_sucursal, s.num_documento as num_sucursal, s.direccion, s.telefono as telefono_suc,
	s.email as email_suc, s.representante, s.logo, sum(di.stock_ingreso * di.precio_compra) as total, ped.total_descuento , ped.impuesto
	from persona p inner join ingreso ped on ped.idproveedor = p.idpersona
	inner join detalle_ingreso di on ped.idingreso = di.idingreso
	inner join sucursal s on ped.idsucursal = s.idsucursal
	where ped.idingreso = $idingreso";
        $query = $conexion->query($sql);
        return $query;
    }

    public function ListarProveedor()
    {
        global $conexion;
        $sql = "select * from persona where tipo_persona = 'Proveedor' and estado = 'A'";
        $query = $conexion->query($sql);
        return $query;
    }

    public function ListarTipoDocumento()
    {
        global $conexion;
        $sql = "select * from tipo_documento where operacion = 'Comprobante' and nombre <> 'PROFORMA' order by idtipo_documento DESC ";
        $query = $conexion->query($sql);
        return $query;
    }

    public function GetTipoDocSerieNum($nombre)
    {
        global $conexion;
        $sql = "select ultima_serie, ultimo_numero from tipo_documento where operacion = 'Comprobante' and nombre = '$nombre'";
        $query = $conexion->query($sql);
        return $query;
    }

    public function ListarProveedores()
    {
        global $conexion;
        $sql = "select * from persona where tipo_perssona = 'Proveedor'";
        $query = $conexion->query($sql);
        return $query;
    }

}
