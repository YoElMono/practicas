var app = angular.module("MyFirseModule",[]);

app.controller("FirseController",function ($scope,$http) {
	$scope.todo = [];
	$scope.ffa = [];
	$scope.bus = function () {
		var link = "index.php/get?d="+$scope.id;
		$http.get(link)
			.success(function (data){
				$scope.todo = data;
			})
			.error(function (err){
				console.log(err);
			});
	}
	$scope.bus_foto = function () {
		var link = "index.php/get_f?id="+$scope.id_foto;
		$http.get(link)
			.success(function (data){
				$scope.ffa = data;
				console.log(data);
			})
			.error(function (err){
				console.log(err);
			});
	}
});
app.controller("verificacion", function ($scope) {
	$scope.no = {
		mostrar1:false,
		mostrar2:false
	};
	$scope.mostrar = function () {
		if ($scope.estatus == 1 || $scope.estatus == 3) {
			$scope.no.mostrar1 = true;
			$scope.no.mostrar2 = false;
			if ($scope.licencia == 1) {
				$scope.no.mostrar2 = true;
			};
		}else if ($scope.estatus == 2) {
			$scope.no.mostrar2 = true;
			$scope.no.mostrar1 = false;
		};
	}
});
app.controller("ocultar", function ($scope) {
	$scope.view = {mostrar1: false,
					mostrar2: true,
					mostrar3: false,
					mostrar4: false};
	$scope.view2 = {dire : true,
					dep : false,
					ubi : false,
					con : true,
					img : false,
					tcu : false,
					conm : false,
					extesc : false,
					piso : false };
	$scope.txt = 'Autorizar';

	$scope.colors = {tono: 'panel panel-primary',
					tono2: 'btn btn-primary'};
	$scope.act2 = function () {
		if ($scope.t == 1) {
			$scope.colors.tono='panel panel-primary';
			$scope.colors.tono2='btn btn-primary';
			falso();
			$scope.view2.dire = true;
			$scope.view2.con = true;
		}else if ($scope.t == 2){
			$scope.colors.tono='panel panel-danger headrojo';
			$scope.colors.tono2='btn btn-primary radrojo';
			falso();
			$scope.view2.dire = true;
			$scope.view2.con = true;
			$scope.view2.ext = true;
			$scope.view2.img = true;
		}else if ($scope.t == 3){
			$scope.colors.tono='panel panel-success headverde';
			$scope.colors.tono2='btn btn-primary radverde';
			falso();
			$scope.view2.piso = true;
			$scope.view2.dep = true;
		}else if($scope.t == 4){
			$scope.colors.tono='panel panel-warning headama';
			$scope.colors.tono2='btn btn-primary radama';
			falso();
			$scope.view2.tcu = true;
			$scope.view2.conm = true;
			$scope.view2.extesc = true;
		}else if($scope.t == 5){
			$scope.colors.tono='panel panel-danger headnar';
			$scope.colors.tono2='btn btn-primary radnar';
			falso();
			$scope.view2.dire = true;
			$scope.view2.ubi = true;
			$scope.view2.dep = true;
			$scope.view2.piso = true;
		}else if($scope.t == 6){
			$scope.colors.tono='panel panel-default headgris';
			$scope.colors.tono2='btn btn-primary radgris';
			falso();
			$scope.view2.dire = true;
		}else{
			$scope.colors.tono='panel panel-default headext';
			$scope.colors.tono2='btn btn-primary radext';
			falso();
		}
		function falso(){
			$scope.view2.dire = false;
			$scope.view2.dep = false;
			$scope.view2.ubi = false;
			$scope.view2.con = false;
			$scope.view2.img = false;
			$scope.view2.tcu = false;
			$scope.view2.conm = false;
			$scope.view2.extesc = false;
			$scope.view2.piso = false;
		}
	}
	$scope.act1 = function () {
		if ($scope.t == 1 || $scope.t == 2 || $scope.t == 5) {
			$scope.view.mostrar1 = false;
			$scope.view.mostrar3 = false;
			$scope.view.mostrar4 = false;
			$scope.txt = 'Autorizar';
		}else if ($scope.t == 3) {
			$scope.view.mostrar1 = true;
			$scope.view.mostrar3 = false;
			$scope.view.mostrar4 = false;
			$scope.txt = 'Autorizar';
		}else if ($scope.t == 4) {
			$scope.view.mostrar1 = false;
			$scope.view.mostrar3 = true;
			$scope.view.mostrar4 = false;
			$scope.txt = 'Autorizar';
		}else{
			$scope.view.mostrar1 = false;
			$scope.view.mostrar4 = true;
			$scope.view.mostrar3 = false;
			$scope.txt = 'Buscar';
		}
		if ($scope.t == 1) {
			$scope.colors.tono='panel panel-primary';
			$scope.colors.tono2='btn btn-primary';
		}else if ($scope.t == 2){
			$scope.colors.tono='panel panel-danger headrojo';
			$scope.colors.tono2='btn btn-primary radrojo';
		}else if ($scope.t == 3){
			$scope.colors.tono='panel panel-success headverde';
			$scope.colors.tono2='btn btn-primary radverde';
		}else if($scope.t == 4){
			$scope.colors.tono='panel panel-warning headnar';
			$scope.colors.tono2='btn btn-primary radnar';
		}else if($scope.t == 5){
			$scope.colors.tono='panel panel-success headextra';
			$scope.colors.tono2='btn btn-primary radextra';
		}else{
			$scope.colors.tono='panel panel-default headext';
			$scope.colors.tono2='btn btn-primary radext';
		}
	}
	$scope.act = function () {
		if ($scope.t == 3 || $scope.t == 4) {
			$scope.view.mostrar1 = true;
			$scope.view.mostrar2 = false;
			$scope.view.mostrar3 = false;
			$scope.view.mostrar4 = false;
			$scope.txt = 'Autorizar';
		}else if($scope.t == 1 || $scope.t == 2){
			$scope.view.mostrar1 = false;
			$scope.view.mostrar2 = true;
			$scope.view.mostrar3 = false;
			$scope.view.mostrar4 = false;
			$scope.txt = 'Autorizar';
		}else if($scope.t == 5){
			$scope.view.mostrar2 = true;
			$scope.view.mostrar1 = false;
			$scope.view.mostrar3 = false;
			$scope.view.mostrar4 = false;
			$scope.txt = 'Autorizar';
		}
		else if($scope.t == 6){
			$scope.view.mostrar1 = true;
			$scope.view.mostrar2 = false;
			$scope.view.mostrar3 = true;
			$scope.view.mostrar4 = false;
			$scope.txt = 'Autorizar';
		}else if($scope.t == 7){
			$scope.view.mostrar1 = false;
			$scope.view.mostrar2 = false;
			$scope.view.mostrar3 = false;
			$scope.view.mostrar4 = true;
			$scope.txt = 'Buscar';
		}
		if ($scope.t == 1) {
			$scope.colors.tono='panel panel-primary';
			$scope.colors.tono2='btn btn-primary';
		}else if ($scope.t == 2){
			$scope.colors.tono='panel panel-danger headrojo';
			$scope.colors.tono2='btn btn-primary radrojo';
		}else if ($scope.t == 3){
			$scope.colors.tono='panel panel-success headverde';
			$scope.colors.tono2='btn btn-primary radverde';
		}else if($scope.t == 4){
			$scope.colors.tono='panel panel-warning headama';
			$scope.colors.tono2='btn btn-primary radama';
		}else if($scope.t == 5){
			$scope.colors.tono='panel panel-danger headnar';
			$scope.colors.tono2='btn btn-primary radnar';
		}else if($scope.t == 6){
			$scope.colors.tono='panel panel-default headgris';
			$scope.colors.tono2='btn btn-primary radgris';
		}else if($scope.t == 7){
			$scope.colors.tono='panel panel-success headextra';
			$scope.colors.tono2='btn btn-primary radextra';
		}else{
			$scope.colors.tono='panel panel-morado headmor';
			$scope.colors.tono2='btn btn-primary radmor';
		}
	}
});
app.controller("verificar",function ($scope) {
	$scope.verifica =  function () {
		if ($scope.pw == $scope.pw2) {
			$scope.estado = "has-success";
			$scope.view = true;
			$scope.view2 = false;
		}else{
			$scope.estado = "has-error";
			$scope.view = false;
			$scope.view2 = true;
		};
	}
});
app.controller("validar", function ($scope) {
	$scope.example = {
       value: new Date(1970, 0, 1, 00, 00, 0)
     };
});
function sub () {
	var p1 = document.getElementById("pw").value;
	var p2 = document.getElementById("pw2").value;
	if (p1 == p2) {
		return true;
	}else{
		alert("Las contraseñas no son iguales");
		return false;

	};
}

