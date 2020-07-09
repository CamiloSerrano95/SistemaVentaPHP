var tabla;

function init() {
    mostrarFormulario(false);
    listar();

    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    })
}

function limpiar() {
    $("#idcategoria").val("");
    $("#nombre").val("");
    $("#descripcion").val("");
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
            url: '../ajax/categoria.php?op=listar',
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
		url: "../ajax/categoria.php?op=guardaryeditar",
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
    $.post("../ajax/categoria.php?op=mostrar", {idcategoria: id}, function (data, status) {
        data = JSON.parse(data);
        mostrarFormulario(true);
        $("#nombre").val(data.nombre);
        $("#descripcion").val(data.descripcion);
        $("#idcategoria").val(data.idcategoria);
    });
}

function desactivar(id) {
    bootbox.confirm("Esta seguro de desactivar la categoria?", function (result) {
        if (result) {
            $.post("../ajax/categoria.php?op=desactivar", {idcategoria: id}, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

function activar(id) {
    bootbox.confirm("Esta seguro de activar la categoria?", function (result) {
        if (result) {
            $.post("../ajax/categoria.php?op=activar", {idcategoria: id}, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

init();