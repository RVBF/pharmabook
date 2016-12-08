<?php

/**
 *	Coleção de Usuario
 *
 *  @author		Rafael Vinicius Barros
 *  @version	0.1
 */

interface ColecaoUsuario extends Colecao {

	function setUsuario($usuario);
	function getUsuario();
	function novaSenha($senhaAtual, $novaSenha, $confirmacaoSenha,  $dataAtualizacao);
}

?>