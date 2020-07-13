var tabla;

function init() {
	mostrarform(false);
	listar();

	$("#formulario").on("submit", function(e) {
		guardaryeditar(e);	
	})

	$("#imagenmuestra").hide();
	
	$.post("../ajax/usuario.php?op=permisos&id=", function(r){
	    $("#permisos").html(r);
	});
}

function limpiar() {
	$("#nombre").val("");
	$("#num_documento").val("");
	$("#direccion").val("");
	$("#telefono").val("");
	$("#email").val("");
	$("#cargo").val("");
	$("#login").val("");
	$("#clave").val("");
	$("#imagenmuestra").attr("src","");
	$("#imagenactual").val("");
	$("#idusuario").val("");
}

function mostrarform(flag) {
	limpiar();
	if (flag) {
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
	} else {
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

function cancelarform() {
	limpiar();
	mostrarform(false);
}

function listar() {
	tabla = $('#tbllistado').dataTable({
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
			url: '../ajax/usuario.php?op=listar',
			type : "get",
			dataType : "json",						
			error: function(e){
				console.log(e.responseText);	
			}
		},
		"bDestroy": true,
		"iDisplayLength": 5,
	    "order": [[ 0, "desc" ]]
	}).DataTable();
}

function guardaryeditar(e) {
	e.preventDefault(); 
	$("#btnGuardar").prop("disabled", true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/usuario.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,
	    success: function(datos) {                    
	        bootbox.alert(datos);	          
	        mostrarform(false);
	        tabla.ajax.reload();
	    }
	});
	limpiar();
}

function mostrar(id) {
	$.post("../ajax/usuario.php?op=mostrar",{idusuario : id}, function(data, status) {
		data = JSON.parse(data);		
		mostrarform(true);

		$("#nombre").val(data.nombre);
		$("#tipo_documento").val(data.tipo_documento);
		$("#tipo_documento").selectpicker('refresh');
		$("#num_documento").val(data.num_documento);
		$("#direccion").val(data.direccion);
		$("#telefono").val(data.telefono);
		$("#email").val(data.email);
		$("#cargo").val(data.cargo);
		$("#login").val(data.login);
		$("#clave").val(data.clave);
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src",`../files/usuarios/${data.imagen}`);
		$("#imagenactual").val(data.imagen);
		$("#idusuario").val(data.idusuario);
    });
     
 	$.post(`../ajax/usuario.php?op=permisos&id=${id}`,function(r){
	    $("#permisos").html(r);
	});
}

function desactivar(id) {
	bootbox.confirm("¿Está Seguro de desactivar el usuario?", function(result){
		if(result) {
        	$.post("../ajax/usuario.php?op=desactivar", {idusuario : id}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

function activar(id) {
	bootbox.confirm("¿Está Seguro de activar el Usuario?", function(result){
		if(result) {
        	$.post("../ajax/usuario.php?op=activar", {idusuario : id}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

init();