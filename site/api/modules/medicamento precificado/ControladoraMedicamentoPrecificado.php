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
	private $colecaoUsuario;
	private $colecaoFarmacia;
	private $colecaoMedicamento;
	private $colecaoMedicamentoPrecificado;

	function __construct(GeradoraResposta $geradoraResposta,  $params, $sessaoUsuario)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->params = $params;
		$this->sessao = $sessaoUsuario;
		$this->servicoLogin = new ServicoLogin($this->sessao);
		$this->colecaoUsuario = DI::instance()->create('ColecaoUsuario');
		$this->colecaoMedicamento = DI::instance()->create('ColecaoMedicamento');
		$this->colecaoFarmacia = DI::instance()->create('ColecaoFarmacia');
		$this->colecaoMedicamentoPrecificado = DI::instance()->create('ColecaoMedicamentoPrecificado');
	}

	function todos() 
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

		$dtr = new \DataTablesRequest($this->params);
		$contagem = 0;
		$objetos = array();
		$erro = null;
		try 
		{
			$contagem = $this->colecaoMedicamentoPrecificado->contagem();

			$objetos = $this->colecaoMedicamentoPrecificado->todos($dtr->limit(), $dtr->offset());

			$resposta = array();

			foreach ($objetos as $objeto)
			{
				$farmacia = $this->colecaoFarmacia->comId($objeto->getFarmacia());
				$objeto->setFarmacia($farmacia);				

				$medicamento = $this->colecaoMedicamento->comId($objeto->getMedicamento());
				$objeto->setMedicamento($medicamento);				

				$usuario = $this->colecaoUsuario->comId($objeto->getUsuario());
				$objeto->setUsuario($usuario);

				array_push($resposta, $objeto);
			}
		}
		catch (\Exception $e ) {
			$erro = $e->getMessage();
		}

		$conteudo = new \DataTablesResponse(
			$contagem,
			$contagem, //count($objetos ),
			$resposta,
			$dtr->draw(),
			$erro
		);
		
		return $this->geradoraResposta->ok(JSON::encode($conteudo), GeradoraResposta::TIPO_JSON);
	}

	function remover()
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
			$usuario = new Usuario(\ParamUtil::value($this->params['usuario'], 'id'));
			
			$medicamento = new Medicamento(\ParamUtil::value($this->params['medicamento'], 'id'));

			$objFarmacia = new Farmacia(\ParamUtil::value($this->params['farmacia'], 'id'));

			$dataCriacao = new DataUtil(\ParamUtil::value($this->params, 'dataCriacao'));
			$dataAtualizacao = new DataUtil(\ParamUtil::value($this->params, 'dataAtualizacao'));

			$medicamentoPrecificado = new MedicamentoPrecificado(
				\ParamUtil::value($this->params, 'id'),
				floatval(\ParamUtil::value($this->params, 'preco')),
				$objFarmacia,
				$medicamento,
				$usuario,
				$dataCriacao->formatarDataParaBanco(),
				$dataAtualizacao->formatarDataParaBanco()
			);

			$this->colecaoMedicamentoPrecificado->adicionar($medicamentoPrecificado);

			return $this->geradoraResposta->semConteudo();
		} 
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}		
	}
		
	function atualizar()
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
			$usuario = new Usuario(\ParamUtil::value($this->params['usuario'], 'id'));
			
			$medicamento = new Medicamento(\ParamUtil::value($this->params['medicamento'], 'id'));

			$objFarmacia = new Farmacia(\ParamUtil::value($this->params['farmacia'], 'id'));

			$dataCriacao = new DataUtil(\ParamUtil::value($this->params, 'dataCriacao'));
			$dataAtualizacao = new DataUtil(\ParamUtil::value($this->params, 'dataAtualizacao'));

			$medicamentoPrecificado = new MedicamentoPrecificado(
				\ParamUtil::value($this->params, 'id'),
				floatval(\ParamUtil::value($this->params, 'preco')),
				$objFarmacia,
				$medicamento,
				$usuario,
				$dataCriacao->formatarDataParaBanco(),
				$dataAtualizacao->formatarDataParaBanco()
			);

			$this->colecaoMedicamentoPrecificado->adicionar($medicamentoPrecificado);

			return $this->geradoraResposta->semConteudo();
		} 
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}		
	}
}

?>