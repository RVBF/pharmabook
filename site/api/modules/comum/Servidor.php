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
	
	const HTTP_LOCAL						= 'localhost/farmabook';
	const HTTP_REMOTO						= '';
	
	// BANCO DE DADOS
	
	const BANCO_DADOS_LOCAL_URL				= 'localhost';
	const BANCO_DADOS_LOCAL_NOME			= 'farmabook';
	const BANCO_DADOS_LOCAL_USUARIO			= 'root';
	const BANCO_DADOS_LOCAL_SENHA			= '';
	
	const BANCO_DADOS_REMOTO_URL			= '';
	const BANCO_DADOS_REMOTO_NOME			= '';
	const BANCO_DADOS_REMOTO_USUARIO		= '';
	const BANCO_DADOS_REMOTO_SENHA			= '';
	
	// DIRETÓRIOS
	
	const DIRETORIO_BASE_LOCAL				= '/farmabook/site/';
	const DIRETORIO_BASE_REMOTO				= '';
		
	const DIRETORIO_IMAGENS_LOCAL			= '/farmabook/site/img/';
	const DIRETORIO_IMAGENS_REMOTO			= '';	
	
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