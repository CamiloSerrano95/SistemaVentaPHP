var tabla;

function init() {
    mostrarFormulario(false);
    listar();

    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    })
}

function limpiar() {
    $("#codigo").val("");
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#stock").val("");
}

function mostrarFormulario(flag) {
    limpiar();

    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
    } else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
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
        $("#nombre").val(data.nombre);
        $("#descripcion").val(data.descripcion);
        $("#codigo").val(data.codigo);
        $("#stock").val(data.stock);
        $("#idarticulo").val(data.idarticulo);
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

init();