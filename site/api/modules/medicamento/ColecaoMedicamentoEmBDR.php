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
	function autoCompleteMedicamento($medicamento, $laboratorioId)
	{
		try
		{
			$query = 'SELECT DISTINCT m.nome_comercial, m.composicao FROM '.self::TABELA.' as m';
			$query .= ' WHERE m.nome_comercial like "%'.$medicamento.'%" ';
			$query .= ' AND ( m.restricao_hospitalar = "Não")';
			
			if($laboratorioId != null and $laboratorioId > 0)
			{
				$query .=  'AND (m.laboratorio_id = ' . $laboratorioId . ') ';
			}
			
			$query .= ' GROUP BY m.composicao, m.classe_terapeutica_id, m.principio_ativo_id;';
			
			$query .= ' ORDER BY m.nome_comercial ASC';

			return  $this->pdoW->query($query);
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}

	function getMedicamentoDoSistema($medicamento, $laboratorioId)
	{
		try
		{
			$sql = 'SELECT * FROM '.self::TABELA.' as m WHERE m.nome_comercial = "'. $medicamento .'" AND ( m.laboratorio_id = "'.$laboratorioId.'" ) ';	

			return  $this->pdoW->queryObjects([$this, 'construirObjeto'],$sql);
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}

	function construirObjeto(array $row)
	{
		return new Medicamento(
			$row['id'],
			$row['ean'],
			$row['cnpj'],
			$row['ggrem'],
			$row['registro'],
			$row['nome_comercial'],
			$row['composicao'],
			$row['preco_fabrica'],
			$row['preco_maximo_consumidor'],
			$row['restricao_hospitalar'],
			$row['laboratorio_id'],
			$row['classe_terapeutica_id'],
			$row['principio_ativo_id']
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
}	

?>