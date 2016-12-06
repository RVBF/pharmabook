<?php

/**
 *	Coleção de Favorito
 *
 *  @author		Rafael Vinicius Barros
 *  @version	0.1
 */

interface ColecaoFavorito extends Colecao {

	function estaNosFavoritos($medicamentoPrecificadoId = 0);
}

?>