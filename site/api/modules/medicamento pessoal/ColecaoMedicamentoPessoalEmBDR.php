<?php
use phputil\TDateTime;
/**
 *	Coleção de MedicamentoPessoal em Banco de Dados Relacional.
 *
 *  @author		Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoMedicamentoPessoalEmBDR implements ColecaoMedicamentoPessoal
{
	const TABELA = 'medicamento_pessoal';

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

	/**
	*  Valida os medicamentos pessoais, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarMedicamentoPessoal($obj)
	{
		if($obj->getId() == 0)
		{
			$this->validarMedicamento($obj->getMedicamento());
		}

		$this->validarValidade($obj->getValidade());
		$this->validarTipoDeAministracao($obj->getAdministracao());
		$this->validarMedicamentoForma($obj->getMedicamentoForma());
		$this->validarTipoUnidade($obj->getMedicamentoForma());
		$this->validarQuantidade($obj->getQuantidade());
		$this->validarCapacidadeDoRecipiente($obj->getCapacidadeRecipiente());
	}

	/**
	*  Valida se o medicamento selecionado já está relacionado com algum cadastro, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarMedicamento($medicamento)
	{
		try
		{
			$sql =  'select medicamento_id from ' . self::TABELA . ' where medicamento_id = :medicamento_id and usuario_id = :usuario_id';

			$resultado  = $this->pdoW->query($sql, ['medicamento_id' => $medicamento->getId(), 'usuario_id' => $this->getDono()->getid()]);

			if(count($resultado) > 0){ throw new Exception("O medicamento selecionado já está relacionado a um outro medicamento pessoal."); }
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	*  Valida o formato e se a data é maior ou igual a data atual.
	*  @throws ColecaoException
	*/
	private function validarValidade($validade)
	{
		try
		{
			$dataValidade  = new DataUtil($validade);
			if(!$dataValidade->formatarDataParaBanco()) throw new Exception("Formato inválido para data");

			$dataValidade =  new TDateTime($dataValidade->formatarDataParaBanco());
			$dataAtual = new TDateTime();

			if($dataAtual->greaterThan($dataValidade)) throw new Exception(" A data de validade deve ser maior ou igual a data atual.");
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	*  Valida o formato e se existe uma chave corresponte a administração selecionada.
	*  @throws ColecaoException
	*/
	private function validarTipoDeAministracao($administracao)
	{
		try
		{
			if(!is_string($administracao))
			{
				throw new Exception("Formato inválido para tipo de administração.");
			}

			if(!Administracao::eUmaChaveValida($administracao))
			{
				throw new Exception("Valor inválido para administração.");
			}
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	*  Valida o formato e se existe uma chave corresponte a forma de medicamento selecionada.
	*  @throws ColecaoException
	*/
	private function validarMedicamentoForma($medicamentoForma)
	{
		try
		{
			if(!is_string($medicamentoForma))
			{
				throw new Exception("Formato inválido para tipo a forma de medicação.");
			}

			if(!MedicamentoForma::eUmaChaveValida($medicamentoForma))
			{
				throw new Exception("Valor inválido para a forma de medicação.");
			}
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	*  Valida o formato e se existe uma chave corresponte a adminsitração selecionada.
	*  @throws ColecaoException
	*/
	private function validarTipoUnidade($tipoUnidade)
	{
		try
		{
			if(!is_string($tipoUnidade))
			{
				throw new Exception("Formato inválido para tipo de unidade.");
			}

			if(!MedicamentoForma::eUmaChaveValida($tipoUnidade))
			{
				throw new Exception("Valor inválido para o tipo de unidade.");
			}
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	*  Valida o formato do parametro  e se a qauntidade de caixas é maior que 0.
	*  @throws ColecaoException
	*/
	private function validarQuantidade($quantidadeCaixas)
	{
		try
		{
			if($quantidadeCaixas <= 0)
			{
				throw new Exception("A quantidade de recepientes deve ser maior que 0.");
			}
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}

	/**
	*  Valida o formato do parametro e a capacidade do recipiente.
	*  @throws ColecaoException
	*/
	private function validarCapacidadeDoRecipiente($capacidadeRecipiente)
	{
		try
		{
			if($capacidadeRecipiente <= 0)
			{
				throw new Exception("A capacidade do recipiente deve ser maior que 0.");
			}
		}
		catch(\Exception $e)
		{
			throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
		}
	}
}
?>