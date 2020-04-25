
<?php

	session_start();

	require_once "../model/Almacen.php";

	$objArticulo = new Articulo();

	switch ($_GET["op"]) {

		case 'modificar':


			$stock= $_POST["txtstock"];
			$venta= $_POST["txtprecio"];
			$preciomayor= $_POST["txtpreciomayor"];
			$idalmacensucursal= $_POST["txtidsucursal"];
			$iddetalleingreso= $_POST["txtiddetalleingreso"];
			$preciocompra= $_POST["txtpreciocompra"];



			if(true){

				if(empty($_POST["txtIdArticulo"])){

					if($objArticulo->Registrar($idcategoria, $idunidad_medida, $nombre, $descripcion, "Files/Articulo/".$ruta,$instruccion,$numero,$vrestringida)){
						echo "Articulo Registrado";
					}else{
						echo "no.";
					}
				}else{

					$idarticulo = $_POST["txtIdArticulo"];
					if($objArticulo->Modificar($stock,$venta,$_POST["txtIdArticulo"],$preciomayor, $idalmacensucursal, $iddetalleingreso, $preciocompra)){
						echo "Informacion del Articulo ha sido actualizada";
					}else{
						echo "Informacion del Articulo No ha sido actualizada.";
					}
				}
			} else {
				$ruta_img = $_POST["txtRutaImgArt"];
				if(empty($_POST["txtIdArticulo"])){

					if($objArticulo->Registrar($idcategoria, $idunidad_medida, $nombre, $descripcion, $ruta_img,$instruccion,$numero,$vrestringida)){
						echo "Articulo Registrado";
					}else{
						echo "no.";
					}
				}else{

					$idarticulo = $_POST["txtIdArticulo"];
					if($objArticulo->Modificar($idarticulo, $idcategoria, $idunidad_medida, $nombre, $descripcion, $ruta_img,$instruccion,$numero,$vrestringida)){
						echo "Informacion del Articulo ha sido actualizada";
					}else{
						echo "no.";
					}
				}
			}

			break;

		case "delete":

			$id = $_POST["id"];
			$result = $objArticulo->Eliminar($id);
			if ($result) {
				echo "Eliminado Exitosamente";
			} else {
				echo "No fue Eliminado";
			}
			break;





		case "list4":
			$query_Tipo = $objArticulo->Listar();
			$data = Array();
            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {

          $CostoTotal = $reg->P_compra * $reg->stock_actual;
					$PrecioTotal = $reg->P_venta * $reg->stock_actual;
					$PrecioMayorTotal = $reg->P_mayor * $reg->stock_actual;

					$stock_actual = $reg->stock_actual;
					$stock_minimo = $reg->minima;
          $idsucursal=$reg->idsucursal;
     if ($stock_actual <= +$stock_minimo) {
		 if ($reg->idsucursal==1) {

     		$data[] = array("id"=>$i,
				"1"=>"<span class='azulClaro'>$reg->razon_social</span>",
				"2"=>$reg->proveedor,
				"3"=>$reg->marca,
				"4"=>$reg->articulo,
				"5"=>$reg->numero,
				"6"=>"<span class='rojo'>$reg->stock_actual</span>",
				"7"=>$reg->P_compra,
				"8"=>$CostoTotal,
				"9"=>$reg->P_venta,
				"10"=>$PrecioTotal,
				"11"=>$reg->P_mayor,
				"12"=>$PrecioMayorTotal,
				"13"=>'
				<script type="text/javascript">
				Shadowbox.init();
				</script>
				<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
						"14"=>	'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->stock_actual.',\''.$reg->P_venta.'\',\''.$reg->idarticulo.'\',\''.$reg->P_mayor.'\',\''.$reg->idsucursal.'\',\''.$reg->iddetalle_ingreso.'\',\''.$reg->P_compra.'\')" data-toggle="tooltip" title="Editar" "><i class="fa fa-pencil"></i> </button>&nbsp;'.
					'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
  }
	else {
if ($reg->idsucursal==2) {

	     			$data[] = array("id"=>$i,
					"1"=>"<span class='naranjaClaro'>$reg->razon_social</span>",
					"2"=>$reg->proveedor,
					"3"=>$reg->marca,
					"4"=>$reg->articulo,
					"5"=>$reg->numero,
					"6"=>"<span class='rojo'>$reg->stock_actual</span>",
					"7"=>$reg->P_compra,
					"8"=>$CostoTotal,
					"9"=>$reg->P_venta,
					"10"=>$PrecioTotal,
					"11"=>$reg->P_mayor,
					"12"=>$PrecioMayorTotal,
					"13"=>'
					<script type="text/javascript">
					Shadowbox.init();
					</script>
					<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
							"14"=>	'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->stock_actual.',\''.$reg->P_venta.'\',\''.$reg->idarticulo.'\',\''.$reg->P_mayor.'\',\''.$reg->idsucursal.'\',\''.$reg->iddetalle_ingreso.'\',\''.$reg->P_compra.'\')" data-toggle="tooltip" title="Editar" "><i class="fa fa-pencil"></i> </button>&nbsp;'.
						'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
}
else
{
	if ($reg->idsucursal==3) {

		     			$data[] = array("id"=>$i,
						"1"=>"<span class='lilaClaro'>$reg->razon_social</span>",
						"2"=>$reg->proveedor,
						"3"=>$reg->marca,
						"4"=>$reg->articulo,
						"5"=>$reg->numero,
						"6"=>"<span class='rojo'>$reg->stock_actual</span>",
						"7"=>$reg->P_compra,
						"8"=>$CostoTotal,
						"9"=>$reg->P_venta,
						"10"=>$PrecioTotal,
						"11"=>$reg->P_mayor,
						"12"=>$PrecioMayorTotal,
						"13"=>'
						<script type="text/javascript">
						Shadowbox.init();
						</script>
						<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
								"14"=>	'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->stock_actual.',\''.$reg->P_venta.'\',\''.$reg->idarticulo.'\',\''.$reg->P_mayor.'\',\''.$reg->idsucursal.'\',\''.$reg->iddetalle_ingreso.'\',\''.$reg->P_compra.'\')" data-toggle="tooltip" title="Editar" "><i class="fa fa-pencil"></i> </button>&nbsp;'.
							'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
	}
	else {
		if ($reg->idsucursal==4) {

			     			$data[] = array("id"=>$i,
								"1"=>"<span class='verdeClaro'>$reg->razon_social</span>",
								"2"=>$reg->proveedor,
								"3"=>$reg->marca,
								"4"=>$reg->articulo,
								"5"=>$reg->numero,
								"6"=>"<span class='rojo'>$reg->stock_actual</span>",
								"7"=>$reg->P_compra,
								"8"=>$CostoTotal,
								"9"=>$reg->P_venta,
								"10"=>$PrecioTotal,
								"11"=>$reg->P_mayor,
								"12"=>$PrecioMayorTotal,
								"13"=>'
								<script type="text/javascript">
								Shadowbox.init();
								</script>
								<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
										"14"=>	'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->stock_actual.',\''.$reg->P_venta.'\',\''.$reg->idarticulo.'\',\''.$reg->P_mayor.'\',\''.$reg->idsucursal.'\',\''.$reg->iddetalle_ingreso.'\',\''.$reg->P_compra.'\')" data-toggle="tooltip" title="Editar" "><i class="fa fa-pencil"></i> </button>&nbsp;'.
									'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
		}
		else {
			if ($reg->idsucursal==5) {

				     			$data[] = array("id"=>$i,
									"1"=>"<span class='amarilloClaro'>$reg->razon_social</span>",
									"2"=>$reg->proveedor,
									"3"=>$reg->marca,
									"4"=>$reg->articulo,
									"5"=>$reg->numero,
									"6"=>"<span class='rojo'>$reg->stock_actual</span>",
									"7"=>$reg->P_compra,
									"8"=>$CostoTotal,
									"9"=>$reg->P_venta,
									"10"=>$PrecioTotal,
									"11"=>$reg->P_mayor,
									"12"=>$PrecioMayorTotal,
									"13"=>'
									<script type="text/javascript">
									Shadowbox.init();
									</script>
									<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
											"14"=>	'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->stock_actual.',\''.$reg->P_venta.'\',\''.$reg->idarticulo.'\',\''.$reg->P_mayor.'\',\''.$reg->idsucursal.'\',\''.$reg->iddetalle_ingreso.'\',\''.$reg->P_compra.'\')" data-toggle="tooltip" title="Editar" "><i class="fa fa-pencil"></i> </button>&nbsp;'.
										'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
			}
			else {
				if ($reg->idsucursal==6) {

					     		$data[] = array("id"=>$i,
									"1"=>"<span class='rojoClaro'>$reg->razon_social</span>",
									"2"=>$reg->proveedor,
									"3"=>$reg->marca,
									"4"=>$reg->articulo,
									"5"=>$reg->numero,
									"6"=>"<span class='rojo'>$reg->stock_actual</span>",
									"7"=>$reg->P_compra,
									"8"=>$CostoTotal,
									"9"=>$reg->P_venta,
									"10"=>$PrecioTotal,
									"11"=>$reg->P_mayor,
									"12"=>$PrecioMayorTotal,
									"13"=>'
									<script type="text/javascript">
									Shadowbox.init();
									</script>
									<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
											"14"=>	'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->stock_actual.',\''.$reg->P_venta.'\',\''.$reg->idarticulo.'\',\''.$reg->P_mayor.'\',\''.$reg->idsucursal.'\',\''.$reg->iddetalle_ingreso.'\',\''.$reg->P_compra.'\')" data-toggle="tooltip" title="Editar" "><i class="fa fa-pencil"></i> </button>&nbsp;'.
										'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
				}
			}
		}
	}
}
}
}

	if ($stock_actual > +$stock_minimo) {
		if ($reg->idsucursal==1) {

     			$data[] = array("id"=>$i,
					"1"=>"<span class='azulClaro'>$reg->razon_social</span>",
					"2"=>$reg->proveedor,
					"3"=>$reg->marca,
					"4"=>$reg->articulo,
					"5"=>$reg->numero,
					"6"=>"<span >$reg->stock_actual</span>",
					"7"=>$reg->P_compra,
					"8"=>$CostoTotal,
					"9"=>$reg->P_venta,
					"10"=>$PrecioTotal,
					"11"=>$reg->P_mayor,
					"12"=>$PrecioMayorTotal,
					"13"=>'
					<script type="text/javascript">
					Shadowbox.init();
					</script>
					<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
							"14"=>	'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->stock_actual.',\''.$reg->P_venta.'\',\''.$reg->idarticulo.'\',\''.$reg->P_mayor.'\',\''.$reg->idsucursal.'\',\''.$reg->iddetalle_ingreso.'\',\''.$reg->P_compra.'\')" data-toggle="tooltip" title="Editar" "><i class="fa fa-pencil"></i> </button>&nbsp;'.
							'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');

  }
	else {
if ($reg->idsucursal==2) {

	     			$data[] = array("id"=>$i,
						"1"=>"<span class='naranjaClaro'>$reg->razon_social</span>",
						"2"=>$reg->proveedor,
						"3"=>$reg->marca,
						"4"=>$reg->articulo,
						"5"=>$reg->numero,
						"6"=>"<span >$reg->stock_actual</span>",
						"7"=>$reg->P_compra,
						"8"=>$CostoTotal,
						"9"=>$reg->P_venta,
						"10"=>$PrecioTotal,
						"11"=>$reg->P_mayor,
						"12"=>$PrecioMayorTotal,
						"13"=>'
						<script type="text/javascript">
						Shadowbox.init();
						</script>
						<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
								"14"=>	'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->stock_actual.',\''.$reg->P_venta.'\',\''.$reg->idarticulo.'\',\''.$reg->P_mayor.'\',\''.$reg->idsucursal.'\',\''.$reg->iddetalle_ingreso.'\',\''.$reg->P_compra.'\')" data-toggle="tooltip" title="Editar" "><i class="fa fa-pencil"></i> </button>&nbsp;'.
								'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
}
else
{
	if ($reg->idsucursal==3) {

		     			$data[] = array("id"=>$i,
							"1"=>"<span class='lilaClaro'>$reg->razon_social</span>",
							"2"=>$reg->proveedor,
							"3"=>$reg->marca,
							"4"=>$reg->articulo,
							"5"=>$reg->numero,
							"6"=>"<span >$reg->stock_actual</span>",
							"7"=>$reg->P_compra,
							"8"=>$CostoTotal,
							"9"=>$reg->P_venta,
							"10"=>$PrecioTotal,
							"11"=>$reg->P_mayor,
							"12"=>$PrecioMayorTotal,
							"13"=>'
							<script type="text/javascript">
							Shadowbox.init();
							</script>
							<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
									"14"=>	'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->stock_actual.',\''.$reg->P_venta.'\',\''.$reg->idarticulo.'\',\''.$reg->P_mayor.'\',\''.$reg->idsucursal.'\',\''.$reg->iddetalle_ingreso.'\',\''.$reg->P_compra.'\')" data-toggle="tooltip" title="Editar" "><i class="fa fa-pencil"></i> </button>&nbsp;'.
									'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
	}
	else {
		if ($reg->idsucursal==4) {

			     			$data[] = array("id"=>$i,
								"1"=>"<span class='verdeClaro'>$reg->razon_social</span>",
								"2"=>$reg->proveedor,
								"3"=>$reg->marca,
								"4"=>$reg->articulo,
								"5"=>$reg->numero,
								"6"=>"<span >$reg->stock_actual</span>",
								"7"=>$reg->P_compra,
								"8"=>$CostoTotal,
								"9"=>$reg->P_venta,
								"10"=>$PrecioTotal,
								"11"=>$reg->P_mayor,
								"12"=>$PrecioMayorTotal,
								"13"=>'
								<script type="text/javascript">
								Shadowbox.init();
								</script>
								<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
										"14"=>	'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->stock_actual.',\''.$reg->P_venta.'\',\''.$reg->idarticulo.'\',\''.$reg->P_mayor.'\',\''.$reg->idsucursal.'\',\''.$reg->iddetalle_ingreso.'\',\''.$reg->P_compra.'\')" data-toggle="tooltip" title="Editar" "><i class="fa fa-pencil"></i> </button>&nbsp;'.
										'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
		}
		else {
			if ($reg->idsucursal==5) {

				     			$data[] = array("id"=>$i,
										"1"=>"<span class='amarilloClaro'>$reg->razon_social</span>",
										"2"=>$reg->proveedor,
										"3"=>$reg->marca,
										"4"=>$reg->articulo,
										"5"=>$reg->numero,
										"6"=>"<span >$reg->stock_actual</span>",
										"7"=>$reg->P_compra,
										"8"=>$CostoTotal,
										"9"=>$reg->P_venta,
										"10"=>$PrecioTotal,
										"11"=>$reg->P_mayor,
										"12"=>$PrecioMayorTotal,
										"13"=>'
										<script type="text/javascript">
										Shadowbox.init();
										</script>
										<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
												"14"=>	'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->stock_actual.',\''.$reg->P_venta.'\',\''.$reg->idarticulo.'\',\''.$reg->P_mayor.'\',\''.$reg->idsucursal.'\',\''.$reg->iddetalle_ingreso.'\',\''.$reg->P_compra.'\')" data-toggle="tooltip" title="Editar" "><i class="fa fa-pencil"></i> </button>&nbsp;'.
												'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
			}
			else {
				if ($reg->idsucursal==6) {

					     			$data[] = array("id"=>$i,
										"1"=>"<span class='rojoClaro'>$reg->razon_social</span>",
										"2"=>$reg->proveedor,
										"3"=>$reg->marca,
										"4"=>$reg->articulo,
										"5"=>$reg->numero,
										"6"=>"<span >$reg->stock_actual</span>",
										"7"=>$reg->P_compra,
										"8"=>$CostoTotal,
										"9"=>$reg->P_venta,
										"10"=>$PrecioTotal,
										"11"=>$reg->P_mayor,
										"12"=>$PrecioMayorTotal,
										"13"=>'
										<script type="text/javascript">
										Shadowbox.init();
										</script>
										<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=100px height=100px src="./'.$reg->imagen.'" /></a>',
												"14"=>	'<button class="btn btn-warning" onclick="cargarDataArticulo2('.$reg->stock_actual.',\''.$reg->P_venta.'\',\''.$reg->idarticulo.'\',\''.$reg->P_mayor.'\',\''.$reg->idsucursal.'\',\''.$reg->iddetalle_ingreso.'\',\''.$reg->P_compra.'\')" data-toggle="tooltip" title="Editar" "><i class="fa fa-pencil"></i> </button>&nbsp;'.
												'<button class="btn btn-danger" data-toggle="tooltip" title="Eliminar" onclick="eliminarArticulo2('.$reg->idarticulo.')"><i class="fa fa-trash"></i> </button>');
				}
			}
		}
	}
}
}
	}

				$i++;
			}
			$results = array(
            "sEcho" => 1,
        	"iTotalRecords" => count($data),
        	"iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
			echo json_encode($results);

			break;

			case "list4C":
				$query_Tipo = $objArticulo->Listar();
				$data = Array();
							$i = 1;
					while ($reg = $query_Tipo->fetch_object()) {

						$stock_actual = $reg->stock_actual;
						$stock_minimo = $reg->minima;
			$idsucursal=$reg->idsucursal;
			if ($stock_actual <= +$stock_minimo) {
			if ($reg->idsucursal==1) {

						$data[] = array("id"=>$i,
							"1"=>'
							<link rel="stylesheet" type="text/css" href="public/shadowbox/shadowbox.css">
							<script type="text/javascript" src="public/shadowbox/shadowbox.js"></script>
							<script type="text/javascript">
							Shadowbox.init();
							</script>
							<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=75px height=75px src="./'.$reg->imagen.'" /></a>',
					"2"=>$reg->articulo,
					"3"=>"<span class='azulClaro'>$reg->razon_social</span>",
					"4"=>$reg->numero,
					"5"=>$reg->marca,
					"6"=>"<span class='rojo'>$reg->stock_actual</span>",
					"7"=>$reg->dolencia,
					"8"=>$reg->instruccion,
					"9"=>$reg->vrestringida);
			}
			else {
			if ($reg->idsucursal==2) {

							$data[] = array("id"=>$i,
							"1"=>'
							<script type="text/javascript">
							Shadowbox.init();
							</script>
							<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=75px height=75px src="./'.$reg->imagen.'" /></a>',
						"2"=>$reg->articulo,
						"3"=>"<span class='naranjaClaro'>$reg->razon_social</span>",
						"4"=>$reg->numero,
						"5"=>$reg->marca,
						"6"=>"<span class='rojo'>$reg->stock_actual</span>",
						"7"=>$reg->dolencia,
						"8"=>$reg->instruccion,
						"9"=>$reg->vrestringida);
			}
			else
			{
			if ($reg->idsucursal==3) {

								$data[] = array("id"=>$i,
								"1"=>'
								<script type="text/javascript">
								Shadowbox.init();
								</script>
								<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=75px height=75px src="./'.$reg->imagen.'" /></a>',
							"2"=>$reg->articulo,
							"3"=>"<span class='lilaClaro'>$reg->razon_social</span>",
							"4"=>$reg->numero,
							"5"=>$reg->marca,
							"6"=>"<span class='rojo'>$reg->stock_actual</span>",
							"7"=>$reg->dolencia,
							"8"=>$reg->instruccion,
							"9"=>$reg->vrestringida);
			}
			else {
			if ($reg->idsucursal==4) {

									$data[] = array("id"=>$i,
									"1"=>'
									<script type="text/javascript">
									Shadowbox.init();
									</script>
									<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=75px height=75px src="./'.$reg->imagen.'" /></a>',
								"2"=>$reg->articulo,
								"3"=>"<span class='verdeClaro'>$reg->razon_social</span>",
								"4"=>$reg->numero,
								"5"=>$reg->marca,
								"6"=>"<span class='rojo'>$reg->stock_actual</span>",
								"7"=>$reg->dolencia,
								"8"=>$reg->instruccion,
								"9"=>$reg->vrestringida);
			}
			else {
				if ($reg->idsucursal==5) {

										$data[] = array("id"=>$i,
										"1"=>'
										<script type="text/javascript">
										Shadowbox.init();
										</script>
										<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=75px height=75px src="./'.$reg->imagen.'" /></a>',
									"2"=>$reg->articulo,
									"3"=>"<span class='amarilloClaro'>$reg->razon_social</span>",
									"4"=>$reg->numero,
									"5"=>$reg->marca,
									"6"=>"<span class='rojo'>$reg->stock_actual</span>",
									"7"=>$reg->dolencia,
									"8"=>$reg->instruccion,
									"9"=>$reg->vrestringida);
				}
				else {
					if ($reg->idsucursal==6) {

											$data[] = array("id"=>$i,
											"1"=>'
											<script type="text/javascript">
											Shadowbox.init();
											</script>
											<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=75px height=75px src="./'.$reg->imagen.'" /></a>',
										"2"=>$reg->articulo,
										"3"=>"<span class='rojoClaro'>$reg->razon_social</span>",
										"4"=>$reg->numero,
										"5"=>$reg->marca,
										"6"=>"<span class='rojo'>$reg->stock_actual</span>",
										"7"=>$reg->dolencia,
										"8"=>$reg->instruccion,
  									"9"=>$reg->vrestringida);
					}
				}
			}
			}
			}
			}
			}

			if ($stock_actual > +$stock_minimo) {
			if ($reg->idsucursal==1) {

						$data[] = array("id"=>$i,
						"1"=>'
						<script type="text/javascript">
						Shadowbox.init();
						</script>
						<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=75px height=75px src="./'.$reg->imagen.'" /></a>',
					"2"=>$reg->articulo,
					"3"=>"<span class='azulClaro'>$reg->razon_social</span>",

					"4"=>$reg->numero,

					"5"=>$reg->marca,

					"6"=>"<span >$reg->stock_actual</span>",

					"7"=>$reg->dolencia,
					"8"=>$reg->instruccion,

					"9"=>$reg->vrestringida);

			}
			else {
			if ($reg->idsucursal==2) {

							$data[] = array("id"=>$i,
							"1"=>'
							<script type="text/javascript">
							Shadowbox.init();
							</script>
							<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=75px height=75px src="./'.$reg->imagen.'" /></a>',
						"2"=>$reg->articulo,
						"3"=>"<span class='naranjaClaro'>$reg->razon_social</span>",

						"4"=>$reg->numero,

						"5"=>$reg->marca,

						"6"=>"<span >$reg->stock_actual</span>",

						"7"=>$reg->dolencia,
						"8"=>$reg->instruccion,

						"9"=>$reg->vrestringida);
			}
			else
			{
			if ($reg->idsucursal==3) {

								$data[] = array("id"=>$i,
								"1"=>'
								<script type="text/javascript">
								Shadowbox.init();
								</script>
								<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=75px height=75px src="./'.$reg->imagen.'" /></a>',
							"2"=>$reg->articulo,
							"3"=>"<span class='lilaClaro'>$reg->razon_social</span>",

							"4"=>$reg->numero,

							"5"=>$reg->marca,

							"6"=>"<span >$reg->stock_actual</span>",

							"7"=>$reg->dolencia,
							"8"=>$reg->instruccion,

							"9"=>$reg->vrestringida);
			}
			else {
			if ($reg->idsucursal==4) {

									$data[] = array("id"=>$i,
									"1"=>'
									<script type="text/javascript">
									Shadowbox.init();
									</script>
									<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=75px height=75px src="./'.$reg->imagen.'" /></a>',
								"2"=>$reg->articulo,
								"3"=>"<span class='verdeClaro'>$reg->razon_social</span>",

								"4"=>$reg->numero,

								"5"=>$reg->marca,

								"6"=>"<span >$reg->stock_actual</span>",

								"7"=>$reg->dolencia,
								"8"=>$reg->instruccion,

								"9"=>$reg->vrestringida);
			}
			else {
				if ($reg->idsucursal==5) {

										$data[] = array("id"=>$i,
										"1"=>'
										<script type="text/javascript">
										Shadowbox.init();
										</script>
										<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=75px height=75px src="./'.$reg->imagen.'" /></a>',
									"2"=>$reg->articulo,
									"3"=>"<span class='amarilloClaro'>$reg->razon_social</span>",

									"4"=>$reg->numero,

									"5"=>$reg->marca,

									"6"=>"<span >$reg->stock_actual</span>",

									"7"=>$reg->dolencia,
									"8"=>$reg->instruccion,

									"9"=>$reg->vrestringida);
				}
				else {
					if ($reg->idsucursal==6) {

											$data[] = array("id"=>$i,
											"1"=>'
											<script type="text/javascript">
											Shadowbox.init();
											</script>
											<a href="./'.$reg->imagen.'" rel="shadowbox"><img width=75px height=75px src="./'.$reg->imagen.'" /></a>',
										"2"=>$reg->articulo,
										"3"=>"<span class='rojoClaro'>$reg->razon_social</span>",

												"4"=>$reg->numero,

												"5"=>$reg->marca,

												"6"=>"<span >$reg->stock_actual</span>",

												"7"=>$reg->dolencia,
												"8"=>$reg->instruccion,

												"9"=>$reg->vrestringida);
					}
				}
			}
			}
			}
			}
			}

					$i++;
				}
				$results = array(
							"sEcho" => 1,
						"iTotalRecords" => count($data),
						"iTotalDisplayRecords" => count($data),
							"aaData"=>$data);
				echo json_encode($results);

				break;


		case "listArtElegir":
			$query_Tipo = $objArticulo->Listar();
			$data = Array();
            $i = 1;
     		while ($reg = $query_Tipo->fetch_object()) {
$numero = $reg->numero;
$unidad = $reg->unidadMedida;
$ress= "$numero". " ". $unidad  ;

     			$data[] = array(
     				"0"=>'<button type="button" class="btn btn-warning" data-toggle="tooltip" title="Agregar al detalle" onclick="Agregar('.$reg->idarticulo.',\''.$reg->nombre.'\')" name="optArtBusqueda[]" data-nombre="'.$reg->nombre.'" id="'.$reg->idarticulo.'" value="'.$reg->idarticulo.'" ><i class="fa fa-check" ></i> </button>',
     				"1"=>$i,
					"2"=>$reg->nombre,
					"3"=>$reg->categoria,
					"4"=>$ress,

					"5"=>$reg->descripcion,
					"6"=> $reg->vrestringida,
					"7"=>'<img width=100px height=100px src="./'.$reg->imagen.'" />');
				$i++;
            }

            $results = array(
            "sEcho" => 1,
        	"iTotalRecords" => count($data),
        	"iTotalDisplayRecords" => count($data),
            "aaData"=>$data);
			echo json_encode($results);

			break;

		case "listCategoria":
	        require_once "../model/Categoria.php";

	        $objCategoria = new Categoria();

	        $query_Categoria = $objCategoria->Listar();

	        while ($reg = $query_Categoria->fetch_object()) {
	            echo '<option value=' . $reg->idcategoria . '>' . $reg->nombre . '</option>';
	        }

	        break;

	    case "listUM":

	    	require_once "../model/Categoria.php";

	        $objCategoria = new Categoria();

	        $query_Categoria = $objCategoria->ListarUM();

	        while ($reg = $query_Categoria->fetch_object()) {
	            echo '<option value=' . $reg->idunidad_medida . '>' . $reg->nombre . '</option>';
	        }

	        break;


	}
