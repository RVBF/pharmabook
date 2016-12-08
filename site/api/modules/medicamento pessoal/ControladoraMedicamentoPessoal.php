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
	private $colecaoMedicamentoPrecificado;
	private $colecaoMedicamentoPessoal;
	private $colecaoMedicamento;
	private $colecaoFarmacia;
	private $colecaoPosologia;

	function __construct(GeradoraResposta $geradoraResposta,  $params, $sessaoUsuario)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->params = $params;
		$this->sessao = $sessaoUsuario;
		$this->servicoLogin = new ServicoLogin($this->sessao);
		$this->colecaoUsuario = DI::instance()->create('ColecaoUsuario');
		$this->colecaoMedicamentoPrecificado = DI::instance()->create('ColecaoMedicamentoPrecificado');
		$this->colecaoMedicamento = DI::instance()->create('ColecaoMedicamento');
		$this->colecaoMedicamentoPessoal = DI::instance()->create('ColecaoMedicamentoPessoal');
		$this->colecaoFarmacia = DI::instance()->create('ColecaoFarmacia');
		$this->colecaoPosologia = DI::instance()->create('ColecaoPosologia');
	}

	function todos() 
	{
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
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
				if($usuario !=  null) $objeto->setUsuario($usuario);

				$medicamentoPrecificado = $this->colecaoMedicamentoPrecificado->comId($objeto->getMedicamentoPrecificado());
				if($medicamentoPrecificado !=  null)
				{
					$medicamento = $this->colecaoMedicamento->comId($medicamentoPrecificado->getMedicamento());
					if($medicamento !=  null) $medicamentoPrecificado->setMedicamento($medicamento);

					$farmacia = $this->colecaoFarmacia->comId($medicamentoPrecificado->getFarmacia());
					if($farmacia !=  null) $medicamentoPrecificado->setFarmacia($farmacia);
					
					$objeto->setMedicamentoPrecificado($medicamentoPrecificado);
				}				
				
				array_push($resposta, $objeto);
			}
		}
		catch (\Exception $e ) {
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
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
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}	

		$inexistentes = \ArrayUtil::nonExistingKeys([
			'id',
			'validade',
			'quantidade',
			'medicamentoPrecificado',
			'dataCriacao',
			'dataAtualizacao',
			'dataNovaCompra'
		], $this->params);

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
			$usuario = $this->colecaoUsuario->comId($this->servicoLogin->getIdUsuario());
			
			if($usuario == null)
			{
				throw new Exception("Usuário não encontrado");
			}

			$medicamentoPrecificado = new MedicamentoPrecificado(\ParamUtil::value($this->params['medicamentoPrecificado'], 'id'));

			$dataCriacao = new DataUtil(\ParamUtil::value($this->params, 'dataCriacao'));
			$dataAtualizacao = new DataUtil(\ParamUtil::value($this->params, 'dataAtualizacao'));
			$validade = new DataUtil(\ParamUtil::value($this->params, 'validade'));
			$dataNovaCompra = new DataUtil(\ParamUtil::value($this->params, 'dataNovaCompra'));

			$medicamentoPessoal = new MedicamentoPessoal(
				\ParamUtil::value($this->params, 'id'),
				$validade->formatarDataParaBanco(),
				\ParamUtil::value($this->params, 'quantidade'),
				$medicamentoPrecificado,
				$usuario,
				$dataCriacao->formatarDataParaBanco(),
				$dataAtualizacao->formatarDataParaBanco(),
				$dataNovaCompra->formatarDataParaBanco()
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
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}	

		$inexistentes = \ArrayUtil::nonExistingKeys([
			'id',
			'validade',
			'quantidade',
			'medicamentoPrecificado',
			'dataCriacao',
			'dataAtualizacao',
			'dataNovaCompra'
		], $this->params);

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
			$usuario = $this->colecaoUsuario->comId($this->servicoLogin->getIdUsuario());
			
			if($usuario == null)
			{
				throw new Exception("Usuário não encontrado");
			}

			$medicamentoPrecificado = new MedicamentoPrecificado(\ParamUtil::value($this->params['medicamentoPrecificado'], 'id'));

			$dataCriacao = new DataUtil(\ParamUtil::value($this->params, 'dataCriacao'));
			$dataAtualizacao = new DataUtil(\ParamUtil::value($this->params, 'dataAtualizacao'));
			$validade = new DataUtil(\ParamUtil::value($this->params, 'validade'));
			$dataNovaCompra = new DataUtil(\ParamUtil::value($this->params, 'dataNovaCompra'));

			$medicamentoPessoal = new MedicamentoPessoal(
				\ParamUtil::value($this->params, 'id'),
				$validade->formatarDataParaBanco(),
				\ParamUtil::value($this->params, 'quantidade'),
				$medicamentoPrecificado,
				$usuario,
				$dataCriacao->formatarDataParaBanco(),
				$dataAtualizacao->formatarDataParaBanco(),
				$dataNovaCompra->formatarDataParaBanco()
			);
			
			$this->colecaoMedicamentoPessoal->atualizar($medicamentoPessoal);

			return $this->geradoraResposta->semConteudo();
		} 
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}		
	}

	function remover()
	{
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
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

			$posologia = $this->colecaoPosologia->comIdMedicamentoPessoal($id);

			if(!empty($posologia)) 
			{
				$this->colecaoPosologia->remover($posologia[0]['id']);
			}
			
			$this->colecaoMedicamentoPessoal->remover($id);

			return $this->geradoraResposta->semConteudo();
		} 
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}
}

?>