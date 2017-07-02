<?php
use phputil\TDateTime;
/**
 *	Coleção de Endereço em Banco de Dados Relacional.
 *
 *  @author		Rafael Vinicius Barros Ferreira
 *	@version	0.1
 */

class ColecaoEnderecoEntidadeEmBDR implements ColecaoEnderecoEntidade
{

	const TABELA = 'enderecos_entidades';

	private $pdoW;

	function __construct(PDOWrapper $pdoW)
	{
		$this->pdoW = $pdoW;
	}

	function adicionar(&$obj)
	{
		if($this->validarEnderecoEntidade($obj))
		{
			try
			{
				$sql = 'INSERT INTO ' . self::TABELA . '(numero, complemento, referencia, endereco_id) VALUES ( :numero, :complemento, :referencia, :endereco_id)';

				$this->pdoW->execute($sql, [ 'numero' => $obj->getNumero(), 'complemento' => $obj->getComplemento(), 'referencia'  => $obj->getReferencia(), 'endereco' => $obj->getEndereco()->getId()]);

				$obj->setId($this->pdoW->lastInsertId());
			}
			catch (\Exception $e)
			{
				throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
			}
		}
	}

	function atualizar(&$obj)
	{
		if($this->validarEnderecoEntidade($obj))
		{
			try
			{
				$sql  = 'SET foreign_key_checks = 0';
				$this->pdoW->execute($sql);

				$sql = 'UPDATE ' . self::TABELA . ' SET numero = :numero, complemento = :complemento, referencia = :referencia, endereco_id = :endereco_id WHERE id = :id';

				$this->pdoW->execute($sql, [ 'numero' => $obj->getNumero(), 'complemento' => $obj->getComplemento(), 'referencia'  => $obj->getReferencia(), 'endereco' => $obj->getEndereco()->getId(), 'id' => $obj->getId() ]);

				$sql  = 'SET foreign_key_checks = 1';
				$this->pdoW->execute($sql);

			}
			catch (\Exception $e)
			{
				throw new ColecaoException($e->getMessage(), $e->getCode(), $e);
			}
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
		$dataCriacao = new TDateTime($row['data_criacao']);
		$dataAtualizacao = new TDateTime($row['data_atualizacao']);

		return new Endereco(
			$row['id'],
			$row['numero'],
			$row['complemento'],
			$row['referencia'],
			$row['endereco_id'],
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

	/**
	*  Valida o endereco, lançando uma exceção caso haja algo inválido.
	*  @throws ColecaoException
	*/
	private function validarEnderecoEntidade(&$obj)
	{
		if($obj->getNumero() != 0) $this->validarNumero($obj->getNumero());
		if($obj->getComplemento() != '') $this->validarComplemento($obj->getComplemento());
		if($obj->getReferencia() != '') $this->validarReferencia($obj->getReferencia());
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

}

?>