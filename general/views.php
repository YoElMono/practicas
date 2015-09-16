<?php
	class vista{
		function __construct(){
		}
		public static function index($html,$arr = null){
			$valores = [
			'Title' => "Inicio",
			'header' => dinamic("general/static/header.html"),
			'contenido' => load_page("general/static/".$html),
			'lateral' => dinamic("general/static/inst.html"),
			'footer' => load_page("general/static/footer.html")
			];
			$templad = load_page("main/templates/principal.html");
			$mostrar = remplas($valores,$templad);
			return $mostrar;
		}
		public function page($html, $arr = null){
			$valores = [
			'Title' => "Inicio",
			'header' => dinamic("general/static/header.html"),
			'contenido' => dinamic("general/static/".$html,$arr),
			'op' => load_page("general/static/op.html"),
			'lateral' => dinamic("general/static/lateral.html"),
			'footer' => load_page("general/static/footer.html")
			];
			$templad = load_page("main/templates/principal.html");
			$mostrar = remplas($valores,$templad);
			return $mostrar;
		}
		public function pageChosen($html, $arr = null){
			$valores = [
			'Title' => "Inicio",
			'header' => dinamic("general/static/header.html"),
			'contenido' => dinamic("general/static/".$html,$arr),
			'lateral' => dinamic("general/static/lateral.html"),
			'footer' => load_page("general/static/footer.html")
			];
			$templad = load_page("main/templates/complementos/chosen/principal.html");
			$mostrar = remplas($valores,$templad);
			return $mostrar;
		}
		public function pageWhite($html,$arr = null,$title){
			$valores = [
			'Title' => $title,
			'contenido' => dinamic("general/static/".$html,$arr)
			];
			$templad = load_page("main/templates/white.html");
			$mostrar = remplas($valores,$templad);
			return $mostrar;
		}
	}
?>