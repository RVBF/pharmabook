<?php

/**
 *	Coleção de Laboratorio
 *
 *  @author		Rafael Vinicius Barros
 *  @version	0.1
 */

interface ColecaoLaboratorio extends Colecao {

	function pesquisaParaAutoComplete($laboratorio, $medicamento);
}

?>