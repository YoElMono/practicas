<?php
	require 'main/templates/complementos/fpdf/fpdf.php';
	class udg extends FPDF {
		public function Header($cod){
			if($cod){
				$this->Image('main/templates/complementos/img/escudo.jpg',35,20,25,25,'JPG');
	      		$this->SetFont('Arial','B',10);
	      		$this->Cell(0,10,'',0,1);
	      		$this->Cell(0,8,'UNIVERSIDAD DE GUADALAJARA',0,1,'C');
	      		$this->Cell(55,8,'',0);
	      		$this->Cell(80,8,'SECRETARIA GENERAL',0,0,'C');
	      		$this->SetFont('Arial','',8);
	      		$this->Cell(0,8,'Formato F1',0,1,'C');
	      		$this->Cell(55,8,'',0);
	      		$this->SetFont('Arial','B',10);
	      		$this->Cell(80,8,'COORDINACION GENERAL DE PATRIMONIO',0,0,'C');
	      		$this->SetFont('Arial','',8);
	      		$this->Cell(20,8,'No. ',0,0,'R');
	      		$this->SetFont('','U',8);
	      		$this->Cell(0,8,$cod,0,1);
	      		$this->Cell(0,3,'',0,1);
			}
		}
		public function Body_sali($pro,$dep,$des){
				$this->SetFont('Arial','B',10);
				$this->Cell(20,6,'',0);
				$this->Cell(0,6,'VALE DE SALIDA DE BIENES MUEBLES',0,1);
				$this->SetFont('','',10);
				$this->Cell(140,6,'FECHA: ',0,0,'R');
				$this->SetFont('','U',10);
				$this->Cell(0,6,date('d / m / Y'),0,1);
				$this->SetFont('Arial','B',10);
				$this->Cell(140,5,'DESCRIPCION DEL ARTICULO',1,0,'C');
				$this->Cell(25,5,'CANTIDAD',1,0,'C');
				$this->Cell(25,5,'UNIDAD',1,1,'C');
				$this->Cell(140,5,$pro,1,0);
				$this->Cell(25,5,'',1,0);
				$this->Cell(25,5,'',1,1);
				for($i=0;$i<10;$i++){
					$this->Cell(140,5,'',1,0);
					$this->Cell(25,5,'',1,0);
					$this->Cell(25,5,'',1,1);
				}
				$this->Cell(70,10,'DEPENDENCIA',0,0);
				$this->Cell(0,10,utf8_decode($dep),0,1);
				$this->Cell(0,10,'MOTIVO DE LA SALIDA, ASI COMO EL DOCUMENTO OFICIAL',0,1);
				$this->SetFont('','',8);
				$this->Cell(0,10,utf8_decode($des),0,1);
		}
		public function Footer($nCap,$nAut){
			if($nCap){
				$this->SetFont('','',10);
				$this->Cell(70,6,'ELABORO',TLR,0,'C');
				$this->Cell(50,6,'',T,0);
				$this->Cell(70,6,'AUTORIZO',TLR,1,'C');
				$this->SetFont('','',8);
				$this->Cell(15,6,'Firma:',L,0);
				$this->Cell(55,6,'_______________________________',R,0);
				$this->Cell(50,6,'',0,0);
				$this->Cell(15,6,'Firma:',L,0);
				$this->Cell(55,6,'_______________________________',R,1);
				$this->Cell(15,6,'Nombre: ',BL,0);
				$this->Cell(55,6,utf8_decode($nCap),RB,0);
				$this->SetFont('','',10);
				$this->Cell(50,6,'Sello',B,0,'C');
				$this->SetFont('','',8);
				$this->Cell(15,6,'Nombre: ',BL,0);
				$this->Cell(55,6,utf8_decode($nAut),RB,0);
			}
		}
	}
	class tarjetas extends FPDF{
		public function tarjeta($no,$nom,$dep,$hE,$hS,$cod,$ind){
			$bor = 0;
			$meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
			$m = date('n');
			if($ind) $m = $m-1;
			if($m == 12) $m = 0;
			switch ($m) {
				case 0:
				case 2:
				case 4:
				case 6:
				case 7:
				case 9:
				case 11:
					$td = 31;
					break;
				case 3:
				case 5:
				case 8:
				case 10:
					$td = 30;
					break;
				default:
					if(date('L') == 1){
						$td = 29;
					}
					else{
						$td = 28;
					}
					break;
			}
			$m = $meses[$m];
			$an = (date('n') == 12)?date('y')+1:date('y');
			$this->SetFont('Arial','B',22);
			$this->Cell(0,4,'',$bor,1);
			//$this->Cell(3,5,'',$bor,0);
			$this->Cell(10,5,$no,$bor,0,'C');
			$this->Cell(35,5,'',$bor,0);
			$this->SetFont('','',10);
			$this->Cell(15,5,'Horario:',$bor,0,'R');
			$this->Cell(20,5,$hE." a ".$hS,$bor,1);
			$this->Cell(75,5,'',$bor,1);
			//$this->Cell(3,5,'',$bor,0);
			$this->SetFont('','B',14);
			$this->Cell(60,5,utf8_decode($nom),$bor,1);
			$this->Cell(30,4,'',$bor,0);
			$this->SetFont('','',10);
			$this->Cell(55,4,utf8_decode($dep),$bor,1);
			$this->Cell(50,5,utf8_decode('Código:'),$bor,0,'R');
			$this->SetFont('','B',13);
			$this->Cell(25,5,$cod,$bor,1);
			$this->SetFont('','',10);
			$this->Cell(11,4,'',$bor,0,'C');
			$this->Cell(7,4,'1',$bor,0,'C');
			$this->Cell(5,4,'',$bor,0);
			$this->Cell(7,4,$td,$bor,0,'C');
			$this->Cell(5,4,'',$bor,0);
			$this->Cell(27,4,$m,$bor,0,'C');
			$this->Cell(16,4,'',$bor,0);
			$this->Cell(7,4,$an,$bor,1,'C');
		}
	}
	class services extends FPDF{
		public function Header(){
		$h = 0;
			if($h == 0){
				$this->Image('main/templates/complementos/img/escudo.jpg',25,20,12,12,'JPG');
	      		$this->SetFont('Times','B',7);
	      		$this->Cell(0,5,'UNIVERSIDAD DE GUADALAJARA',0,1,'C');
	      		$this->Cell(0,5,'SECRETARIA GENERAL',0,1,'C');
	      		$this->Cell(0,5,'UNIDAD DE CONSERVACION Y MANTENIMIENTO',0,1,'C');
	      		$h = 1;
	      	}
		}
		public function body($no,$dep,$sol,$ser,$des,$hor,$fec,$ext,$asi,$nom){
			$s2 = ['','','','','',''];
			$s2[$ser-1] = 'X';
			$s = ['Elecricidad','Fontanería','Aire Acondicionado','Limpieza','Mobiliario','Varios'];
			$n = [$asi,$nom];
			$a = ['FIRMA:','NOMBRE:','CARGO:'];
			$this->Cell(50,5,'ORDEN DE TRABAJO',LTB,0);
			$this->SetFont('Arial','BU');
			$this->Cell(95,5,"NO. DE ORDEN",T,0,"R");
			$this->SetFont('Arial','');
			$this->Cell(20,5,$no,TB,0,"C");
			$this->Cell(0,5,"",TR,1);
			$this->SetFont('Times');
			$this->Cell(35,5,"DEPENDENCIA:",L,0);
			$this->Cell(130,5,utf8_decode($dep),B,0);
			$this->Cell(0,5,"",R,1);
			$this->Cell(35,5,"REPORTO:",L,0);
			$this->Cell(70,5,utf8_decode($sol),B,0);
			$this->Cell(35,5,utf8_decode("EXTENSIÓN :"),0,0,"C");
			$this->Cell(25,5,$ext,B,0);
			$this->Cell(0,5,"",R,1);
			$this->Cell(35,5,"SERVICIO A REALIZAR:",L,0);
			$this->Cell(70,5,utf8_decode($s[$ser-1]),B,0);
			$this->Cell(35,5,"FECHA:",0,0,"C");
			$this->Cell(25,5,date('d/m/y'),B,0);
			$this->Cell(0,5,"",R,1);
			$this->Cell(0,5,"",RL,1);
			$this->SetFont('','B');
			$this->Cell(35,5,"TIPO DE SERVICIO",L,0,"R");
			$this->SetFont('','');
			$this->Cell(0,5,'',R,1);
			$this->Cell(5,5,'',L,0);
			$this->Cell(23.4,5,"ELECTRICIDAD",0,0,'R');
			$this->Cell(5,5,$s2[0],1,0,'C');
			$this->Cell(23.3,5,"FONTANERIA",0,0,'R');
			$this->Cell(5,5,$s2[1],1,0,'C');
			$this->Cell(23.4,5,"AIRE ACOND.",0,0,'R');
			$this->Cell(5,5,$s2[2],1,0,'C');
			$this->Cell(23.3,5,"LIMPIEZA",0,0,'R');
			$this->Cell(5,5,$s2[3],1,0,'C');
			$this->Cell(23.3,5,"MOBILIARIO",0,0,'R');
			$this->Cell(5,5,$s2[4],1,0,'C');
			$this->Cell(23.3,5,"VARIOS",0,0,'R');
			$this->Cell(5,5,$s2[5],1,0,'C');
			$this->Cell(0,5,'',R,1);
			$this->Cell(5,5,"",L,0);
			$this->SetFont('Arial','',8);
			$this->Cell(0,5,"DESCRIPCION DEL TRABAJO A REALIZAR",R,1);
			$this->Cell(5,5,'',L,0);
			$this->Cell(160,5,utf8_decode(strip_tags($des)),1,0);
			$this->Cell(0,5,'',R,1);
			for($i=1;$i<7;$i++){
				$this->Cell(5,5,'',L,0);
				$this->Cell(160,5,'',1,0);
				$this->Cell(0,5,'',R,1);
			}
			$this->Cell(5,5,'',L,0);
			$this->SetFont('Times','',7);
			$this->Cell(60,5,'HORA DE NOTIFICACION:',0,0);
			$this->Cell(20,5,$hor,B,0);
			$this->Cell(5,5,'',0,0);
			$this->Cell(60,5,'FECHA DE SERVICIO:',0,0);
			$this->Cell(25,5,$fec,B,0);
			$this->Cell(0,5,'',R,1);
			$this->Cell(5,5,'',L,0);
			$this->Cell(85,5,'RECIBI NOTIFICACION',0,0);
			$this->Cell(0,5,'RECIBI CONFORMIDAD',R,1);
			for($i=0;$i<3;$i++){
				$this->Cell(5,5,'',L,0);
				$this->Cell(25,5,$a[$i],0,0);
				$this->Cell(50,5,utf8_decode($n[$i-1]),B,0);
				$this->Cell(10,5,'',0,0);
				$this->Cell(25,5,$a[$i],0,0);
				$this->Cell(50,5,'',B,0);
				$this->Cell(0,5,'',R,1);
			}
			$this->Cell(0,2,'',LBR);
		}
	}
	class RD extends FPDF{
		public function header(){
			$this->SetFont('Arial','B',10);
			$this->Cell(0,5,'REPORTE DE ASISTENCIA DIARIO',0,1,'C');
		}
		public function body($data,$fecha){
			$turno = 0;
			$turnos = array('Matutino','Vespertino','Mixto','Nocturno');
			$this->Cell(0,10,'',0,1);
			$this->Cell(0,5,utf8_decode('Día: ').$fecha,1,1);
			$this->Cell(0,5,'',0,1);
			$this->SetFont('Arial','',10);
			foreach ($data as $key => $value) {
				if($turno<$value['turno']){
					$this->SetFont('','B');
					$this->SetTextColor(77,139,245);
					$this->Cell(0,5,$turnos[$turno],1,1);
					$this->SetTextColor(0);
					$this->Cell(60,5,'NOMBRE',1,0,'C');
					$this->Cell(20,5,'ENTRADA',1,0,'C');
					$this->Cell(35,5,'NOTAS',1,0,'C');
					$this->Cell(20,5,'SALIDA',1,0,'C');
					$this->Cell(35,5,'NOTAS',1,0,'C');
					$this->Cell(20,5,'HORAS',1,1,'C');
				}
				$this->SetFont('','');
				if(strlen($value['nom']) > 25) $value['nom'] = substr($value['nom'], 0,25);
				$this->Cell(60,5,utf8_decode($value['nom']),1,0);
				$this->Cell(20,5,$value['horEn'],1,0);
				$this->Cell(35,5,utf8_decode($value['notE']),1,0);
				$this->Cell(20,5,$value['horSa'],1,0);
				$this->Cell(35,5,utf8_decode($value['notS']),1,0);
				$this->Cell(20,5,$value['hor'],1,1);
				$turno = $value['turno'];
			}
		}
	}
	class RDSS extends FPDF{
		public function header(){
			$this->SetFont('Arial','B',10);
			$this->Cell(0,5,'REPORTE DE ASISTENCIA DIARIO',0,1,'C');
		}
		public function body($data,$fecha){
			$turno = "";
			//$turnos = array('Matutino','Vespertino','Mixto','Nocturno');
			$this->Cell(0,10,'',0,1);
			$this->Cell(0,5,utf8_decode('Día: ').$fecha,1,1);
			$this->Cell(0,5,'',0,1);
			$this->SetFont('Arial','',10);
			foreach ($data as $key => $value) {
				if($turno!=$value['turno']){
					$this->SetFont('','B');
					$this->SetTextColor(77,139,245);
					$this->Cell(0,5,$value['turno'],1,1);
					$this->SetTextColor(0);
					$this->Cell(60,5,'NOMBRE',1,0,'C');
					$this->Cell(20,5,'ENTRADA',1,0,'C');
					$this->Cell(35,5,'NOTAS',1,0,'C');
					$this->Cell(20,5,'SALIDA',1,0,'C');
					$this->Cell(35,5,'NOTAS',1,0,'C');
					$this->Cell(20,5,'HORAS',1,1,'C');
				}
				$this->SetFont('','');
				if(strlen($value['nom']) > 25) $value['nom'] = substr($value['nom'], 0,25);
				$this->Cell(60,5,utf8_decode($value['nom']),1,0);
				$this->Cell(20,5,$value['horEn'],1,0);
				$this->Cell(35,5,utf8_decode($value['notE']),1,0);
				$this->Cell(20,5,$value['horSa'],1,0);
				$this->Cell(35,5,utf8_decode($value['notS']),1,0);
				$this->Cell(20,5,$value['hor'],1,1);
				$turno = $value['turno'];
			}
		}
	}
	class RS extends FPDF{
		public function header(){
			$this->SetFont('Arial','B',10);
			$this->Cell(0,5,'REPORTE DE ASISTENCIA SEMANAL',0,1,'C');
		}
		public function body($data,$fecha){
			$this->Cell(0,10,'',0,1);
			$this->Cell(0,5,$fecha,1,1);
			$this->Cell(0,5,'',0,1);
			$this->Cell(70,5,'NOMBRE',1,0,'C');
			$this->Cell(60,5,'CARGA HORARIA',1,0,'C');
			$this->Cell(60,5,'HORAS',1,1,'C');
			$this->SetFont('Arial','',10);
			foreach ($data as $key => $value) {
				$this->Cell(70,5,utf8_decode($value['nom']),1,0);
				$this->Cell(60,5,$value['ch'],1,0,'C');
				$this->Cell(60,5,$value['hor'],1,1,'C');
			}
		}
	}
	class RSSS extends FPDF{
		public function header(){
			$this->SetFont('Arial','B',10);
			$this->Cell(0,5,'REPORTE DE ASISTENCIA SEMANAL',0,1,'C');
		}
		public function body($data,$fecha){
			$this->Cell(0,10,'',0,1);
			$this->Cell(0,5,$fecha,1,1);
			$this->Cell(0,5,'',0,1);
			$this->Cell(70,5,'NOMBRE',1,0,'C');
			$this->Cell(60,5,'CARGA HORARIA',1,0,'C');
			$this->Cell(60,5,'HORAS',1,1,'C');
			$this->SetFont('Arial','',10);
			foreach ($data as $key => $value) {
				$this->Cell(70,5,utf8_decode($value['nom']),1,0);
				$this->Cell(60,5,$value['ch'],1,0,'C');
				$this->Cell(60,5,$value['hor'],1,1,'C');
			}
		}
	}
	class RM extends FPDF{
		public function header(){
			$this->SetFont('Arial','B',10);
			$this->Cell(0,5,'REPORTE DE ASISTENCIA MENSUAL',1,1,'C');
			$this->Cell(0,10,'',0,1);
		}
		public function body($data,$faltas,$fecha){
			$turno = 0;
			$turnos = array('Matutino','Vespertino','Mixto','Nocturno');
			$i = 0;
			foreach ($data as $key => $value) {
				if(strlen($value['nom']) > 25) $value['nom'] = substr($value['nom'], 0,25);
				if($turno < $value['turno']){
					if($key > 0){
						$this->Cell(170,5,'FALTAS',1,0);
						$this->Cell(0,5,$faltas[$i],1,1,'C');
						$this->Cell(0,5,'',0,1);
					}
					$this->SetFont('','B');
					$this->SetTextColor(77,139,245);
					$this->Cell(0,5,$turnos[$turno],1,1);
					$this->SetTextColor(0);
					$f = false;
					$this->Cell(0,5,'',0,1);
					$this->SetFont('Arial','B',10);
					$this->Cell(10,5,'DIA',1,0,'C');
					$this->Cell(50,5,'NOMBRE',1,0,'C');
					$this->Cell(20,5,'ENTRADA',1,0,'C');
					$this->Cell(35,5,'NOTAS',1,0,'C');
					$this->Cell(20,5,'SALIDA',1,0,'C');
					$this->Cell(35,5,'NOTAS',1,0,'C');
					$this->Cell(20,5,'HORAS',1,1,'C');
				}
				if($per != $value['nom'] and $key > 0 and $f){
					$this->Cell(170,5,'FALTAS',1,0);
					$this->Cell(0,5,$faltas[$i],1,1,'C');
					$this->Cell(0,5,'',0,1);
					$i++;
					$this->SetFont('Arial','B',10);
					$this->Cell(10,5,'DIA',1,0,'C');
					$this->Cell(50,5,'NOMBRE',1,0,'C');
					$this->Cell(20,5,'ENTRADA',1,0,'C');
					$this->Cell(35,5,'NOTAS',1,0,'C');
					$this->Cell(20,5,'SALIDA',1,0,'C');
					$this->Cell(35,5,'NOTAS',1,0,'C');
					$this->Cell(20,5,'HORAS',1,1,'C');
				}
				$this->SetFont('Arial','',10);
				$this->Cell(10,5,$value['dia'],1,0);
				$this->Cell(50,5,utf8_decode($value['nom']),1,0);
				$this->Cell(20,5,$value['ent'],1,0);
				$this->Cell(35,5,utf8_decode($value['notEnt']),1,0);
				$this->Cell(20,5,$value['sal'],1,0);
				$this->Cell(35,5,utf8_decode($value['notSal']),1,0);
				$this->Cell(20,5,$value['hor'],1,1,'C');
				$per = $value['nom'];
				$turno = $value['turno'];
				$f = true;
			}
			$this->Cell(170,5,'FALTAS',1,0);
			$this->Cell(0,5,$faltas[$i],1,1,'C');
			$this->Cell(0,5,'',0,1);
		}
	}
	class RMSS extends FPDF{
		public function header(){
			$this->SetFont('Arial','B',10);
			$this->Cell(0,5,'REPORTE DE ASISTENCIA MENSUAL',1,1,'C');
			$this->Cell(0,10,'',0,1);
		}
		public function body($data,$faltas,$fecha){
			$turno = '';
			$turnos = array('Matutino','Vespertino','Mixto','Nocturno');
			$i = 0;
			foreach ($data as $key => $value) {
				if(strlen($value['nom']) > 25) $value['nom'] = substr($value['nom'], 0,25);
				if($turno != $value['turno']){
					$turno = $value['turno'];
					if($key > 0){
						$this->Cell(170,5,'FALTAS',1,0);
						$this->Cell(0,5,$faltas[$i],1,1,'C');
						$this->Cell(0,5,'',0,1);
					}
					$this->SetFont('','B');
					$this->SetTextColor(77,139,245);
					$this->Cell(0,5,$turno,1,1);
					$this->SetTextColor(0);
					$f = false;
					$this->Cell(0,5,'',0,1);
					$this->SetFont('Arial','B',10);
					$this->Cell(10,5,'DIA',1,0,'C');
					$this->Cell(50,5,'NOMBRE',1,0,'C');
					$this->Cell(20,5,'ENTRADA',1,0,'C');
					$this->Cell(35,5,'NOTAS',1,0,'C');
					$this->Cell(20,5,'SALIDA',1,0,'C');
					$this->Cell(35,5,'NOTAS',1,0,'C');
					$this->Cell(20,5,'HORAS',1,1,'C');
				}
				if($per != $value['nom'] and $key > 0 and $f){
					$this->Cell(170,5,'FALTAS',1,0);
					$this->Cell(0,5,$faltas[$i],1,1,'C');
					$this->Cell(0,5,'',0,1);
					$i++;
					$this->SetFont('Arial','B',10);
					$this->Cell(10,5,'DIA',1,0,'C');
					$this->Cell(50,5,'NOMBRE',1,0,'C');
					$this->Cell(20,5,'ENTRADA',1,0,'C');
					$this->Cell(35,5,'NOTAS',1,0,'C');
					$this->Cell(20,5,'SALIDA',1,0,'C');
					$this->Cell(35,5,'NOTAS',1,0,'C');
					$this->Cell(20,5,'HORAS',1,1,'C');
				}
				$this->SetFont('Arial','',10);
				$this->Cell(10,5,$value['dia'],1,0);
				$this->Cell(50,5,utf8_decode(utf8_decode($value['nom'])),1,0);
				$this->Cell(20,5,$value['ent'],1,0);
				$this->Cell(35,5,utf8_decode($value['notEnt']),1,0);
				$this->Cell(20,5,$value['sal'],1,0);
				$this->Cell(35,5,utf8_decode($value['notSal']),1,0);
				$this->Cell(20,5,$value['hor'],1,1,'C');
				$per = $value['nom'];
				$f = true;
			}
			$this->Cell(170,5,'FALTAS',1,0);
			$this->Cell(0,5,$faltas[$i],1,1,'C');
			$this->Cell(0,5,'',0,1);
		}
	}
	class DL extends FPDF{
		public function header(){
			$b = 0;
			$this->AddFont('DejaVuSerifCondensed','','DejaVuSerifCondensed.php');
			$this->Image('main/templates/complementos/img/escudo.jpg',10,10,18,20,'JPG');
			$this->Cell(1,10,'',$b,1,'',1);
			$this->Cell(1,6,'',$b,0,'',1);
			$this->Cell(9,6,'',$b,0);
			$this->SetFont('DejaVuSerifCondensed','',15);
			$this->Cell(0,6,'UNIVERSIDAD DE GUADALAJARA',$b,1);
			$this->Cell(1,2,'',$b,0,'',1);
			$this->Cell(0,2,'',$b,1);
			$this->Cell(1,4,'',$b,0,'',1);
			$this->Cell(9,4,'',$b,0);
			$this->SetFont('Arial','',10);
			$this->Cell(0,4,'SECRETARIA GENERAL',$b,1);
			$this->Cell(1,4,'',$b,0,'',1);
			$this->Cell(9,4,'',$b,0);
			$this->Cell(0,4,'CONSERVACION Y MANTENIMIENTO DE LA ADMINISTRACION GENERAL',$b,1);
			$this->Cell(1,7,'',$b,1,'',1);
		}
		public function body($mes,$data){
			$meses = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
			$mes = $meses[$mes-1];
			if(strlen($mes)<8){
				$mes = str_split($mes);
				$mes1 = '';
				for($i = 0; $i < count($mes); $i++){
					$mes1.= $mes[$i].' ';
				}
				$mes = $mes1;
			}
			$h = 7;
			$b = 0;
			$len = 1.8;
			$con[0] = ' Por este medio me permito informa a usted que de acuerdo a la cláusula número 34 :';
			$con[1] = '"Los trabajadaores que no hagan uso de sus tolerancias convenidas en el contrato colectivo de trabajo, contarán con un día de descanso como estímulo de puntualidad...';
			$con[2] = 'el cuál deberá de tomar dentro del ';
			$con[3] = 'mes inmediato siguiente",';
			$con[4] = ' y en virtud de que en';
			$con[5] = 'el mes de ';
			$con[6] = ' cumplió usted de acuerdo con esta cláusula se le otorgará un';
			$con[7] = 'día de descanso, se le solicita que informe a su jefe inmediato con anticipación la fecha en que tomará dicho beneficio.';
			$des = 'Sin más por el momento, reciba un cordial saludo.';
			$anc[5] = $len * (strlen($con[5]));
			$this->SetFont('Arial','B',10.5);
			$this->Cell(0,25,'MEMORANDUM',$b,1,'C');
			$this->Cell(0,4,utf8_decode($data['nom']),$b,1);
			$this->Cell(15,4,utf8_decode('Código: '),$b,0);
			$this->Cell(0,4,$data['codigo'],$b,1);
			$this->SetFont('','');
			$this->Cell(0,4,'P r e s e n t e',$b,1);
			$this->Cell(0,15,'',$b,1);
			$this->Cell(0,$h,utf8_decode($con[0]),$b,1);
			$this->SetFont('','I');
			$this->MultiCell(0,$h,utf8_decode($con[1]),$b);
			$this->Cell(58,$h,utf8_decode($con[2]),$b,0);
			$this->SetFont('','BI');
			$this->Cell(47,$h,utf8_decode($con[3]),$b,0);
			$this->SetFont('','');
			$this->Cell(0,$h,$con[4],$b,1);
			$this->Cell($anc[5],$h,$con[5],$b,0);
			$this->SetFont('','B');
			$this->Cell(22,$h,$mes,$b,0,'C');
			$this->SetFont('','');
			$this->Cell(0,$h,utf8_decode($con[6]),$b,1);
			$this->MultiCell(0,$h,utf8_decode($con[7]),$b);
			$this->Cell(0,35,utf8_decode($des),$b,1);
		}
		public function pie($frase='',$res='',$fin){
			$meses = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
			$mes = $meses[date('m')-1];
			$h = 5;
			$b = 0;
			if($frase!='') $frase = '"'.$frase.'"';
			$con = 'ATENTAMENTE
			"PIENSA Y TRABAJA"
			Guadalajara, Jal. a '.date('d').' de '.$mes.' de '.date('Y');
			$this->MultiCell(0,$h,$con,$b,'C');
			$this->SetFont('','BI');
			$this->Cell(0,$h,utf8_decode($frase),$b,1,'C');
			$this->Cell(0,25,'',$b,1);
			$this->SetFont('','B');
			$this->Cell(0,$h,utf8_decode($res),$b,1,'C');
			$this->SetFont('','');
			$this->Cell(0,$h,'Responsable',$b,1,'C');
			$this->Cell(0,15,'',$b,1);
			$this->SetFont('','',7);
			$this->Cell(0,$h,$fin,$b,1);		
		}
	}
	class RGM extends FPDF{
		public function header(){
			$this->SetFont('Arial','B',10);
			$this->Cell(0,5,'REPORTE GLOBAL DEL MES',0,1,'C');
		}
		public function body($data,$mes){
			$meses = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
			$this->Cell(0,10,'',0,1);
			$this->Cell(0,5,$meses[$mes-1],1,1);
			$this->Cell(0,5,'',0,1);
			$this->Cell(70,5,'NOMBRE',1,0,'C');
			$this->Cell(20,5,'CARGA H.',1,0,'C');
			//$this->Cell(60,5,'HORAS',1,1,'C');
			$ancho = 100/count($data['semanas']);
			$X = 0;
			foreach ($data['semanas'] as $key => $value) {
				$salto = $X == (count($data['semanas'])-1) ? 1 : 0;
				$this->Cell($ancho,5,"S. $value[semana_check]",1,$salto,'C');
				$X++;
			}
			$this->SetFont('Arial','',10);
			foreach ($data['data'] as $key => $value) {
				$this->Cell(70,5,utf8_decode($value['nombre']),1,0);
				$this->Cell(20,5,$value['carga'],1,0,'C');
				$X = 0;
				foreach ($value['semana'] as $_key => $_value) {
					$salto = $X == (count($data['semanas'])-1) ? 1 : 0;
					$this->Cell($ancho,5,$_value['horas'],1,$salto,'C');
					$X++;
				}
			}
		}
	}
?>