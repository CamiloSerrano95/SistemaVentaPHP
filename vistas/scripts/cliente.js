var tabla;

function init() {
    mostrarFormulario(false);
    listar();

    $("#formulario").on("submit", function (e) {
        guardaryeditar(e);
    })
}

function limpiar() {
    $("#nombre").val("");
    $("#num_documento").val("");
    $("#telefono").val("");
    $("#email").val("");
    $("#idpersona").val("");
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
            url: '../ajax/persona.php?op=listarc',
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
		url: "../ajax/persona.php?op=guardaryeditar",
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
    $.post("../ajax/persona.php?op=mostrar", {idpersona: id}, function (data, status) {
        data = JSON.parse(data);
        mostrarFormulario(true);
        
        $("#nombre").val(data.nombre);
        $("#tipo_documento").val(data.tipo_documento);
        $("#tipo_documento").selectpicker('refresh');
        $("#num_documento").val(data.num_documento);
        $("#direccion").val(data.direccion);
        $("#telefono").val(data.telefono);
        $("#email").val(data.email);
        $("#idpersona").val(data.idpersona);
    });
}

function eliminar(id) {
    bootbox.confirm("Esta seguro de eliminar este cliente?", function (result) {
        if (result) {
            $.post("../ajax/persona.php?op=eliminar", {idpersona: id}, function (e) {
                bootbox.alert(e);
                tabla.ajax.reload();
            });
        }
    });
}

init();