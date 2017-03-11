<?php

/**
 *  Informações do servidor.
 *
 *  @author	Thiago Delgado Pinto
 */
class Servidor {

	//
	// CONSTANTES - *NÃO* ACESSE-AS FORA DESTA CLASSE
	//

	// ENDEREÇOS DO SERVIDOR

	const HTTP_LOCAL						= 'localhost/alfa';
	const HTTP_REMOTO						= '192.168.0.4/alfa';

	// BANCO DE DADOS

	const BANCO_DADOS_LOCAL_URL				= 'localhost';
	const BANCO_DADOS_LOCAL_NOME			= 'pharmabook';
	const BANCO_DADOS_LOCAL_USUARIO			= 'root';
	const BANCO_DADOS_LOCAL_SENHA			= '';

	const BANCO_DADOS_REMOTO_URL			= '192.168.0.4';
	const BANCO_DADOS_REMOTO_NOME			= 'pharmabook';
	const BANCO_DADOS_REMOTO_USUARIO		= 'remoto';
	const BANCO_DADOS_REMOTO_SENHA			= 'abcde15243';

	// DIRETÓRIOS

	const DIRETORIO_BASE_LOCAL				= '/alfa/site/';
	const DIRETORIO_BASE_REMOTO				= '192.168.0.4/alfa/site/';

	const DIRETORIO_IMAGENS_LOCAL			= '/alfa/site/img/';
	const DIRETORIO_IMAGENS_REMOTO			= '192.168.0.4/alfa/site/img/';

	//
	// MÉTODOS BÁSICOS
	//

	static function local()
	{
		if (! isset($_SERVER[ 'SERVER_NAME' ] ))
	{ return false; }

		return 'localhost' === $_SERVER[ 'SERVER_NAME' ]
			|| 'localhost' === $_SERVER[ 'SERVER_NAME' ];
	}

	static function diretorioRaiz()
	{
		if (! isset($_SERVER[ 'DOCUMENT_ROOT' ] ))
	{ return ''; }
		return $_SERVER[ 'DOCUMENT_ROOT' ];
	}

	static function http()
	{
		return self::local() ? self::HTTP_LOCAL : self::HTTP_REMOTO;
	}

	//
	// MÉTODOS PARA ACESSO ÀS CONSTANTES
	//

	static function diretorioBase()
	{
		return self::diretorioRaiz() .
			(self::local() ? self::DIRETORIO_BASE_LOCAL : self::DIRETORIO_BASE_REMOTO );
	}

	static function bancoDadosURL()
	{
		return self::local() ? self::BANCO_DADOS_LOCAL_URL : self::BANCO_DADOS_REMOTO_URL;
	}

	static function bancoDadosNome()
	{
		return self::local() ? self::BANCO_DADOS_LOCAL_NOME : self::BANCO_DADOS_REMOTO_NOME;
	}

	static function bancoDadosUsuario()
	{
		return self::local() ? self::BANCO_DADOS_LOCAL_USUARIO : self::BANCO_DADOS_REMOTO_USUARIO;
	}

	static function bancoDadosSenha()
	{
		return self::local() ? self::BANCO_DADOS_LOCAL_SENHA : self::BANCO_DADOS_REMOTO_SENHA;
	}

	static function diretorioImagens()
	{
		return self::diretorioRaiz()
			. (self::local() ? self::DIRETORIO_IMAGENS_LOCAL : self::DIRETORIO_IMAGENS_REMOTO );
	}
}

?>