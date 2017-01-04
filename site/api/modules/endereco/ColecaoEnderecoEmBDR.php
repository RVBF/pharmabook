<?php

/**
 *	Coleção de Endereço em Banco de Dados Relacional.
 *
 *  @author		Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoEnderecoEmBDR implements ColecaoEndereco
{
	
	const TABELA = 'endereco';
	
	private $pdoW;
	
	function __construct(PDOWrapper $pdoW)
	{
		$this->pdoW = $pdoW;
	}

	function adicionar(&$obj)
	{
		$this->validarEndereco($obj);

		try
		{
			$sql = 'INSERT INTO ' . self::TABELA . '(
				cep,
				logradouro,
				numero,
				bairro,
				complemento,
				referencia,
				cidade,
				estado,
				pais,
				dataCriacao,
				dataAtualizacao
			)
			VALUES (
				:cep,
				:logradouro,
				:numero,
				:bairro,
				:complemento,
				:referencia,
				:cidade,
				:estado,
				:pais,
				:dataCriacao,
				:dataAtualizacao
			)';

			$this->pdoW->execute($sql, [
				'cep' => $obj->getCep(),
				'logradouro' => $obj->getLogradouro(),
				'numero' => $obj->getNumero(),
				'bairro' => $obj->getBairro(),
				'complemento' => $obj->getComplemento(),
				'referencia' => $obj->getReferencia(),
				'cidade' => $obj->getCidade(),
				'estado' => $obj->getEstado(),
				'pais' => $obj->getPais(),
				'dataCriacao' => $obj->getDataCriacao(),
				'dataAtualizacao' => $obj->getDataAtualizacao()
			]);

			$obj->setId($this->pdoW->lastInsertId());
		} 
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	function atualizar(&$obj)
	{
		$this->validarEndereco($obj);

		try
		{
			$sql = 'UPDATE ' . self::TABELA . ' SET 
			cep = :cep,
			logradouro = :logradouro,
			numero = :numero,
			bairro = :bairro,
			complemento = :complemento,
			referencia = :referencia,
			cidade = :cidade,
			estado = :estado,
			pais = :pais,
			dataAtualizacao = :dataAtualizacao  
			WHERE id = :id';

			$this->pdoW->execute($sql, [
				'cep' => $obj->getCep(),
				'logradouro' => $obj->getLogradouro(),
				'numero' => $obj->getNumero(),
				'bairro' => $obj->getBairro(),
				'complemento' => $obj->getComplemento(),
				'referencia' => $obj->getReferencia(),
				'cidade' => $obj->getCidade(),
				'estado' => $obj->getEstado(),
				'pais' => $obj->getPais(),
				'dataAtualizacao' => $obj->getDataAtualizacao(),
				'id' => $obj->getId()
			]);
		} 
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}

	function remover($id)
	{
		try
		{
			$sql  = 'SET foreign_key_checks = 0';
			$this->pdoW->execute($sql);
			if($this->pdoW->deleteWithId($id, self::TABELA))
			{
				$sql  = 'SET foreign_key_checks = 1';
				$this->pdoW->execute($sql);
				return true;
			}
			else
			{
				return false;
			}
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}

	function comId($id)
	{
		try
		{
			return $this->pdoW->objectWithId([$this, 'construirObjeto'], $id, self::TABELA);
		}catch(\Exception $e)
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
			return $this->pdoW->allObjects([$this, 'construirObjeto'], self::TABELA, $limite, $pulo);
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}

	function construirObjeto(array $row)
	{
		$dataCriacao = new DataUtil($row['dataCriacao']);
		$dataAtualizacao = new DataUtil($row['dataAtualizacao']);

		return new Endereco(	
			$row['id'],
			$row['cep'],
			$row['logradouro'],
			$row['numero'],
			$row['bairro'],
			$row['complemento'],
			$row['referencia'],
			$row['cidade'],
			$row['estado'],
			$row['pais'],
			$dataCriacao->formatarData(),
			$dataAtualizacao->formatarData()
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

	/**
	*  Valida o endereco, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarEndereco($obj)
	{
		$this->validarLogradouro($obj->getLogradouro());
		if($obj->getNumero() != 0) $this->validarNumero($obj->getNumero());			
		if($obj->getComplemento() != '') $this->validarComplemento($obj->getComplemento());			
		if($obj->getReferencia() != '') $this->validarReferencia($obj->getReferencia());			
		$this->validarBairro($obj->getBairro());			
		$this->validarCidade($obj->getCidade());			
		if($obj->getEstado() != '') $this->validarEstado($obj->getEstado());			
		if($obj->getPais() != '') $this->validarPais($obj->getPais());			
		if($obj->getCep() != '') $this->validarCep($obj->getCep());			
	}

	/**
	*  Valida o nome da farmácia, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarLogradouro($logradouro)
	{
		if(!is_string($logradouro))
		{
			throw new ColecaoException('Valor inválido para logradouro.');
		}
	}

	/**
	*  Valida o complemento, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarComplemento($complemento)
	{
		if(!is_string($complemento))
		{
			throw new ColecaoException('Valor inválido para complemento.');
		}
	}

	/**
	*  Valida o referencia, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarReferencia($referencia)
	{
		if(!is_string($referencia))
		{
			throw new ColecaoException('Valor inválido para referencia.');
		}
	}

	/**
	*  Valida o cidade, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarBairro($bairro)
	{
		if(!is_string($bairro))
		{
			throw new ColecaoException('Valor inválido para bairro.');
		}
	}

	/**
	*  Valida o cidade, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarCidade($cidade)
	{
		if(!is_string($cidade))
		{
			throw new ColecaoException('Valor inválido para cidade.');
		}
	}	


	/**
	*  Valida o estado, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarEstado($estado)
	{
		if(!is_string($estado))
		{
			throw new ColecaoException('Valor inválido para estado.');
		}
	}

	/**
	*  Valida o pais, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarPais($pais)
	{
		if(!is_string($pais))
		{
			throw new ColecaoException('Valor inválido para pais.');
		}
	}			

	/**
	*  Valida o pais, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarCep($cep)
	{
		if(!is_string($cep))
		{
			throw new ColecaoException('Valor inválido para cep.');
		}

		if (!eregi("^[0-9]{5}-[0-9]{3}$", $cep)) 
		{
			throw new Exception(" Cep inválido.");
		}
	}		

	/**
	*  Valida se o número está no formato certo
	*  @throws ColecaoException
	*/
	private function validarNumero($numero)
	{
		if(is_int($numero) == false)
		{
			throw new ColecaoException('Tipo inválido, insira o valor do tipo inteiro.');
		}

		if(!($numero > 0))
		{
			throw new ColecaoException('O número deve ser maior que 0.');
		}
	}

	/**
	*  Remove os caracteres especiais do telefone.
	*  @throws ColecaoException
	*/
	private function retirarCaracteresEspeciais($cep)
	{
		$pontos = ["(", ")", '-'];
		$resultado = str_replace($pontos, "", $cep);

		return $resultado;
	}
}	

?>