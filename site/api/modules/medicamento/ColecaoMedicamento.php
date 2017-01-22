<?php

/**
 *	Coleção de Medicamento
 *
 *  @author		Rafael Vinicius Barros
 *  @version	0.1
 */

interface ColecaoMedicamento extends Colecao {

	/**
	 * Pesquisa medicamentos.
	 *
	 * @param string $medicamento	nome do medicamento.
	 * @param string $laboratorio	nome do medicamento.
	 * @return array medicamentos.
	 * @throws	ColecaoException
	 */

	function pesquisarMedicamentoParaAutoComplete($medicamento);
}

?>