$(document).on("ready", init);// Inciamos el jquery


var email = "";

function init() {
    var total = 0.0;
    //Ver();
    $('#tblVentaPedido').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ]
    });

    ListadoVenta();// Ni bien carga la pagina que cargue el metodo
    ComboTipo_Documento();
    GetPrimerIDTicket();


    $("#VerFormPed").hide();
    $("#VerForm").hide();// Ocultamos el formulario
    $("#btnGenerarProforma").click(SaveOrUpdate);// Evento submit de jquery que llamamos al metodo SaveOrUpdate para poder registrar o modificar datos
    $("#cboTipoComprobante").change(VerNumSerie);
    $("#btnNuevo").click(VerForm);// evento click de jquery que llamamos al metodo VerForm
    $("#btnNuevoPedido").click(VerFormPedido);
    $("form#frmcreditos").submit(Savecredito);
    // donde ejecuta la funcion interna "AbrirModalCliente linea 181" el boton con id "btnBuscarCliente" al hacer  click
    $("#btnBuscarCliente").click(AbrirModalCliente);
    $("#btnBuscarDetIng").click(AbrirModalDetPed);
    $("#btnAgregarCliente").click(function (e) {
        e.preventDefault();

        var opt = $("input[type=radio]:checked");
        $("#txtIdCliente").val(opt.val());
        $("#txtCliente").val(opt.attr("data-nombre"));
        email = opt.attr("data-email");

        $("#modalListadoCliente").modal("hide");
    });

    function ComboTipo_Documento() {

        $.get("./ajax/PedidoAjax.php?op=listTipoDoc", function (r) {
            $("#cboTipoComprobante").html(r);

        })
    }

    $("#btnAgregarArtPed").click(function (e) {
        e.preventDefault();

        var opt = tablaArtPed.$("input[name='optDetIngBusqueda[]']:checked", {"page": "all"});

        opt.each(function () {
            AgregarDetallePed($(this).val(), $(this).attr("data-nombre"), $(this).attr("data-precio-venta"), "1", "0.0", $(this).attr("data-stock-actual"), $(this).attr("data-codigo"), $(this).attr("data-serie"));
        })

        $("#modalListadoArticulosPed").modal("hide");
    });

    // OBETENEMOS EL ID DE TICKET
    function GetPrimerIDTicket() {
        var data = {

            txtIdSucursal: $("#txtIdSucursal").val(),

        };
        $.post("./ajax/VentaAjax.php?op=GetPrimerIDTicket", data, function (r) {// llamamos la url por post. function(r). r-> llamada del callback

            //$.toaster({ priority : 'success', title : 'Mensaje', message : r});

            $("#txtNumeroVent").val(r)

        });
    }

    function SaveOrUpdate(e) {

        e.preventDefault();// para que no se recargue la pagina
        swal("estamos dentro de este buvle ");

        var detalle = JSON.parse(consultarDet());
        var data = {
            idUsuario: $("#txtIdUsuario").val(),
            idSucursal: $("#txtIdSucursal").val(),
            idCliente: $("#txtIdCliente").val(),
            tipo_Pedido: $("#cboTipoPedido").val(),
            tipo_pago: $("#tipo_pago").val(),
            descuento: $("#descuento").val(),
            tiempo_entrega: $("#txtTiempoEntrega").val(),
            fecha_validez: $("#cboFechaValidez").val(),
            impuesto: $("#txtImpuestoPed").val(),
            total_vent: $("#txtTotalPed").val(),
            Numero_TF: $("#txtNumeroVent").val(),
            tipo_comprobante: $("#cboTipoComprobante").val(),
            detalle: detalle
        };

        $.post("./ajax/VentaAjax.php?op=SaveOrUpdate", data, function (r) {// llamamos la url por post. function(r). r-> llamada del callback
            location.href = "../petshop/Venta.php";

            //
            var es = String(r);
            window.open('./Reportes/exVenta.php?id=' + es, 'target', ' toolbar=0 , location=1 , status=0 , menubar=1 , scrollbars=0');

        });
    }

    function Savecredito(e) {
        e.preventDefault();// para que no se recargue la pagina
        $.post("./ajax/CreditoAjax.php?op=SaveOrUpdate", $(this).serialize(), function (r) {// llamamos la url por post. function(r). r-> llamada del callback

            swal("Mensaje del Sistema", r, "success");
            $("#modalcredito").modal("hide");
            OcultarForm();
            ListadoVenta();
            ListadoPedidos();
        });
    }

    function GetIdVenta() {

        $.get("./ajax/CreditoAjax.php?op=GetIdVenta", function (r) {
            $("#txtIdVentaCred").val(r);

        })
    }

    function ComboTipoDocumentoS_N() {

        $.get("./ajax/VentaAjax.php?op=listTipo_DocumentoPersona", function (r) {
            $("#cboTipoDocumentoSN").html(r);

        })
    }

    function GetTotal(idPedido) {
        $.getJSON("./ajax/VentaAjax.php?op=GetTotal", {idPedido: idPedido}, function (r) {
            if (r) {
                total = r.Total;
                $("#txtTotalVent").val(total);

                var igvPed = total * parseInt($("#txtImpuesto").val()) / (100 + parseInt($("#txtImpuesto").val()));
                $("#txtIgvPedVer").val(Math.round(igvPed * 100) / 100);

                var subTotalPed = total - (total * parseInt($("#txtImpuesto").val()) / (100 + parseInt($("#txtImpuesto").val())));
                $("#txtSubTotalPedVer").val(Math.round(subTotalPed * 100) / 100);

                $("#txtTotalPedVer").val(Math.round(total * 100) / 100);
            }
        });
    }

    function VerNumSerie() {
        var nombre = $("#cboTipoComprobante").val();
        var idsucursal = $("#txtIdSucursal").val();

        $.getJSON("./ajax/VentaAjax.php?op=GetTipoDocSerieNum", {nombre: nombre, idsucursal: idsucursal}, function (r) {
            if (r) {
                $("#txtIdTipoDoc").val(r.iddetalle_documento_sucursal);
                $("#txtSerieVent").val(r.ultima_serie);
                $("#txtNumeroVent").val(r.ultimo_numero);
            } else {
                $("#txtIdTipoDoc").val("");
                $("#txtSerieVent").val("");
                $("#txtNumeroVent").val("");
            }
        });

    }

    function VerFormPedido() {
        $("#VerFormPed").show();// Mostramos el formulario
        $("#btnNuevoPedido").hide();// ocultamos el boton nuevo
        $("#btnGenerarVenta").hide();
        $("#VerListado").hide();// ocultamos el listado
        $("#btnReporte").hide();
    }

    function VerForm() {
        $("#VerForm").show();// Mostramos el formulario
        $("#btnNuevo").hide();// ocultamos el boton nuevo
        $("#VerListado").hide();// ocultamos el listado
        $("#btnReporte").hide();
    }

    //funcion donde llamamos al formulario de clientes y ejecutamos
    function AbrirModalCliente() {
        $("#modalListadoCliente").modal("show");

        $.post("./ajax/VentaAjax.php?op=listClientesV", function (r) {//cambiar a uno propio
            $("#Cliente").html(r);//linea 327 venta.html donde esta el id=cliente
            $("#tblClientees").DataTable();
        });
    }

    function AbrirModalDetPed() {
        $("#modalListadoArticulosPed").modal("show");
        var tabla = $('#tblArticulosPed').dataTable(
            {
                "aProcessing": true,
                "aServerSide": true,
                "iDisplayLength": 4,
                //"aLengthMenu": [0, 4],
                "aoColumns": [
                    {"mDataProp": "0"},
                    {"mDataProp": "1"},
                    {"mDataProp": "2"},
                    {"mDataProp": "3"},
                    {"mDataProp": "4"},
                    {"mDataProp": "5"},
                    {"mDataProp": "6"},
                    {"mDataProp": "7"},
                    {"mDataProp": "8"},
                    {"mDataProp": "9"},
                    {"mDataProp": "10"},
                    {"mDataProp": "11"}
                    //------------------------


                ], "ajax":
                    {
                        url: './ajax/VentaAjax.php?op=listDetIng',
                        type: "get",
                        dataType: "json",

                        error: function (e) {
                            console.log(e.responseText);
                        }
                    },
                "bDestroy": true

            }).DataTable();
    }

    function OcultarForm() {
        $("#VerForm").hide();// Mostramos el formulario
        $("#VerListado").show();// ocultamos el listado
        $("#btnReporte").show();
        $("#btnNuevo").show();
        $("#VerFormVentaPed").hide();
        $("#btnNuevoVent").show();
        // $("#lblTitlePed").html("Pedidos");
    }


    function LimpiarPedido() {
        $("#txtIdCliente").val("");
        $("#txtCliente").val("");

        $("#cboTipoPedido").val("Pedido");
        $("#txtNumeroPed").val("");
        elementos.length = 0;
        $("#tblDetallePedido tbody").html("");
        $("#txtSerieVent").val("");
        $("#txtNumeroVent").val("");
        GetNextNumero();
    }

    function GetTotal(idPedido) {
        $.getJSON("./ajax/VentaAjax.php?op=GetTotal", {idPedido: idPedido}, function (r) {
            if (r) {
                total = r.Total;
                $("#txtTotalVent").val(total);

                var igvPed = total * parseInt($("#txtImpuesto").val()) / (100 + parseInt($("#txtImpuesto").val()));
                $("#txtIgvPedVer").val(Math.round(igvPed * 100) / 100);

                var subTotalPed = total - (total * parseInt($("#txtImpuesto").val()) / (100 + parseInt($("#txtImpuesto").val())));
                $("#txtSubTotalPedVer").val(Math.round(subTotalPed * 100) / 100);

                $("#txtTotalPedVer").val(Math.round(total * 100) / 100);
            }
        });
    }

    function GetNextNumero() {
        $.getJSON("./ajax/PedidoAjax.php?op=GetNextNumero", function (r) {
            if (r) {
                $("#txtNumeroPed").val(r.numero);
            }
        });
    }


}

