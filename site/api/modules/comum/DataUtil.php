<?php

/**
 *	Classe para Trabalhar com datas
 *  @author		Rafael Vinicius Barros Ferreira
 */
class DataUtil {

	private $data;

	function __construct($data = '')
	{
		$this->data = $data;
	}

	function formatarDataParaBanco() 
	{
		$formato = '/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/';
		$formato2 = '/^([0-9]{1})\/([0-9]{2})\/([0-9]{4})$/';
		$formato3 = '/^([0-9]{1})\/([0-9]{1})\/([0-9]{4})$/';

		if ($this->data != null && preg_match($formato, $this->data, $partes) || $this->data != null && preg_match($formato2, $this->data, $partes) || $this->data != null && preg_match($formato3, $this->data, $partes)) 
		{
			return $partes[3].'-'.$partes[2].'-'.$partes[1];
		}

		return false;
	}	

	function formatarData() 
	{
		$formato = '/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/';

		if ($this->data != null && preg_match($formato, $this->data, $partes)) 
		{
			return $partes[3].'/'.$partes[2].'/'.$partes[1];
		}

		return '';
	}
}
?>

