var tabla;

function init() {
    mostrarFormulario(false);
    listar();

    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    })

    $.post("../ajax/articulo.php?op=selectCategoria", function (res) {
        $('#idcategoria').html(res);
        $('#idcategoria').selectpicker('refresh');
    });

    $("#imagenmuestra").hide();
}

function limpiar() {
    $("#codigo").val("");
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#stock").val("");
    $("#imagenmuestra").attr("src", "");
    $("#imagenactual").val("");
    $("#print").hide();
    $("#idarticulo").val("");
}

function mostrarFormulario(flag) {
    limpiar();

    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
    } else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }
}

function cancelarFormulario() {
    limpiar();
    mostrarFormulario(false);
}

function listar() {
    tabla = $("#tbllistado").dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdf'
        ],
        "ajax": {
            url: '../ajax/articulo.php?op=listar',
            type: "get",
            dateType: "json",
            error: function (e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 5,
        "order": [[0, "desc"]]
    }).DataTable();
}

function guardaryeditar(e) {
    e.preventDefault();
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/articulo.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
        processData: false,
        success: function(res) {    
            bootbox.alert(res);	          
	        mostrarFormulario(false);
	        tabla.ajax.reload();
	    }
	});
	limpiar();
}

function mostrar(id) {
    $.post("../ajax/articulo.php?op=mostrar", {idarticulo: id}, function (data, status) {
        data = JSON.parse(data);
        mostrarFormulario(true);
        $("#idcategoria").val(data.idcategoria);
        $('#idcategoria').selectpicker('refresh');
        $("#nombre").val(data.nombre);
        $("#descripcion").val(data.descripcion);
        $("#codigo").val(data.codigo);
        $("#stock").val(data.stock);
        $("#idarticulo").val(data.idarticulo);
        $("#imagenmuestra").show();
        $("#imagenmuestra").attr("src", `../files/articulos/${data.imagen}`);
        $("#imagenactual").val(data.imagen);
        generarBarCode();
    });
}

function desactivar(id) {
    bootbox.confirm("Esta seguro de desactivar el articulo?", function (result) {
        if (result) {
            $.post("../ajax/articulo.php?op=desactivar", {idarticulo: id}, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

function activar(id) {
    bootbox.confirm("Esta seguro de activar el articulo?", function (result) {
        if (result) {
            $.post("../ajax/articulo.php?op=activar", {idarticulo: id}, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

function generarBarCode() {
    codigo = $("#codigo").val();
    JsBarcode("#barcode", codigo);
    $("#print").show();
}

function imprimir() {
    $("#print").printArea();
}

init();