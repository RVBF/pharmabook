<?php

/**
 * Controladora de MedicamentoPessoal
 *
 * @author	Rafael Vinicius Barros Ferreira
 * @version	0.1
 */
class ControladoraMedicamentoPessoal {

	private $geradoraResposta;
	private $params;
	private $colecaoUsuario;
	private $colecaoPosologia;
	private $colecaoMedicamentoPrecificado;
	private $colecaoMedicamentoPessoal;

	function __construct(GeradoraResposta $geradoraResposta,  $params, $sessaoUsuario)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->params = $params;
		$this->sessao = $sessaoUsuario;
		$this->servicoLogin = new ServicoLogin($this->sessao);
		$this->colecaoUsuario = DI::instance()->create('ColecaoUsuario');
		$this->colecaoMedicamentoPrecificado = DI::instance()->create('ColecaoMedicamentoPrecificado');
		$this->colecaoMedicamento = DI::instance()->create('ColecaoMedicamento');
		$this->colecaoPosologia = DI::instance()->create('ColecaoPosologia');
		$this->colecaoMedicamentoPessoal = DI::instance()->create('ColecaoMedicamentoPessoal');
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
			$usuario = $this->colecaoUsuario->comId($this->servicoLogin->getIdUsuario());
			
			if($usuario == null)
			{
				throw new Exception("Usuário não encontrado.");
			}

			$this->colecaoMedicamentoPessoal->setDono($usuario);

			$contagem = $this->colecaoMedicamentoPessoal->contagem();

			$objetos = $this->colecaoMedicamentoPessoal->todos($dtr->limit(), $dtr->offset());

			$resposta = array();

			foreach ($objetos as $objeto)
			{
				$usuario = $this->colecaoUsuario->comId($objeto->getUsuario());
				if($usuario !=  null) $objeto->setFarmacia($usuario);				

				$medicamentoPrecificado = $this->colecaoMedicamentoPrecificado->comId($objeto->getMedicamentoPrecificado());
				if($medicamentoPrecificado !=  null)
				{
					$medicamento = $this->colecaoMedicamento->comId($medicamentoPrecificado->getMedicamento());
					if($medicamento !=  null) $medicamentoPrecificado->setMedicamento($medicamento);	
					
					$objeto->setMedicamentoPrecificado($medicamentoPrecificado);
				}				
							

				$posologia = $this->colecaoPosologia->comId($objeto->getPosologia());
				if($posologia !=  null) $objeto->setPosologia($posologia);
				
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
			'validade',
			'quantidade',
			'dataNovaCompra',
			'medicamentoPrecificado',
			'posologia',
			'usuario',
			'dataCriacao',
			'dataAtualizacao'	
		], $this->params);

		$inexistentes += \ArrayUtil::nonExistingKeys([
			'id'		
		], $this->params['usuario']);

		$inexistentes += \ArrayUtil::nonExistingKeys([
			'id'
		], $this->params['posologia']);

		$inexistentes += \ArrayUtil::nonExistingKeys([
			'id'
		], $this->params['medicamentoPrecificado']);

		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		try
		{
			$usuario = new Usuario(\ParamUtil::value($this->params['usuario'], 'id'));
			
			$posologia = new Medicamento(\ParamUtil::value($this->params['posologia'], 'id'));

			$medicamentoPrecificado = new Farmacia(\ParamUtil::value($this->params['medicamentoPrecificado'], 'id'));

			$dataCriacao = new DataUtil(\ParamUtil::value($this->params, 'dataCriacao'));
			$dataAtualizacao = new DataUtil(\ParamUtil::value($this->params, 'dataAtualizacao'));

			$medicamentoPessoal = new MedicamentoPessoal(
				\ParamUtil::value($this->params, 'id'),
				\ParamUtil::value($this->params, 'validade'),
				\ParamUtil::value($this->params, 'quantidade'),
				\ParamUtil::value($this->params, 'dataNovaCompra'),
				\ParamUtil::value($this->params, 'medicamentoPrecificado'),
				\ParamUtil::value($this->params, 'posologia'),
				\ParamUtil::value($this->params, 'usuario'),
				$dataCriacao->formatarDataParaBanco(),
				$dataAtualizacao->formatarDataParaBanco()
			);

			$this->colecaoMedicamentoPessoal->adicionar($medicamentoPessoal);

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
			'validade',
			'quantidade',
			'dataNovaCompra',
			'medicamentoPrecificado',
			'posologia',
			'usuario',
			'dataCriacao',
			'dataAtualizacao'	
		], $this->params);

		$inexistentes += \ArrayUtil::nonExistingKeys([
			'id'		
		], $this->params['usuario']);

		$inexistentes += \ArrayUtil::nonExistingKeys([
			'id'
		], $this->params['posologia']);

		$inexistentes += \ArrayUtil::nonExistingKeys([
			'id'
		], $this->params['medicamentoPrecificado']);

		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		try
		{
			$usuario = new Usuario(\ParamUtil::value($this->params['usuario'], 'id'));
			
			$posologia = new Medicamento(\ParamUtil::value($this->params['posologia'], 'id'));

			$medicamentoPrecificado = new Farmacia(\ParamUtil::value($this->params['medicamentoPrecificado'], 'id'));

			$dataCriacao = new DataUtil(\ParamUtil::value($this->params, 'dataCriacao'));
			$dataAtualizacao = new DataUtil(\ParamUtil::value($this->params, 'dataAtualizacao'));

			$medicamentoPessoal = new MedicamentoPessoal(
				\ParamUtil::value($this->params, 'id'),
				\ParamUtil::value($this->params, 'validade'),
				\ParamUtil::value($this->params, 'quantidade'),
				\ParamUtil::value($this->params, 'dataNovaCompra'),
				\ParamUtil::value($this->params, 'medicamentoPrecificado'),
				\ParamUtil::value($this->params, 'posologia'),
				\ParamUtil::value($this->params, 'usuario'),
				$dataCriacao->formatarDataParaBanco(),
				$dataAtualizacao->formatarDataParaBanco()
			);

			$this->colecaoMedicamentoPessoal->atualizarw($medicamentoPessoal);

			return $this->geradoraResposta->semConteudo();
		} 
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}		
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
			$id = (int) \ParamUtil::value($this->params, 'id');
			
			if (!is_int($id))
			{
				$msg = 'O id informado não é um número inteiro.';
				return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
			}

			$medicamentoPessoal = $this->colecaoMedicamentoPessoal->comId($id);

			if(!$this->colecaoMedicamentoPessoal->remover($medicamentoPessoal->getId())) throw new Exception("Não foi possível deletar a farmácia.");
			
			if(!$this->colecaoPosologia->remover($medicamentoPessoal->getPosologia())) throw new Exception("Não foi possível deletar o endereço");

			return $this->geradoraResposta->semConteudo();
		} 
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}
}

?>