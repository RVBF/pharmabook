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
	 * @param string $term	nome do medicamento.
	 * @return Collection 	Retorna uma coleção de medicamentos.
	 * @throws	ColecaoException
	 */

	function pesquisarMedicamentos($term);

}

?>