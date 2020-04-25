$(document).on("ready", init);

function init(){

	var tabla = $('#1tblArticulos').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });

		var tabla = $('#1tblArticulosC').dataTable({
					dom: 'Bfrtip',
					buttons: [
							'copyHtml5',
							'excelHtml5',
							'csvHtml5',
							'pdfHtml5'
					]
			});

	ListadoArticulos2();
		ListadoArticulos2C();
	ComboCategoria();
	ComboUM();
	$("#VerForm").hide();
	$("#txtRutaImgArt").hide();

	$("form#afrmArticulos").submit(modificar);

	$("#btnNuevo").click(VerForm);


	function modificar(e){
			e.preventDefault();

	        var formData = new FormData($("#afrmArticulos")[0]);

	        $.ajax({

	                url: "./ajax/AlmacenAjax.php?op=modificar",

	                type: "POST",

	               data: formData,

	                contentType: false,

	                processData: false,

	                success: function(datos)

	                {

	                    swal("Mensaje del Sistema", datos, "success");
						  ListadoArticulos2();
						  OcultarForm();
						  $('#afrmArticulos').trigger("reset");
	                }

	            });
	};





	function ComboCategoria(){
			$.post("./ajax/ArticuloAjax.php?op=listCategoria", function(r){
	            $("#cboCategoria").html(r);
	        });
	}

	function ComboUM(){
			$.post("./ajax/ArticuloAjax.php?op=listUM", function(r){
	            $("#cboUnidadMedida").html(r);
	        });
	}

	function Limpiar(){
			$("#txtIdArticulo").val("");
		    $("#txtNombre").val("");
	}

	function VerForm(){
			$("#VerForm").show();
			$("#btnNuevo").hide();
			$("#VerListado").hide();
	}

	function OcultarForm(){
			$("#VerForm").hide();// Mostramos el formulario
			$("#btnNuevo").show();// ocultamos el boton nuevo
			$("#VerListado").show();
	}
}


	function ListadoArticulos2(){
	var tabla = $('#1tblArticulos').dataTable(
		{   "aProcessing": true,
       		"aServerSide": true,
       		dom: 'Bfrtip',
	        buttons: [
	            'copyHtml5',
	            'excelHtml5',
	            'csvHtml5',
	            'pdfHtml5'
	        ],
        	"aoColumns":[
        	     	{   "mDataProp": "id"},
                {   "mDataProp": "1"},
					      {   "mDataProp": "2"},
                {   "mDataProp": "3"},
                {   "mDataProp": "4"},
                {   "mDataProp": "5"},
                {   "mDataProp": "6"},
					      {   "mDataProp": "7"},
					      {   "mDataProp": "8"},
						    {   "mDataProp": "9"},
						    {   "mDataProp": "10"},
						    {   "mDataProp": "11"},
								{   "mDataProp": "12"},
								{   "mDataProp": "13"},
								{   "mDataProp": "14"},

        	],"ajax":
	        	{
	        		url: './ajax/AlmacenAjax.php?op=list4',
					type : "get",
					dataType : "json",

					error: function(e){
				   		console.log(e.responseText);
					}
	        	},
	        "bDestroy": true

    	}).DataTable();

    };
		function ListadoArticulos2C(){
		var tabla = $('#1tblArticulosC').dataTable(
			{   "aProcessing": true,
						"aServerSide": true,
						dom: 'Bfrtip',
						buttons: [
								'copyHtml5',
								'excelHtml5',
								'csvHtml5',
								'pdfHtml5'
						],
						"aoColumns":[
									{   "mDataProp": "id"},
											{   "mDataProp": "1"},
						 {   "mDataProp": "2"},
											{   "mDataProp": "3"},
											{   "mDataProp": "4"},
											{   "mDataProp": "5"},
											{   "mDataProp": "6"},
						 {   "mDataProp": "7"},
							{   "mDataProp": "8"},
							{   "mDataProp": "9"},



						],"ajax":
							{
								url: './ajax/AlmacenAjax.php?op=list4C',
						type : "get",
						dataType : "json",

						error: function(e){
								console.log(e.responseText);
						}
							},
						"bDestroy": true

				}).DataTable();

			};
function eliminarArticulo2(id){
	bootbox.confirm("���Esta Seguro de eliminar la Articulo?", function(result){
		if(result){
			$.post("./ajax/AlmacenAjax.php?op=delete", {id : id}, function(e){

				swal("Mensaje del Sistema", e, "success");
				ListadoArticulos2();

            });
		}

	})
}




function cargarDataArticulo2(stock,venta,idarticulo, preciomayor, idalmacensucursal, iddetalleingreso, preciocompra){
		$("#VerForm").show();
		$("#btnNuevo").hide();
		$("#VerListado").hide();
		$("#txtIdArticulo").val(idarticulo);

		 $("#txtstock").val(stock);

			   $("#txtprecio").val(venta);
				 $("#txtpreciomayor").val(preciomayor);
				 $("#txtidsucursal").val(idalmacensucursal);
				 $("#txtiddetalleingreso").val(iddetalleingreso);
				 $("#txtpreciocompra").val(preciocompra);



}
