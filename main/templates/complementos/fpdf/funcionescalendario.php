<?php

function UltimoDia($anho,$mes){
  if (((fmod($anho,4)==0) and (fmod($anho,100)!=0)) or (fmod($anho,400)==0)) {
    $dias_febrero = 29;
  } else {
    $dias_febrero = 28; 
  }
  switch($mes) {
    case 01: return 31; break;
    case 02: return $dias_febrero; break;
    case 03: return 31; break;
    case 04: return 30; break;
    case 05: return 31; break;
    case 06: return 30; break;
    case 07: return 31; break;
    case 08: return 31; break;
    case 09: return 30; break;
    case 10: return 31; break;
    case 11: return 30; break;
    case 12: return 31; break;
  }
}

function nombre_mes($mes){
  switch($mes) {
    case 01: return "Enero"; break;
    case 02: return "Febrero"; break;
    case 03: return "Marzo"; break;
    case 04: return "Abril"; break;
    case 05: return "Mayo"; break;
    case 06: return "Junio"; break;
    case 07: return "Julio"; break;
    case 08: return "Agosto"; break;
    case 09: return "Septiembre"; break;
    case 10: return "Octubre"; break;
    case 11: return "Noviembre"; break;
    case 12: return "Diciembre"; break;
  }
}

function numero_dia_semana($dia,$mes,$anho){ 
  $fecha = getdate(mktime(0,0,0,$mes,$dia,$anho));
  return $fecha["wday"];
}

function nombre_dia_semana($dia,$mes,$anho){ 
  $fecha = getdate(mktime(0,0,0,$mes,$dia,$anho));
  switch($fecha["wday"]) {
    case 1: return "Lunes"; break;
    case 2: return "Martes"; break;
    case 3: return "Miercoles"; break;
    case 4: return "Jueves"; break;
    case 5: return "Viernes"; break;
    case 6: return "Sabado"; break;
    case 7: return "Domingo"; break;
  }
}

$fecha = getdate();
$anho = $fecha["year"];
$mes  = $fecha["mon"];
$dia  = $fecha["mday"];
$dias_mes = UltimoDia($anho,$mes);
$DiaSemana = nombre_dia_semana($dia,$mes,$anho);
$NombreMes = nombre_mes($mes);

echo "<html>";
echo "<font face='arial' size='2'>";
echo "<b>";
echo "<dl>";
echo "<li>Hoy es ".$DiaSemana." ".$dia." de ".$NombreMes." del ".$anho.".</li>";
echo "<li>El mes de ".$NombreMes." del ".$anho." tiene ".$dias_mes." días.</li>";
echo "</dl>";
echo "</b>";
echo "</font>";
echo "</html>";
?>