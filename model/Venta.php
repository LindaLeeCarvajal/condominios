<?php

require "Conexion.php";


class Venta
{


    public function __construct()
    {

    }


    public function Registrar($idsucursal, $idusuario, $idcliente, $tipo_pedido, $tipo_pago, $descuento, $tiempo_entrega, $fecha_validez, $impuesto, $total_vent, $tipo_comprobante, $numero_TF, $detalle)
    {

        global $conexion;
        $today = date("Y-m-d");

        $sw = true;
        try {


            $sql = "INSERT INTO venta(idCliente,idusuario,idSucursal,tipo_comprobante,CodigoAutorizacion,num_comprobante, fecha ,total,estado,Recibi,Cambio,estado_factura,tipo_pago,tipo_venta,descuento,tiempo_entrega,fecha_validez)

						VALUES('$idcliente','$idusuario','$idsucursal','$tipo_comprobante',null,'$numero_TF', '$today','$total_vent','A','0','0',null,'$tipo_pago','$tipo_pedido','$descuento','$tiempo_entrega','$fecha_validez')";

            //var_dump($sql);

            $conexion->query($sql);

            // recuperamos el idventa de la tabla venta

            $get_id_venta = "select  idventa  from venta where num_comprobante = '$numero_TF' and  total = '$total_vent' and Recibi= '$recibi' ";

            $consulta = $conexion->query($get_id_venta);


            while ($reg = $consulta->fetch_object()) {

                $idVenta = $reg->idventa;

            }

            // ahora agregamos lo que falta en la tabla venta despues de recuperar el idVenta

            $conexion->autocommit(true);

            foreach ($detalle as $indice => $valor) {

                $sql_detalle = "INSERT INTO detalle_pedido(idventa, iddetalle_ingreso, cantidad, precio_venta,estado_pedido)

													VALUES($idVenta, " . $valor[0] . ", " . $valor[3] . ", " . $valor[2] . ",'A')";

                $conexion->query($sql_detalle);

            }


            // ahora por ultimo vamos a actualziar el valor  del ticket   +1 cada ves que compre

            $entero = intval($numero_TF);


            $cant_letra = strlen($entero);


            $parte_izquierda = substr($numero_TF, 0, -$cant_letra);


            $suma = $entero + 1;


            $numero = $parte_izquierda . "" . $suma;

            //Recuperamos el id  de ticket

            $get_id_ticket = "select  idtipo_documento  from tipo_documento where nombre = '$tipo_comprobante'";

            $consultax = $conexion->query($get_id_ticket);


            while ($regx = $consultax->fetch_object()) {

                $iddo = $regx->idtipo_documento;

            }


            $sql_detalle_doc = "UPDATE detalle_documento_sucursal set ultimo_numero = '$numero' where idtipo_documento = '$iddo'";

            //var_dump($sql);

            $conexion->query($sql_detalle_doc) or $sw = false;


            if ($conexion != null) {

                $conexion->close();

            }


        } catch (Exception $e) {

            $conexion->rollback();

        }

        return $idVenta;

    }


    public function Modificar($idventa, $idpedido, $idusuario, $tipo_venta, $tipo_comprobante, $serie_comprobante, $num_comprobante, $impuesto, $total, $estado, $descuento, $tiempo_entrega, $fecha_validez)
    {

        global $conexion;
        $today = date("Y-m-d");

        $sql = "UPDATE venta set idpedido = '$idpedido',idusuario='$idusuario',tipo_venta='$tipo_venta',serie_comprobante	='$serie_comprobante',num_comprobante='$num_comprobante', fecha = '$today', impuesto='$impuesto',total='$total',estado='$estado',descuento='$descuento',tiempo_estimado='$tiempo_estimado',fecha_validez='$fecha_validez'

						WHERE idventa = $idventa";

        $query = $conexion->query($sql);

        return $query;

    }


    public function Eliminar($idventa)
    {

        global $conexion;

        $sql = "DELETE FROM venta WHERE idventa = $idventa";

        $query = $conexion->query($sql);

        return $query;

    }