function ListadoVenta() {
    var tabla = $('#tblVentaPedido').dataTable(
        {
            "aProcessing": true,
            "aServerSide": true,
            dom: 'Bfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            "aoColumns": [
                {"mDataProp": "0"},
                {"mDataProp": "1"},
                {"mDataProp": "2"},
                {"mDataProp": "3"},
                {"mDataProp": "4"},
                {"mDataProp": "5"},
                {"mDataProp": "6"}

            ], "ajax":
                {
                    url: './ajax/VentaAjax.php?op=list',
                    type: "get",
                    dataType: "json",

                    error: function (e) {
                        console.log(e.responseText);
                    }
                },
            "bDestroy": true

        }).DataTable();
};

function ConsultarDetallesPed() {
    $("table#tblDetallePedido tbody").html("");
    var data = JSON.parse(objinit.consultar());

    for (var pos in data) {

        //----------------------------------------------------------DETALLE DE VENTA ---------------------------------------------------------------------------

        $("table#tblDetallePedido").append("<tr><td>" + data[pos][1] + " <input class='form-control' type='hidden' name='txtIdDetIng' id='txtIdDetIng[]' value='" + data[pos][0] + "' /></td><td> " + data[pos][6] + "</td><td> " + data[pos][7] + "</td><td>" + data[pos][5] + "</td><td><input class='form-control' type='text' name='txtPrecioVentPed' readonly id='txtPrecioVentPed[]' value='" + data[pos][2] + "' onchange='calcularTotalPed(" + pos + ")' /></td><td><input class='form-control' type='text' name='txtCantidaPed' id='txtCantidaPed[]'   value='" + data[pos][3] + "' onchange='calcularTotalPed(" + pos + ")' /></td><td><input class='form-control' type='hidden' name='txtDescuentoPed' id='txtDescuentoPed[]'  value='" + data[pos][4] + "' onchange='calcularTotalPed(" + pos + ")' /></td><td><button type='button' onclick='eliminarDetallePed(" + pos + ")' class='btn btn-danger'><i class='fa fa-remove' ></i> </button></td></tr>");
    }
    calcularIgvPed();
    calcularSubTotalPed();
    calcularTotalPed();
}

