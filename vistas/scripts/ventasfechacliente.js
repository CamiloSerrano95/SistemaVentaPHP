var tabla;

function init() {
	listar();
	$.post("../ajax/venta.php?op=selectCliente", function(r){
	    $("#idcliente").html(r);
	    $('#idcliente').selectpicker('refresh');
	});
}

function listar() {
	var fecha_inicio = $("#fecha_inicio").val();
	var fecha_fin = $("#fecha_fin").val();
	var idcliente = $("#idcliente").val();

	tabla=$('#tbllistado').dataTable({
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
			url: '../ajax/consultas.php?op=ventasfechacliente',
			data:{fecha_inicio: fecha_inicio,fecha_fin: fecha_fin, idcliente: idcliente},
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

init();