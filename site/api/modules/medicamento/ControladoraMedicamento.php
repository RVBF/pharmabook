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
	private $sessao;
	private $servicoLogin;
	private $colecao;

	function __construct(GeradoraResposta $geradoraResposta, $params, $sessaoUsuario)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->sessao = $sessaoUsuario;
		$this->servicoLogin = new ServicoLogin($this->sessao);
		$this->params = $params;
		$this->colecao = DI::instance()->create('ColecaoMedicamentoEmBDR');
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

	function comId($id)
	{
		if($this->servicoLogin->estaLogado())
		{
			if(!$this->servicoLogin->sairPorInatividade())
			{
				$this->servicoLogin->atualizaAtividadeUsuario();
			}
			else
			{
				return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
			}
		}
		else
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}	

		try
		{
			$obj = $this->colecao->comId($id);

			return $this->geradoraResposta->ok(JSON::encode($obj), GeradoraResposta::TIPO_JSON);
		} 
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}	
	}

	function autoCompleteMedicamento()
	{
		if($this->servicoLogin->estaLogado())
		{
			if(!$this->servicoLogin->sairPorInatividade())
			{
				$this->servicoLogin->atualizaAtividadeUsuario();
			}
			else
			{
				return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
			}
		}
		else
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}	

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
			$resultados = $this->colecao->autoCompleteMedicamento(
				\ParamUtil::value($this->params, 'medicamento'),
				\ParamUtil::value($this->params, 'laboratorio')
			);

			$conteudo = array();

			foreach ($resultados as $resultado)
			{
				array_push($conteudo, [
					'label' => $resultado['nome_comercial'],
					'value' => $resultado['nome_comercial'],
					'composicao' => $resultado['composicao']
				]);
			}
		} 
		catch (\Exception $e )
		{
			$erro = $e->getMessage();
		}

		return $this->geradoraResposta->resposta(json_encode($conteudo), GeradoraResposta::OK, GeradoraResposta::TIPO_JSON);
	}

	function autoCompleteLaboratorioDoMedicamento()
	{
		if($this->servicoLogin->estaLogado())
		{
			if(!$this->servicoLogin->sairPorInatividade())
			{
				$this->servicoLogin->atualizaAtividadeUsuario();
			}
			else
			{
				return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
			}
		}
		else
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}
		
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
			$objeto = $this->colecaoMedicamento->autoCompleteLaboratorioDoMedicamento(
				\ParamUtil::value($this->params, 'medicamento'),
				\ParamUtil::value($this->params, 'laboratorio')
			);
		} 
		catch (\Exception $e )
		{
			$erro = $e->getMessage();
		}

		if(!empty($objeto))
		{
			return $this->geradoraResposta->resposta(JSON::encode($objeto), GeradoraResposta::OK, GeradoraResposta::TIPO_JSON);
		}
		else
		{
			return $this->geradoraResposta->semConteudo();
		}
	}
}

?>