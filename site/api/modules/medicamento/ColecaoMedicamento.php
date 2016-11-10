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
	 * @param string $principioAtivo	nome do medicamento.
	 * @return Collection 	Retorna uma coleção de medicamentos.
	 * @throws	ColecaoException
	 */

	function pesquisaParaAutoCompleteMedicamentos($medicamento, laboratorio, principioAtivo);

}

?>