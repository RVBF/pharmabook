<?php

/**
 *	Coleção de Usuario em Banco de Dados Relacional.
 *
 *  @author		Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoUsuarioEmBDR implements ColecaoUsuario
{
	
	const TABELA = 'usuario';
	
	private $pdoW;
	
	function __construct(PDOWrapper $pdoW)
	{
		$this->pdoW = $pdoW;
	}
	
	function comLoginESenha($login, $senha)
	{
		try
		{
			$sql = 'SELECT * FROM ' . self::TABELA . ' WHERE login = :login AND senha = :senha';
			
			$registros = $this->pdoW->query($sql, [
				'login' => $login,
				'senha' => $senha
			]);

			if(count($registros) < 1){
				return null;
			}

			$obj = (object) $registros[ 0 ];
			
			return new Usuario($obj->id, $obj->nome, $obj->login, $obj->email, $obj->senha);
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}
	
	/**
	 * @inheritDoc
	 */
	function adicionar(&$obj)
	{
		$this->validar($obj);

		try
		{
			$sql = 'INSERT INTO ' . self::TABELA . '(
				nome,
				email,
				login,
				senha,
				dataCriacao,
				dataAtualizacao
			)
			VALUES (
				:nome,
				:email,
				:login,
				:senha,
				:telefone,
				:dataCriacao,
				:dataAtualizacao
			)';

			$today = date('y-m-d H:i:s');     // 05-16-18, 10-03-01, 1631 1618 6 Satpm01

			$this->pdoW->execute($sql, [
				'nome' => $obj->getNome(), 
				'email' => $obj->getEmail(),
				'login' => $obj->getLogin(),
				'senha' => $obj->getSenha(),
				'dataCriacao' => $today,
				'dataAtualizacao' =>$today
			]);

			$obj->setId($this->pdoW->lastInsertId());
		} 
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}
	
	/**
	 * @inheritDoc
	 */
	function atualizar(&$obj)
	{
		$this->validar($obj);

		try
		{
			$sql = 'UPDATE ' . self::TABELA . ' SET 
			 	nome = :nome,
			 	email = :email, 
			 	login = :login, 
			 	senha = :senha,
			 	dataCriacao = :dataCriacao,
			 	dataAtualizacao = :dataAtualizacao
			 	WHERE id = :id';

			$this->pdoW->execute($sql, [
				'nome' => $obj->getNome(), 
				'email' => $obj->getEmail(),
				'login' => $obj->getLogin(),
				'senha' => $obj->getSenha(),
				'dataCriacao' => $obj->getdataCriacao(),
				'dataAtualizacao' => $obj->getDataAtualizacao(),
				'id' => $obj->getId()
			]);
		} 
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}
	
	/**
	 * @inheritDoc
	 */
	function remover($id)
	{
		try
		{
			return $this->pdoW->deleteWithId($id, self::TABELA);
		} catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}
	
	/**
	 * @inheritDoc
	 */
	function comId($id)
	{
		try
		{
			return $this->pdoW->objectWithId([$this, 'construirObjeto'], $id, self::TABELA);
		} catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}

	/**
	 * @inheritDoc
	 */
	function todos($limite = 0, $pulo = 0)
	{
		try
		{
			return $this->pdoW->allObjects([$this, 'construirObjeto'], self::TABELA);
		} catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}

	function construirObjeto(array $row)
	{
		return new Usuario(

			$row['id'],
			$row['nome'],
			$row['email'],
			$row['login'],
			$row['senha'],
			$row['dataCriacao'],
			$row['dataAtualizacao']
		);
	}

	function contagem() 
	{
		try 
		{
			return $this->pdoW->countRows(self::TABELA);
		} 
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}

	private function validar(Usuario $obj)
	{
		if(!is_string($obj->getNome())) 
		{
			throw new ColecaoException('Por favor, informe um valor válido para o nome  do usuário.');
		}
		
		$tamNome = mb_strlen($obj->getNome());

		if($tamNome >= $obj->getTamanhoMinimoNome())
		{
			throw new ColecaoException('O nome deve ter pelo menos ' . $obj->getTamanhoMinimoNome() . ' caracteres.');
		}

		if($tamNome <= $obj->getTamanhoMaximoNome())
		{
			throw new ColecaoException('O nome deve ter no máximo ' .  $obj->getTamanhoMaximoNome() . ' caracteres.');
		}

		// verifica se é email.
		if(! filter_var($obj->getEmail(), FILTER_VALIDATE_EMAIL) and $this->validarFormatoEmail($obj->getEmail()))
		{
			throw new ColecaoException('Por favor, informe o email.');
		}

		$tamEmail = mb_strlen($obj->getEmail());
		
		if($tamEmail >= $obj->getTamanhoMinimoEmail())
		{
			throw new ColecaoException('O email deve ter pelo menos ' . $obj->getTamanhoMinimoEmail(). ' caracteres.');
		}

		if($tamEmail <= $obj->getTamanhoMaximoEmail())
		{
			throw new ColecaoException('O email deve ter no máximo ' . $obj->getTamanhoMaximoEmail() . ' caracteres.');
		}		

		if($this->validarFormatoLogin($obj->getLogin()))
		{
			throw new ColecaoException('Por favor, informe um login válido.');
		}

		$tamEmail = mb_strlen($obj->getLogin());
		
		if($tamEmail >= $obj->getTamanhoMinimoLogin())
		{
			throw new ColecaoException('O login deve ter pelo menos ' . $obj->getTamanhoMinimoLogin(). ' caracteres.');
		}

		if($tamLogin <= $obj->getTamanhoMaximoLogin())
		{
			throw new ColecaoException('O login deve ter no máximo ' . $obj->getTamanhoMaximoLogin() . ' caracteres.');
		}

		$tamSenha = mb_strlen($obj->getSenha());

		if($tamSenha >= $obj->getTamanhoMinimoSenha())
		{
			throw new ColecaoException('A senha deve ter pelo menos ' . $obj->getTamanhoMinimoSenha() . ' caracteres.');
		}

		if($tamSenha <= $obj->getTamanhoMaximoSenha())
		{
			throw new ColecaoException('A senha deve ter no máximo ' . $obj->getTamanhoMaximoSenha() . ' caracteres.');
		}
		//verifica se já existe um email com o mesmo valor no banco de dados.
		$sql = 'SELECT  email FROM ' . self::TABELA . ' WHERE email = :email';
		
		$email = $this->pdoW->run($sql, ['email' => $obj->getEmail()]);
		
		if($email > 0)
		{
			throw new ColecaoException('O email  ' . $obj->getEmail() . ' já está cadastrado.');
		}			

		//verifica se já existe um login com o mesmo valor no banco de dados.
		$sql = 'SELECT  login FROM ' . self::TABELA . ' WHERE login = :login';
		
		$login = $this->pdoW->run($sql, ['login' => $obj->getLogin()]);
		
		if($login > 0)
		{
			throw new ColecaoException('O login  ' . $obj->getLogin() . ' já está cadastrado.');
		}			
	}

	private function validarFormatoEmail($email)
	{
		$conta = "^[a-zA-Z0-9\._-]+@";
		$domino = "[a-zA-Z0-9\._-]+.";
		$extensao = "([a-zA-Z]{2,4})$";

		$pattern = $conta.$domino.$extensao;
		
		if (ereg($pattern, $email))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	private function validarFormatoLogin($email)
	{
		$conta = "^[a-zA-Z0-9\._-]+@";
		$domino = "[a-zA-Z0-9\._-]+.";
		$extensao = "([a-zA-Z]{2,4})$";

		$pattern = $conta.$domino.$extensao;
		
		if (ereg($pattern, $email))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	private function validarFormatoSenha()
	{

		if (preg_match("/^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/", $senha)) 
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