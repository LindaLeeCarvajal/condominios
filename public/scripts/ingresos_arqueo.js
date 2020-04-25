$(document).on("ready", init);

var objinit = new init();

var bandera = 1;

function init() {

    elementos = new Array();

    	$('#tb1listar').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });
  ListadoDocSuc();
    ComboTipoDoc();
    ListadoDocSuc();

    $("#VerForm").hide();

    $("#btnAgregarSN").click(AgregarDetalleDocumentoSucursal);
    $("#btnNuevoDocSuc").click(VerForm);
   // $("#cboTipoDocumento").change(VerNumSerie);

    $("form#frmDocSuc").submit(GuardarDocSucursal);

    function AgregarDetalleDocumentoSucursal(){

        var idtipo_doc = $("#tipomovimiento").val(),
            tipo_doc = $("#tipomovimiento option:selected").html(),
            idsucursal = $("#txtIdSucursal").val(),
            serie = $("#txtmotivo").val(),
            numero = $("#txtmonto").val();

            if(idtipo_doc != "" && idsucursal != "" && serie !="" && numero !=""){
            AgregarDetalleDocSuc(idsucursal, idtipo_doc, tipo_doc, serie, numero);

        }
        ConsultarDetallesDocSuc();
        $("#txtmonto").val("");
        $("#txtmotivo").val("");

    }


    function GuardarDocSucursal(e){
        e.preventDefault();

        var detalle =  JSON.parse(consultar());

        var data = [];

        if (bandera == 1) {
            data  = {
                idDocSucursal : $("#txtIdDocSucursal").val(),
                idSucursal: $("#txtIdSucursal").val(),
                tipo_transaccion: $("#tipomovimiento option:selected").html(),
                motivo: $("#txtmotivo").val(),
                monto : $("#txtmonto").val(),
                idusuario :$("#txtIdUsuario").val(),
                detalle : detalle
            };
        } else {
            data = {
                idDocSucursal : $("#txtIdDocSucursal").val(),
                idSucursal: $("#txtIdSucursal").val(),
                tipo_transaccion: $("#tipomovimiento option:selected").html(),
                motivo: $("#txtmotivo").val(),
                monto : $("#txtmonto").val(),
                idusuario :$("#txtIdUsuario").val(),

            };
        }

        $.post("./ajax/ingresos_arqueoAjax.php?op=Save", data, function(r){
          swal("Mensaje del Sistema", r, "success");
           Limpiar();
            ListadoDocSuc();
            OcultarForm();

        });

    }

    function ListadoDocSuc(){
        var tabla = $('#tb1listar').dataTable(
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
                      {   "mDataProp": "0"},
                      {   "mDataProp": "1"},
                      {   "mDataProp": "2"},
                      {   "mDataProp": "3"},
                      {   "mDataProp": "4"},


                    ],"ajax":
          	        	{
                     url: './ajax/ingresos_arqueoAjax.php?op=list',
                     type : "get",
           					dataType : "json",

           					error: function(e){
           				   		console.log(e.responseText);
           					}
           	        	},
           	        "bDestroy": true

               	}).DataTable();

               };

    function ComboTipoDoc() {

        $.get("./ajax/ingresos_arqueoAjax.php?op=listTipoDoc", function(r) {
                $("#tipomovimiento").html(r);

        })
    }

    function VerForm(){
        bandera = 1;
        $("#VerForm").show();
        $("#btnNuevoDocSuc").hide();
        $("#VerListado").hide();
        $("#btnAgregarSN").show();
        $("#tblingresoarqueo").show();
    }
    function Limpiar (){
      $("#txtIdDocSucursal").val("");
      $("#cboTipoDocumento").val("");
      $("#txtSerie").val("");
      $("#txtNumero").val("");
      elementosReg.length = 0;
      elementos.length = 0;
      $("#tb1listar tbody").html("");
    }

    function OcultarForm(){
        $("#VerForm").hide();
        $("#btnNuevoDocSuc").show();
        $("#VerListado").show();
    }


    function AgregarDetalleDocSuc(idsucursal, idtipo_doc, tipo_doc, serie, numero) {

        var detalles = new Array(idsucursal, idtipo_doc, tipo_doc, serie, numero);
        elementos.push(detalles);
        ConsultarDetallesDocSuc();
    }

    function consultar() {
        return JSON.stringify(elementos);
    }

    this.eliminar = function(pos){
        //var pos = elementos[].indexOf( 'c' );
        console.log(pos);

        pos > -1 && elementos.splice(parseInt(pos),1);
        console.log(elementos);

        //this.elementos.splice(pos, 1);
        //console.log(this.elementos);
    };

    this.consultar = function(){
        /*
        for(i=0;i<this.elementos.length;i++){
            for(j=0;j<this.this.elementos[i].length;j++){
                console.log("Elemento: "+this.elementos[i][j]);
            }
        }
        */
        return JSON.stringify(elementos);
    };

};

function eliminarDetalleDocSuc(ele){
        console.log(ele);
        objinit.eliminar(ele);
        ConsultarDetallesDocSuc();
    }


function ConsultarDetallesDocSuc() {

        $("table#tblingresoarqueo tbody").html("");
        var data = JSON.parse(objinit.consultar());

        for (var pos in data) {
            $("table#tblingresoarqueo").append("<tr><td>"+data[pos][2]+"</td><td>"+data[pos][3]+"</td><td>"+data[pos][4]+"</td><td><button type='button' onclick='eliminarDetalleDocSuc(" + pos + ")' class='btn btn-danger'><i class='fa fa-remove' ></i> </button></td></tr>");
        }
        //calcularIgvPed();
        //calcularSubTotalPed();
        //calcularTotalPed();
    }

    function cargarDataDocSucursal(idingresocaja, tipo_documento, motivo, monto){
        bandera = 2;
        $("#VerForm").show();
        $("#btnNuevoDocSuc").hide();
        $("#VerListado").hide();
       // $("#VerMod").show();


        $("#txtIdDocSucursal").val(idingresocaja);// aqui vamos a mandarle el idde cajarque
        $("#tipomovimiento").val(tipo_documento);
        $("#txtmotivo").val(motivo);
        $("#txtmonto").val(monto);
        $("#btnAgregarSN").hide();
        $("#tblingresoarqueo").hide();
    }

    function eliminarDocSucursal(id){
        bootbox.confirm("¿Esta Seguro de Eliminar el registro?", function(result){
            if(result){
                $.post("./ajax/ingresos_arqueoAjax.php?op=delete", {id : id}, function(e){

                    swal("Mensaje del Sistema", e, "success");

                    location.href ="../petshop/ingresos_arqueo.php";


                });
            }

        })
    }

/*
    function Modificar(pos){
        var idDetIng = document.frmDocSucursals.elements["txtIdDetIng[]"];
        var pvd = document.frmDocSucursals.elements["txtPrecioVentPed[]"];
        var cantPed = document.frmDocSucursals.elements["txtCantidaPed[]"];
        var descPed = document.frmDocSucursals.elements["txtDescuentoPed[]"];
       // alert(pos);

        elementos[pos][0] = idDetIng[pos].value;
        elementos[pos][2] = pvd[pos].value;
        elementos[pos][3] = cantPed[pos].value;
        elementos[pos][4] = descPed[pos].value;
        ConsultarDetalles();
    }

    */
