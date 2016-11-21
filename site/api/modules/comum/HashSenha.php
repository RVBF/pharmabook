<?php

class HashSenha
{
	private $senha;

	function __construct($senha)
	{
		$this->senha =$senha;
	}

	/**
	*  Cria uma senha criptografada em MD5
	*  @throws ColecaoException
	*  @return $senha
	*/
	function gerarHashDeSenhaComSaltEmMD5()
	{
		$senha = '';

		$salt = "abchefghjkmnpqrstuvwxyz0123456789abchefghjkmnpqrstuvwxyz0123456789";
		$i = 0;

		while ($i <= 7)
		{

			$senha = $salt . $this->senha . $salt;
			$i++;
		}

		return md5($senha);
	}
}
	
?>