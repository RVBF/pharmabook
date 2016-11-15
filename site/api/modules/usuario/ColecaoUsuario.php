<?php

/**
 *	Coleção de Usuario
 *
 *  @author		Rafael Vinicius Barros
 *  @version	0.1
 */

interface ColecaoUsuario extends Colecao {

	function validarEmail($email);

	function validarLogin($login);

	function validarSenha($senha);
}

?>