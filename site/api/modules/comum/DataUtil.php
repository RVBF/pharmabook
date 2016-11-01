<?php

/**
 *	Classe para Trabalhar com datas
 *  @author		Rafael Vinicius Barros Ferreira
 */
class DataUtil {


	public static function formatarDataParaBanco($data = null) 
	{
		$formato = '/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/';
		$formato2 = '/^([0-9]{1})\/([0-9]{2})\/([0-9]{4})$/';
		$formato3 = '/^([0-9]{1})\/([0-9]{1})\/([0-9]{4})$/';

		if ($data != null && preg_match($formato, $data, $partes) || $data != null && preg_match($formato2, $data, $partes) || $data != null && preg_match($formato3, $data, $partes)) 
		{
			return $partes[3].'-'.$partes[2].'-'.$partes[1];
		}

		return false;
	}	

	public static function formatarData($data = null) 
	{
		$formato = '/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/';

		if ($data != null && preg_match($formato, $data, $partes)) 
		{
			return $partes[3].'/'.$partes[2].'/'.$partes[1];
		}

		return '';
	}
}
?>

