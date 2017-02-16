<?php
	class principal{
		var $data;
		function __construct(){$this->data = new general();}
		public function index(){
			if ($_POST) {
				$user = $this->data->log($_POST['email']);
				if ($user == '') {
					$_SESSION['error'] = TRUE;
					return HttpResponse('index.php');
				} else {
					if ($user['pw_user'] == $_POST['pw']) {
						$_SESSION = $user;
						return HttpResponse('index.php');
					} else {
						$_SESSION['error'] = TRUE;
						return HttpResponse('index.php');
					}
				}
			}else{
				if ($_SESSION['code_user']) {
					$str_datos = file_get_contents("main/templates/complementos/apps.json");
    				$app = json_decode($str_datos,true);
    				$_SESSION['app'] = $app[$_SESSION['nivel_user']];
    				$ents['user'] = $this->data->usSel();
					$ents['depe'] = $this->data->depSel();
    				return render_to_response(vista::page('inicio.html',$ents));
				} else {
					return render_to_response(vista::index('form-log.html'));
				}
			}
		}
		public function ips(){
			if ($_POST) {
				if($_POST['ip4E']){
					$this->data->editIp($_POST);
					return HttpResponse('index.php/ips');
				}elseif ($_POST['ip4S']) {
					$this->data->saveIp($_POST);
					return HttpResponse('index.php/ips');
				} else {
					$ip = $this->data->ip($_POST['ip4']);
					if ($ip != '') {
						if ($_POST['edit']) {
							$ip['error'] = TRUE;
							$ip['edit'] = TRUE;
							$ip['us'] = $this->data->us();
							$ip['ar'] = $this->data->ar();
						$ip['ip4'] = $_POST['ip4'];
						return render_to_response(vista::page('ips.html',$ip));
						} else {
							return render_to_response(vista::page('ips.html',$ip));
						}
					} else {
						$ip['us'] = $this->data->us();
						$ip['ar'] = $this->data->ar();
						$ip['error'] = TRUE;
						$ip['ip4'] = $_POST['ip4'];
						return render_to_response(vista::page('ips.html',$ip));
					}
				}
			} else {
				return render_to_response(vista::page("ips.html"));
			}
		}
		public function reporteIp(){
			$ips = $this->data->ips();
			return render_to_response(vista::page('reporteIp.html',$ips));
		}
		public function cDependencia(){
			global $url_array;
			$depen['lis'] = $this->data->lisDe();
			$depen['ffa'] = $this->data->ffaDep();
			if ($url_array[2] == 'delete'){
				if ($url_array[3] == 'si') {
					$this->data->deleDepe($_GET['depeName']);
					return HttpResponse('index.php/cap-dep');
				}else{
					$depen['uni'] = $this->data->dpUni($_GET['depeName']);
					return render_to_response(vista::page('deleteDepe.html',$depen));
				}
			}
			if ($_GET['depeName'] and !$_POST['depEdi']) {
				$depen['uni'] = $this->data->dpUni($_GET['depeName']);
				$depen['view'] = TRUE;
				if ($_POST['depEdit'])
					$depen['edit'] = $depen['error'] = TRUE;
			}else{
				if ($_POST['depSave']) {
					$depen['error'] = TRUE;
				}else{
					if ($_POST['depSav']) {
						$_POST['abr'] = strtoupper($_POST['abr']);
						$this->data->depSav($_POST);
					}else{
						$_POST['abr'] = strtoupper($_POST['abr']);
						$this->data->depEdi($_POST);
					}
				}
			}
			return render_to_response(vista::page('cDependecia.html',$depen));
		}
		public function entradaProdu(){
			$ents['dep'] = $this->data->depSel();
			if($_GET){
				if ($_POST){
					$arr = array('id'=>$_POST['cod'],'user'=>$_SESSION['name_user'],'fecha'=>date('j-n-y'),'hora'=>date('H:i'));
					$this->data->bajasEn($arr);
					return HttpResponse('index.php');
				}else{
					$cod=$_GET['codigoBus'];
					$cod = $this->data->ent($cod);
					$cod['ddb'] = true;
					return render_to_response(vista::page('repoEntra.html',$cod));
				}
			}else{
				if ($_POST) {
					$nombreDirectorio = "main/templates/complementos/fotos/";
					for($i = 0;$i < 3;$i++){
						$foto = explode(".", $_FILES['foto']['name'][$i]);
						$ext = strtolower($foto[count($foto)-1]);
						$sis['foto'][$i] = $_POST['codeFecha'].'('.($i+1).').'.$ext;
						move_uploaded_file($_FILES['foto']['tmp_name'][$i], $nombreDirectorio.$sis['foto'][$i]);
					}
					$sis['foto1'] = $sis['foto'][0];
					$sis['foto2'] = $sis['foto'][1];
					$sis['foto3'] = $sis['foto'][2];
					$sis['user'] = $_SESSION['id_user'];
					$sis['fecha'] = date('j-n-y');
					$sis['hora'] = date('H:i');
					if ($_POST['options'] == 0) {
						$_POST['options'] = 1;
					}
					$this->data->saveEn($_POST,$sis);
					$this->barcode($_POST['codeFecha'],$_POST['depen'],$_POST['des']);
					return HttpResponse('index.php');
				} else {
					$ents['data'] = date('syimHd');
					return render_to_response(vista::page('entradas.html',$ents));
				}
			}
		}
		public function conGet(){
			$d[0] = $this->data->dpUni($_GET['d']);
			$d[1] = $this->data->dpF($_GET['d']);
			echo json_encode($d);exit();
		}
		public function get_f(){
			$d = $this->data->nffa($_GET['id']);
			return JsonResponse($d);
		}
		public function reporteEnt(){
			global $url_array;
			if ($url_array[2]) {
				$ent = $this->data->ent($url_array[2]);
				return render_to_response(vista::page('repoEntra.html',$ent));
			}else{
				$fec = date('j-n-y');
				$ents['dh'] = $this->data->ent2($fec);
				$ents['user'] = $this->data->usSel();
				$ents['depe'] = $this->data->depSel();
				$ents['nombres'] = $this->data->entradas();
				if ($_POST) {
					$us['nom'] = $_POST['nombre'];
					$us['user'] = $_POST['user'];
					$us['dp'] = $_POST['dep'];
					$us['fec'] = $_POST['dia']."-".$_POST['mes']."-".$_POST['anio'];
					$ents['inf'] = $this->data->busentra($us);
					$ents['dat'] = true;
 				}
				return render_to_response(vista::page('ReporteEnt.html',$ents));
			}
		}
		public function regisUser(){
			$arr['are'] = $this->data->arSel();
			$arr['dep'] = $this->data->depSel();
			if ($_POST){
				$this->data->regisUs($_POST);
				return render_to_response(vista::page('good.html'));
			}else{
				return render_to_response(vista::page('regisUser.html',$arr));
			}
		}
		public function salidaProd(){
			$arr['user'] = $_SESSION['name_user'];
			$arr['fecha'] = date('d-m-y');
			$arr['hora'] = date('H:m');
			$arr['dep'] = $this->data->dpSal($_SESSION['depen_user']);
			$arr['ffa'] = $this->data->ffaDep();
			$arr['dep2'] = $this->data->depSel();
			$arr['todo'] = $this->data->codsSali();
			if($_GET){
				if($_POST){
					$arr = array('id'=>$_POST['cod'],'user'=>$_SESSION['name_user'],'fecha'=>date('j-n-y'),'hora'=>date('H:i'));
					$this->data->altasSal($arr);
					return HttpResponse('index.php');
				}else{
					$cod = $_GET['codigoBus'];
					$cod = $this->data->sal($cod);
					$cod['dda'] = true;
					return render_to_response(vista::page('repoSalida.html',$cod));
				}
			}else{
				if ($_POST){
					if($_FILES){
						$nombreDirectorio = "main/templates/complementos/fotos/";
						$foto = explode('.',$_FILES['foto2']['name']);
						$ext = $foto[count($foto)-1];
						$arr['foto2'] = $_POST['codeFecha'].'.'.$ext;
						copy($_FILES['foto2']['tmp_name'], $nombreDirectorio.$arr['foto2']);
					}
					if ($_POST['options'] == 0) {
						$_POST['options'] = 1;
					}
					$this->data->salPro($_POST, $arr);
					$dep = $arr['dep']['nombre_depe'];
					$nffa = $this->data->nffa($_POST['ffaoptions']);
				}
				$arr['data'] = date('syimHd');
				return render_to_response(vista::page('salidas.html',$arr));
			}
		}
		public function reporteSal(){
			global $url_array;
			if ($url_array[2]) {
				if($_POST){
					$this->data->autSal($_POST);
					return render_to_response(vista::page('autSal.html'));
				}else{
					$sal = $this->data->sal($url_array[2]);
					return render_to_response(vista::page('repoSalida.html',$sal));
				}
			}else{
				$sali['dh'] = $this->data->saliDH();
				$sali['dep'] = $this->data->depSel();
				$sali['todo'] = $this->data->repoSali();
				if($_POST){
					$us['pro'] = $_POST['prod'];
					$us['dp'] = $_POST['dep'];
					$us['fec'] = $_POST['dia']."-".$_POST['mes']."-".$_POST['anio'];
					$sali['inf'] = $this->data->busali($us);
					$sali['dat'] = true;
				}
				return render_to_response(vista::page('ReporteSal.html',$sali));
			}
		}
		public function reporteSalAut(){
			global $url_array;
			if ($url_array[2]) {
				$sal = $this->data->sal($url_array[2]);
				return render_to_response(vista::page('repoSalida.html',$sal));
			}else{
				$sali = $this->data->repoSali();
				return render_to_response(vista::page('ReporteSalAut.html',$sali));
			}
		}
		public function ffas(){
			$arr['dep'] = $this->data->depSel();
			if($_POST){
				$nombreDirectorio = "main/templates/complementos/firmas/";
				$nombreFichero = $_POST['nom'].'-'.$_POST['dp'].'.jpg';
				move_uploaded_file($_FILES['foto']['tmp_name'], $nombreDirectorio.$nombreFichero);
				$arr['foto'] = $nombreFichero;
				$this->data->ffasSave($_POST, $arr);
				return render_to_response(vista::page('ffas.html', $arr));
			}else{
				return render_to_response(vista::page('ffas.html',$arr));
			}
		}
		public function Delffas(){
			$arr['dep'] = $this->data->lisDe();
			if($_GET){
				$arr['ffas'] = $this->data->dpF($_GET['depen']);
			}
			if($_POST){
				for ($i=0; $i < count($_POST['ffas']) ; $i++){ 
					$f = $this->data->busffa($_POST['ffas'][$i]);
					unlink('main/templates/complementos/firmas/'.$f['foto_ffa']);
					$this->data->delffa($_POST['ffas'][$i]);
				}
				unset($arr['ffas']);
			}
			return render_to_response(vista::page('delffa.html',$arr));
		}
		public function fpdf($cod,$pro,$dep,$des,$nCap,$nAut){
			require_once 'main/templates/complementos/fpdf/udgpdf.php';
			$pdf=new udg('P');
			$pdf->AddPage();
			$pdf->Header($cod);
			$pdf->Body_sali($pro,$dep,$des);
			$pdf->Footer($nCap,$nAut);
			$pdf->Output('prueba','i');
		}
		public function pdft($no,$nom,$dep,$hE,$hS,$cod,$ind = false){
			require_once 'main/templates/complementos/fpdf/udgpdf.php';
			$pdf=new tarjetas('P');
			$pdf->SetMargins(60,5,0);
			$pdf->AddPage();
			$pdf->tarjeta($no,$nom,$dep,$hE,$hS,$cod,$ind);
			$pdf->Output('tarjeta','i');
		}
		public function pdfs($no,$dep,$sol,$ser,$des,$hor,$fec,$ext,$asi,$nom){
			require_once 'main/templates/complementos/fpdf/udgpdf.php';
			$pdf=new services('P');
			$pdf->SetMargins(20,20,10);
			$pdf->AddPage();
			$pdf->body($no,$dep,$sol,$ser,$des,$hor,$fec,$ext,$asi,$nom);
			$pdf->Output('solicitud_de_servicio'.$no,'f');
			rename('solicitud_de_servicio'.$no,'main/templates/complementos/archivos_servicios/solicitud_de_servicio'.$no);
			unlink('solicitud_de_servicio'.$no);
			$pdf=new services('P');
			$pdf->SetMargins(20,20,10);
			$pdf->AddPage();
			$pdf->body($no,$dep,$sol,$ser,$des,$hor,$fec,$ext,$asi,$nom);
			$pdf->Output('solicitud_de_servicio'.$no,'i');
		}
		public function services(){
			if ($_POST) {
				if($_POST['options'] == 0){
					$_POST['options'] = 1;
				}
				$_POST['fecha'] = date('j-n-y');
				$_POST['hora'] = date('H:m');
				$this->data->solSer($_POST);
				$_POST['newCod'] = date('syimHd');
				if($_POST['js'] == 1){
					echo json_encode($_POST);exit();
				}else{
					return render_to_response(vista::page('sersSol.html', $_POST));
				}
			}else{
				$arr['data'] = date('syimHd');
				$arr['dep'] = $this->data->depSel();
				return render_to_response(vista::page('servicios.html',$arr));
			}
		}
		public function staticPage($html){
			if ($_SESSION) {
				return render_to_response(vista::page($html));
			}else{
				return render_to_response(vista::index($html));
			}
		}
		public function personal(){
			$arr = $this->data->arSel();
			if($_POST){
				$nombreDirectorio = "main/templates/complementos/fotos_per/";
				$foto = explode('.', $_FILES['foto']['name']);
				$ext = $foto[count($foto)-1];
				$nombreFichero = $_POST['cod'].'.'.$ext;
				move_uploaded_file($_FILES['foto']['tmp_name'], $nombreDirectorio.$nombreFichero);
				$_POST['foto'] = $nombreFichero;
				$horD = $_POST['hor']['d'];
				$horL = $_POST['hor']['l'];
				$horMa = $_POST['hor']['ma'];
				$horMi = $_POST['hor']['mi'];
				$horJ = $_POST['hor']['j'];
				$horV = $_POST['hor']['v'];
				$horS = $_POST['hor']['s'];
				if($horL[0]==1){$hor[0]=$horL[1]; $hor[1]=$horL[2];}
				elseif($horMa[0]==1){$hor[0]=$horMa[1]; $hor[1]=$horMa[2];}
				elseif($horMi[0]==1){$hor[0]=$horMi[1]; $hor[1]=$horMi[2];}
				elseif($horJ[0]==1){$hor[0]=$horJ[1]; $hor[1]=$horJ[2];}
				elseif($horV[0]==1){$hor[0]=$horV[1]; $hor[1]=$horV[2];}
				elseif($horS[0]==1){$hor[0]=$horS[1]; $hor[1]=$horS[2];}
				elseif($horD[0]==1){$hor[0]=$horD[1]; $hor[1]=$horD[2];}
				$_POST['situacion'] = utf8_encode($_POST['situacion']);
				$this->data->perSave($_POST,$hor,$horD,$horL,$horMa,$horMi,$horJ,$horV,$horS);
				return HttpResponse('index.php/');
			}else{
				return render_to_response(vista::page('personal.html',$arr));
			}
		}
		public function personalSS(){
			if($_POST){
				$fecha = explode('/', $_POST['fechaIngreso_pss']);
				$_POST['nombre_pss'] = utf8_encode($_POST['nombre_pss']);
				$_POST['domicilio_pss'] = utf8_encode($_POST['domicilio_pss']);
				$_POST['escuela_pss'] = utf8_encode($_POST['escuela_pss']);
				$_POST['carrera_pss'] = utf8_encode($_POST['carrera_pss']);
				$_POST['observaciones_pss'] = utf8_encode($_POST['observaciones_pss']);
				$_POST['fechaIngreso_pss'] = $fecha[2].'-'.(($fecha[1]<10)?'0'.$fecha[1]:$fecha[1]).'-'.(($fecha[0]<10)?'0'.$fecha[0]:$fecha[0]);
				if($_POST['editar']){
					$id = $_POST['editar'];
					unset($_POST['editar']);
					$sql = "UPDATE pss_mant SET ";
					foreach ($_POST as $key => $value) {
						(strstr($key, 'check')) ? $val = 1:$val = $value;
							$sql.= $key." = '".$val."', ";
					}
					$sql = substr($sql, 0,-2);
					$sql.= " WHERE id_pss = '".$id."'";
				}else{
					$a = "INSERT INTO pss_mant (";
					$b = "VALUES (";
					$i = 0;
					foreach ($_POST as $key => $value) {
						(strstr($key, 'check')) ? $val = 1:$val = $value;
						if($i<(count($_POST)-1)){
							$a .= $key.',';
							$b .= "'".$val."',";
						}else{
							$a .= $key.')';
							$b .= "'".$val."')";
						}
						$i++;
					}
					$sql = $a.$b;
				}
				$this->data->saveCheckPSS($sql);
				return HttpResponse('index.php');
			}else{
				if($_GET){
					$arr = $this->data->getDataPSS($_GET['pss']);
					$fecha = explode('-',$arr['fechaIngreso_pss']);
					$arr['fechaIngreso_pss'] = (($fecha[2]<10)?substr($fecha[2],-1):$fecha[2]).'/'.(($fecha[1]<10)?substr($fecha[1],-1):$fecha[1]).'/'.$fecha[0];
				}else{
					$arr = null;
				}
				return render_to_response(vista::page('registroSS.html',$arr));
			}
		}
		public function viewPSS(){
			if($_POST){
				$id = $_POST['id'];
				$tipo = ($_POST['tipo'] == 'Baja')?'Inactivo':$_POST['tipo'];
				$this->data->bajaPSS($id,$tipo);
				echo json_encode($_POST);exit();
			}else{
				$arr = $this->data->getPSS();
				return render_to_response(vista::page('viewSS.html',$arr));
			}
		}
		public function checkPSS(){
			if($_POST){
				if($_POST['todo']){
					for($i=0;$i<count($_POST['nota']);$i++){
						if($_POST['horc'][$i] != ""){
							if(isset($_POST['verifica']) && $_POST['verifica'][$i] !=""){
								switch ($_POST['verifica'][$i]) {
									case 3:
										$datos[$i]['id_cpss'] = $_POST['id'][$i];
										$datos[$i]['verifica_cpss'] = $_POST['verifica'][$i];
										$datos[$i]['horaCap_cpss'] = '00:00:00';
										$datos[$i]['notas_cpss'] = $_POST['nota'][$i];
									break;
									case 4:
										$datos[$i]['id_cpss'] = $_POST['id'][$i];
										$datos[$i]['verifica_cpss'] = $_POST['verifica'][$i];
										$datos[$i]['horaCap_cpss'] = $_POST['horc'][$i].':00';
										$datos[$i]['notas_cpss'] = $_POST['nota'][$i];
									break;
									default:
										$hora1 = explode(':', $_POST['check'][$i]);
										$hora1 = ($hora1[0]*60)+$hora1[1];
										$hora2 = explode(':', $_POST['horc'][$i]);
										$hora2 = ($hora2[0]*60)+$hora2[1];
										if($_POST['tipo'][$i] == 1){
											if($hora1>=$hora2) $verifica = 1;
											elseif($hora2<=$hora1+30) $verifica = 2;
											else $verifica = 3; 
										}else{
											if($hora2>=$hora1) $verifica = 1;
											if($hora2>=$hora1+60) $verifica = 5;
											if($hora2<$hora1) $verifica = 3;
										}
										$datos[$i]['id_cpss'] = $_POST['id'][$i];
										$datos[$i]['verifica_cpss'] = $verifica;
										$datos[$i]['horaCap_cpss'] = $_POST['horc'][$i].':00';
										$datos[$i]['notas_cpss'] = $_POST['nota'][$i];
									break;
								}
							}else{
								$hora1 = explode(':', $_POST['check'][$i]);
								$hora1 = ($hora1[0]*60)+$hora1[1];
								$hora2 = explode(':', $_POST['horc'][$i]);
								$hora2 = ($hora2[0]*60)+$hora2[1];
								if($_POST['tipo'][$i] == 1){
									if($hora1>=$hora2) $verifica = 1;
									elseif($hora2<=$hora1+30) $verifica = 2;
									else $verifica = 3; 
								}else{
									if($hora2>=$hora1) $verifica = 1;
									if($hora2>=$hora1+60) $verifica = 5;
									if($hora2<$hora1) $verifica = 3;
								}
								$datos[$i]['id_cpss'] = $_POST['id'][$i];
								$datos[$i]['verifica_cpss'] = $verifica;
								$datos[$i]['horaCap_cpss'] = $_POST['horc'][$i].':00';
								$datos[$i]['notas_cpss'] = $_POST['nota'][$i];
							}
						}
					}
					foreach ($datos as $key => $value) {
						$sql = "UPDATE checkPss_mant SET ";
						$i = 0;
						foreach ($value as $key2 => $val) {
							if($key2 == 'id_cpss'){
								$final = " WHERE ".$key2." = '".$val."';";
							}elseif($key2 == 'verifica_cpss'){
								$sql.=" ".$key2." = ".$val.", ";
							}else{
								if($i<count($value)-1)
									$sql.=" ".$key2." = '".$val."', ";
								else
									$sql.=" ".$key2." = '".$val."' ";
							}
							$i++;
						}
						$this->data->saveCheckPSS($sql.$final);
						$consultas[] = $sql.$final;
					}
					return HttpResponse('index.php/Servicio_Social_Check');
				}elseif($_POST['codigo']){
					$arr['per'] = $this->data->personalSS();
					$arr['check'] = $this->data->checksSS($_POST['codigo']);
					return render_to_response(vista::page('checkSS.html',$arr));
				}
			}else{
				$arr['per'] = $this->data->personalSS();
				$arr['check'] = $this->data->checksSS();
				return render_to_response(vista::page('checkSS.html',$arr));
			}
		}
		public function reportePSS(){
			if ($_GET) {
				$dia = $_GET['mes'] == date('m')?date('d'):null;
				$cal = $this->crear_cal($dia,$_GET['mes'],$_GET['anio']);
			}else{
				$cal = $this->crear_cal(date('d'),date('m'),date('Y'));
			}
			$cal['data'] = $this->data->getPrestadores();
			return render_to_response(vista::page('reportePSS.html',$cal));
		}
		public function reporteDiaPSS(){
			if($_POST){
				$fecha = $_POST['fecha'];
				unset($_POST['fecha']);
				unset($_POST['reporte']);
				$this->pdfRDSS($_POST,$fecha);
			}else{
				$repo = $this->data->repDiaPSS($_GET);
				$data = array();
				$i = 0;
				$per = '';
				foreach ($repo as $key => $value) {
					if($per != $value['nombre_pss']){
						$hora = explode(":", $value['horaCap_cpss']);
						$hora = ($hora[0]*60)+$hora[1];
						$data[$i]['horE'] = $value['horaCap_cpss'];
						$data[$i]['notE'] = $value['notas_cpss'];
					}
					else{
						if($hora == 0) $horaExt = 'Entrada';
						else{
							if($value['horaCap_cpss'] == '00:00:00') $horaExt = 'Salida';
							else{
								$horas = explode(':', $value['horaCap_cpss']);
								$horas = ($horas[0]*60)+$horas[1];
								$horaExt = $horas-$hora;
								$horasExt[0] = floor($horaExt/60); 
								$horasExt[1] = $horaExt%60;
								$horaExt = $horasExt[0].':'.(($horasExt[1]<10)?'0'.$horasExt[1]:$horasExt[1]).':00';
							}
						}
						$data[$i]['horS'] = $value['horaCap_cpss'];
						$data[$i]['notS'] = $value['notas_cpss'];
						$data[$i]['nom'] = $per;$data[$i]['hor']=$horaExt;$data[$i]['turno']=$value['turno_pss'];$i++;
					}
					$per = $value['nombre_pss'];
				}
				return render_to_response(vista::pageWhite('recordAsisSS.html',$data,'Reporte de asistencia (Servicio Social)'));
			}
		}
		public function reporteSemaPSS(){
			if($_POST){
				$fecha = $_POST['fecha'];
				unset($_POST['fecha']);
				unset($_POST['reporte']);
				$this->pdfRS($_POST,$fecha);
			}else{
				$a = 0; 
				$data = array();
				$repo = $this->data->repSemaPSS($_GET);
				foreach ($repo as $key => $value) {
					if($cod != $value['codigo_cpss']){
						$a++;
						$conH[$a-1] = 0;
						$value['horaCap_cpss'] = substr($value['horaCap_cpss'], 0,-3);
						$ho[1]=str_replace(":","",$value['horaCap_cpss']);
						$ho[2]=substr($ho[1], 0, -2);$ho[1]=substr($ho[1], -2);
						$ho[1]+=($ho[2]*=60);
					}else{
						if($value['tipo_cpss']==1){
							$value['horaCap_cpss'] = substr($value['horaCap_cpss'], 0,-3);
							$ho[1]=str_replace(":","",$value['horaCap_cpss']);
							$ho[2]=substr($ho[1], 0, -2);$ho[1]=substr($ho[1], -2);
							$ho[1]+=($ho[2]*=60);
						}else{
							$value['horaCap_cpss'] = substr($value['horaCap_cpss'], 0,-3);
							$ho[2]=str_replace(":","",$value['horaCap_cpss']);
							$ho[3]=substr($ho[2], 0, -2);$ho[2]=substr($ho[2], -2);
							if($value['turno_pss'] == 3) $ho[3]+=24;
							$ho[2]+=($ho[3]*=60);
							$ho[0] = $ho[2]-$ho[1];
							if ($ho[0]>0)
							$conH[$a-1]+=$ho[0];
						}
						$data[$a-1] = array('nom'=>$value['nombre_pss'],'ch'=>$value['cargaHoraria_pss']);
					}
					$cod = $value['codigo_cpss'];
				}
				for ($i=0; $i<count($conH); $i++) { 
					$Sho[1]=floor($conH[$i]/60);$Sho[2]=($conH[$i]%60);
					if($Sho[2]<10)$Sho[2]="0".$Sho[2];
					$conH[$i]=$Sho[1].":".$Sho[2];
					$data[$i]['hor'] = $conH[$i];
				}
				return render_to_response(vista::pageWhite('recordAsisSS.html',$data,'Reporte de asistencia (Servicio Social)'));
			}
		}
		public function reporteMesPSS(){
			if($_POST){
				header('location:../reporte-mensual-Servicio-Social.pdf');
			}else{
				$repo = $this->data->repMesPSS($_GET);
				$faltasen = $faltassal = $i = $j = 0;
				foreach ($repo as $key => $value){
					if(($per != $value['nombre_pss'] and $key > 0 )|| $key == count($repo)-1){
						if($faltasen > $faltassal){
							$datos['faltas'][$j] = $faltasen;
							$faltasen = 0; $faltassal = 0;$j++;
						}else{
							$datos['faltas'][$j] = $faltassal;
							$faltasen = 0; $faltassal = 0;$j++;
						}
					}
					$per = $value['nombre_pss'];
					if($dia == $value['dia_cpss']){
						$datos[$i]['sal'] = $value['horaCap_cpss'];
						$datos[$i]['notSal'] = $value['notas_cpss'];
						$hora = explode(':', $value['horaCap_cpss']);
						$horaS = ($hora[0]*60)+$hora[1];
						$horasR = ($horaE > 0 && $horaS > 0) ? ($horaS-$horaE):0;
						$horas[0] = floor($horasR / 60);
						$horas[1] = $horasR % 60;
						$datos[$i]['hor'] = $horas[0].':'.(($horas[1]<10)?'0'.$horas[1]:$horas[1]).':00';
						if($value['verifica_cpss'] == 3 || $value['horaCap_cpss'] == "00:00:00") $faltassal++;
						if($value['horaCap_cpss'] == "00:00:00") $datos[$i]['sal'] = "FALTA";
						$i++;
					}else{
						$datos[$i]['dia'] = $value['dia_cpss'];
						$datos[$i]['nom'] = $value['nombre_pss'];
						$datos[$i]['ent'] = $value['horaCap_cpss'];
						$datos[$i]['notEnt'] = $value['notas_cpss'];
						$datos[$i]['turno'] = $value['turno_pss'];
						$hora = explode(':', $value['horaCap_cpss']);
						$horaE = ($hora[0]*60)+$hora[1];
						if($value['verifica_cpss'] == 3 || $value['horaCap_cpss'] == "00:00:00") $faltasen++;
						if($value['horaCap_cpss'] == "00:00:00") $datos[$i]['ent'] = "FALTA";
					}
					$dia = $value['dia_cpss'];
				}
				$fecha = $_GET['mes'];
				for($i=0;$i<count($datos['faltas']);$i++){ $faltas[$i] = $datos['faltas'][$i];} 
				unset($datos['faltas']);	
				$this->pdfRMSS($datos,$faltas,$fecha);
				for($i=0;$i<count($faltas);$i++){ $datos['faltas'][$i] = $faltas[$i];} 
				return render_to_response(vista::pageWhite('recordAsisSS.html',$datos,'Reporte de asistencia'));
			}
		}
		public function admPer(){
			global $url_array;
			$pers['ar'] = $this->data->ar();
			if ($url_array[2] == 'delete') {
				if ($url_array[3] == 'yes') {
					$this->data->deletePer($url_array[4]);
					return HttpResponse('index.php/admPer');
				}else{
					$pers['per'] = $this->data->per($url_array[3]);
					return render_to_response(vista::page('deletePer.html',$pers));
				}
			}elseif($url_array[2]){
				if ($_POST) {
					$nombreDirectorio = "main/templates/complementos/fotos_per/";
					if($_FILES['foto']['name'] != ''){
						$nombreFichero = $_POST['cod'].'.jpg';
						move_uploaded_file($_FILES['foto']['tmp_name'], $nombreDirectorio.$nombreFichero);
						$_POST['foto'] = $nombreFichero;
					}else 
						$_POST['foto'] = $_POST['foto1'];
					$hor = array();
					$horD = $_POST['hor']['d'];
					$horL = $_POST['hor']['l'];
					$horMa = $_POST['hor']['ma'];
					$horMi = $_POST['hor']['mi'];
					$horJ = $_POST['hor']['j'];
					$horV = $_POST['hor']['v'];
					$horS = $_POST['hor']['s'];
					if($horL[0]==1){$hor[0]=$horL[1]; $hor[1]=$horL[2];}
					elseif($horMa[0]==1){$hor[0]=$horMa[1]; $hor[1]=$horMa[2];}
					elseif($horMi[0]==1){$hor[0]=$horMi[1]; $hor[1]=$horMi[2];}
					elseif($horJ[0]==1){$hor[0]=$horJ[1]; $hor[1]=$horJ[2];}
					elseif($horV[0]==1){$hor[0]=$horV[1]; $hor[1]=$horV[2];}
					elseif($horS[0]==1){$hor[0]=$horS[1]; $hor[1]=$horS[2];}
					elseif($horD[0]==1){$hor[0]=$horD[1]; $hor[1]=$horD[2];}
					$_POST['situacion'] = utf8_encode($_POST['situacion']);
					$this->data->updatePer($_POST,$hor,$horD,$horL,$horMa,$horMi,$horJ,$horV,$horS,$url_array[2]);
					return HttpResponse('index.php/admPer');
				}else{
					$pers['per'] = $this->data->per($url_array[2]);
					return render_to_response(vista::page('editPer.html',$pers));
				}
			}else{
				$list = $this->data->listper();
				return render_to_response(vista::page('admPerlist.html', $list));
			}
		}
		public function tarjetas(){
			global $url_array;
			if ($_POST['btn']){
				$this->pdft($_POST['no'],$_POST['nom'],$_POST['dep'],$_POST['hE'],$_POST['hS'],$_POST['cod'],true);
				$this->genHorarios($_POST['cod']);
			}elseif($_POST['todos']){
				$arr = $this->data->personal();
				require_once 'main/templates/complementos/fpdf/udgpdf.php';
				$pdf=new tarjetas('P');
				$pdf->SetMargins(60,5,0);
				$turno = 0;
				foreach ($arr as $key => $value) {
					if($value['turno_per'] > $turno) $j = 1;
					$pdf->AddPage();
					$pdf->tarjeta($j,$value['nombre_per'],utf8_encode($value['name_area']),$value['horEn_per'],$value['horSal_per'],$value['cod_per']);
					$j++;
					$turno = $value['turno_per'];
				}
				$pdf->Output('tarjetas','i');
			}else{
				$mat = $this->data->permat1();
				$ves = $this->data->perves1();
				$mix = $this->data->permix1();
				$var = $this->data->pervar1();
				$tod = $this->data->conPer();
				$ar = $this->data->ar();
				$arr = [$mat,$ves,$mix,$var,$tod,$ar];
				return render_to_response(vista::page('tarjetas.html', $arr));
			}
		}
		public function tarjetasPSS(){
			if ($_POST['btn']){
				$this->pdft($_POST['no'],$_POST['nom'],'',$_POST['hE'],$_POST['hS'],$_POST['cod'],true);
				$this->genHorariosSS($_POST['cod']);
			}elseif($_POST['todos']){
				$arr = $this->data->allPSS();
				require_once 'main/templates/complementos/fpdf/udgpdf.php';
				$pdf=new tarjetas('P');
				$pdf->SetMargins(60,5,0);
				$turno = 'Matutino';
				$j = 1;
				foreach ($arr as $key => $value) {
					if($value['turno_pss'] != $turno) $j = 1;
					$pdf->AddPage();
					$pdf->tarjeta($j,utf8_decode($value['nombre_pss']),'',$value['entradaLunes_pss'],$value['salidaLunes_pss'],$value['codigo_pss']);
					$j++;
					$turno = $value['turno_pss'];
				}
				$pdf->Output('tarjetas','i');
			}else{
				$mat = $this->data->PSSmat();
				$ves = $this->data->PSSves();
				$arr = [$mat,$ves];
				return render_to_response(vista::page('tarjetasSS.html',$arr));
			}
		}
		public function admUser(){
			global $url_array;
			if ($url_array[2]){
				if($_POST){
					//echo "<pre>";print_r($_POST);exit();
					if($_POST['deshabilitar']){
						$this->data->deshabilitar_user($_POST['deshabilitar']);
					}else{
						$this->data->actUsers($_POST);
					}
					return render_to_response(vista::page('actgood.html'));
				}else{
					$arr['are'] = $this->data->arSel();
					$arr['dep'] = $this->data->depSel();
					if ($url_array[2] == 'delete'){
						if ($url_array[3] == 'yes') {
							$this->data->deleteUse($url_array[4]);
							return HttpResponse('index.php/admUsers');
						}else{
							$arr['us'] = $this->data->user($url_array[3]);
							return render_to_response(vista::page('deleteUser.html',$arr));
						}
					}else{
						$arr['us'] = $this->data->user($url_array[2]);
						return render_to_response(vista::page('editUser.html',$arr));
					}
				}
			}else{
				$arr['users'] = $this->data->users();
				return render_to_response(vista::page('adm_users.html',$arr));
			}
		}
		public function asigserver(){
			global $url_array;
			if ($_POST['ser'] && $_POST['asig']) {
				$asig = $this->data->asigpdf($_POST['asig']);
				$this->pdfs($_POST['cod'],$_POST['dep'],$_POST['sol'],$_POST['tipo'],$_POST['des'],$_POST['hor'],$_POST['fec'],$_POST['ext'],$asig['nombre_per'],$asig['nombra_per']);
				$this->data->asigPer($_POST);
				return HttpResponse('index.php/asigserver');
			}else if($_POST['id'] && $_POST['btn']){
				$this->data->actestser($_POST);
				return HttpResponse('index.php/asigserver');
			}elseif($_POST['id']&& !$_POST['btn']){
				$result = $this->data->servic($_POST['id']);
				$this->data->elmiOficioSer($result['oficio_ser']);
				$result = $this->data->elimServ($_POST['id']);
				echo $result; exit();
			}else{
				if ($url_array[2]) {
					$serv = $this->data->servic($url_array[2]);
					$ar[0] = 4;
					if ($serv['tipo_ser'] == 4) {
						$ar[0] = 6;
					}elseif($serv['tipo_ser'] >4){
						$ar[1] = 6;
					}
					if(is_numeric($serv['asig_ser']) && $serv['asig_ser'] != '0') $serv = $this->data->busSerAsig($serv['id_ser']);
					return render_to_response(vista::page('asigser.html',$serv));
				}else{
					if($_SESSION['depen_user'] == 14 && $_SESSION['area_user'] != 5){ $depen = '';}
					elseif($_SESSION['area_user'] == 5){ $depen = "AND tipo_ser = '6'";}
					else $depen = 'AND dep_ser = "'.$_SESSION['depen_user'].'"';
					$seg['sa'] = $this->data->servi($depen);
					$seg['pen'] = $this->data->servi2($depen);
					$seg['fin'] = $this->data->servif($depen);
					$seg['fin2'] = $this->data->servif2($depen);
					foreach ($seg['pen'] as $clave => $valor) {
						if(is_numeric($valor['asig_ser'])) $seg['pen'][$clave] = $this->data->busSerAsig($valor['id_ser']);
					}
					return render_to_response(vista::page('asigsers.html',$seg));
				}
			}
		}
		public function direc(){
			if($_POST){
				if($_POST['options'] == 0){
					$_POST['options'] = 1;
				}
				$nombreDirectorio = "main/templates/complementos/fotos/";
				$nombreFichero = $_FILES['img']['name'];
				move_uploaded_file($_FILES['img']['tmp_name'], $nombreDirectorio.$nombreFichero);
				$_POST['img'] = $nombreFichero;
				$_POST['web'] = str_replace('http://','',$_POST['web']);
				if($_POST['save'] == 1){
					$this->data->dirSave($_POST);
				}else{
					$this->data->dirEdit($_POST);
				}
				return HttpResponse('index.php/dir');
			}else{
				return render_to_response(vista::page('directorio.html'));
			}
		}
		public function mdir(){
			global $url_array;
			if ($url_array[2]) {
				$dat = $this->data->dir($url_array[2]);
				return render_to_response(vista::page("juan.mono",$dat));
			}else{
				if ($_POST['nombre']){
					$sql = "SELECT * FROM directorio_mant WHERE pes_dir = ".$_POST['pestana']." AND nom_dir LIKE '%".$_POST['nombre']."%' ORDER BY nom_dir ASC";
					$data = $this->data->query($sql);
					echo json_encode($data);exit();
				}elseif ($_POST && !$_POST['nombre']) {
					if($_POST['options'] == 0){
						$_POST['options'] = 1;
					}
					$dat['m'] = $this->data->mdirs($_POST['options']);
					$dat['pes'] = $_POST['options'];
					$dat['a'] = TRUE;
				}
				return render_to_response(vista::page('mdir.html',$dat));
			}
		}
		public function generar_tarjeta_directorio(){
			$id = $_GET['ver'];
			$registro = $this->data->query("Select * From directorio_mant Where id_dir = $id");
			//echo "<pre>";print_r($registro);exit();
			if($registro != ''){
				$this->crear_tarjeta_directorio($registro[0]);
			}
		}
		public function crear_tarjeta_directorio($registro){
			require_once 'main/templates/complementos/fpdf/fpdf.php';
			$margin = 0.5;
			$borde = 0;
			$renglon = 8;
			$pdf = new FPDF('P','mm','tarjeta');
			$pdf->SetMargins($margin,$margin);
			$pdf->SetAutoPageBreak(true,0);
			$pdf->SetFont('Arial','B',22);
			$pdf->AddPage();
			//$pdf->Image('main/templates/complementos/img/escudo.jpg',$margin,$margin,15,15,'JPG');
			$pdf->Cell(20,16,'UdeG',$borde,0,'C');
			$pdf->SetFont('Arial','B',16);
			//$pdf->Cell(0,$renglon,"UNIVERSIDAD DE GUADALAJARA",$borde,1,'C');
			//$pdf->Cell(0,7,'',$borde,1);
			//$pdf->MultiCell(20,$renglon,'U. de G.',$borde,'C');
			if($registro['pes_dir'] == 2){
				$pdf->MultiCell(0,$renglon,utf8_decode($registro['nom_dir']),$borde,'C');
				$pdf->Cell(20,$renglon,'',$borde,0);
				if($registro['calle_dir'] != '')
					$pdf->MultiCell(0,$renglon,utf8_decode($registro['calle_dir'].($registro['no_dir'] != '' ? " #".$registro['no_dir']:' S/N')." ".$registro['col_dir']),$borde,'C');
				$pdf->MultiCell(0,$renglon,utf8_encode("Tel: ".$registro['tel_dir']),$borde,'C');
				$pdf->MultiCell(0,$renglon,utf8_encode("Ext: ".$registro['ex_dir']),$borde,'C');
			}elseif($registro['pes_dir'] == 3){
				$pdf->MultiCell(0,$renglon,utf8_decode($registro['depa_dir']),$borde,'C');
				$pdf->Cell(20,$renglon,'',$borde,0);
				$pdf->MultiCell(0,$renglon,utf8_encode("Ext: ".$registro['ex_dir']),$borde,'C');
				$pdf->Cell(20,$renglon,'',$borde,0);
				$pdf->Cell(0,$renglon,"Piso: $registro[piso_dir]",$borde,1,'C');
			}else{
				$pdf->MultiCell(0,$renglon,utf8_decode($registro['nom_dir']),$borde,'C');
				$pdf->Cell(20,$renglon,'',$borde,0);
				$pdf->MultiCell(0,$renglon,utf8_decode($registro['calle_dir'].($registro['no_dir'] != '' ? " #".$registro['no_dir']:' S/N')." ".$registro['col_dir']),$borde,'C');
				$pdf->MultiCell(0,$renglon,utf8_encode("Tel: ".$registro['tel_dir']),$borde,'C');
			}
			if($registro['web_dir'] != '')
				$pdf->MultiCell(0,$renglon,utf8_decode("Página web: ").utf8_encode($registro['web_dir']),$borde,'C');
			$pdf->Output('prueba', 'i');
		}
		public function admdir(){
			global $url_array;
			if ($url_array[2]){
				if ($url_array[2] == 'delete') {
					$this->data->delDir($url_array[3]);
				}else{
					$dat = $this->data->dir($url_array[2]);
					return render_to_response(vista::page('directorio.html',$dat));
				}
				return HttpResponse('index.php/admdir');
			}
			if ($_POST) {
				if($_POST['options'] == 0){
					$_POST['options'] = 1;
				}
				$dat['m'] = $this->data->mdirs($_POST['options']);
				$dat['pes'] = $_POST['options'];
				$dat['a'] = TRUE;
			}
			return render_to_response(vista::page('admdir.html',$dat));
		}
		public function check(){
			$arr['per'] = $this->data->personal();
			if($_POST['nom']){
				$arr['per'] = $this->data->personal();
				$arr['check'] = $this->data->conHor(date('Y-m-d H:i:s'),$_POST['nom']);
				return render_to_response(vista::page('checkin.html',$arr));
			}
			elseif($_POST['total']){
				for ($i=1; $i <= $_POST['total'] ; $i++){
					if ($_POST['horc'][$i] != "" or ($_POST['hor'][$i] && $_POST['hor'][$i] != 3)) {
						$nota = '';
						if($_POST['hor'][$i]){
							if ($_POST['hor'][$i] == 1) {
								$veri = 2;
								$nota = $_POST['nota'][$i];
							} else {
								$veri = 4;
							}
							$hor = $_POST['horr'][$i];
						}else{
							$hor = split(':',$_POST['horc'][$i]);
							$hoa = split(":",$_POST['horr'][$i]);
							$hor[0] = (int)($hor[0]*60)+$hor[1];
							$hoa[0] = (int)($hoa[0]*60)+$hoa[1];
							if ($_POST['tip'][$i] == 1) {
								if ($hor[0] <= $hoa[0]) {
									$veri = 1;
								}elseif ($hor[0] <= ($hoa[0]+30)) {
									$veri = 6;
								}else {
									$veri = 3;
								}	
							} else {
								if ($hor[0] <= ($hoa[0]+60)) {
									$veri = 1;
								} else {
									$veri = 5;
								}
							}
							$hor = $_POST['horc'][$i];
						}
						$id = $_POST['idck'][$i];
						$this->data->saveche($id,$veri,$hor,$nota);
					}
				}
				return HttpResponse('index.php/check');
			}else{
				$arr['per'] = $this->data->personal();
				$arr['check'] = $this->data->conHor(date('Y-m-d H:i:s'));
				return render_to_response(vista::page('checkin.html',$arr));
			}
		}
		public function crear_cal($dia,$mes,$anio){
			require_once 'main/templates/complementos/calendario.php';
			$cal['mes_hoy'] = $mes;
			$cal['anio_hoy'] = $anio;
			$cal['dia'] = $dia;
			$cal['dia_ac'] = 1;
			$cal['name_mes'] = dame_nombre_mes($cal['mes_hoy']);
			$cal['mes_an'] = mes_anterior($cal['mes_hoy']);
			$cal['mes_sig'] = mes_siguiente($cal['mes_hoy']);
			$cal['anio_ant'] = anio_an($cal['mes_hoy'],$cal['anio_hoy']);
			$cal['anio_sig'] = anio_sig($cal['mes_hoy'],$cal['anio_hoy']);
			$cal['n_dias'] = calcula_numero_dia_semana(1,$cal['mes_hoy'],$cal['anio_hoy']);
			$cal['nd_mes'] =  ultimoDia($cal['mes_hoy'],$cal['anio_hoy']);
			$cal['nd_dias'] = 0;
			$cal['nd_semana'] = numeroDeSemana($mes, $anio);
			return $cal;
		}
		public function jusfal(){
			if ($_GET) {
				$dia = $_GET['mes'] == date('m')?date('d'):null;
				$cal = $this->crear_cal($dia,$_GET['mes'],$_GET['anio']);
			}else{
				$cal = $this->crear_cal(date('d'),date('m'),date('Y'));
			}
			return render_to_response(vista::page('calendario.html',$cal));
		}
		public function jufal(){
			require_once 'main/templates/complementos/calendario.php';
			if($_POST){
				if($_POST['ver'] == 1)unset($_POST['jus']);elseif($_POST['ver'] == 0) unset($_POST['cor']);
				if($_POST['extra']){
					$dia = $_GET['dia'];$mes = $_GET['mes'];$anio = $_GET['anio'];
					if((int)$dia<10 and strlen($dia)>1){
						$dia = substr($dia, strlen($dia)-1);
					}if((int)$mes<10 and strlen($mes)>1){
						$mes = substr($mes, strlen($mes)-1);
					}

					foreach ($_POST['extra'] as $key => $value){
						$value['semana'] = numeroDeSemana2($dia,$mes,$anio);
						if($dia<10) $dia = '0'.$dia;
						$value['fecha'] = split('/', $value['fecha']);
						if($value['codigo']){
							$yaexiste = $this->data->busCheck($value);
							for ($i=1; $i < 3; $i++) {
								$value['tipo'] = $i;
								if($i==2){
									$value['fec'] = $mes.$dia.(str_replace(':', '', $value['Salida']));
									if($key>$_POST['botonExtra']){
										$value['horasSalida'] = $value['Salida']; 
									}
								}else{
									$value['fec'] = $mes.$dia.(str_replace(':', '', $value['Entrada']));
									if($key>$_POST['botonExtra']){
										$value['horasEntrada'] = $value['Entrada']; 
									}
								}
								if($key>$_POST['botonExtra']){
									$this->data->agregarCheck($value);
								}else{
									if($yaexiste){
										$this->data->updateCheck($value);
									}else{
										$this->data->agregarCheck($value);
									}
								}
							}
						}
					}
				}elseif($_POST['ver'] == 0){foreach ($_POST['jus'] as $key => $value){if($value['id']){$this->data->jusf($value['id'],$value['nota'],$value['hor'],$_POST['ver']);}}}
				elseif($_POST['ver'] == 1){
					//echo '<pre>';print_r($_POST['cor']);echo '</pre>';exit();
					foreach ($_POST['cor'] as $key => $value){
						if($value['id']){

							//echo '<pre>'; print_r($_FILES); exit();
							/***

							Aquí va la subida del pdf

							***/

							if($_FILES['pdf']['name'][$key] != "" and $_FILES['pdf']['error'][$key] == 0){
								$Folder = __DIR__."/../main/templates/complementos/pdf_corregir/";
								@mkdir($Folder,0777);
								$archivo = explode(".", $_FILES['pdf']['name'][$key]);
								$ext = $archivo[count($archivo)-1];
								$nombre_pdf = "pdf_".uniqid()."_".$key.".".$ext;
								@copy($_FILES['pdf']['tmp_name'][$key], $Folder.$nombre_pdf);
								//echo "nombre:".$_FILES['pdf']['name'][$key]; exit();
							}else{
								$nombre_pdf = "";
							}
 
							for($i=$value['id'];$i<=($value['id']+1);$i++){
								$sql = "UPDATE check_mant SET ";
								if($value['entrada'] != "" and $value["salida"] != ""){
									$check = $this->data->unicheck($i);
									if($check['tipo_check'] == 1)$pos = 'entrada';else $pos = 'salida';
									$hor = split(':',$value[$pos]);
									$value['comparar'] = (int)$hor[1]+($hor[0]*60);
									$hor = split(':',$check['horcap_check']);
									$check['horcap_check'] = (int)$hor[1]+($hor[0]*60);
									if($check['tipo_check'] == 1){
										if($value['comparar']<=$check['horcap_check']+30) $value['verifica'] = 1;
										else $value['verifica'] = 3;
									}else{
										if($value['comparar']<$check['horcap_check']) $value['verifica'] = 3;
										elseif($value['comparar']>=$check['horcap_check'] && $value['comparar']<($check['horcap_check']+60)) $value['verifica'] = 1;
										else $value['verifica'] = 5;
									}
									$sql.= "verifica_check = $value[verifica], hor_check = '$value[$pos]'";
									if($value['nota'] != "")
										$sql.= ", notas_check = '$value[nota]'";
									if($nombre_pdf != "")
										$sql.= ", pdf_check = '$nombre_pdf'";
									$sql.=" WHERE id_check = $i ";
										//echo "$sql\nKey:$key";//exit();
									//$this->data->jusf($i,$value['verifica'],$value[$pos],$_POST['ver']);
									$this->data->query($sql);
								}else{
									if($value['nota'] != "" and $nombre_pdf != "")
										$sql.= "notas_check = '$value[nota]', pdf_check = '$nombre_pdf'";
									elseif($nombre_pdf != "")
										$sql.= "pdf_check = '$nombre_pdf'";
									elseif ($value['nota'] != "")
										$sql.= "notas_check = '$value[nota]'";
									$sql.= " WHERE id_check = $i ";
									$this->data->query($sql);
								}
							}
						}
					} 
				}
			}else{
				$dia = calcula_numero_dia_semana($_GET['dia'],$_GET['mes'],$_GET['anio']);
				$dia = dias_semana($dia);
				$faltantes['a'] = $this->data->faltantes($_GET);
				$faltantes['b']['extra'] = $this->data->notrabaja($dia);
				$faltantes['b']['repo'] = $this->data->personal();
				return render_to_response(vista::pageWhite('faltantes.html',$faltantes,'Justificar faltas'));
			}
		}
		public function justificarPSS(){
			if ($_GET) {
				$dia = $_GET['mes'] == date('m')?date('d'):null;
				$cal = $this->crear_cal($dia,$_GET['mes'],$_GET['anio']);
			}else{
				$cal = $this->crear_cal(date('d'),date('m'),date('Y'));
			}
			return render_to_response(vista::page('justificarPSSCalendar.html',$cal));
		}
		public function jufalSS(){
			require_once 'main/templates/complementos/calendario.php';
			if($_POST){
				if($_POST['ver'] == 1)unset($_POST['jus']);elseif($_POST['ver'] == 0) unset($_POST['cor']);
				//echo '<pre>';print_r($_POST);echo '</pre>';exit();
				if($_POST['extra']){
					$dia = $_GET['dia'];$mes = $_GET['mes'];$anio = $_GET['anio'];
					if((int)$dia<10 and strlen($dia)>1){
						$dia = substr($dia, strlen($dia)-1);
					}if((int)$mes<10 and strlen($mes)>1){
						$mes = substr($mes, strlen($mes)-1);
					}

					foreach ($_POST['extra'] as $key => $value){
						$value['semana'] = numeroDeSemana2($dia,$mes,$anio);
						$dia = str_pad($dia, 2,"0",STR_PAD_LEFT);
						$mes = str_pad($mes, 2,"0",STR_PAD_LEFT);
						$value['fecha'] = split('/', $value['fecha']);
						if($value['codigo']){
							$yaexiste = $this->data->busCheckSS($value);
							for ($i=1; $i < 3; $i++) {
								$value['tipo'] = $i;
								if($i==2){
									//$value['fec'] = $mes.$dia.(str_replace(':', '', $value['Salida']));
									if($key>$_POST['botonExtra']){
										$value['horasSalida'] = $value['Salida']; 
									}
								}else{
									//$value['fec'] = $mes.$dia.(str_replace(':', '', $value['Entrada']));
									if($key>$_POST['botonExtra']){
										$value['horasEntrada'] = $value['Entrada']; 
									}
								}
								if($key>$_POST['botonExtra']){
									$this->data->agregarCheckSS($value);
								}else{
									if($yaexiste){
										$this->data->updateCheckSS($value);
									}else{
										$this->data->agregarCheckSS($value);
									}
								}
							}
						}
					}
				}elseif($_POST['ver'] == 0) {
					foreach ($_POST['jus'] as $key => $value){
						if($value['id']){
							$this->data->jusfSS($value['id'],$value['nota'],$value['hor'],$_POST['ver']);
						}
					}
				}elseif($_POST['ver'] == 1){
					//echo 'holas';exit();
					foreach ($_POST['cor'] as $key => $value){
						if($value['id']){
							for($i=$value['id'];$i<=($value['id']+1);$i++){
								$check = $this->data->unicheckSS($i);
								//echo 'holas<pre>';print_r($check);echo '</pre>';exit();
								if($check['tipo_cpss'] == 1)$pos = 'entrada';else $pos = 'salida';
								$hor = split(':',$value[$pos]);
								$value['comparar'] = (int)$hor[1]+($hor[0]*60);
								$hor = split(':',$check['hora_cpss']);
								$check['hora_cpss'] = (int)$hor[1]+($hor[0]*60);
								if($check['tipo_cpss'] == 1){
									if($value['comparar']<=$check['hora_cpss']+30) $value['verifica'] = 1;
									else $value['verifica'] = 3;
								}else{
									if($value['comparar']<$check['hora_cpss']) $value['verifica'] = 3;
									elseif($value['comparar']>=$check['hora_cpss'] && $value['comparar']<($check['hora_cpss']+60)) $value['verifica'] = 1;
									else $value['verifica'] = 5;
								}
								$this->data->jusfSS($i,$value['verifica'],$value[$pos],$_POST['ver']);
							}
						}
					} 
				}
			}else{
				$dia = calcula_numero_dia_semana($_GET['dia'],$_GET['mes'],$_GET['anio']);
				$dia = dias_semanaSS($dia);
				$_GET['dia'] = str_pad($_GET['dia'], 2,"0",STR_PAD_LEFT);
				$_GET['mes'] = str_pad($_GET['mes'], 2,"0",STR_PAD_LEFT);
				$faltantes['a'] = $this->data->faltantesSS($_GET);
				$faltantes['b']['extra'] = $this->data->notrabajaSS($dia);
				$faltantes['b']['repo'] = $this->data->personalSS();
				//echo '<pre>';print_r($faltantes);echo '</pre>';exit();
				return render_to_response(vista::pageWhite('faltantesSS.html',$faltantes,'Justificar faltas'));
			}
		}
		public function repFaltas(){
			if ($_GET) {
				$dia = $_GET['mes'] == date('m')?date('d'):null;
				$cal = $this->crear_cal($dia,$_GET['mes'],$_GET['anio']);
			}else{
				$cal = $this->crear_cal(date('d'),date('m'),date('Y'));
			}
			$cal['data'] = $this->data->getPersonal();
			//echo '<pre>';print_r($cal['data']);echo '</pre>';exit();
			return render_to_response(vista::page('reportesFal.html',$cal));
		}
		public function repFal(){
			if($_GET){
				$dia = $_GET['mes'] == date('m')?date('d'):null;
				$cal = $this->crear_cal($dia,$_GET['mes'],$_GET['anio']);
			}else
				$cal = $this->crear_cal(date('d'),date('m'),date('Y'));
			return render_to_response(vista::page('reporteDeFaltas.html',$cal));
		}
		public function pdfRD($data,$fecha){
			require_once 'main/templates/complementos/fpdf/udgpdf.php';
			$pdf=new RD('P');
			$pdf->AddPage();
			$pdf->body($data,$fecha);
			$pdf->Output('prueba','i');
		}
		public function pdfRDSS($data,$fecha){
			require_once 'main/templates/complementos/fpdf/udgpdf.php';
			$pdf=new RDSS('P');
			$pdf->AddPage();
			$pdf->body($data,$fecha);
			$pdf->Output('prueba','i');
		}
		public function pdfRS($data,$fecha){
			require_once 'main/templates/complementos/fpdf/udgpdf.php';
			$pdf=new RS('P');
			$pdf->AddPage();
			$pdf->body($data,$fecha);
			$pdf->Output('prueba','i');
		}
		public function pdfRSSS($data,$fecha){
			require_once 'main/templates/complementos/fpdf/udgpdf.php';
			$pdf=new RSSS('P');
			$pdf->AddPage();
			$pdf->body($data,$fecha);
			$pdf->Output('prueba','i');
		}
		public function pdfRM($data,$faltas,$fecha){
			require_once 'main/templates/complementos/fpdf/udgpdf.php';
			$pdf=new RM('P');
			$pdf->AddPage();
			$pdf->body($data,$faltas,$fecha);
			$pdf->Output(__DIR__.'/../reporte-mensual.pdf','f');
		}
		public function pdfREs($data){
			require_once 'main/templates/complementos/fpdf/udgpdf.php';
			$pdf=new REs('P');
			$pdf->AddPage();
			$pdf->body($data);
			$nombre = 'reporte-especial-'.date('YmdHis').'.pdf';
			$pdf->Output($nombre,'f');
			return $nombre;
		}
		public function pdfREsPss($data){
			require_once 'main/templates/complementos/fpdf/udgpdf.php';
			$pdf=new REs('P');
			$pdf->AddPage();
			$pdf->body($data);
			$nombre = 'reporte-especial-pss-'.date('YmdHis').'.pdf';
			$pdf->Output(__DIR__.'/../'.$nombre,'f');
			return $nombre;
		}
		public function pdfRMSS($data,$faltas,$fecha){
			require_once 'main/templates/complementos/fpdf/udgpdf.php';
			$pdf=new RMSS('P');
			$pdf->AddPage();
			$pdf->body($data,$faltas,$fecha);
			$pdf->Output('reporte-mensual-Servicio-Social.pdf','f');
		}
		public function pdfOficioREs($data){
			require_once 'main/templates/complementos/fpdf/udgpdf.php';
			$pdf=new OficioREs('P');
			$pdf->SetMargins(35,5,30);
			$pdf->AddPage();
			$pdf->body($data);
			$pdf->pie();
			$pdf->Output('oficio_reporte_especial.pdf','i');
		}
		public function repDia(){
			if($_POST){
				$fecha = $_POST['fecha'];
				unset($_POST['fecha']);
				unset($_POST['reporte']);
				$this->pdfRD($_POST,$fecha);
			}else{
				$repo = $this->data->repDia($_GET);
				$data = array();
				if(!$_GET['fal']){
					$i=0;
					foreach ($repo as $key => $value) {
						if($per != $value['nombre_per'] or $value['tipo_check'] == 1){
							$hora=$value['hor_check'];
							$ho[1]=str_replace(":","",$value['hor_check']);
							$ho[2]=substr($ho[1], 0, -2);$ho[1]=substr($ho[1], -2);
							$ho[1]+=($ho[2]*=60);
							$data[$i] = array('horaE'=>$value['hor_check'],'notE'=>$value['notas_check']);
						}else{
							if(!$hora) $ho[3] = 'Entrada';
							else{
								if(!$value['hor_check']) $ho[3]='Salida';
								else{
									$ho[2]=str_replace(":","",$value['hor_check']);
									$ho[3]=substr($ho[2], 0, -2);$ho[2]=substr($ho[2], -2);
									if($value['turno_per'] == 3) $ho[3]+=24;
									$ho[2]+=($ho[3]*=60);$ho[0] = $ho[2]-$ho[1];
									$ho[1]=floor(($ho[0]/60));$ho[2]=($ho[0]%60);
									if($ho[2]<0)$ho[2]=0;
									if($ho[2]<10)$ho[2]="0".$ho[2];
									$ho[3]=$ho[1].":".$ho[2];
								}
							} 
							$data[$i]['horaS']=$value['hor_check'];$data[$i]['notS']=$value['notas_check'];
							$data[$i]['nom']=$per;$data[$i]['hor']=$ho[3];$data[$i]['turno']=$value['turno_per'];$i++;
						}
						$per = $value['nombre_per'];
					}
					return render_to_response(vista::pageWhite('recordAsis.html',$data,'Reporte de asistencia'));
				}else{
					foreach ($repo as $key => $value) {
						$nom = $value['nombre_per'];
						if($value['tipo_check'] == 1)
							$data[$nom]['entrada'] = true;
						else
							$data[$nom]['salida'] = true;
						$data[$nom]['turno'] = $value['turno_per'];
					}
					foreach ($data as $key => $value) {
						if(!isset($data[$key]['entrada']))
							$data[$key]['entrada'] = false;
						if(!isset($data[$key]['salida']))
							$data[$key]['salida'] = false;
					}
					return render_to_response(vista::pageWhite('recordFaltas.html',$data,'Reporte de Faltas'));
				}
			}
		}
		public function repSema(){
			if($_POST){
				$fecha = $_POST['fecha'];
				unset($_POST['fecha']);
				unset($_POST['reporte']);
				$this->pdfRS($_POST,$fecha);
			}else{
				$a = 0; 
				$data = array();
				$repo = $this->data->repSema($_GET);
				foreach ($repo as $key => $value) {
					if($cod != $value['codigo_check']){
						$a++;
						$conH[$a-1] = 0;
						$ho[1]=str_replace(":","",$value['hor_check']);
						$ho[2]=substr($ho[1], 0, -2);$ho[1]=substr($ho[1], -2);
						$ho[1]+=($ho[2]*=60);
					}else{
						if($value['tipo_check']==1){
							$ho[1]=str_replace(":","",$value['hor_check']);
							$ho[2]=substr($ho[1], 0, -2);$ho[1]=substr($ho[1], -2);
							$ho[1]+=($ho[2]*=60);
						}else{
							$ho[2]=str_replace(":","",$value['hor_check']);
							$ho[3]=substr($ho[2], 0, -2);$ho[2]=substr($ho[2], -2);
							if($value['turno_per'] == 3) $ho[3]+=24;
							$ho[2]+=($ho[3]*=60);
							$ho[0] = $ho[2]-$ho[1];
							if ($ho[0]>0)
							$conH[$a-1]+=$ho[0];
						}
						$data[$a-1] = array('nom'=>$value['nombre_per'],'ch'=>$value['ch_per']);
					}
					$cod = $value['codigo_check'];
				}
				for ($i=0; $i<count($conH); $i++) { 
					$Sho[1]=floor($conH[$i]/60);$Sho[2]=($conH[$i]%60);
					if($Sho[2]<10)$Sho[2]="0".$Sho[2];
					$conH[$i]=$Sho[1].":".$Sho[2];
					$data[$i]['hor'] = $conH[$i];
				}
				return render_to_response(vista::pageWhite('recordAsis.html',$data,'Reporte de asistencia'));
			}
		}
		public function repMes(){
			$datos = array();
			$faltas = array();
			if($_POST){
				header('location:../reporte-mensual.pdf');
			}else{
				$b = date('ndHi');
				$repo = $this->data->repMes($_GET,$b);
				$data = array();
				if(!$_GET['fal']){
					$repo = $this->data->repMes($_GET,$b);
					$faltasen = $faltassal = $i = $j = 0;
					foreach ($repo as $key => $value){
						if($per != $value['nombre_per']){
							if($faltasen > $faltassal){
								$datos['faltas'][$j] = $faltasen;
								$faltasen = 0; $faltassal = 0;$j++;
							}else{
								$datos['faltas'][$j] = $faltassal;
								$faltasen = 0; $faltassal = 0;$j++;
							}
						}
						$per = $value['nombre_per'];
						if($value['tipo_check'] == 2){
							$datos[$i]['sal'] = $value['hor_check'];
							$datos[$i]['notSal'] = $value['notas_check'];
							$ho[2]=str_replace(":","",$value['hor_check']);
							$ho[3]=substr($ho[2], 0, -2);$ho[2]=substr($ho[2], -2);
							if($value['turno_per'] == 3) $ho[3]+=24;
							$ho[2]+=($ho[3]*=60);$ho[0] = $ho[2]-$ho[1];
							$ho[1]=floor(($ho[0]/60));$ho[2]=($ho[0]%60);
							if($ho[2]<0)$ho[2]=0;
							if($ho[2]<10)$ho[2]="0".$ho[2];
							$ho[3]=$ho[1].":".$ho[2];
							$datos[$i]['hor'] = $ho[3];
							if($value['verifica_check'] == 4) $faltassal++;
							$i++;
						}else{
							$datos[$i]['dia'] = $value['dia_check'];
							$datos[$i]['nom'] = $value['nombre_per'];
							$datos[$i]['ent'] = $value['hor_check'];
							$datos[$i]['notEnt'] = $value['notas_check'];
							$datos[$i]['turno'] = $value['turno_per'];
							$hora=$value['hor_check'];
							$ho[1]=str_replace(":","",$value['hor_check']);
							$ho[2]=substr($ho[1], 0, -2);$ho[1]=substr($ho[1], -2);
							$ho[1]+=($ho[2]*=60);
							if($value['verifica_check'] == 4) $faltasen++;
						}
						$dia = $value['dia_check'];
					}
					$fecha = $_GET['mes'];
					for($i=0;$i<count($datos['faltas']);$i++){ $faltas[$i] = $datos['faltas'][$i];} 
					unset($datos['faltas']);
					$this->pdfRM($datos,$faltas,$fecha);
					for($i=0;$i<count($faltas);$i++){ $datos['faltas'][$i] = $faltas[$i];} 
					return render_to_response(vista::pageWhite('recordAsis.html',$datos,'Reporte de asistencia'));
				}else{
					foreach ($repo as $key => $value) {
						if($nom != $value['nombre_per']){
							$nom = $value['nombre_per'];
							$dia = $value['dia_check'];
							$datos[$nom]['totalE'] = $datos[$nom]['totalS'] = 0;
							if($value['tipo_check'] == 1){
								$datos[$nom][$dia]['entrada'] = true;
								$datos[$nom]['totalE']++;
							}else{
								$datos[$nom][$dia]['salida'] = true;
								$datos[$nom]['totalS']++;
							}
						}else{
							if($dia == $value['dia_check']){
								if($value['tipo_check'] == 1){
									$datos[$nom][$dia]['entrada'] = true;
									$datos[$nom]['totalE']++;
								}else{
									$datos[$nom][$dia]['salida'] = true;
									$datos[$nom]['totalS']++;
								}
							}else{
								$dia = $value['dia_check'];
								if($value['tipo_check'] == 1){
									$datos[$nom][$dia]['entrada'] = true;
									$datos[$nom]['totalE']++;
								}else{
									$datos[$nom][$dia]['salida'] = true;
									$datos[$nom]['totalS']++;
								}
							}
						}
						$nom = $value['nombre_per'];
					}
					return render_to_response(vista::pageWhite('recordFaltas.html',$datos,'Reporte de Faltas'));
				}
			}
		}
		public function repEspecial(){
			$datos = array();
			$faltas = array();
			if($_POST){
				//include 'static/reporte_especial.html';
				//echo "<pre>";print_r($_POST);exit();
				$this->pdfOficioREs($_POST);
			}else{
				$array = $_GET;
				$array['b'] = date('Y-m-d',(strtotime($_GET['b'])+(60*60*24)));
				$repo = $this->data->repEsp($array);
				$data = array();
				$faltasen = $faltassal = $i = $j = 0;
				$_mes = $_anio = $_dia = $acumulado_laborado = $acumulado_capturado = 0;
				$dias = ["D","L","Ma","Mi","J","V","S"];
				$horario = '';
				//echo "<pre>";print_r($repo);exit();
				foreach ($repo as $key => $value) {
					if($_anio != $value['anio_check']){
						$_anio = $value['anio_check'];
						$_mes = $value['mes_check'];
						$_dia = $value['dia_check'];
					}else{
						if($_mes != $value['mes_check']){
							$_mes = $value['mes_check'];
							$_dia = $value['dia_check'];
						}else{
							if($_dia != $value['dia_check']){
								$_dia = $value['dia_check'];
							}
						}
					}

					if($horario == ''){
						foreach ($dias as $_key => $_value) {
							if($value["horE".$_value."_per"] != '' and $value["horS".$_value."_per"] != ''){
								$horario = $value["horE".$_value."_per"]." - ".$value["horS".$_value."_per"];
								break;
							}
						}
					}

					if($value['tipo_check'] == 1){
						$array['entrada'] = ($value['hor_check'] != "")?$value['hor_check']:"nada";
						$array['notas_ent'] = $value['notas_check'];
						$array['fechcon_ent'] = $value['fechcon_check'];
						$entrada_cap = explode(":", $value['horcap_check']);
						$entrada_cap = mktime($entrada_cap[0],$entrada_cap[1],0,0,0,0);
						if($array['entrada'] != "nada"){
							$entrada = explode(":", $array['entrada']);
							$entrada = mktime($entrada[0],$entrada[1],0,0,0,0);
						}else{
							$entrada = 0;
						}
					}else{
						$array['salida'] = ($value['hor_check'] != "")?$value['hor_check']:"nada";
						$array['notas_sal'] = $value['notas_check'];
						$array['fechcon_sal'] = $value['fechcon_check'];
						$salida_cap = explode(":", $value['horcap_check']);
						$salida_cap = mktime($salida_cap[0],$salida_cap[1],0,0,0,0);
						$resultado = $salida_cap-$entrada_cap;
						$resultado = $resultado/60;
						$acumulado_capturado += $resultado;
						if($array['entrada'] != "nada"){
							$salida = explode(":", $array['salida']);
							$salida = mktime($salida[0],$salida[1],0,0,0,0);
						}else{
							$salida = 0;
						}
						if($entrada != 0 && $salida != 0){
							$resultado = $salida-$entrada;
							$resultado = $resultado/60;
							$acumulado_laborado += $resultado;
							$res1 = floor($resultado/60);
							$res2 = $resultado%60;
							$resultado = ((strlen($res1)<2)?"0".$res1:$res1).":".((strlen($res2)<2)?"0".$res2:$res2);
						}else{
							$resultado = "Faltan datos";
						}
						$array['resultado'] = $resultado;
						$datos[$_anio][$_mes][$_dia] = $array;
					}
					$nombre = $value['nombre_per'];
					$ch = $value['ch_per'];
					$codigo = $value['codigo_check'];
				}
				//$horas = $acumulado_laborado/60;
				$horas = floor($acumulado_laborado/60);
				$minutos = $acumulado_laborado%60;
				$datos1['acumulado_laborado'] = str_pad($horas,2,"0",STR_PAD_LEFT).":".str_pad($minutos,2,"0",STR_PAD_LEFT);
				//$horas = $acumulado_capturado/60;
				$horas = floor($acumulado_capturado/60);
				$minutos = $acumulado_capturado%60;
				$datos1['acumulado_capturado'] = str_pad($horas,2,"0",STR_PAD_LEFT).":".str_pad($minutos,2,"0",STR_PAD_LEFT);
				$diferencia = $acumulado_laborado-$acumulado_capturado;
				$horas = floor($diferencia/60);
				$minutos = $diferencia%60;
				if($horas < 0) $horas++;
				if($minutos < 0) $minutos*=(-1);
				$datos1['diferencia'] = str_pad($horas,2,"0",STR_PAD_LEFT).":".str_pad($minutos,2,"0",STR_PAD_LEFT);
				$datos1['data'] = $datos;
				$datos1['nombre'] = $nombre;
				$datos1['horario'] = $horario;
				$datos1['ch'] = $ch;
				$datos1['codigo'] = $codigo;
				$datos1['pdf'] = $this->pdfREs($datos1);
				return render_to_response(vista::pageWhite('recordAsis.html',$datos1,'Reporte de asistencia'));
			}
		}
		public function repEspecialPss(){
			$datos = array();
			$faltas = array();
			if($_POST){
				//include 'static/reporte_especial.html';
				//echo "<pre>";print_r($_POST);exit();
				$this->pdfOficioREs($_POST);
			}else{
				$array = $_GET;
				$array['b'] = date('Y-m-d',(strtotime($_GET['b'])+(60*60*24)));
				$repo = $this->data->repEspPss($array);
				$data = array();
				$faltasen = $faltassal = $i = $j = 0;
				$_mes = $_anio = $_dia = $acumulado_laborado = $acumulado_capturado = 0;
				$dias = ["Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado"];
				$horario = '';
				//echo "<pre>";print_r($repo);exit();
				foreach ($repo as $key => $value) {
					if($_anio != $value['anio_cpss']){
						$_anio = $value['anio_cpss'];
						$_mes = $value['mes_cpss'];
						$_dia = $value['dia_cpss'];
					}else{
						if($_mes != $value['mes_cpss']){
							$_mes = $value['mes_cpss'];
							$_dia = $value['dia_cpss'];
						}else{
							if($_dia != $value['dia_cpss']){
								$_dia = $value['dia_cpss'];
							}
						}
					}

					if($horario == ''){
						foreach ($dias as $_key => $_value) {
							if($value["entrada".$_value."_pss"] != '' and $value["salida".$_value."_pss"] != ''){
								$horario = $value["entrada".$_value."_pss"]." - ".$value["salida".$_value."_pss"];
								break;
							}
						}
					}

					if($value['tipo_cpss'] == 1){
						$array['entrada'] = ($value['hora_cpss'] != "")?$value['hora_cpss']:"nada";
						$array['notas_ent'] = $value['notas_cpss'];
						$array['fechcon_ent'] = $value['fechaCon_cpss'];
						$entrada_cap = explode(":", $value['horaCap_cpss']);
						$entrada_cap = mktime($entrada_cap[0],$entrada_cap[1],0,0,0,0);
						if($array['entrada'] != "nada"){
							$entrada = explode(":", $array['entrada']);
							$entrada = mktime($entrada[0],$entrada[1],0,0,0,0);
						}else{
							$entrada = 0;
						}
					}else{
						$array['salida'] = ($value['hora_cpss'] != "")?$value['hora_cpss']:"nada";
						$array['notas_sal'] = $value['notas_cpss'];
						$array['fechcon_sal'] = $value['fechaCon_cpss'];
						$salida_cap = explode(":", $value['horaCap_cpss']);
						$salida_cap = mktime($salida_cap[0],$salida_cap[1],0,0,0,0);
						$resultado = $salida_cap-$entrada_cap;
						$resultado = $resultado/60;
						$acumulado_capturado += $resultado;
						if($array['entrada'] != "nada"){
							$salida = explode(":", $array['salida']);
							$salida = mktime($salida[0],$salida[1],0,0,0,0);
						}else{
							$salida = 0;
						}
						if($entrada != 0 && $salida != 0){
							$resultado = $salida-$entrada;
							$resultado = $resultado/60;
							$acumulado_laborado += $resultado;
							$res1 = floor($resultado/60);
							$res2 = $resultado%60;
							$resultado = ((strlen($res1)<2)?"0".$res1:$res1).":".((strlen($res2)<2)?"0".$res2:$res2);
						}else{
							$resultado = "Faltan datos";
						}
						$array['resultado'] = $resultado;
						$datos[$_anio][$_mes][$_dia] = $array;
					}
					$nombre = $value['nombre_pss'];
					$ch = $value['cargaHoraria_pss'];
					$codigo = $value['codigo_cpss'];
				}
				//echo "<pre>";print_r($datos);exit();
				//$horas = $acumulado_laborado/60;
				$horas = floor($acumulado_laborado/60);
				$minutos = $acumulado_laborado%60;
				$datos1['acumulado_laborado'] = str_pad($horas,2,"0",STR_PAD_LEFT).":".str_pad($minutos,2,"0",STR_PAD_LEFT);
				//$horas = $acumulado_capturado/60;
				$horas = floor($acumulado_capturado/60);
				$minutos = $acumulado_capturado%60;
				$datos1['acumulado_capturado'] = str_pad($horas,2,"0",STR_PAD_LEFT).":".str_pad($minutos,2,"0",STR_PAD_LEFT);
				$diferencia = $acumulado_laborado-$acumulado_capturado;
				$horas = floor($diferencia/60);
				$minutos = $diferencia%60;
				if($horas < 0) $horas++;
				if($minutos < 0) $minutos*=(-1);
				$datos1['diferencia'] = str_pad($horas,2,"0",STR_PAD_LEFT).":".str_pad($minutos,2,"0",STR_PAD_LEFT);
				$datos1['data'] = $datos;
				$datos1['nombre'] = $nombre;
				$datos1['horario'] = $horario;
				$datos1['ch'] = $ch;
				$datos1['codigo'] = $codigo;
				$datos1['pdf'] = $this->pdfREsPss($datos1);
				$datos1['pss'] = true;
				//echo "<pre>";print_r($datos1);exit();
				return render_to_response(vista::pageWhite('recordAsis.html',$datos1,'Reporte de asistencia'));
			}
		}
		public function genHorariosSS($per=""){
			require_once 'main/templates/complementos/calendario.php';
			$mes = mes_siguiente(date('n'));
			$anio = ($mes == 1)?date('Y')+1:date('Y');


			if($per != ''){
				$arr = $this->data->perespSS($per);
				$mes = date('n');
				$anio = date('Y');
				$hoy = date('j');
			}else{
				$arr = $this->data->personalSS();
				$hoy = 1;
			}

			foreach ($arr as $key => $value) {
				for ($i=$hoy;$i<=ultimoDia($mes,$anio);$i++) { 
					$s = date('W',  mktime(0,0,0,$mes,$i,$anio));
					$d = calcula_numero_dia_semana($i,$mes,$anio);
					if($d == 0) $s+=1;
					$a = horasESS($d);
					$b = horasSSS($d);
					$d = dias_semanaSS($d);
					$data['codigo_cpss'] = $value['codigo_pss'];
					$data['dia_cpss'] = ($i<10)?'0'.$i:$i;
					$data['mes_cpss'] = ($mes<10)?'0'.$mes:$mes;
					$data['anio_cpss'] = $anio;
					$data['semana_cpss'] = $s;
					if($value[$d] == 1){
						for($j=1;$j<3;$j++){
							$data['tipo_cpss'] = $j;
							$data['hora_cpss'] = ($j == 1)?$value[$a].':00':$value[$b].':00';
							$data['fechaCon_cpss'] = $data['anio_cpss'].'-'.$data['mes_cpss'].'-'.$data['dia_cpss'].' '.$data['hora_cpss'];
							$data['idPss_cpss'] = $value['id_pss'];
							$sql1 = "INSERT INTO checkPss_mant( ";
							$sql2 = "VALUES (";
							$k = 0;
							foreach ($data as $key2 => $value2) {
								if($k<count($data)-1){
									$sql1 .= $key2.", ";
									$sql2 .= "'".$value2."', ";
								}else{
									$sql1 .= $key2.") ";
									$sql2 .= "'".$value2."')";
								}
								$k++;
							}
							$this->data->saveCheckPSS($sql1.$sql2);
							$consultas[] = $sql1.$sql2;
						}
					}
				}
			}
			return render_to_response('Listo\n');
		}
		public function genHorarios($per=""){
			require_once 'main/templates/complementos/calendario.php';
			$mes = mes_siguiente(date('n'));
			if ($mes == 1) {
				$anio = date('Y')+1;
			} else {
				$anio = date('Y');
			}
			if($per != ''){
				$arr = $this->data->peresp($per);
				$mes = date('n');
				$anio = date('Y');
				$hoy = date('j');
			}else{
				$arr = $this->data->personal();
				$hoy = 1;
			}
			foreach ($arr as $key => $value){
				for($i=$hoy;$i<=ultimoDia($mes,$anio);$i++){
					$s = date('W',  mktime(0,0,0,$mes,$i,$anio));
					$d = calcula_numero_dia_semana($i,$mes,$anio);
					if($d == 0) $s+=1;
					$a = horasE($d);
					$b = horasS($d);
					$d = dias_semana($d);
					$data['cod'] = $value['cod_per'];
					$data['dia'] = $i;
					$data['mes'] = $mes;
					$data['anio'] = $anio;
					$data['semana'] = $s;
					if ($i <= 9) {
						$dia = "0".$i;
					} else {
						$dia = $i;
					}
					if($value[$d] == 1){
						for($j=1;$j<3;$j++){
							$data['tipo'] = $j;
							if($j == 1)
								$data['hor'] = $value[$a];
							else
								$data['hor'] = $value[$b];
							$ho = str_replace(":","",$data['hor']);
							if(strlen($ho) <= 3){
								for($k = strlen($ho);$k > 0;$k--)
									$ho[$k] = $ho[$k-1];
								$ho[0]='0';
							} 
							$data['fec'] = $mes.$dia.$ho;
							$this->data->horarios($data);
						}
					}
				}
			}
			/** Obtener Vacaciones **/
			$query = "SELECT id_vacaciones,codigo_vacaciones,dia_vacaciones,mes_vacaciones,anio_vacaciones,semana_vacaciones,tipo_vacaciones,hora_vacaciones as hor_vacaciones,horaCap_vacaciones as horcap_vacaciones,verifica_vacaciones,fechcon_vacaciones,notas_vacaciones,fecha_vacaciones FROM vacaciones_mant where mes_vacaciones like '%$mes' and anio_vacaciones = '$anio'";
			$vacaciones = $this->data->query($query);
			//echo '<pre>';print_r($vacaciones);exit();
			$sql = '';
			if(count($vacaciones) > 0){
				foreach ($vacaciones as $key => $value) {
					$sql = " UPDATE check_mant SET ";
					foreach ($value as $_key => $_value) {
						if($_key != "id_vacaciones"){
							if(strstr($_key, "hor"))
								$_value = substr($_value, 0,-3);
							if($_key != "fecha_vacaciones"){
								$sql .= str_replace("vacaciones", "check", $_key)." = ".( ( strstr($_key,"hor") || strstr($_key,"codigo") || strstr($_key,"notas")) ? "'$_value'":(int) $_value).", ";
							}else{
								$sql .= str_replace("vacaciones", "check", $_key)." = '$_value' WHERE codigo_check = '$value[codigo_vacaciones]' and fecha_check = '$value[fecha_vacaciones]' ;";
							}
						}
					}
					$this->data->query($sql);
				}
			}
			/** ################## **/
			if($per){
				return ;
			}else{
				return render_to_response('Listo\n');
			}
		}
		public function segSer(){
			global $url_array;
			if ($url_array[2]) {
				$seg = $this->data->servic($url_array[2]);
				return render_to_response(vista::page('repSe.html',$seg));
			}else{
				if (!$_GET['page']) {
					$seg = $this->data->serviSeg();
				}else{
					$num = $_GET['page']*5;
					$seg = $this->data->serviSeg($num);
				}
				
				return render_to_response(vista::page('repSer.html',$seg));	
			}
		}
		public function barcode($cod = '',$dep = '',$des = ''){
			$arr[0] = $cod;
			$arr[1] = $this->data->selDep($dep);
			$arr[2] = $des;
			return render_to_response(vista::page('barcode.html',$arr));
		}
		public function oficialia(){
			if($_POST){
				if($_POST['edit']){
					$oficio = $this->data->verOficio($_POST['edit']);
					if($_FILES['foto']['name'] != '' && $_FILES['foto']['name'] != $oficio['foto_ofi']){
						$_POST['foto'] = $_FILES['foto']['name'];
						move_uploaded_file($_FILES['foto']['tmp_name'],"main/templates/complementos/fotos_oficios/".$_POST['foto']);
					}
					$_POST['des'] = strtoupper($_POST['des']);
					$_POST['dp'] = $_POST['depen'];
					$this->data->actOfi($_POST);
					if($_POST['con'] == 6){ 
						
					}
					if($_POST['js']) unset($_POST['js']);
				}else{
					if($_FILES['foto']['name'] != ""){
						$nombreDirectorio = "main/templates/complementos/archivos_oficios/";
						$archivo = explode('.',$_FILES['foto']['name']);
						$ext = strtolower($archivo[count($archivo)-1]);
						$replace = array('-','/',' ','.',',');
						$archivo = str_replace($replace, '-', $_POST['numero']);
						$archivo.= '.'.$ext;
						move_uploaded_file($_FILES['foto']['tmp_name'], $nombreDirectorio.$archivo);
						$_POST['foto'] = $archivo;
					}
					$_POST['des'] = strtoupper($_POST['des']);
					$_POST['fecha'] = date('j-n-y');
					$_POST['hora'] = date('H:i');
					$_POST['nCap'] = $_SESSION['id_user'];
					$_POST['cod'] = date('syimHd');
					$_POST['dp'] = $_POST['depen'];
					$ya=false;
					if($_POST['con'] == 6 || $_POST['con'] == 1 || $_POST['con'] == 2 || $_POST['con'] == 3){
						if($_POST['con'] == 6){
							$_POST['options'] = 6;
							$this->data->solSer($_POST);
						}
						$post = $_POST;
						for($count=0;$count<count($post['sede_eve']);$count++){
							$_POST['sede_eve'] = utf8_encode($post['sede_eve'][$count]);
							$_POST['fecha_eve'] = explode('/',$post['fecha_eve'][$count]);
							$_POST['fecha_eve'] = $_POST['fecha_eve'][2].'-'.$_POST['fecha_eve'][1].'-'.$_POST['fecha_eve'][0];
							$fecha = explode('-', $_POST['fecha_eve']);
							$dia = $fecha[2];$mes = $fecha[1]; $anio = $fecha[0];
							$_POST['semana_eve'] = date('W',  mktime(0,0,0,$mes,$dia,$anio));
							$_POST['hora_eve'] = $post['hora_eve'][$count];
							$_POST['nom_eve'] = $post['nom_eve'][$count];
							$this->data->newEvent($_POST);
						}
						$ya = true;
					}elseif($_POST['ser'] && !$ya){
						$_POST['options'] = $_POST['tser'];
						$this->data->solSer($_POST);
					}
					$_POST['fecha'] = date('Y-m-d');
					$this->data->saveofi($_POST);
				}
				if(!$_POST['js'])
					return HttpResponse('index.php/');
			}else{
				$dep['dep'] = $this->data->depSel(); 
				$dep['todo'] = $this->data->oficios();
				foreach ($dep['todo'] as $key => $value) $caps[] = $value['no_ofi'];
				$dep['nums'] = json_encode(array_unique($caps));
				return render_to_response(vista::page('oficialia.html',$dep));
			}
		}
		public function Reporte_ofi(){
			if($_GET){
				if($_POST['edit']){
					if($_FILES['foto']['name'] != ''){
						$nombreDirectorio = "main/templates/complementos/archivos_oficios/";
						$archivo = explode('.',$_FILES['foto']['name']);
						$ext = strtolower($archivo[count($archivo)-1]);
						$replace = array('-','/',' ','.',',');
						$archivo = str_replace($replace, '-', $_POST['numero']);
						$archivo.= '.'.$ext;
						move_uploaded_file($_FILES['foto']['tmp_name'], $nombreDirectorio.$archivo);
						$_POST['foto'] = $archivo;
					}else{
						$_POST['foto'] = $_POST['foto1'];
					}
					$_POST['des'] = strtoupper($_POST['des']);
					$this->data->actualizarOficios($_POST);
					if($_POST['con'] == 6 || $_POST['con'] == 1 || $_POST['con'] == 2 || $_POST['con'] == 3){

						$post = $_POST;
						$this->data->eliminarEventosID($_POST['numero']);
						for($count=0;$count<count($post['sede_eve']);$count++){
							$_POST['sede_eve'] = utf8_encode($post['sede_eve'][$count]);
							$_POST['fecha_eve'] = explode('/',$post['fecha_eve'][$count]);
							$_POST['fecha_eve'] = $_POST['fecha_eve'][2].'-'.$_POST['fecha_eve'][1].'-'.$_POST['fecha_eve'][0];
							$fecha = explode('-', $_POST['fecha_eve']);
							$dia = $fecha[2];$mes = $fecha[1]; $anio = $fecha[0];
							$_POST['semana_eve'] = date('W',  mktime(0,0,0,$mes,$dia,$anio));
							$_POST['hora_eve'] = $post['hora_eve'][$count];
							$_POST['nom_eve'] = $post['nom_eve'][$count];
							//echo "<pre>";print_r($_POST);exit();
							$this->data->newEvent($_POST);
						}
					} 
					return HttpResponse('index.php/Reporte_ofi');
				}
				if($_GET['borrar']){
					$this->data->borrarOficio($_GET['borrar']);
					return HttpResponse('index.php/Reporte_ofi');
				}
				$pos = array_keys($_GET);
				$arr = $this->data->verOficio($_GET[$pos[0]]);
				if($_GET['id'] || $_GET['elim']){
					$arr[$pos[0]] = 1;
					return render_to_response(vista::page('Repo_ofi.html',$arr));
				}else{
					$dep['dep'] = $this->data->depSel(); 
					$dep['inf'] = $arr;
					$dep['todo'] = $this->data->oficios();
					foreach ($dep['todo'] as $key => $value) $caps[] = $value['no_ofi'];
					$dep['nums'] = json_encode(array_unique($caps));
					return render_to_response(vista::page('oficialia.html',$dep));
				}
			}else{
				$arr['dh'] = $this->data->verOfiHoy();
				foreach ($arr['dh'] as $key => $value) {
					$fecha = explode('-', $value['fecha_ofi']);
					$fecha = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
					$arr['dh'][$key]['fecha_ofi'] = $fecha;
				}
				$arr['dep'] = $this->data->depSel();
				$arr['todo'] = $this->data->oficios();
				foreach ($arr['todo'] as $key => $value) $caps[] = $value['userCap_ofi'];
				$arr['caps'] = array_unique($caps);
				$caps = [];
				foreach ($arr['todo'] as $key => $value) $caps[] = $value['no_ofi'];
				$arr['nums'] = array_unique($caps);
				if($_POST){
					if($_POST['id']){
						$file = $this->data->deleteFile($_POST['id']);
						unlink('main/templates/complementos/archivos_oficios/'.$file);
						echo json_encode($file);exit();
					}else{
						$us['pro'] = $_POST['prod'];
						$us['dp'] = $_POST['dep'];
						$us['fec'] = $_POST['anio']."-".$_POST['mes']."-".$_POST['dia'];
						$us['con'] = $_POST['con'];
						$us['num'] = $_POST['num'];
						$us['cl'] = strtoupper($_POST['cl']);
						if($us['fec']=="0-0-0") unset($us['fec']);
						if($_POST['fecIni'] && $_POST['fecFin']){
							$fecha = explode('/', $_POST['fecIni']);
							$us['inicio'] = $fecha[2].'-';
							$us['inicio'].= ($fecha[1]>9)?$fecha[1].'-':'0'.$fecha[1].'-';
							$us['inicio'].= ($fecha[0]>9)?$fecha[0]:'0'.$fecha[0];
							$fecha = explode('/', $_POST['fecFin']);
							$us['fin'] = $fecha[2].'-';
							$us['fin'].= ($fecha[1]>9)?$fecha[1].'-':'0'.$fecha[1].'-';
							$us['fin'].= ($fecha[0]>9)?$fecha[0]:'0'.$fecha[0];
							$us['rango'] = true;
						}
						$arr['inf'] = $this->data->busofi($us,isset($_POST['cerrado']));
						foreach ($arr['inf'] as $key => $value) {
							$fecha = explode('-', $value['fecha_ofi']);
							$fecha = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
							$arr['inf'][$key]['fecha_ofi'] = $fecha;
						}
						$arr['dat'] = true;
					}
				}
				return render_to_response(vista::page('repo_ofi.html',$arr));
			}
		}
		public function eventos(){
			if($_POST){
				$eventos = $this->data->getEventosDate($_POST['fecha']);
				echo json_encode($eventos);exit();
			}else{
				if ($_GET) {
					$dia = $_GET['mes'] == date('m')?date('d'):null;
					$cal = $this->crear_cal($dia,$_GET['mes'],$_GET['anio']);
				}else{
					$cal = $this->crear_cal(date('d'),date('m'),date('Y'));
				}
				return render_to_response(vista::page('eventos.html',$cal));
			}
		}
		public function cambiar_imagen(){
			if($_FILES){
				foreach ($_FILES as $key => $value) {
					$a = 'main/templates/complementos/img-tmp/';
					$dir = $a.$key.'.jpg';
					@move_uploaded_file($value['tmp_name'], $dir);
					@rename($dir,'main/templates/complementos/img/'.$key.'.jpg');
				}
				return HttpResponse('index.php');
			}else{
				return render_to_response(vista::page('cambiar_imagen.html'));
			}
		}
		public function reporte_global_mes(){
			if($_POST){
				header('location:../reporte_global_mes.pdf');
			}elseif($_GET){
				$sql = "Select id_check,semana_check,tipo_check,hor_check,nombre_per,cod_per,ch_per,day(fecha_check) as dia FROM check_mant INNER JOIN personal_mant ON codigo_check = cod_per and status_per in (0,1) WHERE mes_check = '".$_GET['mes']."' and anio_check = '".$_GET['anio']."' order by turno_per asc, nombre_per asc, date(fecha_check) asc, tipo_check asc";
				$meses = $this->data->query($sql);
				$sql = "SELECT semana_check FROM check_mant where mes_check = '".$_GET['mes']."' and anio_check = '".$_GET['anio']."' group by semana_check order by semana_check Asc";
				$semanas = $this->data->query($sql);
				$data = [];
				$codigo = "";
				$X = 0;
				foreach ($meses as $key => $value) {
					if($codigo != $value['cod_per'] and $codigo != ""){
						$data[$X]['nombre'] = $nombre;
						$data[$X]['carga'] = $carga;
						
						foreach ($semanas as $_key => $_value) {
							$data[$X]['semana'][$_value['semana_check']]["horas"] = 0;
						}

						$X++;
					}
					$nombre = $value['nombre_per'];
					$carga = $value['ch_per'];
					$data[$X]['semana'][$value['semana_check']][$value['dia']][$value['tipo_check']] = $value['hor_check'];
					$codigo = $value['cod_per'];
				}
				$data[$X]['nombre'] = $nombre;
				$data[$X]['carga'] = $carga;
				foreach ($semanas as $_key => $_value) {
					$data[$X]['semana'][$_value['semana_check']]["horas"] = 0;
				}
				foreach ($data as $X => $personal) {
					foreach ($personal['semana'] as $Semana => $dias) {
						$semana = 0;
						foreach ($dias as $dia => $horario) {
							$entrada = "$horario[1]:00";
							$salida = "$horario[2]:00";
							$diferencia = 0;
							if(($entrada = strtotime($entrada)) && ($salida = strtotime($salida))){
								$un_dia = strtotime("1970-01-01 18:00:00");
								$diferencia = $salida-$entrada;
								$semana += $diferencia;
							}
						}
						unset($data[$X]['semana'][$Semana]);
						$data[$X]['semana'][$Semana]['horas'] = floor(($semana/60)/60).":".str_pad(floor(($semana/60)%60),2,"0",STR_PAD_LEFT);
					}
				}
				$array = ["data"=>$data,"semanas"=>$semanas];
				$this->pdfRGM($array,(int)$_GET['mes']);
				return render_to_response(vista::pageWhite("reporte_global_mes.html", $array));
			}else{
				return render_to_response("");
			}
		}
		public function pdfRGM($data,$mes){
			require_once 'main/templates/complementos/fpdf/udgpdf.php';
			$pdf=new RGM('P');
			$pdf->AddPage();
			$pdf->body($data,$mes);
			$pdf->Output(__DIR__.'/../reporte_global_mes.pdf','f');
		}
		/*
		public function consolidacion()	{
			if($_GET){
				if(isset($_GET['id_dias']) and isset($_GET['fecha'])){
					require_once __DIR__."/../main/templates/complementos/calendario.php";
					$sql = "SELECT * FROM dia_ganados_mant where id_dias = ".$_GET['id_dias'];
					$personal = $this->data->query($sql);
					//echo '<pre>';print_r($personal);exit();
					$sql = "SELECT * FROM personal_mant where id_per = '".$personal[0]['id_per_dias']."'";
					$personal = $this->data->query($sql);
					//echo "sql: $sql";exit();
					$fecha = explode("-", $_GET['fecha']);
					$data["dia"] = $fecha[2];
					$data["mes"] = $fecha[1];
					$data["anio"] = $fecha[0];
					$data["semana"] = numeroDeSemana2($data["dia"],$data["mes"],$data["anio"]);
					$data["dia_semana"] = calcula_numero_dia_semana($data["dia"],$data["mes"],$data["anio"]);
					$data["entrada"] = $personal[0][horasE($data["dia_semana"])];
					$data["salida"] = $personal[0][horasS($data["dia_semana"])];
					$data["fecha_entrada"] = "$_GET[fecha] $data[entrada]:00";
					$data["fecha_salida"] = "$_GET[fecha] $data[salida]:00";
					$data["fechcon_entrada"] = (int)$data['mes'].$data['dia'].(explode(":", $data['entrada'])[0]).(explode(":", $data['entrada'])[1]);
					$data["fechcon_salida"] = (int)$data['mes'].$data['dia'].(explode(":", $data['salida'])[0]).(explode(":", $data['salida'])[1]);
					for($i=1;$i<=2;$i++){
						$tipo = $i == 1 ? "entrada":"salida";
						$sql = "INSERT INTO vacaciones_mant (codigo_vacaciones,dia_vacaciones,mes_vacaciones,anio_vacaciones,semana_vacaciones,tipo_vacaciones,hora_vacaciones,horaCap_vacaciones,verifica_vacaciones,fechcon_vacaciones,notas_vacaciones,fecha_vacaciones) VALUES ('".$personal[0]['cod_per']."','$data[dia]','$data[mes]','$data[anio]','$data[semana]',$i,'$data[$tipo]','$data[$tipo]',2,'".$data["fechcon_$tipo"]."','Consolidacion de dias','".$data["fecha_$tipo"]."')";
						//echo "sql: $sql";exit();
						//$this->data->query($sql);
					}
					$sql = "UPDATE dia_ganados_mant SET fecha_dias = '".$_GET['fecha']."' WHERE id_dias = ".$_GET['id_dias'];
					//$this->data->query($sql);
					$this->pdfDL((int)$data['mes'],$frase='',$data,$res='',"fin");exit();
					echo '<pre>';print_r($data);exit();
				}else{
					
				}
			}else{
				$dias = $this->data->getConsolidacion();
				//echo '<pre>';print_r($dias);exit();
				return render_to_response(vista::page('consolidacion.html', $dias));
			}
		}
		*/
		public function dias_ganados(){
			$meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
			$mes = date('n') == 1 ? 12 : date('n')-1;
			$anio = $mes == 12 ? date('Y')-1 : date('Y');
			$check = $this->data->query("SELECT * FROM check_mant INNER JOIN personal_mant ON codigo_check = cod_per WHERE mes_check = $mes and anio_check = $anio ORDER BY codigo_check,dia_check,tipo_check");
			foreach ($check as $key => $value) {
				$Check[$value['codigo_check']][] = $value;
			}
			foreach ($Check as $key => $value) {
				$gano = true;
				foreach ($value as $_key => $_value) {
					if($_value['verifica_check'] != 2){
						$gano = false;
						break;
					}
				}
				if($gano){
					$Ganadores[] = array("id_per_dias" => $_value['id_per'],"codigo_dias" => $key,"comentario_dias" => utf8_encode("Día ganado del mes de ".$meses[$mes-1]." - $anio"));
				}
			}
			echo '<pre>';print_r($Ganadores);exit();
		}
		public function eliminar_checkin(){
			if($_GET){
				$sql = "SELECT fecha_check,id_check FROM check_mant WHERE codigo_check = '$_GET[personal]' AND (fecha_check BETWEEN '$_GET[de] 00:00:00' AND '$_GET[a] 23:59:59')";
				echo json_encode($this->data->query($sql));exit();
			}else{
				if($_POST){
					if(count($_POST['borrar']) > 0){
						$sql = " UPDATE check_mant SET hor_check = '0:00', verifica_check = '4', notas_check = 'Registro eliminado' WHERE id_check in ( ";
						foreach ($_POST['borrar'] as $key => $value) {
							$sql.="$value,";
						}
					}else{
						$sql = '';
					}
					if($sql != ''){
						$sql = substr($sql, 0,-2).")";
						$this->data->query($sql);
					}
					$personal['eliminado'] = true;
				}
				$personal['personal'] = $this->data->getPersonal();
				return render_to_response(vista::page('eliminar_checkin.html',$personal));
			}
		}
		public function eventos_sin_archivos(){
			/** Seleccionar todos los registros de eventos que no tengan un archivo pdf. **/
			$eventos = $this->data->query("SELECT nombre_eve,num_ofi_eve,fecha_eve,descripcion_eve,id_ofi FROM eventos_mant INNER JOIN oficios_mant ON num_ofi_eve = no_ofi WHERE archivo_eve = '' ");
			return render_to_response(vista::page('eventos_sin_archivos.html',$eventos));
		}
		public function recuperar_personal(){
			$lista['completado'] = false;
			if(isset($_GET['id'])){
				$sql = "UPDATE personal_mant SET status_per = 0 WHERE id_per = $_GET[id] ";
				$this->data->query($sql);
				$lista['completado'] = true;
			}
			$lista['data'] = $this->data->query("SELECT cod_per,nombre_per,id_per FROM personal_mant WHERE status_per = 5");
			return render_to_response(vista::page('recuperar_personal.html',$lista));
		}
		public function events(){
			$save = false;
			if($_POST){
				//echo "<pre>";print_r($_SESSION);exit();
				$sql = "INSERT INTO events_mant (id_dependencia_event,evento_event,inicio_event,fin_event,id_usuario_event) VALUES ($_SESSION[depen_user],'".utf8_encode($_POST['evento'])."','$_POST[fecha_inicio] $_POST[hora_inicio]:00','$_POST[fecha_fin] $_POST[hora_fin]:00',$_SESSION[id_user]);";
				//echo $sql;exit();
				$this->data->query($sql);
				$save = true;
			}
			$data['save'] = $save;
			return render_to_response(vista::page('events.html',$data));
		}
		public function eventos_info(){
			$eventos = json_decode($this->get_events2(),true);
			return render_to_response(vista::pageWhite('eventos_info.html',$eventos,"Información de Eventos"));
		}
		public function get_events2(){
			$dias = ["Domingo","Lunes","Martes","Miércoles","Juves","Viernes","Sábado"];
			$un_dia = (60*60*24*2);
			$ahora = date('Y-m-d H:i:s');
			$despues = date('Y-m-d',strtotime($ahora)+$un_dia);
			$sql = "Select * From events_mant Inner Join depe_mant On id_dependencia_event = id_depe where fin_event >= '$ahora' limit 10";// and inicio_event <= '$despues 23:59:59'";
			//echo $sql;exit();
			$pasado = $dias[date('w',strtotime($despues))];
			$events = $this->data->query($sql);
			$eventos = ['ahora'=>[],'mas_tarde'=>[],'manana'=>[],'pasado'=>[],"dia"=>$pasado];
			if(count($events)>0){
				foreach ($events as $key => $value) {
					if(strtotime($value['inicio_event']) <= strtotime($ahora)){
						$eventos['ahora'][] = $value;
					}/*elseif(date('Ymd',strtotime($value['inicio_event'])) == date('Ymd',strtotime($ahora))){
						$eventos['mas_tarde'][] = $value;
					}elseif(strtotime($value['inicio_event']) >= (strtotime($despues)-$un_dia) && strtotime($value['inicio_event']) <= strtotime($despues)){
						$eventos['manana'][] = $value;
					}*/else{
						//$eventos['pasado'][] = $value;
						$eventos['mas_tarde'][] = $value;
					}
				}
			}
			//echo '<pre>';print_r($eventos);exit();
			return json_encode($eventos);exit();
		}
		public function get_events(){
			echo $this->get_events2();exit();
		}
		public function appAdmin(){
			if ($_POST) {
				if(!isset($_POST[1]))
					$_POST[1] = [];
				if(!in_array('appAdmin', $_POST[1]))
					$_POST[1][] = 'appAdmin';
				ksort($_POST);
				//echo "<pre>";print_r($_POST);exit();
				$json = json_encode($_POST);
				@file_put_contents("main/templates/complementos/apps.json", $json);
				return HttpResponse('index.php/');
			}else{
				$str_datos = file_get_contents("main/templates/complementos/apps.json");
    			$app['user'] = json_decode($str_datos,true);
    			$app['app'] = [
    						"newUser",
    						"ips",
    						"ipR",
    						"cap-dep",
    						"entraPro",
    						"repEntra",
    						"salidaProd",
    						"repSalida",
    						"ffas",
    						"Delffas",
    						"sers",
    						"personal",
    						"recuperar_personal",
    						"tarjetas",
    						"admUsers",
    						"asigserver",
    						"segSer",
    						"admPer",
    						"dir",
    						"mdir",
    						"admdir",
    						"check",
    						"jusfal",
    						"repFal",
    						"repFal2",
    						"capVaca",
    						//"diaRegalo",
    						"Oficialia",
    						"Reporte_ofi",
    						"cambiar_imagen",
    						"eventos",
    						"events",
    						//"consolidacion",
    						"eliminar_checkin",
    						"eventos_sin_archivos",
    						"Servicio_Social_Registro",
    						"Servicio_Social_Adm",
    						"Servicio_Social_Tarjetas",
    						"Servicio_Social_Reportes",
    						"Servicio_Social_Check",
    						"Servicio_Social_Justificar",
    						"appAdmin"];
				return render_to_response(vista::pageChosen('adapp.html',$app));
			}
		}
		public function editPass(){
			if ($_POST) {
				$this->data->editPass($_POST,$_SESSION['id_user']);
				return HttpResponse('index.php/');
			}else{
				return render_to_response(vista::page('editPass.html'));
			}
		}
		public function pdfDL($mes,$frase='',$data,$res='',$fin,$fecha){
			require_once 'main/templates/complementos/fpdf/udgpdf.php';
			$pdf=new DL('P');
			$pdf->SetMargins(35,5,30);
			//for($i = 1; $i<=count($data);$i++){
			foreach ($data as $key => $value) {
				$pdf->AddPage();
				$pdf->body($mes,$value);
				$pdf->pie($frase,$res,$fin,$fecha);
			}
			$pdf->Output('oficio','i');
		}
		public function diaLibre(){
			if($_POST){
				$mes = $_POST['mes'];
				$frase = $_POST['frase'];
				$res = $_POST['jefe'];
				$fin = $_POST['fin'];
				$fecha = $_POST['fecha'];
				unset($_POST['mes']);
				unset($_POST['frase']);
				unset($_POST['jefe']);
				unset($_POST['fin']);
				unset($_POST['fecha']);
				$this->pdfDL($mes,$frase,$_POST,$res,$fin,$fecha);exit();
				//echo '<pre>';print_r($_POST);echo '</pre>';
			}else{
				$traba = $this->data->empleados($_GET['mes'],$_GET['anio']);
				$gano = true;
				$i = 0;
				$nom = 'Nadie :(';
				$ganadores = [];
				$codigo = '';
				//echo "<pre>";print_r($traba);exit();
				foreach ($traba as $key => $value){
					/*
					if ($nom != $value['nombre_per']){
						if ($gano){ 
							$traba['ganadores'][$i] = array('nombre'=>$nom,'area'=>$ar,'codigo'=>$cod);
							$i++;
						}
						$gano = true;
					}
					*/
					//echo "$value[verifica_check]<br>";
					if($codigo != $value['cod_per'] and $codigo != ''){
						//echo $nom;
						if($gano){
							//echo " ganó";
							$ganadores[] = ["nombre"=>$nom,"area"=>$ar,"codigo"=>$codigo];
						}
						//echo "<br>";
						$gano = true;
					}
					$val = $value['verifica_check'];
					//if($val != 1 && $val != 2 /*&& $val != 5*/) $gano = false;
					if($value['tipo_check'] == 1){
						if($val > 2) 
							$gano = false;
					}else{
						if($val > 2 && $val < 5)
							$gano = false;
					}

					$nom = $value['nombre_per'];
					$codigo = $value['cod_per'];
					$ar = $value['name_area'];
				}
				if ($gano){
					$ganadores[] = array('nombre'=>$nom,'area'=>$ar,'codigo'=>$codigo);
				}
				//echo "<pre>";print_r($ganadores);exit();
				return render_to_response(vista::pageWhite('dia-libre.html', $ganadores,'Ganadores del Día de Estímulo'));
			}
		}
		public function capVaca(){
			if ($_GET) {
				$dia = $_GET['mes'] == date('m')?date('d'):null;
				$cal = $this->crear_cal($dia,$_GET['mes'],$_GET['anio']);
			}else{
				$cal = $this->crear_cal(date('d'),date('m'),date('Y'));
			}
			return render_to_response(vista::page('calendarioVaca.html',$cal));
		}
		public function vacDia(){
			require_once 'main/templates/complementos/calendario.php';
			if ($_POST) {
				$_POST['semana'] = numeroDeSemana2($_POST['dia'],$_POST['mes'],$_POST['anio']);
				$a = "_vacaciones";
				$sql = "INSERT INTO vacaciones_mant (codigo$a,dia$a,mes$a,anio$a,semana$a,tipo$a,hora$a,horaCap$a,verifica$a,fechcon$a,notas$a,fecha$a) VALUES ";
				$ejecutar = false;
				foreach ($_POST['trab'] as $key => $value) {
					if($value[0] == 1){
						$ejecutar = true;
						$fechcon[1] = $_POST['mes'].str_pad($_POST['dia'], 2, "0", STR_PAD_LEFT).($value[1] == "" ? "0000" : str_replace(":", "", $value[1]))
						;
						$fechcon[2] = $_POST['mes'].str_pad($_POST['dia'], 2, "0", STR_PAD_LEFT).($value[2] == "" ? "0000" : str_replace(":", "", $value[2]));
						$value[1] = $value[1] == "" ? "00:00:00" : "$value[1]:00";
						$value[2] = $value[2] == "" ? "00:00:00" : "$value[2]:00";
						$fecha[1] = "$_POST[anio]-$_POST[mes]-$_POST[dia] $value[1]";
						$fecha[2] = "$_POST[anio]-$_POST[mes]-$_POST[dia] $value[2]";
						$value[4] = utf8_decode($value[4]);
						for($i = 1; $i < 3;$i++)
							$sql.= " ('$value[3]','$_POST[dia]','$_POST[mes]','$_POST[anio]',$_POST[semana],$i,'$value[$i]','$value[$i]',2,$fechcon[$i],'$value[4]','$fecha[$i]'), ";
					}
				}
				if($ejecutar){
					$sql = substr($sql, 0,strlen($sql)-2);
					$this->data->query($sql);
				}
			}else{
				$anio = 1 == (int) $_GET['mes'] ? ((int) $_GET['anio'] - 1) : $_GET['anio'];
				$mes = 1 == (int) $_GET['mes'] ? 12 : str_pad(((int) $_GET['mes'] - 1), 2, "0", STR_PAD_LEFT);
				$limite = date("$anio-$mes-28");
				$limite = strtotime($limite);
				$per['limite'] = $limite;
				$dia = calcula_numero_dia_semana($_GET['dia'],$_GET['mes'],$_GET['anio']);
				$dia = dias_semana($dia);
				$sql = "Select nombre_per,cod_per,".str_replace("check", "horE", $dia)." as entrada, ".str_replace("check", "horS", $dia)." as salida from personal_mant where $dia = 1 and status_per in (0,1)";
				$per['per'] = $this->data->query($sql);
				return render_to_response(vista::pageWhite('marcarVaca.html',$per,'Vacaciones'));
			}			
		}
		public function getEventoData(){
			$id = $_POST['id'];
			$data = $this->data->getEventoData($id);
			echo json_encode($data);exit();
		}
		/*public function diaRegalo(){
			echo "Juan";
		}*/
		/*
		public function especial(){
			$data = $this->data->checkmaster();
			$sql = "";
			foreach ($data as $key => $value) {
				$hora = (strlen($value['horcap_check'])<5)?"0".$value['horcap_check']:$value['horcap_check'];
				$fecha = $value['anio_check']."-".($value['mes_check']<10?'0'.$value['mes_check']:$value['mes_check'])."-".($value['dia_check']<10?'0'.$value['dia_check']:$value['dia_check']);
				$hora = $hora=="0"?"00:00":$hora;
				$hora = str_replace("::", ":", $hora);
				$hora = $hora.":00";
				$sql.= "UPDATE `check_mant` SET `fecha_check` = '$fecha $hora' WHERE `id_check` = $value[id_check];\n";
			}
			echo '<pre>'.$sql.'</pre>';
			exit();
		}
		*/
		/*Funciones del sistema*/
		public function url_p($url){
			global $url_array;
			$url_array = explode('/', $url);
			if ($url_array[0] == '' and !$url_array[1]) {
				return '/';
			}elseif(!$url_array[2]){
				if (!$_SESSION['name_user'] and $url_array[1] != 'view') {
					return 'restringido';
				}else{
					if ($url_array[1] == 'view') {
						return $url_array[2];
					}else{
						return $url_array[1];
					}
				}
			}elseif ($url_array[2]) {
				if (!$_SESSION['name_user'] and $url_array[1] != 'view') {
					return 'restringido';
				}else{
					if ($url_array[1] == 'view') {
						return $url_array[2];
					}else{
						return $url_array[1];
					}
				}
			}else{
				return '404';
			}
		}
		public function e404(){
			echo "404";
		}
		public function system_off(){
			echo "Sistema apagado";
		}
		public function distroy(){
			$url = URL_short();
			if ($url =="/distroy") {
				session_destroy();
			}
			return HttpResponse("");
		}
	}
?>