    public function Listar($idsucursal)
    {

        global $conexion;

        $sql = "SELECT venta.idventa,venta.descuento,venta.tiempo_entrega,venta.fecha_validez,persona.nombre,fecha,total,tipo_comprobante  ,detalle_pedido.estado_pedido from persona,venta,detalle_pedido

WHERE venta.idSucursal = $idsucursal

and venta.idCliente =persona.idpersona

and detalle_pedido.idventa = venta.idventa

and venta.tipo_comprobante = 'PROFORMA'

order by venta.idventa desc limit 0,2999";


        $query = $conexion->query($sql);

        return $query;

    }


    public function ListarClientes()
    {

        global $conexion;

        $sql = "select * from persona where tipo_persona = 'Cliente'";

        $query = $conexion->query($sql);

        return $query;

    }


    public function GetTipoDocSerieNum($nombre, $idsucursal)
    {

        global $conexion;

        $sql = "select dds.iddetalle_documento_sucursal, dds.ultima_serie, dds.ultimo_numero

	from detalle_documento_sucursal dds inner join tipo_documento td on dds.idtipo_documento = td.idtipo_documento

	where td.operacion = 'Comprobante' and nombre = '$nombre' and dds.idsucursal='$idsucursal'";

        $query = $conexion->query($sql);

        return $query;

    }


    public function TotalPedido($idpedido)
    {

        global $conexion;

        $sql = "select sum((cantidad * precio_venta) - descuento) as Total

	from detalle_pedido where idventa = $idpedido";

        $query = $conexion->query($sql);

        return $query;

    }


    public function ListarTipoDocumento($idsucursal)
    {

        global $conexion;

        $sql = "select dds.*, td.nombre

	from detalle_documento_sucursal dds inner join tipo_documento td on dds.idtipo_documento = td.idtipo_documento

	where dds.idsucursal = $idsucursal and operacion = 'Comprobante' and td.nombre='PROFORMA'";

        $query = $conexion->query($sql);

        return $query;

    }


    public function ListarDetalleIngresos($idsucursal)
    {

        global $conexion;

        $sql = "SELECT DISTINCT di.iddetalle_ingreso, di.stock_actual, a.nombre AS Articulo, a.codigo_interno, a.minima, a.idarticulo, di.codigo, di.serie AS caducidad, di.precio_ventapublico, a.imagen, a.descripcion, a.numero, a.instruccion, a.vrestringida, di.serie, i.fecha, c.nombre AS presentacion, u.nombre AS unidad_medida, u.prefijo FROM ingreso i INNER JOIN detalle_ingreso di ON di.idingreso = i.idingreso INNER JOIN sucursal s ON s.idsucursal = i.idsucursal INNER JOIN articulo a ON di.idarticulo = a.idarticulo INNER JOIN categoria c ON a.idcategoria = c.idcategoria INNER JOIN unidad_medida u ON a.idunidad_medida = u.idunidad_medida

					where i.estado = 'A' and i.idsucursal = $idsucursal and di.stock_actual > 0 order by a.idarticulo desc";

        $query = $conexion->query($sql);

        return $query;

    }


    public function Get_id_ticket($Sucursal)
    {

        global $conexion;


        //Recuperamos el ID DE TICKET


        $get_id_ticket = "select  idtipo_documento  from tipo_documento where nombre = 'PROFORMA'";

        $consultax = $conexion->query($get_id_ticket);


        while ($regx = $consultax->fetch_object()) {

            $iddo = $regx->idtipo_documento;

        }


        // ahora con esto traemos el ultimo valor que tenia ticket

        $sql = "SELECT ultimo_numero from detalle_documento_sucursal where idtipo_documento ='$iddo' and idsucursal = '$Sucursal'";

        $query = $conexion->query($sql);


        while ($reg1 = $query->fetch_object()) {

            $iduTicket = $reg1->ultimo_numero;


        }


        return $iduTicket;

    }


    public function CambiarTipoPedido($idpedido)
    {

        global $conexion;

        $sql = "UPDATE venta set tipo_pedido = 'Venta' where idpedido = 21";

        $query = $conexion->query($sql);

        return $query;

    }


}

