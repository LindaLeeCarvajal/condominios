<?php
	require "Conexion.php";

	class articulo{


		public function __construct(){
		}

		public function Registrar($idcategoria, $idunidad_medida, $nombre, $descripcion, $imagen,$instruccion,$numero,$vrestringida){
			global $conexion;
			$sql = "INSERT INTO articulo(idcategoria, idunidad_medida, nombre, descripcion, imagen, estado,instruccion,numero,vrestringida)
						VALUES($idcategoria, $idunidad_medida, '$nombre', '$descripcion', '$imagen', 'A','$instruccion','$numero','$vrestringida')";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Modificar($stock,$venta ,$idarticulo, $mayor, $idSucursal, $iddetalleingreso, $preciocompra){
			global $conexion;
			$sql = "UPDATE detalle_ingreso set stock_actual  = $stock, precio_ventapublico= $venta, precio_ventadistribuidor=$mayor, precio_compra=$preciocompra

						WHERE idarticulo = $idarticulo and  iddetalle_ingreso=$iddetalleingreso";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Eliminar($idarticulo){
			global $conexion;
			$sql = "delete from detalle_ingreso WHERE idarticulo = $idarticulo";
			$query = $conexion->query($sql);
			return $query;
		}

		public function Listar(){
			global $conexion;
			$sql = "SELECT a.nombre AS articulo, s.razon_social, s.idsucursal, a.codigo_interno AS codigo_interno,(select nombre from persona where persona.idpersona=i.idproveedor) as proveedor, a.minima, a.idarticulo AS idarticulo, a.imagen AS imagen, a.descripcion AS dolencia, a.instruccion AS instruccion, a.numero AS numero, a.vrestringida AS vrestringida, max(di.iddetalle_ingreso) as iddetalle_ingreso, di.idingreso, di.idarticulo, di.codigo, di.serie, di.descripcion, di.stock_ingreso,
(SELECT stock_actual FROM detalle_ingreso INNER JOIN articulo ON detalle_ingreso.idarticulo = articulo.idarticulo INNER JOIN ingreso ON detalle_ingreso.idingreso = ingreso.idingreso INNER JOIN sucursal  ON ingreso.idsucursal = sucursal.idsucursal where articulo.idarticulo = a.idarticulo and sucursal.idsucursal = s.idsucursal ORDER by iddetalle_ingreso DESC limit 1) as stock_actual , (SELECT precio_compra FROM detalle_ingreso INNER JOIN articulo ON detalle_ingreso.idarticulo = articulo.idarticulo INNER JOIN ingreso ON detalle_ingreso.idingreso = ingreso.idingreso INNER JOIN sucursal  ON ingreso.idsucursal = sucursal.idsucursal where articulo.idarticulo = a.idarticulo and sucursal.idsucursal = s.idsucursal ORDER by iddetalle_ingreso DESC limit 1) as P_compra, (SELECT precio_ventadistribuidor FROM detalle_ingreso INNER JOIN articulo ON detalle_ingreso.idarticulo = articulo.idarticulo INNER JOIN ingreso ON detalle_ingreso.idingreso = ingreso.idingreso INNER JOIN sucursal  ON ingreso.idsucursal = sucursal.idsucursal where articulo.idarticulo = a.idarticulo and sucursal.idsucursal = s.idsucursal ORDER by iddetalle_ingreso DESC limit 1) as P_mayor,  (SELECT precio_ventapublico FROM detalle_ingreso INNER JOIN articulo ON detalle_ingreso.idarticulo = articulo.idarticulo INNER JOIN ingreso ON detalle_ingreso.idingreso = ingreso.idingreso INNER JOIN sucursal  ON ingreso.idsucursal = sucursal.idsucursal where articulo.idarticulo = a.idarticulo and sucursal.idsucursal = s.idsucursal ORDER by iddetalle_ingreso DESC limit 1) as P_venta,( di.stock_ingreso * di.precio_compra ) AS sub_total, un.nombre AS marca, un.prefijo AS procedencia FROM detalle_ingreso di INNER JOIN articulo a ON di.idarticulo = a.idarticulo INNER JOIN unidad_medida un ON a.idunidad_medida = un.idunidad_medida INNER JOIN ingreso i ON di.idingreso = i.idingreso INNER JOIN sucursal s ON i.idsucursal = s.idsucursal GROUP BY a.idarticulo, s.idsucursal ORDER BY a.nombre ASC";
			$query = $conexion->query($sql);
			return $query;
		}
		/*                   ************************Listar sin sucursal**********************
		public function Listar(){
			global $conexion;
			$sql = "SELECT a.nombre AS articulo, a.codigo_interno AS codigo_interno, a.minima,a.idarticulo AS idarticulo, a.imagen AS imagen, a.descripcion AS dolencia, a.instruccion AS instruccion, a.numero AS numero, a.vrestringida AS vrestringida, di.*, ( di.stock_ingreso * di.precio_compra ) AS sub_total, un.nombre as marca, un.prefijo as procedencia FROM detalle_ingreso di INNER JOIN articulo a ON di.idarticulo = a.idarticulo INNER JOIN unidad_medida un ON a.idunidad_medida = un.idunidad_medida WHERE di.idingreso = idingreso";
			$query = $conexion->query($sql);
			return $query;
		}
*/

		public function Reporte(){
			global $conexion;
			$sql = "select a.*, c.nombre as categoria, um.nombre as unidadMedida
	from articulo a inner join categoria c on a.idcategoria = c.idcategoria
	inner join unidad_medida um on a.idunidad_medida = um.idunidad_medida where a.estado = 'A' order by a.nombre asc";
			$query = $conexion->query($sql);
			return $query;
		}

	}
