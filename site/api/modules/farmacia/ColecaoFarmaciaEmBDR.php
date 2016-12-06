<?php

/**
 *	Coleção de Farmácia em Banco de Dados Relacional.
 *
 *  @author		Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoFarmaciaEmBDR implements ColecaoFarmacia
{
	
	const TABELA = 'farmacia';
	
	private $pdoW;
	
	function __construct(PDOWrapper $pdoW)
	{
		$this->pdoW = $pdoW;
	}

	function adicionar(&$obj)
	{
		$this->validarFarmacia($obj);
		try
		{
			$sql = 'INSERT INTO ' . self::TABELA . '( nome, telefone, endereco_id, dataCriacao, dataAtualizacao)
			VALUES (
				:nome,
				:telefone,
				:endereco_id,
				:dataCriacao,
				:dataAtualizacao
			)';

			$this->pdoW->execute($sql, [
				'nome' => $obj->getNome(),
				'telefone' => $obj->getTelefone(),
				'endereco_id' => $obj->getEndereco()->getId(),
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

	function remover($id)
	{
		$this->temMedicamentosRelacionados($id);

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
	
	function atualizar(&$obj)
	{
		$this->validarFarmacia($obj);

		try
		{
			$sql = 'UPDATE ' . self::TABELA . ' SET 
				nome = :nome,
				telefone = :telefone,
				endereco_id = :endereco,
				dataAtualizacao = :dataAtualizacao
			 	WHERE id = :id';

			$this->pdoW->execute($sql, [
				'nome' => $obj->getNome(),
				'telefone' => $obj->getTelefone(),
				'endereco' => $obj->getEndereco()->getId(),
				'dataAtualizacao' => $obj->getDataAtualizacao(),
				'id' => $obj->getId()
			]);
		} 
		catch (\Exception $e)
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
			$sql = "SELECT * FROM ". self::TABELA. $this->pdoW->makeLimitOffset($limite, $pulo);
			return $this->pdoW->queryObjects([$this, 'construirObjeto'], $sql );
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e );
		}		
	}
	
	function construirObjeto(array $row)
	{
		$dataCriacao = new DataUtil($row['dataCriacao']);
		$dataAtualizacao = new DataUtil($row['dataAtualizacao']);

		return new Farmacia(
			$row['id'],
			$row['nome'],
			$row['telefone'],
			$row['endereco_id'],
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

	function autoCompleteFarmacia($farmacia, $medicamento)
	{
		try
		{
			$query = 'SELECT DISTINCT f.nome, f.id FROM '.self::TABELA. ' as f';
			$query .= ' join '.ColecaoMedicamentoPrecificadoEmBDR::TABELA.' as mp on mp.farmacia_id = f.id';
			$query .= ' join '.ColecaoMedicamentoEmBDR::TABELA.' as m on mp.medicamento_id = m.id';
			$query .= ' WHERE f.nome like "%'.$farmacia.'%"';
			$query .= ' and f.id = mp.farmacia_id';
			
			if($medicamento != '')
			{
				$query .= ' AND ( m.nome_comercial like "%'.$medicamento.'%" )';
			}			

			$query .= ' ORDER BY f.nome ASC';

			return  $this->pdoW->query($query);
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}


	/**
	*  Valida a farmácia, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarFarmacia($obj)
	{
		$this->validarNome($obj->getNome());
		$this->validarTelefone($obj->getTelefone());			
		$this->verificarExistenciaDeEndereco($obj->getEndereco()->getId());			
	}

	/**
	*  Valida o nome da farmácia, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarNome($nome)
	{
		if(!is_string( $nome))
		{
			throw new ColecaoException( 'Valor inválido para nome.' );
		}
		
		$tamNome = mb_strlen($nome);

		if($tamNome <= Farmacia::TAMANHO_MINIMO_NOME)
		{
			throw new ColecaoException('O nome deve conter no minímo ' . Farmacia::TAMANHO_MINIMO_NOME . ' caracteres.');
		}
		if ($tamNome >= Farmacia::TAMANHO_MAXIMO_NOME)
		{
			throw new ColecaoException('O nome deve conter no máximo ' . Farmacia::TAMANHO_MAXIMO_NOME . ' caracteres.');
		}
	}		

	/**
	*  Valida a quantidade de caracteres do telefone.
	*  @throws ColecaoException
	*/
	private function validarTelefone($telefone)
	{
		if(!is_string($telefone))
		{
			throw new ColecaoException( 'Valor inválido para sobrenome.' );
		}

		$tamSobrenome = mb_strlen($this->retirarCaracteresEspeciais($telefone));

		if($tamSobrenome === Farmacia::TAMANHO_TELEFONE)
		{
			throw new ColecaoException('O telefone deve conter ' . Farmacia::TAMANHO_TELEFONE . ' caracteres.');
		}
	}

	/**
	*  Verifica se o endereço está cadastrado na base de dados.
	*  @throws ColecaoException
	*/
	private function verificarExistenciaDeEndereco($id)
	{
		try
		{
			$sql = 'SELECT  id from '. ColecaoEnderecoEmBDR::TABELA . ' where id = :id';
			$resultado  = $this->pdoW->query($sql, ['id' => $id]);

			if(!(count($resultado) === 1))
			{
				throw new Exception("O Endereço informado não está cadastrado na base de dados.");
			}
		}catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}	
	}

	/**
	*  Remove os caracteres especiais do telefone.
	*  @throws ColecaoException
	*/
	private function retirarCaracteresEspeciais($telefone)
	{
		$pontos = ["(", ")", '-'];
		$resultado = str_replace($pontos, "", $telefone);

		return $resultado;
	}

	private function temMedicamentosRelacionados($id)
	{
		$sql =  'select * from ' . ColecaoMedicamentoPrecificadoEmBDR::TABELA . ' where farmacia_id = :id';

		$resultados = $this->pdoW->query($sql, ['id'=> $id]);

		if(count($resultados) > 0)
		{
			throw new Exception("Não foi possível deletar a famácia, pois tem medicamentos relacionados a ela.");
		}
	}
}	

?>