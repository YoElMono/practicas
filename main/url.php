<?php
	/*Switch que funciona para redirigir a la funcion necesario
	basado en la URL que se requiere para poder usar las url's
	limpias.*/
	switch ($objects["principal"]->url_p(URL_short())) {
		case '/':{$objects["principal"]->index();}break;
		case 'ips':{$objects["principal"]->ips();}break;
		case 'distroy':{$objects["principal"]->distroy();}break;
		case 'ipR':{$objects["principal"]->reporteIp();}break;
		case 'entraPro':{$objects["principal"]->entradaProdu();}break;
		case 'cap-dep':{$objects["principal"]->cDependencia();}break;
		case 'get':{$objects["principal"]->conGet();}break;
		case 'get_f':{$objects["principal"]->get_f();}break;
		case 'repEntra':{$objects["principal"]->reporteEnt();}break;
		case 'newUser':{$objects["principal"]->regisUser();}break;
		case 'salidaProd':{$objects["principal"]->salidaProd();}break;
		case 'repSalida':{$objects["principal"]->reporteSal();}break;
		case 'repSalidaAut':{$objects["principal"]->reporteSalAut();}break;
		case 'ffas':{$objects["principal"]->ffas();}break;
		case 'Delffas':{$objects["principal"]->Delffas();}break;
		case 'sers':{$objects["principal"]->services();}break;
		case 'personal':{$objects["principal"]->personal();}break;
		case 'tarjetas':{$objects["principal"]->tarjetas();}break;
		case 'admUsers':{$objects["principal"]->admUser();}break;
		case 'dir':{$objects["principal"]->direc();}break;
		case 'check':{$objects["principal"]->check();}break;
		case 'admdir':{$objects["principal"]->admdir();}break;
		case 'appAdmin':{$objects["principal"]->appAdmin();}break;
		case 'proCivil':{$objects["principal"]->staticPage("proCivil.html");}break;
		case 'medica':{$objects["principal"]->staticPage("medica.html");}break;
		case 'capasitacion':{$objects["principal"]->staticPage("capasitacion.html");}break;
		case 'atencionPrehos':{$objects["principal"]->staticPage("atencionPrehos.html");}break;
		case 'asigserver':{$objects["principal"]->asigserver();}break;
		case 'admPer':{$objects["principal"]->admPer();}break;
		case 'mdir':{$objects["principal"]->mdir();}break;
		case 'editPass':{$objects["principal"]->editPass();}break;
		case 'jusfal':{$objects["principal"]->jusfal();}break;
		case 'jufal':{$objects["principal"]->jufal();}break;
		case 'repFal':{$objects["principal"]->repFaltas();}break;
		case 'repDia':{$objects["principal"]->repDia();}break;
		case 'repSema':{$objects["principal"]->repSema();}break;
		case 'repMes':{$objects["principal"]->repMes();}break;
		case 'segSer':{$objects["principal"]->segSer();}break;
		case 'restringido':{$objects["principal"]->staticPage('restringido.html');}break;
		case 'diaLibre':{$objects["principal"]->diaLibre();}break;
		case 'capVaca':{$objects["principal"]->capVaca();}break;
		case 'diaRegalo':{$objects["principal"]->diaRegalo();}break;
		case 'vacDia':{$objects["principal"]->vacDia();}break;
		case 'Oficialia':{$objects["principal"]->Oficialia();}break;
		case 'Reporte_ofi':{$objects["principal"]->Reporte_ofi();}break;
		case 'Reporte_ofi2':{$objects["principal"]->Reporte_ofi2();}break;
		case 'cambiar_imagen':{$objects["principal"]->cambiar_imagen();}break;
		case 'repFal2':{$objects["principal"]->repFal();}break;
		case 'eventos':{$objects["principal"]->eventos();}break;
		case 'getEventoData':{$objects["principal"]->getEventoData();}break;
		case 'Servicio_Social_Registro':{$objects["principal"]->personalSS();}break;
		case 'Servicio_Social_Adm':{$objects["principal"]->viewPSS();}break;
		case 'Servicio_Social_Check':{$objects["principal"]->checkPSS();}break;
		case 'Servicio_Social_Tarjetas':{$objects["principal"]->tarjetasPSS();}break;
		default:{$objects["principal"]->e404();}break;
	}
?>