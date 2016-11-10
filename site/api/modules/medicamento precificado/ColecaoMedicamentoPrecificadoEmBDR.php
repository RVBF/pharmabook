<?php

/**
 *	Coleção de MedicamentoPrecificado em Banco de Dados Relacional.
 *
 *  @author		Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoMedicamentoPrecificadoEmBDR implements ColecaoMedicamentoPrecificado
{
	
	const TABELA = 'medicamento_precificado';
	
	private $pdoW;
	
	function __construct(PDOWrapper $pdoW)
	{
		$this->pdoW = $pdoW;
	}

	function adicionar(&$obj)
	{
		try
		{
			$sql = 'INSERT INTO ' . self::TABELA . '(
				preco,
				farmacia_id,
				medicamento_id,
				usuario_id,
				dataCriacao,
				dataAtualizacao
			)
			VALUES (
				:preco,
				:farmacia_id,
				:medicamento_id,
				:usuario_id,
				:dataCriacao,
				:dataAtualizacao
			)';

			$today = date('y-m-d H:i:s');     // 05-16-18, 10-03-01, 1631 1618 6 Satpm01

			$this->pdoW->execute($sql, [
				'preco' => getPreco(),
				'farmacia_id' => getFarmacia(),
				'medicamento_id' => getMedicamento(),
				'usuario_id' => getUsuario(),
				'dataCriacao' => getDataCriacao(),
				'dataAtualizacao' => getDataAtualizacao()
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
		try
		{
			$sql = 'UPDATE ' . self::TABELA . ' SET 
				preco = :preco,
				farmacia_id = :farmacia_id,
				medicamento_id = :medicamento_id,
				usuario_id = :usuario_id,
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
				. self::TABELA . ' as  mp  join '
				.ColecaoMedicamentoEmBDR::TABELA.' as m on m.id  = mp.medicamento_id join '
				.ColecaoFarmaciaEmBDR::TABELA.' as f on f.id = mp.farmacia_id join '
				.ColecaoUsuarioEmBDR::TABELA.' as u on u.id = mp.usuario_id'
				.$this->pdoW->makeLimitOffset( $limite, $pulo ) 
			;

			Debuger::printr($query);

			return  $this->pdoW->queryObjects([$this, 'construirObjeto'], $query);
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}		
	}

	function construirObjeto(array $row)
	{
		Debuger::printr($row);
		$medicamento = new Medicamento(
			$row['medicamento_id'],
			$row['ean'],
			$row['cnpj'],
			$row['ggrem'],
			$row['registro'],
			$row['nomeComercial'],
			$row['composicao'],
			$row['laboratorio_id'],
			$row['classe_terapeutica_id'],
			$row['principio_ativo_id']
		);

		$farmacia = new Farmacia(
			$row['medicamento_id'],
		 	$row['nome'],
		 	$row['telefone'],
		 	$row['endereco'],
		 	$row['dataCriacao'],
		 	$row['dataAtualizacao']
		);

		$usuario = new Usuario(
			$row['usuario_id'],
			$row['id'],
			$row['nome'],
			$row['email'],
			$row['login'],
			$row['senha'],
			$row['dataCriacao'],
			$row['dataAtualizacao']
		);

		return new MedicamentoPrecificado(
			$row['id'],
			$row['preco'],
			$farmacia,
			$medicamento,
			$usuario,
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
}	

?>