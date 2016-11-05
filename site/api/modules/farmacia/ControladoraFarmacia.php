<?php

/**
 * Controladora de Farmácia
 *
 * @author	Rafael Vinicius Barros Ferreira
 *  @version	0.1
 */
class ControladoraFarmacia {

	private $geradoraResposta;
	private $params;
	private $colecaoFarmacia;
	private $pdoW;
	private $colecaoEndereco;

	function __construct(GeradoraResposta $geradoraResposta,  $params)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->params = $params;
		$this->colecaoFarmacia = DI::instance()->create('ColecaoFarmacia');
		$this->colecaoEndereco = DI::instance()->create('ColecaoEndereco');
	}

	function todos()
	{
		$dtr = new \DataTablesRequest($this->params);
		$contagem = 0;
		$objetos = [];
		$erro = null;
		try
		{
			$contagem = $this->colecaoFarmacia->contagem();
			$objetos = $this->colecaoFarmacia->todos($dtr->limit(), $dtr->offset());
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

			$this->colecaoFarmacia->remover($id);

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

			$this->colecaoEndereco->adicionar($objEndereco);

			$objFarmacia = new Farmacia(
				\ParamUtil::value($this->params,'id'),
				\ParamUtil::value($this->params,'nome'),
				\ParamUtil::value($this->params,'telefone'),
				$objEndereco,		
				\ParamUtil::value($this->params,'dataCriacao'),
				\ParamUtil::value($this->params,'dataAtualizacao')
			);

			$this->colecaoFarmacia->adicionar($objFarmacia);

			return $this->geradoraResposta->semConteudo();
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


		$objFarmacia = new Farmacia(
			\ParamUtil::value($this->params,'id'),
			\ParamUtil::value($this->params,'nome'),
			\ParamUtil::value($this->params,'telefone'),
			$objEndereco,		
			\ParamUtil::value($this->params,'dataCriacao'),
			\ParamUtil::value($this->params,'dataAtualizacao')
		);
		
		try
		{
			$this->colecaoEndereco->atualizar($objEndereco);

			$this->colecaoFarmacia->atualizar($objFarmacia);

			return $this->geradoraResposta->semConteudo();
		} 
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}		
	}
}
?>