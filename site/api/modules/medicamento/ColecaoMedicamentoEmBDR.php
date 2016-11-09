<?php

/**
 *	Coleção de Medicamento em Banco de Dados Relacional.
 *
 *  @author		Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoMedicamentoEmBDR implements ColecaoMedicamento
{
	
	const TABELA = 'medicamento';
	
	private $pdoW;
	
	function __construct(PDOWrapper $pdoW)
	{
		$this->pdoW = $pdoW;
	}

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
				telefone,
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
				'telefone' => $obj->getTelefone(),
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

	function remover($id)
	{
		try
		{
			return $this->pdoW->deleteWithId($id, self::TABELA);
		}catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}
	
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
			 	telefone = :telefone,
			 	dataCriacao = :dataCriacao,
			 	dataAtualizacao = :dataAtualizacao
			 	WHERE id = :id';

			$this->pdoW->execute($sql, [
				'nome' => $obj->getNome(), 
				'email' => $obj->getEmail(),
				'login' => $obj->getLogin(),
				'senha' => $obj->getSenha(),
				'telefone' => $obj->getTelefone(),
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
			$query = 'SELECT * from ' 
				. self::TABELA . ' as  medicamentos  join '
				.ColecaoLaboratorioEmBDR::TABELA.' as laboratorios on laboratorios.id  = medicamentos.laboratorio_id join '
				.ColecaoClasseTerapeuticaEmBDR::TABELA.' as classesTerapeuticas on classesTerapeuticas.id = medicamentos.classe_terapeutica_id join '
				.ColecaoPrincipioAtivo::TABELA.' as principioAtivo on principioAtivo.id = medicamentos.principio_ativo_id'
				.$this->pdoW->makeLimitOffset( $limite, $pulo ) 
			;

			return  $this->pdoW->queryObjects([$this, 'construirObjeto'], $query);
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}

	/**
	* @inheritDoc
	*/
	function pesquisarMedicamentos($term)
	{
		try
		{
			$query = 'SELECT * from FROM '.self::TABELA. 'WHERE m.nome_comercial like "%'.$term.'%"';

			return  $this->pdoW->query($query);
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}

	function construirObjeto(array $row)
	{
		Debuger::printr($row);	
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
}	

?>