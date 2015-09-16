<?php
	class datebase	{
		private $conexion;
		function __construct(){
			if (!isset($this->conexion)) {
				$this->conexion=mysql_connect(host,user,pw)or die("Probelmas con la conexion ".mysql_errno()); 
				mysql_select_db(db,$this->conexion) or die("Probelmas con la base de datos ".mysql_error());
			}
		}
		public function consulta($sql){
			$resultado = mysql_query($sql,$this->conexion);
			if (!$resultado) {
				echo "Error mysql ".mysql_error();
				exit();
			}
			return $resultado;
		}
		function numero_de_filas($result){
			if(!is_resource($result)) return false;
				return mysql_num_rows($result);

		}
		function fetch_assoc($result){
			if(!is_resource($result)) return false;
				return mysql_fetch_assoc($result);
		}
		function fetch_array($result){
			if(!is_resource($result)) return false;
			return mysql_fetch_array($result);
		}
		function __destruct() {
			mysql_close();
		}
	}
?>