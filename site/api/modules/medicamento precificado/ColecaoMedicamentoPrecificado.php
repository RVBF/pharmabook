<?php

/**
 *	Coleção de MedicamentoPrecificado
 *
 *  @author		Rafael Vinicius Barros
 *  @version	0.1
 */

interface ColecaoMedicamentoPrecificado extends Colecao {

	function pesquisarMedicamentoParaAutoCompletePrecificado($medicamentoPrecificado);
	function getMedicamentosPrecificados($medicamentoPrecificado, $farmaciaId);
}

?>