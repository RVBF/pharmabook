<?php

/**
 *	Coleção
 *  @author		Rafael Vinicius Barros Ferreira
 */
interface Colecao {

	/**
	 * Adiciona um objeto à coleção.
	 *
	 * @param object $obj	Objeto a ser adicionado.
	 * @throws	ColecaoException
	 */
	function adicionar(&$obj);
	
	/**
	 * Atualiza um objeto.
	 *
	 * @param object $obj	Objeto a ser atualizado.
	 * @throws	ColecaoException
	 */
	function atualizar(&$obj);
	
	/**
	 * Remove um objeto.
	 *
	 * @param int $id	Identificação do objeto a ser removido.
	 * @throws	ColecaoException
	 */
	function remover($id);
	
	/**
	 * Obtém um objeto pelo seu id.
	 *
	 * @param int $id	Identificação do objeto a ser obtido.
	 * @return object	Objeto a ser retornado.
	 * @throws	ColecaoException
	 */
	function comId($id);

	/**
	 * Obtém todos os objetos.
	 *
	 * @param int $limite	Quantos objetos serão retornados.
	 * @param int $pulo		Quantos objetos serão pulados.
	 * @return array	Array de objetos a serem retornados.
	 * @throws	ColecaoException
	 */
	function todos($limite = 0, $pulo = 0);
	
	/**
	 *  Retorna a contagem de elementos na coleção.
	 *  @return int
	 */
	function contagem();
}

?>