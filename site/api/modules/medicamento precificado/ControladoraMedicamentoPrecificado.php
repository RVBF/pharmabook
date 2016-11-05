<?php

/**
 * Controladora de MedicamentoPrecificado
 *
 * @author	Rafael Vinicius Barros Ferreira
 * @version	0.1
 */
class ControladoraMedicamentoPrecificado {

	private $geradoraResposta;
	private $params;
	private $colecaoFarmacia;
	private $colecaoMedicamento;
	private $colecaoMedicamentoPrecificado;
	private $pdoW;

	function __construct(GeradoraResposta $geradoraResposta,  $params)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->params = $params;
		$this->colecaoMedicamentoPrecificado = DI::instance()->create('colecaoMedicamentoPrecificado');
		$this->colecaoFarmacia = DI::instance()->create('ColecaoFarmacia');
		$this->colecaoMedicamento = DI::instance()->create('ColecaoMedicamento');
	}

	function todos() 
	{
		$dtr = new \DataTablesRequest($this->params);
		$contagem = 0;
		$objetos = array();
		$erro = null;
		try 
		{
			$contagem = $this->colecaoMedicamentoPrecificado->contagem();
			$objetos = $this->colecaoMedicamentoPrecificado->todos($dtr->limit(), $dtr->offset());
		} catch (\Exception $e ) {
			$erro = $e->getMessage();
		}
		$conteudo = new \DataTablesResponse(
			$contagem,
			$contagem, //count($objetos ),
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

			$this->colecaoMedicamentoPrecificado->remover($id);

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
			'preco',
			'dataCriacao',
			'dataAtualizacao'		
		], $this->params);

		$inexistentes += \ArrayUtil::nonExistingKeys([
			'id'		
		], $this->params['farmacia']);

		$inexistentes += \ArrayUtil::nonExistingKeys([
			'id'
		], $this->params['usuario']);

		$inexistentes += \ArrayUtil::nonExistingKeys([
			'id'
		], $this->params['medicamento']);

		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		try
		{
			$objUsuario = new Usuario(\ParamUtil::value($this->params['usuario'], 'id'));

			$this->colecaoUsario->comId($objUsuario->id);
	
			$objMedicamento = new Medicamento(\ParamUtil::value($this->params['medicamento'], 'id'));

			$this->colecaoMedicamento->comId($objMedicamento->id);
	
			$objFarmacia = new farmacia(\ParamUtil::value($this->params['farmacia'], 'id'));

			$this->colecaoFarmacia->comId($objFarmacia->id);
			
			$objMedicamentoPrecificado = new MedicamentoPrecificado(
				\ParamUtil::value($this->params['farmacia'], 'id'),
				\ParamUtil::value($this->params['farmacia'], 'preco'),
				\ParamUtil::value($this->params['farmacia'], 'dataCriacao'),
				\ParamUtil::value($this->params['farmacia'], 'dataAtualizacao'),
				$objFarmacia,
				$objMedicamento,
				$objUsuario	
			);

			$this->colecaoMedicamentoPrecificado->adicionar($objMedicamentoPrecificado);

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
			'ean',
			'cnpj',
			'ggrem',
			'registro',
			'nomeComercial',
			'composicao',
		], $this->params);

		$inexistentes += \ArrayUtil::nonExistingKeys([
			'id',
			'nome'
		], $this->params['laboratorio']);

		$inexistentes += \ArrayUtil::nonExistingKeys([
			'id',
			'nome'
		], $this->params['classeTerapeutica']);

		$inexistentes += \ArrayUtil::nonExistingKeys([
			'id',
			'nome'
		], $this->params['principioAtivo']);


		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		try
		{
			$objPrincipioAtivo = new PrincipioAtivo(
				\ParamUtil::value($this->params['principioAtivo'], 'id'), 
				\ParamUtil::value($this->params['principioAtivo'], 'nome')
			);

			$this->colecaoPrincipioAtivo->atualizar($objPrincipioAtivo);
	
			$classeTerapeutica = new classeTerapeutica(
				\ParamUtil::value($this->params['classeTerapeutica'], 'id'), 
				\ParamUtil::value($this->params['classeTerapeutica'], 'nome')
			);

			$this->colecaoClasseTerapeutica->atualizar($classeTerapeutica);
	
			$laboratorio = new laboratorio(
				\ParamUtil::value($this->params['laboratorio'], 'id'), 
				\ParamUtil::value($this->params['laboratorio'], 'nome')
			);
			$this->colecaLaboratorio->atualizar($classeTerapeutica);
			
			$objMedicamentoPrecificado = new MedicamentoPrecificado(
				\ParamUtil::value($this->params), 'id',
				\ParamUtil::value($this->params), 'ean',
				\ParamUtil::value($this->params), 'cnpj',
				\ParamUtil::value($this->params), 'ggrem',
				\ParamUtil::value($this->params), 'registro',
				\ParamUtil::value($this->params), 'nomeComercial',
				\ParamUtil::value($this->params), 'composicao'	
			);

			$this->colecaoMedicamentoPrecificado->atualizar($objMedicamentoPrecificado);
			return $this->geradoraResposta->semConteudo();
		} 
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}		
	}
}

?>