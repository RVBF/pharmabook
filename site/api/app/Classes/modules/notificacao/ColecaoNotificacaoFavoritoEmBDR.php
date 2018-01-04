<?php
use phputil\TDateTime;
/**
 *	Coleção de Notificacao Favorito em Banco de Dados Relacional.
 *
 *  @author		Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoNotificacaoFavoritoEmBDR implements ColecaoFavorito
{
	const TABELA = 'notificacao_promocao';

	private $pdoW;
	private $dono;

	function __construct(PDOWrapper $pdoW)
	{
		$this->pdoW = $pdoW;
	}

	/**
	* 	Retorna  um  o usuário responsável pelo medicamento pessoal em que está ocorrendo o procedimento
	*  @return $usuario
	*  @throws ColecaoException
	*/
	function getDono()
	{
		return $this->dono;
	}

	/**
	* 	Seta  um  o usuário responsável pelo medicamento pessoal em que está ocorrendo o procedimento
	*  @param $usuario
	*  @throws ColecaoException
	*/
	function setDono(Usuario $usuario)
	{
		$this->dono = $usuario;
	}

	/**
	* 	Valida e adiciona os dados do objeto no banco de dados.
	*  @param $id
	*	@return true or false
	*  @throws ColecaoException
	*/
	function adicionar(&$obj)
	{
		$this->validarMedicamentoPessoal($obj);

		try
		{
			$sql = 'INSERT INTO ' . self::TABELA . ' (
				validade,
				quantidade,
				capacidade_recipiente,
				tipo_unidade,
				administracao,
				medicamento_forma,
				medicamento_id,
				usuario_id
			)
			VALUES (
				:validade,
				:quantidade,
				:capacidade_recipiente,
				:tipo_unidade,
				:administracao,
				:medicamento_forma,
				:medicamento_id,
				:usuario_id
			)';

			$validade = new DataUtil($obj->getValidade());

			$this->pdoW->execute($sql, [
				'validade' => $validade->formatarDataParaBanco(),
				'quantidade' => $obj->getQuantidade(),
				'capacidade_recipiente' => $obj->getCapacidadeRecipiente(),
				'tipo_unidade' => UnidadeTipo::getValor($obj->getTipoUnidade()),
				'administracao' => Administracao::getValor($obj->getAdministracao()),
				'medicamento_forma' => MedicamentoForma::getValor($obj->getMedicamentoForma()),
				'medicamento_id' =>$obj->getMedicamento()->getId(),
				'usuario_id' => $obj->getUsuario()->getId()
 			]);

			$obj->setId($this->pdoW->lastInsertId());
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	* 	Remove o medicamento referente ao id passado
	*  @param $id
	*	@return true or false
	*  @throws ColecaoException
	*/
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

	/**
	* 	Atualiza um medicamento pessoal
	*  @param $obj
	*	@return $obj
	*  @throws ColecaoException
	*/
	function atualizar(&$obj)
	{
		$this->validarMedicamentoPessoal($obj);
		try
		{
			$sql = 'UPDATE ' . self::TABELA . ' SET
				validade = :validade,
				quantidade = :quantidade,
				capacidade_recipiente = :capacidade_recipiente,
				tipo_unidade = :tipo_unidade,
				administracao = :administracao,
				medicamento_forma = :medicamento_forma
			 	WHERE id = :id';
			$validade = new DataUtil($obj->getValidade());
			$this->pdoW->execute($sql, [
				'validade' => $validade->formatarDataParaBanco(),
				'quantidade' => $obj->getQuantidade(),
				'capacidade_recipiente' => $obj->getCapacidadeRecipiente(),
				'tipo_unidade' => UnidadeTipo::getValor($obj->getTipoUnidade()),
				'administracao' => Administracao::getValor($obj->getAdministracao()),
				'medicamento_forma' => MedicamentoForma::getValor($obj->getMedicamentoForma()),
				'id' => $obj->getId()
			]);
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	* 	Retorna o objeto corresponde ao id passado
	*  @param $id
	*	@return $obj
	*  @throws ColecaoException
	*/
	function comId($id)
	{
		try
		{
			return $this->pdoW->objectWithId([$this, 'construirObjeto'], $id, self::TABELA);
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	*  Retorna todos os medicamentos pessoais refente ao usuário logado.
	*	@param $limite, $pulo
	*  @return array $obj
	*  @throws ColecaoException
	*/
	function todos($limite = 0, $pulo = 0)
	{
		try
		{
			$sql = 'SELECT * FROM '. self::TABELA . ' where usuario_id = ' . $this->getDono()->getId() . ' ' . $this->pdoW->makeLimitOffset($limite, $pulo);

			return $this->pdoW->queryObjects([$this, 'construirObjeto'], $sql);
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	*  Cria um objeto de medicamento Pessoal.
	*  @throws ColecaoException
	*/
	function construirObjeto(array $row)
	{
		$dataCriacao = new TDateTime($row['data_criacao']);
		$dataAtualizacao = new TDateTime($row['data_atualizacao']);
		$validade = new TDateTime($row['validade']);

		return new MedicamentoPessoal(
			$row['id'],
			$validade,
			$row['capacidade_recipiente'],
			$row['quantidade'],
			$row['administracao'],
			$row['tipo_unidade'],
			$row['medicamento_forma'],
			$row['usuario_id'],
			$row['medicamento_id'],
			$dataCriacao,
			$dataAtualizacao
		);
	}

	/**
	*  Retorna a contagem de registro na base de dados.
	*  @throws ColecaoException
	*/
	function contagem()
	{
		try
		{
			return $this->pdoW->countRows(self::TABELA, 'usuario_id', 'where usuario_id = :usuario_id', ['usuario_id' => $this->getDono()->getId()]);
		}
		catch (\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}
}
?>