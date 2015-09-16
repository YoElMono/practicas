<?php
	function calcula_numero_dia_semana($dia,$mes,$ano){
		$numerodiasemana = date('w', mktime(0,0,0,$mes,$dia,$ano));
		return $numerodiasemana;
	}
	//funcion que devuelve el último día de un mes y año dados
	function ultimoDia($mes,$ano){
		$ultimo_dia=28;
		while (checkdate($mes,$ultimo_dia,$ano)){
	        $ultimo_dia++;
		}    
		$ultimo_dia--;
		return $ultimo_dia;
	}
	function dame_nombre_mes($mes){
		 switch ($mes){
		 	case 1:
				$nombre_mes="Enero";
				break;
		 	case 2:
				$nombre_mes="Febrero";
				break;
		 	case 3:
				$nombre_mes="Marzo";
				break;
		 	case 4:
				$nombre_mes="Abril";
				break;
		 	case 5:
				$nombre_mes="Mayo";
				break;
		 	case 6:
				$nombre_mes="Junio";
				break;
		 	case 7:
				$nombre_mes="Julio";
				break;
		 	case 8:
				$nombre_mes="Agosto";
				break;
		 	case 9:
				$nombre_mes="Septiembre";
				break;
		 	case 10:
				$nombre_mes="Octubre";
				break;
		 	case 11:
				$nombre_mes="Noviembre";
				break;
		 	case 12:
				$nombre_mes="Diciembre";
				break;
		}
		return $nombre_mes;
	}
	function dias($dia){
		$d = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
		$dia = $d[$dia];
		return $dia;
	}
	function horasE($dia){
		$d = ['horED_per','horEL_per','horEMa_per','horEMi_per','horEJ_per','horEV_per','horES_per'];
		$dia = $d[$dia];
		return $dia;
	}
	function horasS($dia){
		$d = ['horSD_per','horSL_per','horSMa_per','horSMi_per','horSJ_per','horSV_per','horSS_per'];
		$dia = $d[$dia];
		return $dia;
	}
	function dias_semana($dia){
		$d = ['checkD_per','checkL_per','checkMa_per','checkMi_per','checkJ_per','checkV_per','checkS_per'];
		$dia = $d[$dia];
		return $dia;
	}
	function mes_anterior($mes){
		$mes_an =  $mes - 1;
		if ($mes_an == 0) {
			$mes_an = 12;
		}
		return $mes_an;
	}
	function mes_siguiente($mes){
		$mes_sg =  $mes + 1;
		if ($mes_sg == 13) {
			$mes_sg = 1;
		}
		return $mes_sg;
	}
	function anio_an($mes,$anio){
		if (($mes-1)==0){
			$anio--;
		}
		return $anio;
	}
	function anio_sig($mes,$anio){
		if (($mes+1)==13){
			$anio++;
		}
		return $anio;
	}
	function numeroDeSemana($mes,$anio){
		$semana = date('W',mktime(0,0,0,$mes,2,$anio));
		return $semana;
	}
	function numeroDeSemana2($dia,$mes,$anio){
		return $semana = date('W',mktime(0,0,0,$mes,$dia,$anio));
	}
?>