$(function(){ 
			
// Evento que se genera cada vez que se mueve el scroll del navegador 
	/*$(document).scroll(function(){ 
		// 40 es el valor de la altura del header 
		// Si aumentas el header, aumenta este valor 
		if($(document).scrollTop()>301) { 
			// Movemos la posición top del css segun el scroll 
			$("aside").css("top",$(document).scrollTop()-318);
			if ($(document).scrollTop()>668) {
				$("aside").css("top",300);
			};
		}else{ 
				// Movemos a la posición inicial 
				$("aside").css("top",0); 
			} 
		});*/

		$(window).scroll(function () {
			var top = $(this).scrollTop();
			var pos = 385-top;
			if(pos<30)pos=30;
			$('.menu,aside').css({'position':'fixed','top':pos+'px'});
			$('aside').css({'right':'48px','width':'293px'});
			//var pos2 = 300-top;
			//if(pos2<30)pos=300
		})


		if(! $("#hord").prop("checked")){$("#hora1").prop("disabled",true); $("#hora2").prop("disabled",true);} 
		if(! $("#horl").prop("checked")){$("#hora3").prop("disabled",true); $("#hora4").prop("disabled",true);} 
		if(! $("#horma").prop("checked")){$("#hora5").prop("disabled",true); $("#hora6").prop("disabled",true);} 
		if(! $("#hormi").prop("checked")){$("#hora7").prop("disabled",true); $("#hora8").prop("disabled",true);} 
		if(! $("#horj").prop("checked")){$("#hora9").prop("disabled",true); $("#horaJ2").prop("disabled",true);} 
		if(! $("#horv").prop("checked")){$("#horaV1").prop("disabled",true); $("#horaV2").prop("disabled",true);} 
		if(! $("#hors").prop("checked")){$("#horaS1").prop("disabled",true); $("#horaS2").prop("disabled",true);} 
		$("#hord").click(function () {
			if(! $(this).prop("checked")){ $("#hora1").prop("disabled",true);$("#hora1").val(""); $("#hora2").prop("disabled",true);$("#hora2").val("");}
			else{ $("#hora1").prop("disabled",false); $("#hora2").prop("disabled",false);}
		})
		$("#horl").click(function () {
			if(! $(this).prop("checked")){ $("#hora3").prop("disabled",true);$("#hora3").val(""); $("#hora4").prop("disabled",true);$("#hora4").val("");}
			else{ $("#hora3").prop("disabled",false); $("#hora4").prop("disabled",false);}
		})
		$("#horma").click(function () {
			if(! $(this).prop("checked")){ $("#hora5").prop("disabled",true);$("#hora5").val(""); $("#hora6").prop("disabled",true);$("#hora6").val("");}
			else{ $("#hora5").prop("disabled",false); $("#hora6").prop("disabled",false);}
		})
		$("#hormi").click(function () {
			if(! $(this).prop("checked")){ $("#hora7").prop("disabled",true);$("#hora7").val(""); $("#hora8").prop("disabled",true);$("#hora8").val("");}
			else{ $("#hora7").prop("disabled",false); $("#hora8").prop("disabled",false);}
		})
		$("#horj").click(function () {
			if(! $(this).prop("checked")){ $("#hora9").prop("disabled",true);$("#hora9").val(""); $("#horaJ2").prop("disabled",true);$("#horaJ2").val("");}
			else{ $("#hora9").prop("disabled",false); $("#horaJ2").prop("disabled",false);}
		})
		$("#horv").click(function () {
			if(! $(this).prop("checked")){ $("#horaV1").prop("disabled",true);$("#horaV1").val(""); $("#horaV2").prop("disabled",true);$("#horaV2").val("");}
			else{ $("#horaV1").prop("disabled",false); $("#horaV2").prop("disabled",false);}
		})
		$("#hors").click(function () {
			if(! $(this).prop("checked")){ $("#horaS1").prop("disabled",true);$("#horaS1").val(""); $("#horaS2").prop("disabled",true);$("#horaS2").val("");}
			else{ $("#horaS1").prop("disabled",false); $("#horaS2").prop("disabled",false);}
		})
		if($("#status").val()==1||$("#status").val()==3){
			$("#lic").css('display','block');
			$("#per").css('display','none');
		}else if ($("#status").val()==2){
			$("#lic").css('display','none');
			$("#per").css('display','block');
		}else if(!$("#status").val()){
			$("#lic").css('display','none');
			$("#per").css('display','none');
		}
		if($("#lic2").val()==1 && $("#status").val() ==1){
			$("#per").css('display','block');
		}else{
			$("#per").css('display','none');
		}
		$("#status").click(function () {
			if($("#status").val()==1||$("#status").val()==3){
				$("#lic").css('display','block');
				$("#per").css('display','none');
			}else if ($("#status").val()==2){
				$("#lic").css('display','none');
				$("#per").css('display','block');
			}else if(!$("#status").val()){
				$("#lic").css('display','none');
				$("#per").css('display','none');
			}
		})
		$("#lic2").click(function () {	
			if($("#lic2").val()==1 && ($("#status").val() ==1 || $('#status').val() == 3)){
				$("#per").css('display','block');
			}else{
				$("#per").css('display','none');
			}
		})
		$("#con").click(function () {
			if($("#con").val()!='Definitivo')
				$("#per1").css('display','block');
			else
				$("#per1").css('display','none');
		})
		$('#cons').change(function (argument) {
			if($(this).val()==7)
				$('#nomCon').css('display','block')
			else
				$('#nomCon').css('display','none')
		})
		if($('#cons').val()==7)
			$('#nomCon').css('display','block')
		else
			$('#nomCon').css('display','none')
		$(document).on('click','#busofi #delete',function () {
			var esto = $(this);
			var url = $('#url').attr('value');
			var id = esto.attr('value');
			id = 'id='+id+'&delete=true'
			console.log(esto,id)
			if(confirm('¿Está segur@?'))
			$.ajax({
				url:url,
				dataType:'json',
				data:id,
				async:true,
				method:'POST',
				success:function(datos){
					console.log(datos)
					esto.parent().parent().remove()
					if(!datos){
						html = 'Oficio y Servicio Eliminados ;)'
					}else{
						html = 'Oficio Eliminado ;)'
					}
					$('div#msj').remove()
					$('<div id="msj" class="msj-delete"></div>').appendTo('#principal');
					$('div#msj').html(html)
					.animate({bottom:'+=200'},1000)
					.delay(2000)
					.animate({opacity:'0'},1500,function () {
						$(this).css({bottom:'-200px',opacity:'0.85'})
					})
				},
				error:function(){
					console.log('error')
				}
			})
		})
		$('i.glyphicon.glyphicon-remove').click(function () {
			$(this).prev().prev().val('')
			console.log($(this).prev())
		})
		$(document).on('click','#busofi #vermas',function () {
			var este = $(this);
			//alert(este.attr('value'))
			//console.log(este)
			var url = $('#url').attr('value');
			var id = este.attr('value')
			id = 'id='+id+'&ver=true'
			$.ajax({
				url:url,
				dataType:'json',
				data:id,
				async:true,
				method:'POST',
				success:function (datos) {
					if(datos.con_ofi == 1) con = 'Toma Fotográfica'
					else if(datos.con_ofi == 2) con = 'Ingreso de Personal'
					else if(datos.con_ofi == 3) con = 'Salida e Ingreso de Vehículos'
					else if(datos.con_ofi == 4) con = 'Salida de Mobiliario'
					else if(datos.con_ofi == 5) con = 'Permiso de Prospección'
					else if(datos.con_ofi == 6) con = 'Personal de Vigilancia (Eventos)'
					else if(datos.con_ofi == 7) con = datos.nomCon_ofi
					else con = ''
					oficio = '<dl class="dl-horizontal">'
					oficio+= '	<div>'
					oficio+= '		<dt>Número</dt>'
					oficio+= '		<dd>'+datos.no_ofi+'</dd>'
					oficio+= '	</div>'
					oficio+= '	<div>'
					oficio+= '		<dt>Fecha y Hora</dt>'
					oficio+= '		<dd>'+datos.fecha_ofi+' '+datos.hora_ofi+'</dd>'
					oficio+= '	</div>'
					oficio+= '	<div>'
					oficio+= '		<dt>Capturó</dt>'
					oficio+= '		<dd>'+datos.userCap_ofi+'</dd>'
					oficio+= '	</div>'
					oficio+= '	<div>'
					oficio+= '		<dt>Dependencia</dt>'
					oficio+= '		<dd>'+datos.nombre_depe+'</dd>'
					oficio+= '	</div>'
					oficio+= '	<div>'
					oficio+= '		<dt>Descripción</dt>'
					oficio+= '		<dd>'+datos.des_ofi+'</dd>'
					oficio+= '	</div>'
					oficio+= '	<div>'
					oficio+= '		<dt>Concepto</dt>'
					oficio+= '		<dd>'+con+'</dd>'
					oficio+= '	</div>'
					oficio+= '	<div>'
					oficio+= '		<dt>Foto</dt>'
					oficio+= '		<dd><img src="main/templates/complementos/fotos_oficios/'+datos.foto_ofi+'" alt="Not Found" width="320" height="200"></dd>'
					oficio+= '	</div>'
					oficio+= '	<form id="pdfOfi">'
					oficio+= '	<div>'
					oficio+= '		<dt>PDF</dt>'
					oficio+= '		<dd><input type="file" name="archivo" accept=".pdf"></dd>'
					oficio+= '		<input type="hidden" name="id" id="id" value="'+datos.id_ofi+'">'
					oficio+= '		<input type="hidden" name="no" id="no" value="'+datos.no_ofi+'">'
					oficio+= '	</div>'
					oficio+= '	</form>'
					if(datos.archivo_ofi != ''){
					oficio+= '	<div id="pdf">'
					oficio+= '		<dt>Ver PDF</dt>'
					oficio+= '		<dd><a class="link_blanco"><button class="btn btn-default" onclick="ventana('
					oficio+= "'main/templates/complementos/archivos_oficios/"+datos.archivo_ofi+"')"
					oficio+= '">VER</button></a></dd>'
					oficio+= '	</div>'
					}
					oficio+= '</dl>'
					oficio+= '<button class="btn btn-success" onclick="volver_busOfi()">Volver</button> '
					oficio+= '<button class="btn btn-default" onclick="return guardarArchivo();">'
					if(datos.archivo_ofi != ''){
					oficio+= 'Actualizar</button>'
					}else{
					oficio+= 'Guardar</button>'
					}
					oficio+='<div id="msj" class="msj-save"></div>'
					$('div#principal').hide()
					$('div#verofi').html(oficio).show()
					console.log(datos)
				},
				error:function (xhr,a,b) {
					console.log(xhr)
				}
			})
		})
		$('.menu ul li').hover(
			function () {
				$(this).children().show()
			},
			function () {
				$(this).children().hide()
			}
		)
		$('.menu li ul li').click(function () {
			var esto = $(this);
			var id = esto.prop('id');
			var top = $('.'+id).offset().top-30;
			$('html').animate({scrollTop: top+'px'},100)
		})
});