function eliminarDetallePed(ele) {
    console.log(ele);
    objinit.eliminar(ele);
    ConsultarDetallesPed();
}

function eliminarVenta(id) {// funcion que llamamos del archivo ajax/CategoriaAjax.php?op=delete linea 53
    bootbox.confirm("¿Esta Seguro de eliminar el Venta seleccionado?", function (result) { // confirmamos con una pregunta si queremos eliminar
        if (result) {// si el result es true
            $.post("./ajax/VentaAjax.php?op=delete", {id: id}, function (e) {// llamamos la url de eliminar por post. y mandamos por parametro el id


                swal("Mensaje del Sistema", e, "success");

                location.reload();
            });
        }

    })
}

function AgregarPedCarrito(iddet_ing, stock_actual, art, cod, serie, precio_venta) {

    if (stock_actual > 0) {
        var detalles = new Array(iddet_ing, art, precio_venta, "1", "0.0", stock_actual, cod, serie);
        elementos.push(detalles);
        ConsultarDetallesPed();
    } else {
        bootbox.alert("No se puede agregar al detalle. No tiene stock");
    }

}

function pasarIdPedido(idPedido, total, correo) {// funcion que llamamos del archivo ajax/CategoriaAjax.php linea 52
    $("#VerForm").show();// mostramos el formulario
    $("#VerListado").hide();// ocultamos el listado
    $("#btnNuevoPedido").hide();
    $("#VerTotalesDetPedido").hide();

    $("#txtIdPedido").val(idPedido);
    $("#txtTotalVent").val(total);
    email = correo;
    AgregatStockCant(idPedido);
    CargarDetallePedido(idPedido);
    var igvPed = total * parseInt($("#txtImpuesto").val()) / (100 + parseInt($("#txtImpuesto").val()));
    $("#txtIgvPed").val(Math.round(igvPed * 100) / 100);

    var subTotalPed = total - (total * parseInt($("#txtImpuesto").val()) / (100 + parseInt($("#txtImpuesto").val())));
    $("#txtSubTotalPed").val(Math.round(subTotalPed * 100) / 100);

    $("#txtTotalVent").val(Math.round(total * 100) / 100);
}
