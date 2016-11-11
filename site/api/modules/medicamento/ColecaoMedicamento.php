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

	function pesquisaParaAutoComplete($medicamento, $laboratorio);

	/**
	 * Pesquisa medicamentos.
	 *
	 * @param string $medicamento	nome do medicamento.
	 * @param string $laboratorio	nome do medicamento.
	 * @return array Objects.
	 * @throws	ColecaoException
	 */
	function getMedicamentoComNomeELaboratorio($medicamento, $laboratorio);
}

?>