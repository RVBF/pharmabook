<?php

/**
 *	TempoUnidade
 *
 *  @author Rafael Vinicius Barros Ferreira
 *  @version	1.0
 */
abstract class TempoUnidade extends Enum {

	const SEGUNDO = 'Segundo';
	const MINUTO = 'Minuto';
	const HORA = 'Hora';
	const DIA = 'Dia';
	const SEMANA = 'Semana';
	const MES = 'Mês';
	const ANO = 'Ano';
	const DECADA = 'Década';

	static function tempoUnidadePlural()
	{
		return [ 'SEGUNDO' => 'Segundos',
			'MINUTO' => 'Minutos',
			'HORA' => 'Horas',
			'DIA' => 'Dias',
			'SEMANA' => 'Semanas',
			'MES' => 'Meses',
			'ANO' => 'Anos',
			'DECADA' => 'Décadas'
		];
	}

	static function getUnidadePlural($valor)
	{
		$unidadesPlural = self::tempoUnidadePlural();

		return (in_array($valor ,$unidadesPlural)) ? $unidadesPlural[array_search($valor, $unidadesPlural)] : false ;
	}
}
?>