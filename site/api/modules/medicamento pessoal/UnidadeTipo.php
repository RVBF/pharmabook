<?php

/**
 *	UnidadeTipo
 *
 *  @author Rafael Vinicius Barros Ferreira
 *  @version	1.0
 */
abstract class UnidadeTipo extends Enum {

	const QUILO = 'Quilo';
	const GRAMA = 'Grama';
	const MICROGRAMA = 'Micrograma';
	const MILIGRAMA = 'Miligrama';
	const LITRO = 'Litro';
	const MILILITRO = 'Mililitro';
	const COMPRIMIDO = 'Comprimido';
	const CAPSULAS = 'Cápsulas';

	public static function unidadesInteiras()
	{
		return [
			['CAPSULAS' => self::CAPSULAS],
			['COMPRIMIDO' => self::COMPRIMIDO]
		];
	}

	public static function unidadesLiquidas()
	{
		return [
			['LITRO' => self::LITRO],
			['MILILITRO' => self::MILILITRO]
		];
	}

	public static function unidadesSolidas()
	{
		return [
			['MILIGRAMA' => self::MILIGRAMA],
			['MICROGRAMA' => self::MICROGRAMA],
			['GRAMA' => self::GRAMA],
			['QUILO' => self::QUILO]
		];
	}
}
?>