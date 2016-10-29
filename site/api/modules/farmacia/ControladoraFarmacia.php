<?php

/**
 * Controladora de Farmácia
 *
 * @author	Rafael Vinicius Barros Ferreira
 */
class ControladoraFarmacia {

	private $geradoraResposta;
	private $params;
	private $colecao;
	private $pdoW;
	private $servicoEndereco;

	function __construct(GeradoraResposta $geradoraResposta,  $params)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->params = $params;
		$this->colecao = DI::instance()->create('ColecaoFarmaciaEmBDR');
		$this->servicoEndereco = DI::instance()->create('ServicoEndereco');
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
		try
		{
			$inexistentes = \ArrayUtil::nonExistingKeys([
				'id',
				'nome',
				'telefone',
				'endereco',
				'dataCriacao',
				'dataAtualizacao'
			], $this->params);		


			$inexistentes += \ArrayUtil::nonExistingKeys([
				'id',
				'cep',
				'logradouro',
				'numero',
				'complemento',
				'referencia',
				'bairro',
				'cidade',
				'estado',
				'pais',
				'dataCriacao',
				'dataAtualizacao'
			], $this->params['endereco']);

			if (count($inexistentes) > 0)
			{
				$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
				return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
			}

			$objEndereco = new Endereco(

				\ParamUtil::value($this->params['endereco'],'id'),
				\ParamUtil::value($this->params['endereco'],'cep'),
				\ParamUtil::value($this->params['endereco'],'logradouro'),
				\ParamUtil::value($this->params['endereco'],'numero'),
				\ParamUtil::value($this->params['endereco'],'complemento'),
				\ParamUtil::value($this->params['endereco'],'referencia'),
				\ParamUtil::value($this->params['endereco'],'bairro'),
				\ParamUtil::value($this->params['endereco'],'cidade'),
				\ParamUtil::value($this->params['endereco'],'estado'),
				\ParamUtil::value($this->params['endereco'],'pais'),
				\ParamUtil::value($this->params['endereco'],'dataCriacao'),
				\ParamUtil::value($this->params['endereco'],'dataAtualizacao')
			);

			Debuger::printr($objEndereco);
			$this->servicoEndereco->adicionar($objEndereco);

			$objFarmacia = new Farmacia(
				\ParamUtil::value($this->params,'id'),
				\ParamUtil::value($this->params,'nome'),
				\ParamUtil::value($this->params,'telefone'),
				$objEndereco,		
				\ParamUtil::value($this->params,'dataCriacao'),
				\ParamUtil::value($this->params,'dataAtualizacao')
			);

			$this->colecao->adicionar($objFarmacia);

			$farmaciaArray = [];

			$farmaciaArray['id'] = $objFarmacia->getId();
			$farmaciaArray['nome'] = $objFarmacia->getNome();
			$farmaciaArray['telefone'] = $objFarmacia->getNome();
			$farmaciaArray['endereco'] = $objFarmacia->getEndereco()->mostrarEndereco();
			$farmaciaArray['dataCriacao'] = $objFarmacia->getDataCriacao();
			$farmaciaArray['dataAtualizacao'] = $objFarmacia->getDataAtualizacao();

			return $this->geradoraResposta->resposta( json_encode($farmaciaArray), GeradoraResposta::CRIADO, GeradoraResposta::TIPO_JSON);
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
			'nome',
			'telefone',
			'endereco',
			'dataCriacao',
			'dataAtualizacao'
		], $this->params);

		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		$obj = new Farmacia(
			\ParamUtil::value($this->params,'ix'),
			\ParamUtil::value($this->params,'nome'),
			\ParamUtil::value($this->params,'telefone'),
			\ParamUtil::value($this->params,'telefone'),
			\ParamUtil::value($this->params,'endereco'),
			\ParamUtil::value($this->params,'dataCriacao'),
			\ParamUtil::value($this->params,'dataAtualizacao')
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