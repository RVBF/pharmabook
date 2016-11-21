<?php

/**
 *	Classe com os métodos de valiodação do usuário
 *
 * @author		Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */


class UsuarioValidate
{

	private $usuario;
	private $colecao;

	function __construct(Usuario $usuario)
	{
		$this->usuario = $usuario;
		$this->colecao = DI::instance()->create('ColecaoUsuario');
	}	

	/**
	*  Valida o usuário, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	function validarUsuario()
	{
		$this->validarNome();
		$this->validarSobrenome();
		self::validarEmail($usuario->getEmail());
		self::validarLogin($usuario->getLogin());
		self::validarSenha($usuario->getSenha());

		if(count($colecao->comLogin()) > 0)
		{
			throw new ColecaoException( 'O login  ' . $this->usuario->getLogin() . ' já está em uso por outro usuário no sistema.' );
		}
		
		if(count($colecao->comEmail()) > 0)
		{
			throw new ColecaoException( 'O email  ' . $this->usuario->getEmail() . ' já está em uso por outro usuário no sistema.' );
		}			
	}

	/**
	*  Valida o nome do usuário, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	function validarNome()
	{
		if(!is_string( $this->usuario->getNome()))
		{
			throw new ColecaoException( 'Valor inválido para nome.' );
		}
		
		$tamNome = mb_strlen($this->usuario->getNome());

		if($tamNome <= Usuario::TAMANHO_MINIMO_NOME)
		{
			throw new ColecaoException('O nome deve conter no minímo ' . Usuario::TAMANHO_MINIMO_NOME . ' caracteres.');
		}
		if ($tamNome >= Usuario::TAMANHO_MAXIMO_NOME)
		{
			throw new ColecaoException('O nome deve conter no máximo ' . Usuario::TAMANHO_MAXIMO_NOME . ' caracteres.');
		}
	}		

	/**
	*  Valida o Sobrenome do usuário, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	function validarSobrenome()
	{
		if(!is_string( $this->usuario->getSobrenome()))
		{
			throw new ColecaoException( 'Valor inválido para sobrenome.' );
		}

		$tamSobrenome = mb_strlen($this->usuario->getSobrenome());

		if($tamSobrenome <= Usuario::TAMANHO_MINIMO_SOBRENOME)
		{
			throw new ColecaoException('O sobrenome deve conter no minímo ' . Usuario::TAMANHO_MINIMO_SOBRENOME . ' caracteres.');
		}
		if ($tamSobrenome >= Usuario::TAMANHO_MAXIMO_SOBRENOME)
		{
			throw new ColecaoException('O sobrenome deve conter no máximo ' . Usuario::TAMANHO_MAXIMO_SOBRENOME . ' caracteres.');
		}
	}


	/**
	*  Valida o e-mail do usuário, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	static function validarEmail($email)	
	{
		if(!self::validarFormatoDeEmail($email))
		{
			throw new Exception("Formato de e-mail inválido, o e-mail deve possuir o seguinte formato (exemplo@domínio.extensão)");
		}

		if(!is_string($email))
		{
			throw new ColecaoException( 'Valor inválido para e-mail, o campo e-mail é um campo do tipo texto.' );
		}
	}


	/**
	*  Valida o login do usuário, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	static function validarLogin($login)	
	{
		if(!self::validarFormatoLogin($login))
		{
			throw new Exception("Formato de Login inválido.");
		}

		if(!is_string($login))
		{
			throw new ColecaoException( 'Valor inválido para login, o campo login é um campo do tipo texto.' );
		}

		$tamLogin = mb_strlen($login);

		if($tamLogin <= Usuario::TAMANHO_MINIMO_LOGIN)
		{
			throw new ColecaoException('O sobrenome deve conter no minímo ' . Usuario::TAMANHO_MINIMO_LOGIN . ' caracteres.');
		}
		if ($tamLogin >= Usuario::TAMANHO_MAXIMO_LOGIN)
		{
			throw new ColecaoException('O sobrenome deve conter no máximo ' . Usuario::TAMANHO_MAXIMO_LOGIN . ' caracteres.');
		}
	}

	/**
	*  Valida o senha do usuário, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	static function validarSenha($senha)
	{

		if(!is_string($senha))
		{
			throw new ColecaoException( 'Valor inválido para senha.' );
		}

		$tamSenha = mb_strlen($senha);

		if($tamSenha <= Usuario::TAMANHO_MINIMO_SENHA)
		{
			throw new ColecaoException('O senha deve conter no minímo ' . Usuario::TAMANHO_MINIMO_SENHA . ' caracteres.');
		}
		if ($tamSenha >= Usuario::TAMANHO_MAXIMO_SENHA)
		{
			throw new ColecaoException('O senha deve conter no máximo ' . Usuario::TAMANHO_MAXIMO_SENHA . ' caracteres.');
		}
	}	

	/**
	*  Valida o formato do e-mail do usuário, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	static function validarFormatoDeEmail($email)
	{
		$conta = "^[a-zA-Z0-9\._-]+@";
		$domino = "[a-zA-Z0-9\._-]+.";
		$extensao = "([a-zA-Z]{2,4})$";

		$pattern = $conta.$domino.$extensao;

		if(ereg($pattern, $email))
		{
			return true;	
		}
		else
		{
			return false;	
		}	
	}
	
	/**
	*  Valida formato do login do usuário, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	static function validarFormatoLogin($email)
	{
		$formato = "[a-zA-Z0-9\. _-]+.";

		if (ereg($formato, $email))
		{
			return true;	
		}
		else
		{
			return false;	
		}	
	}
}
?>