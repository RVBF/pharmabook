<?php

/**
 * Controladora de Medicamento
 *
 * @author	Rafael Vinicius Barros Ferreira
 * @version	0.1
 */
class ControladoraMedicamento {

	private $geradoraResposta;
	private $params;
	private $colecao;
	private $pdoW;

	function __construct(GeradoraResposta $geradoraResposta,  $params)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->params = $params;
		$this->colecaoMedicamento = DI::instance()->create('ColecaoMedicamentoEmBDR');
		$this->colecaoPrincipioAtivo = DI::instance()->create('ColecaoPrincipioAtivoEmBDR');
		$this->colecaoClasseTerapeutica = DI::instance()->create('ColecaoClasseTerapeuticaEmBDR');
		$this->colecaoLaboratorio = DI::instance()->create('ColecaoLaboratorioEmBDR');
	}

	function pesquisaParaAutoComplete()
	{
		$inexistentes = \ArrayUtil::nonExistingKeys([
			'medicamento',
			'laboratorio',
		], $this->params);

		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		try 
		{
			$resultados = $this->colecaoMedicamento->pesquisaParaAutoComplete(
				\ParamUtil::value($this->params, 'medicamento'),
				\ParamUtil::value($this->params, 'laboratorio')
			);

			$conteudo = array();

			foreach ($resultados as $resultado)
			{
				$principioAtivo = $this->colecaoPrincipioAtivo->comId($resultado['principio_ativo_id']);
				$classeTerapeutica = $this->colecaoClasseTerapeutica->comId($resultado['classe_terapeutica_id']);

				array_push($conteudo, [
					'label' => $resultado['nome_comercial'],
					'value' => $resultado['nome_comercial'],
					'composicao' => $resultado['composicao'],
					'principioId' => $principioAtivo->getid(),
					'principio' => $principioAtivo->getNome(),					
					'classeId' => $classeTerapeutica->getid(),
					'classe' => $classeTerapeutica->getNome()
				]);
			}
		} 
		catch (\Exception $e )
		{
			$erro = $e->getMessage();
		}

		$this->geradoraResposta->resposta(json_encode($conteudo), GeradoraResposta::OK, GeradoraResposta::TIPO_JSON);
	}

	function getMedicamentoComNomeELaboratorio()
	{
		$inexistentes = \ArrayUtil::nonExistingKeys([
			'medicamento',
			'laboratorio',
		], $this->params);

		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		try 
		{
			$objeto = $this->colecaoMedicamento->getMedicamentoComNomeELaboratorio(
				\ParamUtil::value($this->params, 'medicamento'),
				\ParamUtil::value($this->params, 'laboratorio')
			);
		} 
		catch (\Exception $e )
		{
			$erro = $e->getMessage();
		}

		$this->geradoraResposta->resposta(json_encode($objeto), GeradoraResposta::OK, GeradoraResposta::TIPO_JSON);
	}

	function todos() 
	{
		$dtr = new \DataTablesRequest($this->params);
		$contagem = 0;
		$objetos = array();
		$erro = null;
		try 
		{
			$contagem = $this->colecao->contagem();
			$objetos = $this->colecao->todos($dtr->limit(), $dtr->offset());
		} 
		catch (\Exception $e ) {
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

			$this->colecaoPrincipioAtivo->adicionar($objPrincipioAtivo);
	
			$classeTerapeutica = new classeTerapeutica(
				\ParamUtil::value($this->params['classeTerapeutica'], 'id'), 
				\ParamUtil::value($this->params['classeTerapeutica'], 'nome')
			);

			$this->colecaoClasseTerapeutica->adicionar($classeTerapeutica);
	
			$laboratorio = new laboratorio(
				\ParamUtil::value($this->params['laboratorio'], 'id'), 
				\ParamUtil::value($this->params['laboratorio'], 'nome')
			);
			$this->colecaLaboratorio->adicionar($classeTerapeutica);
			
			$objMedicamento = new Medicamento(
				\ParamUtil::value($this->params), 'id',
				\ParamUtil::value($this->params), 'ean',
				\ParamUtil::value($this->params), 'cnpj',
				\ParamUtil::value($this->params), 'ggrem',
				\ParamUtil::value($this->params), 'registro',
				\ParamUtil::value($this->params), 'nomeComercial',
				\ParamUtil::value($this->params), 'composicao'	
			);

			$this->colecaoMedicamento->adicionar($objMedicamento);
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
			
			$objMedicamento = new Medicamento(
				\ParamUtil::value($this->params), 'id',
				\ParamUtil::value($this->params), 'ean',
				\ParamUtil::value($this->params), 'cnpj',
				\ParamUtil::value($this->params), 'ggrem',
				\ParamUtil::value($this->params), 'registro',
				\ParamUtil::value($this->params), 'nomeComercial',
				\ParamUtil::value($this->params), 'composicao'	
			);

			$this->colecaoMedicamento->adicionar($objMedicamento);
			return $obj;
		} 
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}		
	}
}

?>