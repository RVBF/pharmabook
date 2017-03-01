<?php
use phputil\TDateTime;

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
		$this->validarMedicamentoPrecificado($obj);

		try
		{
			$sql = 'INSERT INTO ' . self::TABELA . '(
				preco,
				farmacia_id,
				medicamento_id,
				criador_id,
				atualizador_id
			)
			VALUES (
				:preco,
				:farmacia_id,
				:medicamento_id,
				:criador_id,
				:atualizador_id
			)';

			$this->pdoW->execute($sql, [
				'preco' => $obj->getPreco(),
				'farmacia_id' => $obj->getFarmacia()->getId(),
				'medicamento_id' => $obj->getMedicamento()->getId(),
				'criador_id' => $obj->getCriador()->getId(),
				'atualizador_id' => $obj->getAtualizador()->getId()
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
		$this->validarDepenciasMedicamentosPrecificado($id);

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
		$this->validarMedicamentoPrecificado($obj);

		try
		{
			$sql = 'UPDATE ' . self::TABELA . ' SET
				preco = :preco,
				atualizador_id = :atualizador
		 	WHERE id = :id';

			$this->pdoW->execute($sql, [
				'preco' => $obj->getPreco(),
				'atualizador' => $obj->getAtualizador()->getId(),
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
			$sql = 'SELECT * FROM '.self::TABELA. $this->pdoW->makeLimitOffset($limite, $pulo);
			return $this->pdoW->queryObjects([$this, 'construirObjeto'], $sql);
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	function construirObjeto(array $row)
	{
		$dataCriacao = new TDateTime($row['data_criacao']);
		$dataAtualizacao = new TDateTime($row['data_atualizacao']);

		return new MedicamentoPrecificado(
			$row['id'],
			$row['preco'],
			$row['farmacia_id'],
			$row['medicamento_id'],
			$row['criador_id'],
			$row['atualizador_id'],
			$dataCriacao,
			$dataAtualizacao
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

	function getMedicamentosPrecificados($medicamento, $farmaciaId)
	{
		try
		{
			$sql = 'SELECT mp.id, mp.preco, mp.farmacia_id, mp.medicamento_id, mp.usuario_id, mp.dataCriacao, mp.dataAtualizacao FROM '.self::TABELA.' as mp join '. ColecaoMedicamentoEmBDR::TABELA .' as m on mp.medicamento_id = m.id WHERE m.nome_comercial = "'. $medicamento .'" AND ( mp.farmacia_id = "'.$farmaciaId.'" ) ';

			return  $this->pdoW->queryObjects([$this, 'construirObjeto'],$sql);
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	private function validarMedicamentoPrecificado($obj)
	{
		if(!$this->validarMedicamentoAnvisa($obj->getMedicamento()))
		{
			throw new Exception("O medicamento selecionado não foi encontrado na base de dados, corrija os dados e tente novamente.");
		}

		if(!$this->validarFarmacia($obj->getFarmacia()))
		{
			throw new Exception("A farmácia selecionado não foi encontrado na base de dados, corrija os dados e tente novamente.");
		}

		if(!$this->validarUsuario(($obj->getCriador() != null) ?  $obj->getCriador() : $obj->getAtualizador()))
		{
			throw new Exception("Erro ao cadastrar medicamento precificado, o usuário que executou a ação não existe na base de dados.");
		}

		if(!$this->validarPreco($obj->getPreco()))
		{
			throw new Exception("Formato inválido para preço, o preço deve ser um valor do tipo real.");
		}
		elseif($obj->getPreco() <= 0)
		{
			throw new Exception("O valor do medicamento deve ser maior que 0.");
		}

		$sql = 'SELECT * from '. self::TABELA . ' WHERE farmacia_id = :farmacia_id and medicamento_id = :medicamento_id and id <>'. $obj->getId();

		$resultado = $this->pdoW->query($sql, [
			'farmacia_id' => $obj->getFarmacia()->getId(),
			'medicamento_id' => $obj->getMedicamento()->getId()
		]);

		if(count($resultado) > 0)
		{
			throw new Exception("Não foi possível cadastrar medicamento, pois ele já está precificado no sistema.");
		}
	}

	private function validarMedicamentoAnvisa($medicamento)
	{
		$sql = 'SELECT id from '. ColecaoMedicamentoEmBDR::TABELA . ' WHERE id = :id ';

		$resultado = $this->pdoW->query($sql,['id' => $medicamento->getId()]);

		return (count($resultado) == 1) ? true : false;
	}

	private function validarFarmacia($farmacia)
	{
		$sql = 'SELECT id from '. ColecaoFarmaciaEmBDR::TABELA . ' WHERE id = :id ';

		$resultado = $this->pdoW->query($sql,['id' => $farmacia->getId()]);


		return (count($resultado) == 1) ? true : false;
	}

	private function validarUsuario($usuario)
	{
		$sql = 'SELECT id from '. ColecaoUsuarioEmBDR::TABELA . ' WHERE id = :id ';

		$resultado = $this->pdoW->query($sql,['id' => $usuario->getId()]);

		return (count($resultado) == 1) ? true : false;
	}

	private function validarPreco($preco)
	{
		return is_float($preco);
	}

	private function validarDepenciasMedicamentosPrecificado($id)
	{
		if($this->temEmAlgumMedicamentoNoFavoritoDoUsuario($id))
		{
			throw new Exception("Não foi possível excluir o medicamento precificado pois esse medicamento possui alguma depência com o estoque ou com os favoritos de algum usuário.");
		}
	}

	private function temEmAlgumMedicamentoNoFavoritoDoUsuario($id)
	{
		$sql = 'SELECT * from '. ColecaoFavoritoEmBDR::TABELA . ' WHERE medicamento_precificado_id = '.$id;
		$resultado = $this->pdoW->query($sql);

		return (count($resultado) > 0) ? true : false;
	}
}

?>