function volver_busOfi () {
	$('div#verofi').hide()
	$('div#principal').show()
}

function ventana (url) {
	window.open(url,"title","resizable=yes,directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=1000, height=680")
}

function abrirVentana(url) {
	window.open(url, "nuevo", "directories=no, location=no, menubar=no, scrollbars=yes, statusbar=no, tittlebar=no, width=850, height=500");

}
	function lim () {
		window.document.per.fecN.value = ''
	}
	function guiones (f) {
		var fec = document.getElementById(f).value
		if(!fec) fec = ""
		if(fec.length == 2 || fec.length == 5) document.getElementById(f).value = fec+"/"
	}
	function dos_puntos (f) {
		var fec = document.getElementById(f).value
		if(!fec) fec = ""
		if(fec.length == 2) document.getElementById(f).value = fec+":"
		if(fec.length > 4){ fec = fec.substring(0,5); document.getElementById(f).value = fec;} 
	}
	function val(f) {
		var fec = document.getElementById(f).value
		if(fec.length == 10){
			if(!validarFormatoFecha(fec)) document.getElementById(f).value = "Formato de fecha invalido"
			else if(!existeFecha(fec)) document.getElementById(f).value = "Fecha no existe"
		}else document.getElementById(f).value = "Formato de fecha incorrecto"
	}
	function enviar() {
		var fec = window.document.per.fecN.value
		if(!validarFormatoFecha(fec)){
			alert("¡La Fecha es erronea! :(")
			window.document.per.fecN.value = ''
		}else document.per.submit()
	}

	function validarFormatoFecha(campo) {
  		var RegExPattern = /^\d{1,2}\/\d{1,2}\/\d{2,4}$/;
  		if ((campo.match(RegExPattern)) && (campo!='')) return true;
  		else return false;
  	}
  	function existeFecha(fecha){
	    var fechaf = fecha.split("/");
	    var day = fechaf[0];
	    var month = fechaf[1];
	    var year = fechaf[2];
	    var date = new Date(year,month,'0');
	    if((day-0)>(date.getDate()-0)) return false;
      	else return true;
	}
	function validarFechaMenorActual(date){
     	var x=new Date();
     	var fecha = date.split("/");
      	x.setFullYear(fecha[2],fecha[1]-1,fecha[0]);
      	var today = new Date();
      	if (x >= today)
        	return false;
      	else
        	return true;
	}
	function validar(){
		if($("#cod").val().length!=7){ $('#codig').addClass('has-error'); $("#cod").val('').prop('required',true)}
		else $('#codig').removeClass('has-error');

	}

	function calcular () {
		var fec = window.document.per.carHor.value;
		var  miDiv  =  document.getElementById('CH');
		var tex =  document.getElementById('text-alert');
		hora1 = document.getElementById('hora1').value.split(":");
		hora2 = document.getElementById('hora2').value.split(":");
		hora3 = document.getElementById('hora3').value.split(":");
		hora4 = document.getElementById('hora4').value.split(":");
		hora5 = document.getElementById('hora5').value.split(":");
		hora6 = document.getElementById('hora6').value.split(":");
		hora7 = document.getElementById('hora7').value.split(":");
		hora8 = document.getElementById('hora8').value.split(":");
		hora9 = document.getElementById('hora9').value.split(":");
		horaJ2 = document.getElementById('horaJ2').value.split(":");
		horaV1 = document.getElementById('horaV1').value.split(":");
		horaV2 = document.getElementById('horaV2').value.split(":");
		horaS1 = document.getElementById('horaS1').value.split(":");
		horaS2 = document.getElementById('horaS2').value.split(":");
		diaD = new Array();
		diaL = new Array();
		diaMa = new Array();
		diaMi = new Array();
		diaJ = new Array();
		diaV = new Array();
		diaS = new Array();
		semana = new Array();
		for(a=0;a<3;a++){
			hora1[a]=(isNaN(parseInt(hora1[a])))?0:parseInt(hora1[a]);
			hora2[a]=(isNaN(parseInt(hora2[a])))?0:parseInt(hora2[a]);
			hora3[a]=(isNaN(parseInt(hora3[a])))?0:parseInt(hora3[a]);
			hora4[a]=(isNaN(parseInt(hora4[a])))?0:parseInt(hora4[a]);
			hora5[a]=(isNaN(parseInt(hora5[a])))?0:parseInt(hora5[a]);
			hora6[a]=(isNaN(parseInt(hora6[a])))?0:parseInt(hora6[a]);
			hora7[a]=(isNaN(parseInt(hora7[a])))?0:parseInt(hora7[a]);
			hora8[a]=(isNaN(parseInt(hora8[a])))?0:parseInt(hora8[a]);
			hora9[a]=(isNaN(parseInt(hora9[a])))?0:parseInt(hora9[a]);
			horaJ2[a]=(isNaN(parseInt(horaJ2[a])))?0:parseInt(horaJ2[a]);
			horaV1[a]=(isNaN(parseInt(horaV1[a])))?0:parseInt(horaV1[a]);
			horaV2[a]=(isNaN(parseInt(horaV2[a])))?0:parseInt(horaV2[a]);
			horaS1[a]=(isNaN(parseInt(horaS1[a])))?0:parseInt(horaS1[a]);
			horaS2[a]=(isNaN(parseInt(horaS2[a])))?0:parseInt(horaS2[a]);
			if (a == 0) {
				if (hora1[a]>hora2[a]) {
					diaD[a]=(24-hora1[a]+hora2[a]);
				}else{
					diaD[a]=(hora2[a]-hora1[a]);
				};
				if (hora3[a]>hora4[a]) {
					diaL[a]=(24-hora3[a]+hora4[a]);
				}else{
					diaL[a]=(hora4[a]-hora3[a]);
				};
				if (hora5[a]>hora6[a]) {
					diaMa[a]=(24-hora5[a]+hora6[a]);
				}else{
					diaMa[a]=(hora6[a]-hora5[a]);
				};
				if (hora7[a]>hora8[a]) {
					diaMi[a]=(24-hora7[a]+hora8[a]);
				}else{
					diaMi[a]=(hora8[a]-hora7[a]);
				};
				if (hora9[a]>horaJ2[a]) {
					diaJ[a]=(24-hora9[a]+horaJ2[a]);
				}else{
					diaJ[a]=(horaJ2[a]-hora9[a]);
				};
				if (horaV1[a]>horaV2[a]) {
					diaV[a]=(24-horaV1[a]+horaV2[a]);
				}else{
					diaV[a]=(horaV2[a]-horaV1[a]);
				};
				if (horaS1[a]>horaS2[a]) {
					diaS[a]=(24-horaS1[a]+horaS2[a]);
				}else{
					diaS[a]=(horaS2[a]-horaS1[a]);
				};
			}else{
				diaD[a]=(hora2[a]-hora1[a]);
				diaL[a]=(hora4[a]-hora3[a]);
				diaMa[a]=(hora6[a]-hora5[a]);
				diaMi[a]=(hora8[a]-hora7[a]);
				diaJ[a]=(horaJ2[a]-hora9[a]);
				diaV[a]=(horaV2[a]-horaV1[a]);
				diaS[a]=(horaS2[a]-horaS1[a]);
			};
			
			semana[a] = (diaD[a]+diaL[a]+diaMa[a]+diaMi[a]+diaJ[a]+diaV[a]+diaS[a]);
		}
		var op = Math.floor(((semana[0]*60)+semana[1])/60);
		document.getElementById('htotal').value = op;
		if (op == fec) {
			miDiv.classList.add('has-success');
			miDiv.classList.remove('has-error');
			tex.innerHTML = "Son iguales";
		}else{
			miDiv.classList.add('has-error');
			miDiv.classList.remove('has-success');
			tex.innerHTML = "Aun no son iguales las Horas";
		}
	}
	function servicio () {
		var ser = $('#form-ser').serialize();
		var url = ser.url; 
		ser = ser+'&js=1';
		$.ajax({
			url:url,
			dataType:'json',
			data:ser,
			success:function (datos) {
				console.log(datos)
				$('#con').val('');
				$('#ext').val('');
				$('textarea').val('');
				$('#codigo').html(datos.newCod);
				$('#hidden').val(datos.newCod);
				$('#msj').html('Solicitud Guardada con éxito :)')
				.animate({bottom:'+=200'},1000)
				.delay(2000)
				.animate({opacity:'0'},1500,function () {
					$(this).css({bottom:'-200px',opacity:'0.85'})
				})
			},
			error:function (datos) {
				console.log('error')
			}
		});
		return false;
	}
	function oficios () {
		var ofi = $('#form-ofi').serialize();
		var url = ofi.url; 
		var numero = $('#numero')
		if(!numero.val()){numero.addClass('vacio').focus();return false;}
		var formData = new FormData(document.getElementById("form-ofi"));
		formData.append('js',1);
		$.ajax({
			url:ofi.url,
			dataType:'html',
			data:formData,            
			cache: false,
            contentType: false,
            processData: false,
			success:function (datos) {
				con = $('#cons').val()
				console.log(datos);
				$('#numero').val('')
				$('#files').val('')
				$('#list').val('')
				$('#cons').val('0')
				$('#nombreCon').val('')
				$('#desc').val('')
				if(con == 6){ con = ' y Servicio'; s = 's';}
				else{ con = ''; s = ''}
				$('#msj').html('Oficio'+con+' Guardado'+s+' con éxito :)')
				.animate({bottom:'+=200'},1000)
				.delay(2000)
				.animate({opacity:'0'},1500,function () {
					$(this).css({bottom:'-200px',opacity:'0.85'})
				})
			},
			error:function (datos) {
				console.log('error')
			}
		});
		return false;
	}

	function guardarArchivo() {
		var info = new FormData(document.getElementById("pdfOfi"));
		var id = $('#id').val();
		info.append('id',id);
		console.log(info);
		$.ajax({
			url:'index.php/Reporte_ofi',
			dataType:'json',
			data:info,            
			cache: false,
            contentType: false,
            processData: false,
            success:function (datos) {
            	console.log(datos)
            	$('div#pdf').remove();
            	html='<div id="pdf"><dt>Ver PDF</dt><dd><a class="link_blanco"><button class="btn btn-default" onclick="ventana(';
            	html+="'main/templates/complementos/archivos_oficios/"+datos.archivo+"'";
            	html+=')">VER</button></a></dd></div>';
				$('dl.dl-horizontal').append(html);
				$('div#msj').html('Actualización Completada :)')
				.animate({bottom:'+=200'},1000)
				.delay(2000)
				.animate({opacity:'0'},1500,function () {
					$(this).css({bottom:'-200px',opacity:'0.85'})
				})
            },
            error:function () {
            	console.log('error')
            }
		})
		return false;
	}

	function borrarArchivo (id) {
		//console.log(id);
		$.ajax({
			url:'index.php/Reporte_ofi',
			dataType:'json',
			data:'id='+id,
			success:function (datos) {
				console.log(datos);
				$('button#borrar, #pdf').remove()
			},
			error:function () {
				console.log('error')
			}
		})
		return false;
	}

	function buscarOficio () {
		var caps = $('#buscarOficio #caps');
		var con = $('#buscarOficio #con');
		var num = $('#buscarOficio #num');
		var cl = $('#buscarOficio #cl');
		var dep = $('#buscarOficio #dep');
		var dia = $('#buscarOficio #dia');
		var mes = $('#buscarOficio #mes');
		var anio = $('#buscarOficio #anio');
		var url = $('#buscarOficio #url');
		var bus = $('#buscarOficio').serialize();
		/*if(dia.val()=='0' || mes.val()=='0' || anio.val()=='0'){
			dia.val(''); mes.val(''); anio.val('');
			var fecha = false
		}*/
		if(!caps.val()&&con.val()=='0'&&!num.val()&&!cl.val()&&dep.val()=='0'&&dia.val()=='0' && mes.val()=='0' && anio.val()=='0')
			bus = bus+'&all=true';
		//console.log(url.val())
		$('#notinfo, table.table').addClass('invisible');
		$('#padre').append('<img class="ajax" src="main/templates/complementos/img/ajax-loader.gif" alt="Cargando..." id="ajax-gif">')
		$.ajax({
			url:url.val(),
			dataType:'json',
			data:bus,
			success:/*setTimeout(*/function (datos) {
				$('#ajax-gif').remove()
				console.log(datos)
				$('#notinfo').remove();
				if(datos){
				tabla = '<tr>'
				tabla+= '	<td><strong>Fecha y Hora</strong></td>'
				tabla+= '	<td><strong>Número</strong></td>'
				tabla+= '	<td><strong>Concepto</strong></td>'
				tabla+= '	<td><strong>Capturó</strong></td>'
				tabla+= '	<td>Ver más</td>'
				tabla+= '	<td>Editar</td>'
				tabla+= '	<td>Borrar</td>'
				tabla+= '</tr>'
				$.each(datos,function (index,value) {
					if(value.con_ofi == 1) conOfi = 'Toma Fotográfica'
					else if(value.con_ofi == 2) conOfi = 'Ingreso de Personal'
					else if(value.con_ofi == 3) conOfi = 'Salida e Ingreso de Vehículos'
					else if(value.con_ofi == 4) conOfi = 'Salida de Mobiliario'
					else if(value.con_ofi == 5) conOfi = 'Permiso de Prospección'
					else if(value.con_ofi == 6) conOfi = 'Personal de Vigilancia (Eventos)'
					else if(value.con_ofi == 7) conOfi = value.nomCon_ofi
					else conOfi = ''
					fecha = value.fecha_ofi.split('-')
					fecha = fecha[2]+'/'+fecha[1]+'/'+fecha[0]
					tabla+='<tr>'
					tabla+='	<td>'+fecha+' '+value.hora_ofi+'</td>'
					tabla+='	<td>'+value.no_ofi+'</td>'
					tabla+='	<td>'+conOfi+'</td>'
					tabla+='	<td>'+value.userCap_ofi+'</td>'
					tabla+='	<td><span id="vermas" value="'+value.id_ofi+'" class="label label-success">Ver más</span></td>'
					tabla+='	<td><a class="link_blanco" href="index.php/Reporte_ofi?edit='+value.id_ofi+'"><span class="label label-warning">Editar</span></a></td>'
					tabla+='	<td><span id="delete" value="'+value.id_ofi+'" class="label label-danger">Borrar</span></td>'
					tabla+='</tr>'
				})
				$('table.table').html(tabla).removeClass('invisible')
				}else{
					$('table.table').html('').removeClass('invisible');
					$('<div class="alert alert-warning" role="alert" id="notinfo">No hay informacion</div>').appendTo('#principal');
				}
				caps.val('');
				con.val(0);
				num.val('');
				cl.val('');
				dep.val(0);
				dia.val(0);
				mes.val(0);
				anio.val(0);
			},//2000),
			error:function (datos) {
				console.log('datos')
			}
		})
		return false
	}

	function eventos (dia,mes,anio) {
		var fecha = anio+'-'+((mes<10)?'0'+mes:mes)+'-';//+((dia==0)?'%':(dia<10)?'0'+dia:dia);
		if(dia == 0){
			fecha += '%';
		}else{
			if(dia<10){
				fecha += '0'+dia;
			}else{
				fecha += dia;
			}
		}
		$.ajax({
			url:'index.php/eventos',
			dataType:'json',
			type:'POST',
			data:'fecha='+fecha,
			success:function (data) {
				console.log(data)
				if(data != ""){
					$("#lista-eventos>table").html("")
					var head = "<tr><td><b>Nombre</b></td><td><b>Descripcion</b></td><td><b>Fecha</b></td><td><b>Hora</b></td><td><b>Sede</b></td><td><b>Archivo</b></td></tr>";
					$("#lista-eventos>table").append(head);
					$.each(data,function (key,value) {
						url = "'main/templates/complementos/archivos_oficios/"+value.archivo_eve+"'";
						boton = '<button class="boton-archivo" onclick="abrirVentana('+url+');">Archivo</button>'
						var fila = '<tr><td>'+value.nombre_eve+'</td><td>'+value.descripcion_eve+'</td><td>'+value.fecha_eve+'</td><td>'+value.hora_eve+'</td><td>'+value.sede_eve+'</td><td>'+boton+'</td></tr>';
						$("#lista-eventos>table").append(fila);
					})
					//$("#eventos_conteiner").css({left:(($("body").width()/2)-($("#eventos_conteiner").width()/2)),top:(($(window).height()/2)-($("#eventos_conteiner").height()/2))})
					$("#lista-eventos>table").show();
					$("#not-found").hide();
				}else{
					//$("#eventos_conteiner").css({left:(($("body").width()/2)-($("#eventos_conteiner").width()/2)),top:(($(window).height()/2)-($("#eventos_conteiner").height()/2))})
					$("#lista-eventos>table").hide();
					$("#not-found").show();
				}
				$("#pantalla").fadeIn(500);
				$("#eventos_conteiner").css({left:(($("body").width()/2)-($("#eventos_conteiner").width()/2)),top:(($(window).height()/2)-($("#eventos_conteiner").height()/2))})
				$("body").css("overflow-y","hidden");
			},
			error:function () {
				console.log('error')
			}
		})
		console.log(fecha);
	}

	function in_array(needle, haystack, argStrict) {
	  //  discuss at: http://phpjs.org/functions/in_array/
	  // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	  // improved by: vlado houba
	  // improved by: Jonas Sciangula Street (Joni2Back)
	  //    input by: Billy
	  // bugfixed by: Brett Zamir (http://brett-zamir.me)
	  //   example 1: in_array('van', ['Kevin', 'van', 'Zonneveld']);
	  //   returns 1: true
	  //   example 2: in_array('vlado', {0: 'Kevin', vlado: 'van', 1: 'Zonneveld'});
	  //   returns 2: false
	  //   example 3: in_array(1, ['1', '2', '3']);
	  //   example 3: in_array(1, ['1', '2', '3'], false);
	  //   returns 3: true
	  //   returns 3: true
	  //   example 4: in_array(1, ['1', '2', '3'], true);
	  //   returns 4: false

	  var key = '',
	    strict = !! argStrict;

	  //we prevent the double check (strict && arr[key] === ndl) || (!strict && arr[key] == ndl)
	  //in just one for, in order to improve the performance 
	  //deciding wich type of comparation will do before walk array
	  if (strict) {
	    for (key in haystack) {
	      if (haystack[key] === needle) {
	        return true;
	      }
	    }
	  } else {
	    for (key in haystack) {
	      if (haystack[key] == needle) {
	        return true;
	      }
	    }
	  }

	  return false;
	}


$.ajaxSetup({
	async:true,
	method:'POST',
})