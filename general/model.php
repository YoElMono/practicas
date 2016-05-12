<?php
	class general extends datebase{
		function __construct(){
			parent::__construct();
		}
		public function log($user){
			$query = $this->consulta("SELECT * FROM user_mant WHERE (email_user = '$user' OR code_user = '$user') AND delete_user != '1'");
			$sea= $this->fetch_array($query);
				return $sea;
		}
		public function ip($ip4){
			$query = $this->consulta("SELECT * FROM ip_mant
										INNER JOIN areas_mant ON area_ip = id_area
										INNER JOIN user_mant ON user_ip = id_user
									 WHERE ip4_ip = '$ip4'");
			$sea= $this->fetch_array($query);
				return $sea;
		}
		public function ips(){
			$query = $this->consulta("SELECT * FROM ip_mant
										INNER JOIN areas_mant ON area_ip = id_area
										INNER JOIN user_mant ON user_ip = id_user
									ORDER BY ip4_ip ASC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;			
				}
				return $data;
			}else{	
				return '';
			}
		}
		public function saveIp($date){
			$query = $this->consulta("INSERT INTO ip_mant (ip1_ip, ip2_ip, ip3_ip, ip4_ip, mac_ip, namePc_ip, area_ip, user_ip, rosetta_ip, sw_ip, puerto_ip) 
				VALUES ('148', '202', '144', '$date[ip4S]', '$date[macD]', '$date[namePC]', '$date[areaIp]', '$date[userIp]', '$date[rosetta]', '$date[swIp]', '$date[puertoIp]');");
		}
		public function editIp($date){
			$query = $this->consulta("UPDATE ip_mant SET mac_ip = '$date[macD]', namePc_ip = '$date[namePC]', area_ip = '$date[areaIp]', user_ip = '$date[userIp]', rosetta_ip = '$date[rosetta]', sw_ip = '$date[swIp]', puerto_ip = '$date[puertoIp]' WHERE ip4_ip = '$date[ip4E]'");
		}
		public function us(){
			$query = $this->consulta("SELECT id_user, name_user FROM user_mant ");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;			
				}
				return $data;
			}else{	
				return '';
			}
		}
		public function ar(){
			$query = $this->consulta("SELECT * FROM areas_mant ");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function lisDe(){
			$query = $this->consulta("SELECT id_depe, nombre_depe FROM depe_mant WHERE id_depe != '0'");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function dpUni($id){
			$query = $this->consulta("SELECT * FROM depe_mant WHERE id_depe = '$id' ");
			$sea= $this->fetch_array($query);
				return $sea;
		}
		public function dpF($id){
			$query = $this->consulta("SELECT * FROM ffa_mant WHERE depe_ffa = '$id' ");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function busffa($id){
			$query = $this->consulta("SELECT foto_ffa FROM ffa_mant WHERE id_ffa = '$id'");
			$sea = $this->fetch_array($query);
			return $sea;
		}
		public function delffa($id){
			$this->consulta("DELETE FROM ffa_mant WHERE id_ffa = '$id'");
		}
		public function depSav($depe){
			$query = $this->consulta("INSERT INTO depe_mant (nombre_depe, ubi_depe, resp_depe, conta_depe, tel_depe, ext_depe, abr_depe/*, ffa_depe*/) 
				VALUES ('$depe[name]','$depe[ubi]', '$depe[res]', '$depe[con]', '$depe[tel]', '$depe[ex]', '$depe[abr]'/*, '$ffa'*/);");
		}
		public function depEdi($depe){
			$query = $this->consulta("UPDATE depe_mant SET nombre_depe = '$depe[name]', ubi_depe = '$depe[ubi]', resp_depe = '$depe[res]', conta_depe = '$depe[con]', tel_depe = '$depe[tel]', ext_depe = '$depe[ex]', abr_depe = '$depe[abr]'/*, ffa_depe = '$ffa'*/ WHERE id_depe = '$_GET[depeName]'");
		}
		public function depSel(){
			$query = $this->consulta("SELECT id_depe, nombre_depe FROM depe_mant ");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function saveEn($ent,$sis){
			$query = $this->consulta("INSERT INTO entradas_mant (tipo_entrada, nombre_entrada, nfac_entrada, depen_entrada, des_entrada, inicioPer_entrada, finPer_entrada, serie_entrada, codigo_entrada, foto_entrada, foto2_entrada, foto3_entrada, aut_entrada, fecha_entrada, hora_entrada, estado_entrada, nombreEn_entrada, iden_entrada, cre_entrada) 
												VALUES ('$ent[options]', '$ent[nombre]', '$ent[no_factura]', '$ent[depen]', '$ent[des]', '$ent[perIni]', '$ent[perFin]', '$ent[no_serie]', '$ent[codeFecha]', '$sis[foto1]', '$sis[foto2]', '$sis[foto3]', '$sis[user]', '$sis[fecha]', '$sis[hora]', '$ent[status]', '$ent[nombreEn]', '$ent[ide]', '$ent[credencial]');");
		}
		public function ent($id){
			$query = $this->consulta("SELECT * FROM entradas_mant
									INNER JOIN depe_mant ON depen_entrada = id_depe
									INNER JOIN  user_mant ON aut_entrada = id_user
									WHERE id_entrada = '$id' OR codigo_entrada = '$id'
									ORDER BY id_entrada DESC");
			$sea= $this->fetch_array($query);
				return $sea;
		}
		public function entradas(){
			$query = $this->consulta("SELECT nombre_entrada FROM entradas_mant");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function ent2($fec){
			$query = $this->consulta("SELECT * FROM entradas_mant
									INNER JOIN depe_mant ON depen_entrada = id_depe
									INNER JOIN  user_mant ON aut_entrada = id_user 
									WHERE fecha_entrada = '$fec'
									ORDER BY id_entrada DESC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function bajasEn($arr){
			$this->consulta("UPDATE entradas_mant SET estado_entrada = 'Baja', autb_entrada = '$arr[user]', fechab_entrada = '$arr[fecha]', horab_entrada = '$arr[hora]' WHERE codigo_entrada = '$arr[id]'");
		}
		public function usSel(){
			$query = $this->consulta("SELECT id_user, name_user FROM user_mant ");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function busentra($ar){
			$query = $this->consulta("SELECT id_entrada, tipo_entrada, nombre_entrada, nombre_depe, name_user, fecha_entrada, hora_entrada FROM entradas_mant
									INNER JOIN depe_mant ON depen_entrada = id_depe
									INNER JOIN  user_mant ON aut_entrada = id_user
									WHERE aut_entrada = '$ar[user]' or depen_entrada = '$ar[dp]' or fecha_entrada = '$ar[fec]' or nombre_entrada = '$ar[nom]'
									ORDER BY id_entrada DESC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function busali($arr){
			$query = $this->consulta("SELECT * FROM salidas_mant
									INNER JOIN depe_mant ON depenEla_sali = id_depe
									INNER JOIN user_mant ON user_sali = id_user
									INNER JOIN ffa_mant ON ffa_sali = id_ffa
									WHERE depen_sali = '$arr[dp]' or pro_sali = '$arr[pro]' or fecha_sali = '$arr[fec]'
									ORDER BY id_sali DESC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function regisUs($newUs){
			$query = $this->consulta("INSERT INTO user_mant (code_user, email_user, name_user, area_user, depen_user, nivel_user, pw_user)
													VALUES ('$newUs[cod]','$newUs[correo]','$newUs[nom]','$newUs[area]','$newUs[dp]','$newUs[nl]','$newUs[pw]');");
		}
		public function arSel(){
			$query = $this->consulta("SELECT id_area, name_area FROM areas_mant ");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function codsSali(){
			$query = $this->consulta("SELECT cod_sali FROM salidas_mant");
			if($this->numero_de_filas($query)){
				while($datos = $this->fetch_assoc($query)){
					$info[] = $datos;
				}
				return $info;
			}else{
				return '';
			}
		}
		public function salPro($sal, $arr){
			$query = $this->consulta("INSERT INTO salidas_mant (status_sali, tipo_sali, pro_sali, obser_sali, ffa_sali, fp_sali, nfac_sali, iniPer_sali, finPer_sali, cont_sali, tel_sali, user_sali, fecha_sali, hora_sali, depen_sali, cod_sali, elab_sali, depenEla_sali)
												VALUES ( '$sal[status]', '$sal[options]', '$sal[prod]', '$sal[des]', '$sal[ffaoptions]', '$arr[foto2]', '$sal[fac]', '$sal[perIni]', '$sal[perFin]', '$sal[con]', '$sal[tel]', '$_SESSION[id_user]', '$arr[fecha]', '$arr[hora]', '$_SESSION[depen_user]', '$sal[codeFecha]', '$sal[elab]', '$sal[depen]');");
		}
		public function autSal($sal){
			$query = $this->consulta("UPDATE salidas_mant SET estado_sali = '$sal[status]', aut_sali = '$sal[autSal]' WHERE id_sali = '$sal[id]';");
		}
		public function altasSal($arr){
			$query = $this->consulta("UPDATE salidas_mant SET status_sali = 'Alta', autA_sali = '$arr[user]', fechaA_sali = '$arr[fecha]', horaA_sali = '$arr[hora]' WHERE cod_sali = '$arr[id]'");
		}
		public function dpSal($id){
			$query = $this->consulta("SELECT id_depe, nombre_depe, ffa_depe FROM depe_mant WHERE id_depe = '$id'");
			$sea= $this->fetch_array($query);
				return $sea;
		}
		public function sal($id){
			$query = $this->consulta("SELECT * FROM salidas_mant
									INNER JOIN depe_mant ON depenEla_sali = id_depe
									INNER JOIN user_mant ON user_sali = id_user
									INNER JOIN ffa_mant ON ffa_sali = id_ffa
									WHERE id_sali = '$id' or cod_sali = '$id'");
			$sea= $this->fetch_array($query);
				return $sea;
		}
		public function repoSali(){
			$query = $this->consulta("SELECT id_sali, pro_sali, fecha_sali, hora_sali, tipo_sali, nombre_depe, name_user, /*nombre_ffa, foto_ffa,*/ estado_sali FROM salidas_mant
									INNER JOIN depe_mant ON depenEla_sali = id_depe
									INNER JOIN user_mant ON user_sali = id_user
									INNER JOIN ffa_mant ON ffa_sali = id_ffa
									ORDER BY id_sali DESC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function saliDH(){
			$query = $this->consulta("SELECT * FROM salidas_mant
									INNER JOIN depe_mant ON depenEla_sali = id_depe
									INNER JOIN user_mant ON user_sali = id_user
									INNER JOIN ffa_mant ON ffa_sali = id_ffa
									WHERE fecha_sali = '".date('d-m-y')."'
									ORDER BY hora_sali DESC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function ffasSave($ffa,$arr){
			$query = $this->consulta("INSERT INTO ffa_mant (nombre_ffa, foto_ffa, depe_ffa)
												VALUES ('$ffa[nom]', '$arr[foto]', '$ffa[dp]');");
		}
		public function ffaDep(){
			$query = $this->consulta("SELECT * FROM ffa_mant ");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function nffa($id){
			$query = $this->consulta("SELECT * FROM ffa_mant WHERE id_ffa = '$id'");
			$sea= $this->fetch_array($query);
				return $sea;
		}
		public function solSer($ser){
			if($ser['numero'] == ''||$ser['numero']==null) $ser['numero'] = '0';
			$query = $this->consulta("INSERT INTO servicios_mant (user_ser, tipo_ser, cod_ser, des_ser, fecha_ser, hora_ser, dep_ser, estado_ser, contacto_ser, extension_ser, oficio_ser)
												VALUES ('$ser[nCap]', '$ser[options]', '$ser[cod]', '$ser[des]', '$ser[fecha]', '$ser[hora]', '$ser[dp]', '1', '$ser[cont]', '$ser[ext]','$ser[numero]');");
		}
		public function perSave($per,$hor,$horD,$horL,$horMa,$horMi,$horJ,$horV,$horS){
			//print_r($hor);
			$this->consulta("INSERT INTO personal_mant (iniPerC_per, finPerC_per,horEn_per, horSal_per, nombre_per, cod_per, nombra_per, area_per, tc_per, ch_per, turno_per,checkD_per, horED_per, horSD_per, checkL_per, horEL_per, horSL_per, checkMa_per, horEMa_per, horSMa_per, checkMi_per, horEMi_per, horSMi_per, checkJ_per, horEJ_per, horSJ_per, checkV_per, horEV_per, horSV_per, checkS_per, horES_per, horSS_per, calle_per, col_per, cp_per, mun_per, telcas_per, telcel_per, fecN_per, rfc_per, curp_per, nss_per, foto_per, diaE_per, email_per, status_per, licencia_per, iniPer_per, finPer_per)
									VALUES ('$per[perInicio1]', '$per[perFin1]', '$hor[0]', '$hor[1]', '$per[nom]', '$per[cod]', '$per[nombra]', '$per[area]', '$per[tc]', '$per[carHor]', '$per[turno]', '$horD[0]', '$horD[1]', '$horD[2]','$horL[0]','$horL[1]','$horL[2]', '$horMa[0]', '$horMa[1]', '$horMa[2]', '$horMi[0]', '$horMi[1]', '$horMi[2]', '$horJ[0]', '$horJ[1]', '$horJ[2]', '$horV[0]', '$horV[1]', '$horV[2]', '$horS[0]', '$horS[1]', '$horS[2]', '$per[calle]', '$per[colonia]', '$per[CP]', '$per[Mun]', '$per[telCas]', '$per[telCel]', '$per[fecN]', '$per[rfc]', '$per[curp]', '$per[nss]', '$per[foto]', '$per[diaE]', '$per[email]', '$per[carga]', '$per[licencia]', '$per[perInicio]', '$per[perFin]')");
		}
		public function updatePer($dat,$hor,$horD,$horL,$horMa,$horMi,$horJ,$horV,$horS,$id){
			$this->consulta("UPDATE personal_mant SET  iniPerC_per = '$dat[perInicio1]', finPerC_per = '$dat[perFin1]', horEn_per = '$hor[0]', horSal_per = '$hor[1]', nombre_per = '$dat[nom]', cod_per = '$dat[cod]', nombra_per = '$dat[nombra]', area_per = '$dat[area]', tc_per = '$dat[tc]', ch_per = '$dat[carHor]', turno_per = '$dat[turno]', checkD_per = '$horD[0]', horED_per = '$horD[1]', horSD_per = '$horD[2]', checkL_per = '$horL[0]', horEL_per = '$horL[1]', horSL_per = '$horL[2]', checkMa_per = '$horMa[0]', horEMa_per = '$horMa[1]', horSMa_per = '$horMa[2]', checkMi_per = '$horMi[0]', horEMi_per = '$horMi[1]', horSMi_per = '$horMi[2]', checkJ_per = '$horJ[0]', horEJ_per = '$horJ[1]', horSJ_per = '$horJ[2]', checkV_per = '$horV[0]', horEV_per = '$horV[1]', horSV_per = '$horV[2]', checkS_per = '$horS[0]', horES_per = '$horS[1]', horSS_per = '$horS[2]', calle_per = '$dat[calle]', col_per = '$dat[colonia]', cp_per = '$dat[CP]', mun_per = '$dat[Mun]', telcas_per = '$dat[telCas]', telcel_per = '$dat[telCel]', fecN_per = '$dat[fecN]', rfc_per = '$dat[rfc]', curp_per = '$dat[curp]', nss_per = '$dat[nss]', diaE_per = '$dat[diaE]', email_per = '$dat[email]', foto_per ='$dat[foto]', status_per = '$dat[carga]', licencia_per = '$dat[licencia]', iniPer_per = '$dat[perInicio]', finPer_per = '$dat[perFin]' WHERE id_per = '$id'");
		}
		public function deletePer($id){
			//$this->consulta("DELETE FROM personal_mant WHERE id_per = '$id' ");
			$this->consulta("UPDATE personal_mant SET status_per = 4 WHERE id_per = '$id' ");
		}
		public function conPer(){
			$query = $this->consulta("SELECT nombre_per, cod_per, name_area, nombra_per, tc_per, turno_per, horEn_per, horSal_per FROM personal_mant
									INNER JOIN areas_mant ON area_per = id_area");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function personal(){
			$query = $this->consulta("SELECT * FROM personal_mant
									INNER JOIN areas_mant ON area_per = id_area
									WHERE status_per in(0,1) and tc_per != 'SS' 
									ORDER BY turno_per,nombre_per ASC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function personalSS(){
			$query = $this->consulta("SELECT * FROM pss_mant WHERE status_pss != 'Inactivo' ORDER BY turno_pss,nombre_pss ASC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function peresp($id){
			$query = $this->consulta("SELECT * FROM personal_mant
									INNER JOIN areas_mant ON area_per = id_area
									WHERE cod_per = '$id' and (status_per = 1 or status_per = 0) and tc_per != 'SS'");
			/*echo "SELECT * FROM personal_mant
									INNER JOIN areas_mant ON area_per = id_area
									WHERE cod_per = '$id' and status_per = 1 and tc_per != 'SS'";
									exit();*/
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function perespSS($id){
			$query = $this->consulta("SELECT * FROM pss_mant WHERE codigo_pss = '$id' and status_pss != 'Inactivo'");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function horarios($hor){
			$fecha = $hor['anio'].'-'.((strlen($hor['mes'])<2)?"0".$hor['mes']:$hor['mes']).'-'.((strlen($hor['dia'])<2)?"0".$hor['dia']:$hor['dia']);
			$fecha .= " ".(strlen($hor['hor'])<5?"0".$hor['hor']:$hor['hor']).":00";
			$this->consulta("INSERT INTO check_mant (codigo_check, dia_check, mes_check, anio_check, semana_check, tipo_check, horcap_check, fechcon_check,fecha_check)
								VALUES ('$hor[cod]','$hor[dia]','$hor[mes]','$hor[anio]','$hor[semana]','$hor[tipo]','$hor[hor]','$hor[fec]','$fecha')");
		}
		public function permat(){
			$query = $this->consulta("SELECT nombre_per, cod_per, name_area, nombra_per, tc_per, turno_per, horEn_per, horSal_per FROM personal_mant
									INNER JOIN areas_mant ON area_per = id_area
									WHERE turno_per = 1 and status_per = 1 and tc_per != 'SS'
									ORDER BY nombre_per ASC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function permat1(){
			$query = $this->consulta("SELECT nombre_per, cod_per, name_area, nombra_per, tc_per, turno_per, horEn_per, horSal_per FROM personal_mant
									INNER JOIN areas_mant ON area_per = id_area
									WHERE turno_per = 1
									ORDER BY nombre_per ASC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function perves(){
			$query = $this->consulta("SELECT nombre_per, cod_per, name_area, nombra_per, tc_per, turno_per, horEn_per, horSal_per FROM personal_mant
									INNER JOIN areas_mant ON area_per = id_area
									WHERE turno_per = 2 and status_per = 1 and tc_per != 'SS'
									ORDER BY nombre_per ASC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function perves1(){
			$query = $this->consulta("SELECT nombre_per, cod_per, name_area, nombra_per, tc_per, turno_per, horEn_per, horSal_per FROM personal_mant
									INNER JOIN areas_mant ON area_per = id_area
									WHERE turno_per = 2
									ORDER BY nombre_per ASC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function permix(){
			$query = $this->consulta("SELECT nombre_per, cod_per, name_area, nombra_per, tc_per, turno_per, horEn_per, horSal_per FROM personal_mant
									INNER JOIN areas_mant ON area_per = id_area
									WHERE turno_per = 3 and status_per = 1 and tc_per != 'SS'
									ORDER BY nombre_per ASC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function permix1(){
			$query = $this->consulta("SELECT nombre_per, cod_per, name_area, nombra_per, tc_per, turno_per, horEn_per, horSal_per FROM personal_mant
									INNER JOIN areas_mant ON area_per = id_area
									WHERE turno_per = 3
									ORDER BY nombre_per ASC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function pervar(){
			$query = $this->consulta("SELECT nombre_per, cod_per, name_area, nombra_per, tc_per, turno_per, horEn_per, horSal_per FROM personal_mant
									INNER JOIN areas_mant ON area_per = id_area
									WHERE turno_per = 4 and status_per = 1 and tc_per != 'SS'
									ORDER BY nombre_per ASC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function pervar1(){
			$query = $this->consulta("SELECT nombre_per, cod_per, name_area, nombra_per, tc_per, turno_per, horEn_per, horSal_per FROM personal_mant
									INNER JOIN areas_mant ON area_per = id_area
									WHERE turno_per = 4
									ORDER BY nombre_per ASC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function conPert(){
			$query = $this->consulta("SELECT nombre_per, name_area FROM personal_mant
									INNER JOIN areas_mant ON area_per = id_area");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function users(){
			$query = $this->consulta("SELECT * FROM  user_mant
									INNER JOIN depe_mant ON depen_user = id_depe
									INNER JOIN areas_mant ON area_user = id_area
									WHERE delete_user != '1'");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function user($id){
			$query = $this->consulta("SELECT * FROM user_mant
									INNER JOIN depe_mant ON depen_user = id_depe
									INNER JOIN areas_mant ON area_user = id_area
									WHERE id_user = '$id'");
			$sea= $this->fetch_array($query);
				return $sea;
		}
		public function actUsers($us){
			$this->consulta("UPDATE user_mant SET name_user = '$us[nom]', code_user = '$us[cod]', email_user = '$us[correo]', area_user = '$us[area]', depen_user = '$us[dp]', nivel_user = '$us[nl]', pw_user = '$us[pw]' WHERE id_user = '$us[id]';");
		}
		public function deleteUse($id){
			$this->consulta("UPDATE user_mant SET delete_user = '1' WHERE id_user = '$id'");
		}
		public function serviSeg($inici = 0){
			$query = $this->consulta("SELECT id_ser, cod_ser, estado_ser, nombre_depe, name_user,tipo_ser, asig_ser FROM servicios_mant
										INNER JOIN depe_mant ON dep_ser = id_depe
										INNER JOIN user_mant ON user_ser = id_user
										WHERE user_ser = '$_SESSION[id_user]'
										ORDER BY estado_ser, asig_ser ASC
										LIMIT $inici , 5");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function servi($dp){
			$query = $this->consulta("SELECT * FROM servicios_mant
										INNER JOIN depe_mant ON dep_ser = id_depe
										INNER JOIN user_mant ON user_ser = id_user
										WHERE asig_ser = '0'".$dp."
										ORDER BY id_ser DESC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function servif($dp){
			$query = $this->consulta("SELECT * FROM servicios_mant
										INNER JOIN depe_mant ON dep_ser = id_depe
										INNER JOIN user_mant ON user_ser = id_user
										WHERE estado_ser = '2'".$dp."
										ORDER BY id_ser DESC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function servif2($dp){
			$query = $this->consulta("SELECT * FROM servicios_mant
										INNER JOIN depe_mant ON dep_ser = id_depe
										INNER JOIN user_mant ON user_ser = id_user
										WHERE estado_ser = '3'".$dp."
										ORDER BY id_ser DESC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function servi2($dp){
			$query = $this->consulta("SELECT * FROM servicios_mant
										INNER JOIN depe_mant ON dep_ser = id_depe
										INNER JOIN user_mant ON user_ser = id_user
										/*INNER JOIN personal_mant ON asig_ser = cod_per*/
										WHERE asig_ser != '0' AND estado_ser = '1'".$dp."
										ORDER BY id_ser DESC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function servic($id){
			$query = $this->consulta("SELECT * FROM servicios_mant
									INNER JOIN depe_mant ON dep_ser = id_depe
									INNER JOIN user_mant ON user_ser = id_user
									INNER JOIN oficios_mant ON oficio_ser = no_ofi
									WHERE id_ser = '$id'");
			$sea= $this->fetch_array($query);
				return $sea;
		}
		public function elimServ($id){
			$this->consulta("DELETE FROM servicios_mant WHERE id_ser = '$id'");
			return 'Hecho :)';
		}
		public function selecper($ar){
			$query = $this->consulta("SELECT cod_per, nombre_per,nombra_per FROM personal_mant WHERE area_per = '$ar[0]' OR area_per = '$ar[1]'");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function busSerAsig($id){
			$query = $this->consulta("SELECT * FROM servicios_mant
										INNER JOIN depe_mant ON dep_ser = id_depe
										INNER JOIN user_mant ON user_ser = id_user
										INNER JOIN personal_mant ON asig_ser = cod_per
										INNER JOIN oficios_mant ON oficio_ser = no_ofi
										WHERE id_ser = '$id'");
			$sea = $this->fetch_array($query);
			return $sea;
		}
		public function asigPer($dat){
			$this->consulta("UPDATE servicios_mant SET asig_ser = '$dat[asig]' WHERE id_ser = '$dat[ser]'");
		}
		public function finSer($dat){
			$this->consulta("UPDATE servicios_mant SET estado_ser = '$dat[btn]' WHERE id_ser = '$dat[id]'");
		}
		public function actestser($dat){
			$this->consulta("UPDATE servicios_mant SET des2_ser = '$dat[des]', estado_ser = '$dat[btn]' WHERE id_ser = '$dat[id]'");
		}
		public function listper(){
			$query = $this->consulta("SELECT id_per, nombre_per, cod_per FROM personal_mant where status_per in (0,1) ORDER BY nombre_per ASC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function per($id){
			$query = $this->consulta("SELECT * FROM personal_mant
									WHERE id_per = '$id'");
			$sea= $this->fetch_array($query);
				return $sea;
		}
		public function dirSave($dir){
			$query = $this->consulta("INSERT INTO directorio_mant (pes_dir, nom_dir, calle_dir, no_dir, col_dir, tel_dir, con_dir, img_dir, ex_dir, tipo_dir, conm_dir, exesc_dir, piso_dir, depa_dir, ubi_dir, web_dir) 
				VALUES ('$dir[options]','$dir[nombre]', '$dir[calle]', '$dir[num]', '$dir[col]', '$dir[tel]', '$dir[con]', '$dir[img]','$dir[ext]', '$dir[tcu]', '$dir[conm]', '$dir[extesc]', '$dir[piso]', '$dir[dep]', '$dir[ubi]', '$dir[web]');");
		}
		public function dirEdit($id){
			$this->consulta("UPDATE directorio_mant SET nom_dir = '$id[nombre]', calle_dir = '$id[calle]', no_dir = '$id[num]', col_dir = '$id[col]', tel_dir = '$id[tel]', con_dir = '$id[con]', img_dir = '$id[img]', ex_dir = '$id[ext]', tipo_dir = '$id[tcu]', conm_dir = '$id[conm]', exesc_dir = '$id[extesc]', piso_dir = '$id[piso]', depa_dir = '$id[dep]', ubi_dir = '$id[ubi]', web_dir = '$id[web]' WHERE id_dir = '$id[id]'");
		}
		public function delDir($id){
			$this->consulta("DELETE FROM directorio_mant WHERE id_dir = '$id' ");
		}
		public function mdirs($tip){
			if($tip != "3" && $tip != "5")
				$ord = 'nom_dir';
			else
				$ord = 'piso_dir';
			//echo "SELECT `id_dir`, `nom_dir`, `tel_dir`, `ex_dir`, `depa_dir`, `piso_dir` FROM `directorio_mant` WHERE `pes_dir` = '".$tip."' ORDER BY `".$ord."` ASC ";exit();
			$query = $this->consulta("SELECT id_dir, nom_dir, tel_dir, ex_dir, depa_dir, piso_dir FROM directorio_mant WHERE pes_dir = '$tip' ORDER BY `".$ord."` ASC ");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function dir($id){
			$query = $this->consulta("SELECT * FROM directorio_mant WHERE id_dir = '$id'");
			$sea= $this->fetch_array($query);
				return $sea;
		}
		public function asigpdf($id){
			$query = $this->consulta("SELECT nombra_per, nombre_per FROM personal_mant WHERE cod_per = '$id'");
			$sea= $this->fetch_array($query);
				return $sea;
		}
		public function editPass($pass,$id){
			$this->consulta("UPDATE user_mant SET pw_user = '$pass[pw]' WHERE id_user = '$id'");
		}
		public function conHor($fec,$nom = ''){
			$a = '';
			if($nom)
				$a = " and codigo_check = '$nom'";
			$query = $this->consulta("SELECT * FROM check_mant
									INNER JOIN personal_mant ON codigo_check = cod_per
									WHERE verifica_check = 0 AND fecha_check < '$fec'".$a." and status_per in (0,1)
									ORDER BY turno_per, nombre_per ASC 
									LIMIT 0, 25");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function saveche($id,$veri,$hor,$nota){
			$this->consulta("UPDATE check_mant SET verifica_check = '$veri', hor_check = '$hor', notas_check = '$nota' WHERE id_check = '$id'");
		}
		public function faltantes($a){
			$query = $this->consulta("SELECT id_check, codigo_check, tipo_check, nombre_per, horcap_check, hor_check,verifica_check,notas_check FROM check_mant
									INNER JOIN personal_mant ON codigo_check = cod_per
									WHERE dia_check = '$a[dia]' AND mes_check = '$a[mes]' AND anio_check = '$a[anio]' and status_per in (0,1)
									ORDER BY nombre_per,tipo_check ASC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}

		public function faltantesSS($a){
			$query = $this->consulta("SELECT id_cpss, codigo_cpss, tipo_cpss, nombre_pss, horaCap_cpss, hora_cpss, verifica_cpss FROM checkPss_mant
									INNER JOIN pss_mant ON codigo_cpss = codigo_pss
									WHERE dia_cpss = '$a[dia]' AND mes_cpss = '$a[mes]' AND anio_cpss = '$a[anio]' and status_pss = 'Activo'
									ORDER BY nombre_pss,tipo_cpss ASC");
			
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}

		public function notrabaja($dia){
			$query = $this->consulta("SELECT * FROM personal_mant WHERE $dia = 0 
										ORDER BY turno_per,nombre_per ASC");
			if($this->numero_de_filas($query) > 0){
				while($datos = $this->fetch_assoc($query))
					$data[] = $datos;
				return $data;
			}else
				return '';
		}

		public function notrabajaSS($dia){
			$query = $this->consulta("SELECT * FROM pss_mant WHERE $dia = 0 and status_pss = 'Activo'
										ORDER BY turno_pss,nombre_pss ASC");
			if($this->numero_de_filas($query) > 0){
				while($datos = $this->fetch_assoc($query))
					$data[] = $datos;
				return $data;
			}else
				return '';
		}

		public function jusf($id, $nota, $hor, $ver){
			if(!$ver)
				$this->consulta("UPDATE check_mant SET verifica_check = '2',notas_check='$nota',hor_check='$hor' WHERE id_check = '$id'");
			else
				$this->consulta("UPDATE check_mant SET hor_check='$hor', verifica_check = '$nota' WHERE id_check = '$id'");

		}

		public function jusfSS($id, $nota, $hor, $ver){
			if(!$ver)
				$this->consulta("UPDATE checkPss_mant SET verifica_cpss = '2',notas_cpss='$nota',horaCap_cpss='$hor' WHERE id_cpss = '$id'");
			else
				$this->consulta("UPDATE checkPss_mant SET horaCap_cpss='$hor', verifica_cpss = '$nota' WHERE id_cpss = '$id'");

		}

		public function unicheck($id){
			$query = $this->consulta("SELECT * FROM check_mant WHERE id_check = '$id'");
			$sea= $this->fetch_array($query);
				return $sea;
		}

		public function unicheckSS($id){
			$query = $this->consulta("SELECT * FROM checkPss_mant WHERE id_cpss = '$id'");
			$sea= $this->fetch_array($query);
				return $sea;
		}

		public function repDia($a){
			$query = $this->consulta("SELECT id_check, codigo_check, tipo_check, nombre_per, hor_check, notas_check, turno_per,status_per FROM check_mant
									INNER JOIN personal_mant ON codigo_check = cod_per
									WHERE status_per in(0,1) and dia_check = '$a[dia]' AND mes_check = '$a[mes]' AND anio_check = '$a[anio]'".$a['fal']."
									ORDER BY turno_per,nombre_per ");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function repSema($a){
			$query = $this->consulta("SELECT id_check, codigo_check, tipo_check, nombre_per, hor_check, ch_per, turno_per,status_per FROM check_mant
									INNER JOIN personal_mant ON codigo_check = cod_per
									WHERE status_per in(0,1) and semana_check = '$a[sem]' AND anio_check = '$a[anio]'
									ORDER BY turno_per, nombre_per,dia_check,tipo_check");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function repSemaPSS($a){
			$query = $this->consulta("SELECT id_cpss, codigo_cpss, tipo_cpss, nombre_pss, horaCap_cpss, cargaHoraria_pss, turno_pss,status_pss FROM checkPss_mant
									INNER JOIN pss_mant ON codigo_cpss = codigo_pss
									WHERE status_pss = 'Activo' and semana_cpss = '$a[sem]' AND anio_cpss = '$a[anio]'
									ORDER BY turno_pss, nombre_pss,dia_cpss,tipo_cpss");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function repMes($a,$b){
			$query = $this->consulta("SELECT turno_per, id_check, codigo_check, tipo_check, dia_check, hor_check,notas_check, nombre_per,verifica_check,fechcon_check,status_per FROM check_mant
									INNER JOIN personal_mant ON codigo_check = cod_per
									WHERE status_per in(0,1) and mes_check = '$a[mes]' AND anio_check = '$a[anio]'".$a['fal'].(($a['anio']<date('Y'))?"":" AND fechcon_check <= '$b'").
									" ORDER BY turno_per, nombre_per,dia_check,tipo_check ");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}

		public function repEsp($a){
			$query = $this->consulta("SELECT turno_per, id_check, codigo_check, tipo_check, dia_check, mes_check, anio_check, hor_check,notas_check, nombre_per,verifica_check,fechcon_check FROM check_mant
									INNER JOIN personal_mant ON codigo_check = cod_per
									WHERE codigo_check = '$a[id]' AND (fecha_check BETWEEN '$a[a]' AND '$a[b]') ORDER BY fecha_check,dia_check,tipo_check ");
			
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}

		public function repMesPSS($a){
			$a['mes'] = str_pad($a['mes'], 2, '0', STR_PAD_LEFT);
			$query = $this->consulta("SELECT turno_pss, id_cpss, codigo_cpss, tipo_cpss, dia_cpss, horaCap_cpss,notas_cpss, status_pss, nombre_pss,verifica_cpss,fechaCon_cpss FROM checkPss_mant
									INNER JOIN pss_mant ON codigo_cpss = codigo_pss
									WHERE status_pss = 'Activo' and mes_cpss = '$a[mes]' AND anio_cpss = '$a[anio]' AND fechaCon_cpss <= '".date('Y-m-d H:i:s')."'
									ORDER BY turno_pss, nombre_pss,dia_cpss,tipo_cpss ");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function deleDepe($id){
			$this->consulta("DELETE FROM depe_mant WHERE id_depe = '$id' ");
		}
		public function selDep($id){
			$query = $this->consulta("SELECT nombre_depe/*,abr_depe*/ FROM depe_mant
										WHERE id_depe = '$id'");
			$sea= $this->fetch_array($query);
				return $sea;
		}
		public function empleados($mes){
			$query = $this->consulta("SELECT cod_per, tipo_check, verifica_check, dia_check, nombre_per, name_area FROM check_mant
										INNER JOIN personal_mant ON codigo_check = cod_per
										INNER JOIN areas_mant ON area_per = id_area 
										WHERE diaE_per = '1' AND mes_check = '$mes'
										ORDER BY nombre_per ASC");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function person(){
			$query = $this->consulta("SELECT nombre_per, cod_per FROM personal_mant ORDER BY nombre_per ASC");
			if($this->numero_de_filas($query) > 0){
				while ($tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}

		public function dias_libres($cod){
			$query = $this->consulta("SELECT id_diar,motivo_diar, mes2_diar, anio2_diar FROM diar_mant
										WHERE /*tipo_diar = '1' and*/ codigo_diar = '$cod' and check_diar = '0';");
			if($this->numero_de_filas($query) > 0){
				while ($tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function saveDiaExtra($data,$date){
			$this->consulta("INSERT INTO diar_mant (motivo_diar,tipo_diar,dia_diar,mes_diar,anio_diar,codigo_diar)
							VALUES('$data[motivo]','2','$date[dia]','$date[mes]','$date[anio]','$data[persona]')");
		}
		public function saveofi($datos){
			$this->consulta("INSERT INTO oficios_mant (dep_ofi,no_ofi,des_ofi,archivo_ofi,fecha_ofi,hora_ofi,userCap_ofi,con_ofi,nomCon_ofi,idUser_ofi)
							VALUES ('$datos[depen]','$datos[numero]','$datos[des]','$datos[foto]','$datos[fecha]','$datos[hora]','$datos[usu]','$datos[con]','$datos[nom_con]','$datos[idusu]')");
		}
		public function oficios(){
			$query = $this->consulta('SELECT * FROM oficios_mant ORDER BY id_ofi DESC');
			if($this->numero_de_filas($query)>0){
				while($datos = $this->fetch_assoc($query))
					$data[] = $datos;
				return $data;
			}else
				return '';
		}
		public function actOfi($value){
			$this->consulta("UPDATE oficios_mant SET des_ofi = '$value[des]', foto_ofi = '$value[foto]', dep_ofi = '$value[dp]', no_ofi = '$value[numero]', con_ofi = '$value[con]', nomCon_ofi = '$value[nom_con]' WHERE id_ofi = '$value[edit]' ");
		}
		public function eliminarEventosID($id){
			$this->consulta("DELETE FROM eventos_mant WHERE num_ofi_eve = '$id'");
		}
		public function verOfiHoy()	{
			$query = $this->consulta("SELECT * FROM oficios_mant WHERE fecha_ofi = '".date('Y-m-d')."' ORDER BY id_ofi DESC");
			if($this->numero_de_filas($query)>0){
				while($datos = $this->fetch_assoc($query))
					$data[] = $datos;
				return $data;
			}else
				return '';
		}
		public function busofi($arr){
			$sql = 'SELECT * FROM oficios_mant WHERE(';
			foreach ($arr as $key => $value) {
				if($arr[$key]){
					switch ($key) {
						case 'fec':
							$sql.=" fecha_ofi = '$arr[fec]' or";
							break;
						case 'dp':
							$sql.=" dep_ofi = '$arr[dp]' or";
							break;
						case 'pro':
							$sql.=" userCap_ofi = '$arr[pro]' or";
							break;
						case 'num':
							$sql.=" no_ofi LIKE '%$arr[num]%' or";
							break;
						case 'con':
							$sql.=" con_ofi = '$arr[con]' or";
							break;
						case 'cl':
							$sql.=" des_ofi LIKE '%$arr[cl]%' or";
							break;
						case 'rango':
							$sql.=" fecha_ofi BETWEEN '$arr[inicio]' AND '$arr[fin]' or";
							break;
					}
				}
			}
			$fin = substr($sql, -2);
			if($fin == 'or'){
				$sql = substr($sql,0,-2);
			}
			if($arr['dp'] != 0 and $arr['num'] != '') $sql ="SELECT * FROM oficios_mant WHERE (dep_ofi = '$arr[dp]' AND no_ofi LIKE '%$arr[num]%') ";
			$sql.=') and no_ofi != "" ORDER BY id_ofi DESC';
			//echo $sql; exit();
			//return $sql;
			$query = $this->consulta($sql);
			if($this->numero_de_filas($query)>0){
				while($datos = $this->fetch_assoc($query))
					$data[] = $datos;
				return $data;
			}else
				return '';
		}
		public function verOficio($id){
			$query = $this->consulta("SELECT * FROM oficios_mant
							INNER JOIN depe_mant ON dep_ofi = id_depe
							INNER JOIN user_mant ON idUser_ofi = id_user
							WHERE id_ofi = '$id'");
			$sea = $this->fetch_array($query);
			return $sea;
		}
		public function borrarOficio($id){
			$this->consulta("DELETE FROM oficios_mant WHERE id_ofi = '$id'");
		}
		public function elimServOfi($oficio){
			$query = $this->consulta("SELECT * FROM servicios_mant WHERE oficio_ser = '$oficio'");
			$datos = $this->fetch_array($query);
			$this->consulta("DELETE FROM servicios_mant WHERE oficio_ser = '$oficio'");
			return $datos;
		}
		public function elimOfiSer($oficio){
			if($oficio != '0') $this->consulta("DELETE FROM oficios_mant WHERE no_ofi = '$oficio'");
		}
		public function actualizarOficios($value){
			$this->consulta("UPDATE oficios_mant SET dep_ofi = '$value[depen]', no_ofi = '$value[numero]', des_ofi = '$value[des]', foto_ofi = '$value[foto]', archivo_ofi = '$value[foto]', con_ofi = '$value[con]', nomCon_ofi = '$value[nom_con]' WHERE id_ofi = '$value[edit]'");
		}
		public function actEventoData($value){
			$this->consulta("UPDATE eventos_mant SET nombre_eve = '$value[nom_eve]', fecha_eve = '$value[fecha_eve]' , descripcion_eve = '$value[des]' , archivo_eve = '$value[foto]' , num_ofi_eve = '$value[numero]' , hora_eve = '$value[hora_eve]' , sede_eve = '$value[sede_eve]' , semana_eve = '$value[semana_eve]' WHERE num_ofi_eve = '$value[num_old]' ");
		}
		public function saveDiar($id, $dia, $mes, $anio){
			$this->consulta("UPDATE diar_mant SET dia_diar = '$dia',mes_diar='$mes',anio_diar='$anio', check_diar='2' WHERE id_diar = '$id'");
		}
		public function saveVacaciones($dia, $mes, $anio){
			$this->consulta("INSERT INTO diar_mant (dia_diar, mes_diar, anio_diar, tipo_diar, motivo_diar)
									VALUES ('$dia', '$mes', '$anio', '2', 'Vacaciones')");
		}
		public function saveDiat($dia, $mes, $anio, $hore,$hors,$cod){
			$this->consulta("INSERT INTO diar_mant (dia_diar, mes_diar, anio_diar, tipo_diar, motivo_diar,codigo_diar, mes2_diar, anio2_diar)
											VALUES ('$dia', '$mes', '$anio', '3', 'Trabaja en Vacaciones', '$cod','$hore','$hors')");
		}
		public function agregarCheck($value){
			if($value['tipo'] == 1)
				$hor = 'Entrada';
			else
				$hor = 'Salida';
			$fecha = $value['fecha'];
			$fec = (($fecha[1]<10)?substr($fecha[1], 1):$fecha[1]).(($fecha[0]<10)?"0".$fecha[0]:$fecha[0]).str_replace(':', "", $value[$hor]);
			if($value['horasSalida'] !="" or $value['horasEntrada'] != ""){ $a = ",hor_check,verifica_check";$b = ",'".$value['horas'.$hor]."',1";}else{$a=$b="";}
			$fecha2 = $fecha[2]."-".(strlen($fecha[1])<2?"0".$fecha[1]:$fecha[1])."-".(strlen($fecha[0])<2?"0".$fecha[0]:$fecha[0]);
			$fecha2 .= " ".(strlen($value[$hor])<5?"0".$value[$hor]:$value[$hor]).":00";
			$this->consulta("INSERT INTO check_mant (codigo_check, dia_check, mes_check, anio_check, semana_check, tipo_check, fechcon_check, horcap_check, fecha_check, notas_check$a)
											VALUES('$value[codigo]','$fecha[0]','$fecha[1]','$fecha[2]','$value[semana]','$value[tipo]','$fec','$value[$hor]','$fecha2','$value[nota]'$b)");
		}

		public function agregarCheckSS($value){
			if($value['tipo'] == 1)
				$hor = 'Entrada';
			else
				$hor = 'Salida';
			$fecha = $value['fecha'];

			if($value['horasSalida'] !="" or $value['horasEntrada'] != ""){ 
				$a = ",horaCap_cpss,verifica_cpss";
				$b = ",'".$value['horas'.$hor]."',1";
			}else{
				$a=$b="";
			}
			$fecha2 = $fecha[2]."-".(strlen($fecha[1])<2?"0".$fecha[1]:$fecha[1])."-".(strlen($fecha[0])<2?"0".$fecha[0]:$fecha[0]);
			$fecha2 .= " ".(strlen($value[$hor])<5?"0".$value[$hor]:$value[$hor]).":00";
			$value[$hor] .= ':00';
			$this->consulta("INSERT INTO checkPss_mant (idPss_cpss, codigo_cpss, dia_cpss, mes_cpss, anio_cpss, semana_cpss, tipo_cpss, hora_cpss, fechaCon_cpss, notas_cpss$a)
											VALUES('$value[id]','$value[codigo]','$fecha[0]','$fecha[1]','$fecha[2]','$value[semana]','$value[tipo]','$value[$hor]','$fecha2','$value[nota]'$b)");
		}

		public function busCheck($value){
			$fecha = $value['fecha'];
			$query = $this->consulta("SELECT * FROM check_mant WHERE dia_check = '$fecha[0]' and mes_check = '$fecha[1]' and anio_check = '$fecha[2]' and codigo_check = '$value[codigo]' ");
			if($this->numero_de_filas($query)>0){
				while($datos = $this->fetch_assoc($query))
					$data[] = $datos;
				return $data;
			}else
				return '';
		}

		public function busCheckSS($value){
			$fecha = $value['fecha'];
			$fecha[0] = str_pad($fecha[0],2,"0",STR_PAD_LEFT);
			$fecha[1] = str_pad($fecha[1],2,"0",STR_PAD_LEFT);
			$query = $this->consulta("SELECT * FROM checkPss_mant WHERE dia_cpss = '$fecha[0]' and mes_cpss = '$fecha[1]' and anio_cpss = '$fecha[2]' and codigo_cpss = '$value[codigo]' ");
			if($this->numero_de_filas($query)>0){
				while($datos = $this->fetch_assoc($query))
					$data[] = $datos;
				return $data;
			}else
				return '';
		}

		public function updateCheck($value){
			if($value['tipo'] == 1)
				$hor = 'Entrada';
			else
				$hor = 'Salida';
			$fecha = $value['fecha'];
			$fecha2 = $fecha[2]."-".(($fecha[1]<10)?substr($fecha[1], 1):$fecha[1])."-".(($fecha[0]<10)?"0".$fecha[0]:$fecha[0])." ".$value[$hor].":00";
			$fec = (($fecha[1]<10)?substr($fecha[1], 1):$fecha[1]).(($fecha[0]<10)?"0".$fecha[0]:$fecha[0]).str_replace(':', "", $value[$hor]);
			$this->consulta("UPDATE check_mant SET fechcon_check = '$fec', horcap_check = '$value[$hor]', notas_check = '$value[nota]' fecha_check = '$fecha2' WHERE dia_check = '$fecha[0]' and mes_check = '$fecha[1]' and anio_check = '$fecha[2]' and codigo_check = '$value[codigo]' and tipo_check = '$value[tipo]' ");
		}

		public function updateCheckSS($value){
			if($value['tipo'] == 1)
				$hor = 'Entrada';
			else
				$hor = 'Salida';
			$fecha = $value['fecha'];
			$fecha2 = $fecha[2]."-".(($fecha[1]<10)?substr($fecha[1], 1):$fecha[1])."-".(($fecha[0]<10)?"0".$fecha[0]:$fecha[0])." ".$value[$hor].":00";
			$this->consulta("UPDATE checkPss_mant SET fechaCon_cpss = '$fecha2', hora_cpss = '$value[$hor]', notas_cpss = '$value[nota]' WHERE dia_cpss = '$fecha[0]' and mes_cpss = '$fecha[1]' and anio_cpss = '$fecha[2]' and codigo_cpss = '$value[codigo]' and tipo_cpss = '$value[tipo]' ");
		}

		public function savePDFofi($archivo,$id){
			$this->consulta("UPDATE oficios_mant SET archivo_ofi = '$archivo' WHERE id_ofi = '$id'");
		}
		public function deleteFile($id){
			$query = $this->consulta("SELECT archivo_ofi FROM oficios_mant WHERE id_ofi = '$id' ");
			$file = $this->fetch_array($query);
			$this->consulta("UPDATE oficios_mant SET archivo_ofi = '' WHERE id_ofi = '$id' ");
			return $file['archivo_ofi'];
		}
		public function newEvent($value){
			$this->consulta("INSERT INTO eventos_mant (nombre_eve,tipo_eve,fecha_eve,descripcion_eve,archivo_eve,num_ofi_eve,hora_eve,sede_eve,semana_eve)
								VALUES ('$value[nom_eve]','$value[con]','$value[fecha_eve]','$value[des]','$value[foto]','$value[numero]','$value[hora_eve]','$value[sede_eve]','$value[semana_eve]')");
		}
		public function getEventosDate($fecha){
			$query = $this->consulta("SELECT * FROM eventos_mant WHERE fecha_eve LIKE $fecha ORDER BY fecha_eve ASC");
			if($this->numero_de_filas($query)>0){
				while($datos = $this->fetch_assoc($query))
					$data[] = $datos;
				return $data;
			}else
				return '';
		}
		public function getEventoData($id){
			$query = $this->consulta("SELECT * FROM eventos_mant WHERE num_ofi_eve = '$id'");
			if($this->numero_de_filas($query)>0){
				while($datos = $this->fetch_assoc($query))
					$data[] = $datos;
				return $data;
			}else
				return '';
		}
		public function saveCheckPSS($sql){
			$this->consulta($sql);
		}
		public function getPSS(){
			$query = $this->consulta("SELECT id_pss,nombre_pss,tel_pss,escuela_pss,tel2_pss,carrera_pss,fechaIngreso_pss,status_pss FROM pss_mant WHERE status_pss != 'Inactivo'");
			if($this->numero_de_filas($query)>0){
				while($datos = $this->fetch_assoc($query))
					$data[] = $datos;
				return $data;
			}else
				return '';
		}
		public function bajaPSS($id,$tipo){
			$this->consulta("UPDATE pss_mant SET status_pss = '$tipo' WHERE id_pss = '$id'");
			$query = $this->consulta("SELECT codigo_pss FROM pss_mant WHERE id_pss = $id ");
			if($this->numero_de_filas($query)>0){
				while($datos = $this->fetch_assoc($query))
					$data[] = $datos;
			}
			$codigo = $data[0]['codigo_pss'];
			//$this->consulta("UPDATE checkPss_mant SET verifica_cpss = 5 WHERE codigo_cpss = '$codigo' and verifica_cpss = 0 ");
		}
		public function getDataPSS($id)	{
			$query = $this->consulta("SELECT * FROM pss_mant WHERE id_pss = '$id'");
			$data = $this->fetch_array($query);
			return $data;
		}
		public function checksSS($personal=""){
			$a = $personal!=""?" and codigo_cpss = '$personal' ":$personal;
			$query = $this->consulta("SELECT id_cpss,hora_cpss,id_pss,nombre_pss,dia_cpss,mes_cpss,anio_cpss,tipo_cpss 
									FROM checkPss_mant INNER JOIN pss_mant ON idPss_cpss = id_pss 
									WHERE fechaCon_cpss < '".date('Y-m-d H:i:s')."' AND verifica_cpss = 0 $a
									ORDER BY turno_pss ASC, nombre_pss ASC,fechaCon_cpss ASC
									LIMIT 0, 25");
			if($this->numero_de_filas($query) > 0){
				while ($datos = $this->fetch_assoc($query)) {
					$data[] = $datos;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function PSSmat(){
			$query = $this->consulta("SELECT * FROM pss_mant WHERE status_pss = 'Activo' AND turno_pss = 'Matutino' ORDER BY nombre_pss ASC");
			if($this->numero_de_filas($query)>0){
				while ($data = $this->fetch_assoc($query)) {
					$datos[] = $data;
				}
				return $datos;
			}else{
				return '';
			}
		}
		public function PSSves(){
			$query = $this->consulta("SELECT * FROM pss_mant WHERE status_pss = 'Activo' AND turno_pss = 'Vespertino' ORDER BY nombre_pss ASC");
			if($this->numero_de_filas($query)>0){
				while ($data = $this->fetch_assoc($query)) {
					$datos[] = $data;
				}
				return $datos;
			}else{
				return '';
			}
		}
		public function allPSS(){
			$query = $this->consulta("SELECT * FROM pss_mant WHERE status_pss = 'Activo' ORDER BY turno_pss,nombre_pss ASC");
			if($this->numero_de_filas($query)>0){
				while ($data = $this->fetch_assoc($query)) {
					$datos[] = $data;
				}
				return $datos;
			}else{
				return '';
			}
		}
		public function repDiaPSS($a){
			$a['dia'] = (strlen($a['dia'])<2)?'0'.$a['dia']:$a['dia'];
			$a['mes'] = (strlen($a['mes'])<2)?'0'.$a['mes']:$a['mes'];
			$fecha = trim(htmlspecialchars($a['anio'].'-'.$a['mes'].'-'.$a['dia']));
			$query = $this->consulta("SELECT id_cpss, codigo_cpss, tipo_cpss, nombre_pss, horaCap_cpss, notas_cpss, turno_pss,status_pss FROM checkPss_mant
									INNER JOIN pss_mant ON codigo_cpss = codigo_pss
									WHERE status_pss = 'Activo' and dia_cpss = '$a[dia]' AND mes_cpss = '$a[mes]' AND anio_cpss = '$a[anio]' ".$a['fal']."
									ORDER BY turno_pss,nombre_pss ");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function query($consulta){
			$query = $this->consulta($consulta);
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}
		public function getPersonal(){
			$query = $this->consulta("SELECT nombre_per,cod_per FROM personal_mant WHERE status_per in(0,1) ");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}


		public function checkmaster(){
			$query = $this->consulta("SELECT id_check,anio_check,mes_check,dia_check,horcap_check FROM check_mant");
			if($this->numero_de_filas($query) > 0){
				while ( $tsArray = $this->fetch_assoc($query) ) {
					$data[] = $tsArray;
				}
				return $data;
			}else{
				return '';
			}
		}


	}
?>
