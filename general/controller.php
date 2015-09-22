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
					//echo'<pre>';print_r($_POST);echo'</pre>';exit();
					$nombreDirectorio = "main/templates/complementos/fotos/";
					for($i = 0;$i < 3;$i++){
						$sis['foto'][$i] = $_POST['codeFecha'].'('.($i+1).').jpg';//$_FILES['foto']['name'][$i];
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
					$this->barcode($_POST['codeFecha'],$_POST['depen'],$_POST['des']);//exit();
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
			//return JsonResponse($d);
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
					//echo '<pre>';print_r($arr);echo '</pre>';exit();
					$this->data->altasSal($arr);
					//echo '<pre>';print_r($arr);echo "</pre>";exit();
					return HttpResponse('index.php');
				}else{
					$cod = $_GET['codigoBus'];
					$cod = $this->data->sal($cod);
					$cod['dda'] = true;
					//$cod['get'] = $_GET['codigoBus'];
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
					//$nombreFichero = $_POST['codeFecha'].'.jpg';
					//echo '<pre>';print_r($_POST);print_r($_FILES);echo '</pre>';exit();
					//$arr['foto2'] = ($_FILES['foto2']['name']);
					if ($_POST['options'] == 0) {
						$_POST['options'] = 1;
					}
					$this->data->salPro($_POST, $arr);
					$dep = $arr['dep']['nombre_depe'];
					$nffa = $this->data->nffa($_POST['ffaoptions']);
					//$this->fpdf($_POST['codeFecha'],$_POST['prod'],$dep,$_POST['des'],$_POST['nCap'], $nffa['nombre_ffa']);
				}//else{
					$arr['data'] = date('syimHd');
					return render_to_response(vista::page('salidas.html',$arr));
				//}
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
					//echo '<pre>'.print_r($sali).'</pre>';exit();
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
				$nombreFichero = $_POST['nom'].'-'.$_POST['dp'].'.jpg';//$_FILES['foto']['name'];
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
				//echo '<pre>';print_r($_POST);echo '</pre>';exit();
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
		public function pdft($no,$nom,$dep,$hE,$hS,$cod){
			require_once 'main/templates/complementos/fpdf/udgpdf.php';
			$pdf=new tarjetas('P');
			$pdf->SetMargins(60,5,0);
			$pdf->AddPage();
			$pdf->tarjeta($no,$nom,$dep,$hE,$hS,$cod);
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
				//echo '<pre>';print_r($_POST);echo'</pre>';exit();
				if($_POST['options'] == 0){
					$_POST['options'] = 1;
				}
				$_POST['fecha'] = date('j-n-y');
				$_POST['hora'] = date('H:m');
				$this->data->solSer($_POST);
				//$_POST['cod'] = date('syimHd');
				$_POST['newCod'] = date('syimHd');
				if($_POST['js'] == 1){
					echo json_encode($_POST);exit();
				}else{
					return render_to_response(vista::page('sersSol.html', $_POST));
				}/*else{
					if($_POST['options'] == 0){
						$_POST['options'] = 1;
					}
					$_POST['fecha'] = date('j-n-y');
					$_POST['hora'] = date('H:m');
					$this->data->solSer($_POST);
				}*/
			}else{
				$arr['data'] = date('syimHd');
				$arr['dep'] = $this->data->depSel();//dpSal($_SESSION['depen_user']);
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
				$nombreFichero = $_POST['cod'].'.jpg';//$_FILES['foto']['name'];
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
				$this->data->perSave($_POST,$hor,$horD,$horL,$horMa,$horMi,$horJ,$horV,$horS);
				return HttpResponse('index.php/');
			}else{
				return render_to_response(vista::page('personal.html',$arr));
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
						$nombreFichero = $_POST['cod'].'.jpg';//$_FILES['foto']['name'];
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
				$this->pdft($_POST['no'],$_POST['nom'],$_POST['dep'],$_POST['hE'],$_POST['hS'],$_POST['cod']);
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
		public function admUser(){
			global $url_array;
			if ($url_array[2]){
				if($_POST){
					$this->data->actUsers($_POST);
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
					//echo '<pre>';print_r($serv);echo '</pre>';exit();
					$ar[0] = 4;
					if ($serv['tipo_ser'] == 4) {
						$ar[0] = 6;
					}elseif($serv['tipo_ser'] >4){
						$ar[1] = 6;
					}
					//echo '<pre>';print_r($serv);echo '</pre>';exit();
					if(is_numeric($serv['asig_ser']) && $serv['asig_ser'] != '0') $serv = $this->data->busSerAsig($serv['id_ser']);
					//$serv['per'] = $this->data->selecper($ar);
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
					//echo '<pre>';print_r($seg['pen']);echo '</pre>';exit();
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
				if ($_POST) {
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
				//echo '<pre>';print_r($_POST);echo '</pre>';exit();
				$arr['per'] = $this->data->personal();
				$arr['check'] = $this->data->conHor(date('ndHi'),$_POST['nom']);
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
								if ($hor[0] <= ($hoa[0]+30)) {
									$veri = 1;
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
				$arr['check'] = $this->data->conHor(date('ndHi'));
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
				$cal = $this->crear_cal(null,$_GET['mes'],$_GET['anio']);
			}else{
				$cal = $this->crear_cal(date('d'),date('m'),date('Y'));
			}
			return render_to_response(vista::page('calendario.html',$cal));
		}
		public function jufal(){
			require_once 'main/templates/complementos/calendario.php';
			if($_POST){
				if($_POST['ver'] == 1)unset($_POST['jus']);elseif($_POST['ver'] == 0) unset($_POST['cor']);
				//echo '<pre>';print_r($_POST);echo '</pre>';exit();
				if($_POST['extra']){
					//echo '<pre>';print_r($_POST);echo '</pre>';exit();
					$dia = $_GET['dia'];$mes = $_GET['mes'];$anio = $_GET['anio'];
					if($dia<10 and strlen($dia)>1){
						$dia[0] = $dia[1];
						$dia[1] = '';
					}if($mes<10 and strlen($mes)>1){
						$mes[0] = $mes[1];
						$mes[1] = '';
					}
					foreach ($_POST['extra'] as $key => $value){
						$value['semana'] = numeroDeSemana2($dia,$mes,$anio);
						if($dia<10) $dia = '0'.$dia;
						$value['fecha'] = split('/', $value['fecha']);
						//if($value['fecha'][1]<10) $value['fecha'][1][0] = '';
						if($value['codigo']){
							$yaexiste = $this->data->busCheck($value);
							for ($i=1; $i < 3; $i++) {
								$value['tipo'] = $i;
								if($i==2){
									$value['fec'] = (int)$mes.$dia.str_replace(':', '', $value['Salida']);
								}else{
									$value['fec'] = (int)$mes.$dia.str_replace(':', '', $value['Entrada']);//.str_replace(':','',$value['Entrada']);
									//echo '<pre>';print_r($value);echo '</pre>';exit();
								}
								if($yaexiste){
									//echo '<pre>';print_r($value);echo '</pre>';exit();
									$this->data->updateCheck($value);
									//echo '<pre>';print_r($value);echo '</pre>';exit();
								}else{
									$this->data->agregarCheck($value);
								}
							}
						}
					}
					//echo '<pre>';print_r($_POST['extra']);echo '</pre>';exit();
				}elseif($_POST['ver'] == 0){foreach ($_POST['jus'] as $key => $value){if($value['id']){$this->data->jusf($value['id'],$value['nota'],$value['hor'],$_POST['ver']);}}}
				elseif($_POST['ver'] == 1){
					//echo '<pre>';print_r($_POST['cor']);echo '</pre>';exit();
					foreach ($_POST['cor'] as $key => $value){
						if($value['id']){
							for($i=$value['id'];$i<=($value['id']+1);$i++){
								$check = $this->data->unicheck($i);
								if($check['tipo_check'] == 1)$pos = 'entrada';else $pos = 'salida';
								$hor = split(':',$value[$pos]);
								$value['comparar'] = (int)$hor[1]+($hor[0]*60);
								$hor = split(':',$check['horcap_check']);
								$check['horcap_check'] = (int)$hor[1]+($hor[0]*60);
								if($check['tipo_check'] == 1){
									if($value['comparar']<=$check['horcap_check']+30) $value['verifica'] = 1;
									//elseif($value['comparar']<=($check['horcap_check'])+30) $value['verifica'] = 3;
									else $value['verifica'] = 3;
								}else{
									if($value['comparar']<$check['horcap_check']) $value['verifica'] = 3;
									elseif($value['comparar']>=$check['horcap_check'] && $value['comparar']<($check['horcap_check']+60)) $value['verifica'] = 1;
									//elseif($value['comparar']>=($check['horcap_check'])+30) $value['verifica'] = 3;
									else $value['verifica'] = 5;
								}
								//echo '<pre>'.$value['comparar'].'</pre>';exit();
								$this->data->jusf($i,$value['verifica'],$value[$pos],$_POST['ver']);
							}
						}
					} 
				}
			}else{
				$dia = calcula_numero_dia_semana($_GET['dia'],$_GET['mes'],$_GET['anio']);
				$dia = dias_semana($dia);
				$faltantes['a'] = $this->data->faltantes($_GET);
				$faltantes['b'] = $this->data->notrabaja($dia);
				return render_to_response(vista::pageWhite('faltantes.html',$faltantes,'Justificar faltas'));
			}
		}
		public function repFaltas(){
			if ($_GET) {
				$cal = $this->crear_cal(null,$_GET['mes'],$_GET['anio']);
			}else{
				$cal = $this->crear_cal(date('d'),date('m'),date('Y'));
			}
			return render_to_response(vista::page('reportesFal.html',$cal));
		}
		public function repFal(){
			if($_GET)
				$cal = $this->crear_cal(null,$_GET['mes'],$_GET['anio']);
			else
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
		public function pdfRS($data,$fecha){
			require_once 'main/templates/complementos/fpdf/udgpdf.php';
			$pdf=new RS('P');
			$pdf->AddPage();
			$pdf->body($data,$fecha);
			$pdf->Output('prueba','i');
		}
		public function pdfRM($data,$faltas,$fecha){
			require_once 'main/templates/complementos/fpdf/udgpdf.php';
			$pdf=new RM('P');
			$pdf->AddPage();
			$pdf->body($data,$faltas,$fecha);
			$pdf->Output('reporte-mensual.pdf','f');
		}
		public function repDia(){
			if($_POST){
				$fecha = $_POST['fecha'];
				unset($_POST['fecha']);
				unset($_POST['reporte']);
				$this->pdfRD($_POST,$fecha);
				//echo"<pre>";print_r($_POST);echo"</pre>";
			}else{
				$repo = $this->data->repDia($_GET);
				$data = array();
				if(!$_GET['fal']){
					$i=0;
					foreach ($repo as $key => $value) {
						if($per != $value['nombre_per']){
							$hora=$value['hor_check'];
							$ho[1]=str_replace(":","",$value['hor_check']);
							$ho[2]=substr($ho[1], 0, -2);$ho[1]=substr($ho[1], -2);
							$ho[1]+=($ho[2]*=60);
							$data[$i] = array('horE'=>$value['hor_check'],'notE'=>$value['notas_check']);
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
							$data[$i]['horS']=$value['hor_check'];$data[$i]['notS']=$value['notas_check'];
							$data[$i]['nom']=$per;$data[$i]['hor']=$ho[3];$data[$i]['turno']=$value['turno_per'];$i++;
						}
						$per = $value['nombre_per'];
					}
					//echo"<pre>";print_r($data);echo"</pre>";exit();
					return render_to_response(vista::pageWhite('recordAsis.html',$data,'Reporte de asistencia'));
				}else{
					//$nom = $repo[0]['nombre_per'];
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
					//echo '<pre>';print_r($data);echo '</pre>';exit();
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
				//echo"<pre>";print_r($_POST);echo"</pre>";
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
				//unlink('reporte-mensual-'.$_GET['mes'].'.pdf');
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
						if($dia == $value['dia_check']){
							$datos[$i]['sal'] = $value['hor_check'];
							$datos[$i]['notSal'] = $value['notas_check'];
							//$data[$i]['turno'] = $value['turno_per'];
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
					//echo'<pre>';print_r($faltas);echo '</pre>';exit();	
					$this->pdfRM($datos,$faltas,$fecha);
					for($i=0;$i<count($faltas);$i++){ $datos['faltas'][$i] = $faltas[$i];} 
					//echo'<pre>';print_r($datos);echo '</pre>';exit();
					return render_to_response(vista::pageWhite('recordAsis.html',$datos,'Reporte de asistencia'));
				}else{
					//echo '<pre>'.$dia.'</pre>';exit();
					//echo '<pre>';print_r($repo);echo '</pre>';exit();
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
		public function genHorarios($per=""){
			require_once 'main/templates/complementos/calendario.php';
			$mes = mes_siguiente(date('n'));
			if (date('n') == 12) {
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
			//echo '<pre>';print_r($arr);echo'</pre>';exit();
			return render_to_response(vista::page('barcode.html',$arr));
		}
		public function oficialia(){
			if($_POST){
					//echo '<pre>';print($_POST);echo '</pre>';exit();
				if($_POST['edit']){
					$oficio = $this->data->verOficio($_POST['edit']);
					if($_FILES['foto']['name'] != '' && $_FILES['foto']['name'] != $oficio['foto_ofi']){
						$_POST['foto'] = $_FILES['foto']['name'];
						move_uploaded_file($_FILES['foto']['tmp_name'],"main/templates/complementos/fotos_oficios/".$_POST['foto']);
					}
					$_POST['des'] = strtoupper($_POST['des']);
					$_POST['dp'] = $_POST['depen'];
					$this->data->actOfi($_POST);
					if($_POST['js']) unset($_POST['js']);
				}else{
					//echo '<pre>';print_r($_POST);echo '</pre>';exit();
					//echo json_encode($_FILES);exit();
					$nombreDirectorio = "main/templates/complementos/archivos_oficios/";
					//$nombreFichero = $_POST['numero'].'.jpg';
					$archivo = explode('.',$_FILES['foto']['name']);
					$ext = $archivo[count($archivo)-1];
					$replace = array('-','/',' ','.',',');
					$archivo = str_replace($replace, '-', $_POST['numero']);
					$archivo.= '.'.$ext;
					move_uploaded_file($_FILES['foto']['tmp_name'], $nombreDirectorio.$archivo);
					$_POST['foto'] = $archivo;
					$_POST['des'] = strtoupper($_POST['des']);
					$_POST['fecha'] = date('j-n-y');
					$_POST['hora'] = date('H:m');
					$_POST['nCap'] = $_SESSION['id_user'];
					$_POST['cod'] = date('syimHd');
					$_POST['dp'] = $_POST['depen'];
					$ya=false;
					if($_POST['con'] == 6 ){
						$_POST['options'] = 6;
						$this->data->solSer($_POST);
						$_POST['sede_eve'] = utf8_encode($_POST['sede_eve']);
						$_POST['fecha_eve'] = explode('/',$_POST['fecha_eve']);
						$_POST['fecha_eve'] = $_POST['fecha_eve'][2].'-'.$_POST['fecha_eve'][1].'-'.$_POST['fecha_eve'][0];
						$this->data->newEvent($_POST);
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
					$nombreDirectorio = "main/templates/complementos/fotos_oficios/";
					$nombreFichero = $_FILES['foto']['name'];
					if($_POST['foto1'] != $nombreFichero){
						move_uploaded_file($_FILES['foto']['tmp_name'], $nombreDirectorio.$nombreFichero);
						$_POST['foto'] = $_FILES['foto']['name'];
					}else{
						$_POST['foto'] = $_POST['foto1'];
					}
					$_POST['des'] = strtoupper($_POST['des']);
					//echo '<pre>';print_r($_POST);echo'</pre>';exit();
					$this->data->actualizarOficios($_POST);
					return HttpResponse('index.php/Reporte_ofi');
				}
				if($_GET['borrar']){
					//echo '<pre>';print_r($_GET);echo '</pre>';exit();
					$this->data->borrarOficio($_GET['borrar']);
					return HttpResponse('index.php/Reporte_ofi');
				}
				$pos = array_keys($_GET);
				$arr = $this->data->verOficio($_GET[$pos[0]]);
				if($_GET['id'] || $_GET['elim']){
					$arr[$pos[0]] = 1;
					//echo '<pre>';print_r($arr);echo '</pre>';exit();
					return render_to_response(vista::page('Repo_ofi.html',$arr));
				}else{
					$dep['dep'] = $this->data->depSel(); 
					$dep['inf'] = $arr;
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
				foreach ($arr['todo'] as $key => $value) $caps[] = $value['no_ofi'];
				$arr['nums'] = array_unique($caps);
				if($_POST['id']){
					/*$otro['files'] = $_FILES;
					$otro['post'] = $_POST;
					//echo json_encode($otro);exit();
					$replace = array('-','/',' ','.',',');
					$_POST['no'] = str_replace($replace, '', $_POST['no']);
					$archivo = $_POST['no'];
					$id = $_POST['id'];
					$carpeta = 'main/templates/complementos/archivos_oficios/';
					@copy($_FILES['archivo']['tmp_name'], $carpeta.$archivo.'.pdf');
					$this->data->savePDFofi($archivo.'.pdf',$id);
					$otro['archivo'] = $archivo.'.pdf';
					echo json_encode($otro);exit();*/
					$file = $this->data->deleteFile($_POST['id']);
					unlink('main/templates/complementos/archivos_oficios/'.$file);
					echo json_encode($file);exit();
				}
				else{
					/*if($_POST['edit']){
						$nombreDirectorio = "main/templates/complementos/fotos_oficios/";
						$nombreFichero = $_FILES['foto']['name'];
						move_uploaded_file($_FILES['foto']['tmp_name'], $nombreDirectorio.$nombreFichero);
						$_POST['foto'] = $_FILES['foto']['name'];
						$_POST['des'] = strtoupper($_POST['des']);
						//echo '<pre>';print_r($_POST);echo'</pre>';exit();
						$this->data->actualizarOficio($_POST);
					}else{*/
						//echo '<pre>';print_r($_POST);echo'</pre>';exit();
						$us['pro'] = $_POST['prod'];
						$us['dp'] = $_POST['dep'];
						$us['fec'] = $_POST['anio']."-".$_POST['mes']."-".$_POST['dia'];
						$us['con'] = $_POST['con'];
						$us['num'] = $_POST['num'];
						$us['cl'] = strtoupper($_POST['cl']);
						if($_POST['fecIni']&&$_POST['fecFin']){
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
						//echo '<pre>';print_r($us);echo'</pre>';exit();
						$arr['inf'] = $this->data->busofi($us);
						foreach ($arr['inf'] as $key => $value) {
							$fecha = explode('-', $value['fecha_ofi']);
							$fecha = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];
							$arr['inf'][$key]['fecha_ofi'] = $fecha;
						}
						//echo '<pre>'.$arr['inf'].'</pre>';exit();
						$arr['dat'] = true;
						//echo '<pre>';print_r($arr);print_r($us);echo'</pre>';exit();
					//}
				}
				return render_to_response(vista::page('repo_ofi.html',$arr));
			}
		}
		/*public function Reporte_ofi(){
			if($_GET['edit']){
				$arr['inf'] = $this->data->verOficio($_GET['edit']);
				$arr['dep'] = $this->data->depSel();
				return render_to_response(vista::page('oficialia.html',$arr));
			}
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
			foreach ($arr['todo'] as $key => $value) $caps[] = $value['no_ofi'];
			$arr['nums'] = array_unique($caps);
			//if($_POST &&)
			if($_FILES){
				$otro['files'] = $_FILES;
				$otro['post'] = $_POST;
				$archivo = $_POST['no'];
				$id = $_POST['id'];
				$carpeta = 'main/templates/complementos/archivos_oficios/';
				move_uploaded_file($_FILES['archivo']['tmp_name'], $carpeta.$archivo);
				$this->data->savePDFofi($archivo,$id);
				echo json_encode($otro);exit();
			}
			if($_POST && !$_FILES){
				if($_POST['id'] && $_POST['delete']){
					$oficios = $this->data->verOficio($_POST['id']);
					//$oficios = $this->data->verServicio($oficios['no_ofi'])
					$oficios = $this->data->elimServOfi($oficios['no_ofi']);
					$this->data->borrarOficio($_POST['id']);
				}elseif($_POST['id'] && $_POST['ver']){
					$oficios = $this->data->verOficio($_POST['id']);
				}else{
					if($_POST['all']){
						$oficios = $this->data->oficios();
					}else{
						$us['pro'] = $_POST['prod'];
						$us['dp'] = $_POST['dep'];
						$us['fec'] = $_POST['anio']."-".$_POST['mes']."-".$_POST['dia'];
						$us['con'] = $_POST['con'];
						$us['num'] = $_POST['num'];
						$us['cl'] = strtoupper($_POST['cl']);
						if($_POST['fecIni']&&$_POST['fecFin']){
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
						$oficios = $this->data->busofi($us);
					}
				}
				echo json_encode($oficios);exit();
			}
			return render_to_response(vista::page('repo_ofi2.html',$arr));
		}*/
		public function eventos(){
			if($_POST){
				$eventos = $this->data->getEventosDate($_POST['fecha']);
				echo json_encode($eventos);exit();
			}else{
				if ($_GET) {
					$cal = $this->crear_cal(null,$_GET['mes'],$_GET['anio']);
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
					move_uploaded_file($value['tmp_name'], $dir);
					rename($dir,'main/templates/complementos/img/'.$key.'.jpg');
				}
				//echo '<pre>';print_r($_FILES);print_r($dirs);print_r($names);echo '</pre>';exit();
				return HttpResponse('index.php');
			}else{
				return render_to_response(vista::page('cambiar_imagen.html'));
			}
		}
		public function appAdmin(){
			if ($_POST) {
				$json = json_encode($_POST);
				file_put_contents("main/templates/complementos/apps.json", $json);
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
    						//"repSalidaAut",
    						"ffas",
    						"Delffas",
    						"sers",
    						"personal",
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
    						"diaRegalo",
    						"Oficialia",
    						"Reporte_ofi",
    						//"Reporte_ofi2",
    						"cambiar_imagen",
    						"eventos",
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
		public function pdfDL($mes,$frase='',$data,$res='',$fin){
			require_once 'main/templates/complementos/fpdf/udgpdf.php';
			$pdf=new DL('P');
			$pdf->SetMargins(35,5,30);
			for($i = 1; $i<=count($data);$i++){
				$pdf->AddPage();
				$pdf->body($mes,$data[$i]);
				$pdf->pie($frase,$res,$fin);
			}
			$pdf->Output('oficio','i');
		}
		public function diaLibre(){
			if($_POST){
				$mes = $_POST['mes'];
				$frase = $_POST['frase'];
				$res = $_POST['jefe'];
				$fin = $_POST['fin'];
				unset($_POST['mes']);
				unset($_POST['frase']);
				unset($_POST['jefe']);
				unset($_POST['fin']);
				$this->pdfDL($mes,$frase,$_POST,$res,$fin);exit();
				echo '<pre>';print_r($_POST);echo '</pre>';
			}else{
				$traba = $this->data->empleados($_GET['mes']);
				$gano = true;
				$i = 0;
				$nom = 'Nadie :(';
				foreach ($traba as $key => $value){
					if ($nom != $value['nombre_per']){
						if ($gano){ 
							$traba['ganadores'][$i] = array('nombre'=>$nom,'area'=>$ar,'codigo'=>$cod);
							$i++;
						}
						$gano = true;
					}
					$val = $value['verifica_check'];
					if($val != 1 && $val != 2 && $val != 5) $gano = false;
					$nom = $value['nombre_per'];$cod = $value['cod_per'];$ar = $value['name_area'];
				}
				if ($gano){
					$traba['ganadores'][$i] = array('nombre'=>$nom,'area'=>$ar,'codigo'=>$cod);
				}
				return render_to_response(vista::pageWhite('dia-libre.html', $traba,'Ganadores del Día de Estímulo'));
			}
		}
		public function capVaca(){
			if ($_GET) {
				$cal = $this->crear_cal(null,$_GET['mes'],$_GET['anio']);
			}else{
				$cal = $this->crear_cal(date('d'),date('m'),date('Y'));
			}
			return render_to_response(vista::page('calendarioVaca.html',$cal));
		}
		public function vacDia(){
			if ($_POST) {
				if ($_POST['accion'] == 1) {
					foreach ($_POST['trab'] as $key => $value){
						if ($value[0]){
							$this->data->saveDiar($value[1],$_POST['dia'],$_POST['mes'],$_POST['anio']);
						}
					}
				}elseif ($_POST['accion'] == 2) {
					$this->data->saveVacaciones($_POST['dia'],$_POST['mes'],$_POST['anio']);
				}elseif($_POST['accion'] == 3){
					foreach ($_POST['trab'] as $key => $value) {
						if ($value[0]) {
							$this->data->saveDiat($_POST['dia'],$_POST['mes'],$_POST['anio'],$value[1],$value[2],$value[3]);
						}
					}
				}elseif($_POST['action'] == 4){
					$this->data->saveDiaExtra($_POST,$_GET);
				}
			}else{
				$per = $this->data->person();
				foreach ($per as $key => $value) {
					$per[$key]['dias'] = $this->data->dias_libres($value['cod_per']); 
				}
				return render_to_response(vista::pageWhite('marcarVaca.html',$per,'Vacaciones'));
			}
			
		}
		public function diaRegalo(){
			echo "Juan";
		}
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