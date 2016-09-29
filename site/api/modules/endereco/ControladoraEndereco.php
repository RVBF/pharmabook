<?php

/**
 * Controladora de Endereco
 *
 * @author	Rafael Vinicius Barros Ferreira
 */
class ControladoraEndereco {

	private $geradoraResposta;
	private $params;
	private $colecao;
	private $pdoW;

	function __construct(GeradoraResposta $geradoraResposta,  $params)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->params = $params;
		$this->colecao = DI::instance()->create('ColecaoEnderecoEmBDR');
	}

	function todos()
	{
		$dtr = new \DataTablesRequest($this->params);
		$contagem = 0;
		$objetos = [];
		$erro = null;
		try
		{
			$contagem = $this->colecao->contagem();
			$objetos = $this->colecao->todos($dtr->limit(), $dtr->offset());
		} 
		catch (\Exception $e)
		{
			$erro = $e->getMessage();
		}
		
		$conteudo = new \DataTablesResponse(
			$contagem,
			$contagem, //contagem dos objetos
			$objetos,
			$dtr->draw(),
			$erro
		);

		$this->geradoraResposta->ok($conteudo, GeradoraResposta::TIPO_JSON);
	}

	function remover()
	{
		try
		{
			$id = \ParamUtil::value($this->params, 'id');
			
			if (! is_numeric($id))
			{
				$msg = 'O id informado não é numérico.';
				return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
			}

			$this->colecao->remover($id);

			return $this->geradoraResposta->semConteudo();
		} 
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}
	
	function adicionar()
	{
		$inexistentes = \ArrayUtil::nonExistingKeys([
			'id',
			'logradouro',
			'bairro',
			'cidade',
			'estado',
			'numero',
			'complemento',
			'referencia',
			'dataCriacao',
			'dataAtualizacao'
		], $this->params);

		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		$obj = new Endereco(
			\ParamUtil::value($this->params,'id'),
			\ParamUtil::value($this->params,'logradouro'),
			\ParamUtil::value($this->params,'bairro'),
			\ParamUtil::value($this->params,'cidade'),
			\ParamUtil::value($this->params,'estado'),
			\ParamUtil::value($this->params,'numero'),
			\ParamUtil::value($this->params,'complemento'),
			\ParamUtil::value($this->params,'referencia'),
			\ParamUtil::value($this->params,'dataCriacao'),
			\ParamUtil::value($this->params,'dataAtualizacao'),
		);

		try
		{
			$this->colecao->adicionar($obj);

			return $obj;
		} 
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}		
	}
		
	function atualizar()
	{
		$inexistentes = \ArrayUtil::nonExistingKeys([
			'id',
			'logradouro',
			'bairro',
			'cidade',
			'estado',
			'numero',
			'complemento',
			'referencia',
			'dataCriacao',
			'dataAtualizacao'
		], $this->params);
		
		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		$obj = new Endereco(
			\ParamUtil::value($this->params,'id'),
			\ParamUtil::value($this->params,'logradouro'),
			\ParamUtil::value($this->params,'bairro'),
			\ParamUtil::value($this->params,'cidade'),
			\ParamUtil::value($this->params,'estado'),
			\ParamUtil::value($this->params,'numero'),
			\ParamUtil::value($this->params,'complemento'),
			\ParamUtil::value($this->params,'referencia'),
			\ParamUtil::value($this->params,'dataCriacao'),
			\ParamUtil::value($this->params,'dataAtualizacao'),
		);
		try
		{
			$this->colecao->atualizar($obj);
			return $this->geradoraResposta->semConteudo();
		} 
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}		
	}
}

?>