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
	private $colecaoMedicamentoPessoal;
	private $colecaoMedicamento;

	function __construct(GeradoraResposta $geradoraResposta,  $params, $sessaoUsuario)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->params = $params;
		$this->sessao = $sessaoUsuario;
		$this->servicoLogin = new ServicoLogin($this->sessao);
		$this->colecaoUsuario = DI::instance()->create('ColecaoUsuario');
		$this->colecaoMedicamento = DI::instance()->create('ColecaoMedicamento');
		// $this->colecaoTipoMedicamento = DI::instance()->create('ColecaoTipoMedicamento');
		$this->colecaoMedicamentoPessoal = DI::instance()->create('ColecaoMedicamentoPessoal');
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

				$medicamentoPrecificado = $this->colecaoMedicamento->comId($objeto->getMedicamentoPrecificado());
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
			'quantidadeRecipiente',
			'quantidadeEstoque',
			'administracao',
			'unidadeTipo',
			'medicamentoForma',
			'medicamento',
		], $this->params);

		$inexistentes = \ArrayUtil::nonExistingKeys([
			'nomeComercial',
			'composicao',
			'laboratorio'
		], $this->params['medicamento']);

		$inexistentes = \ArrayUtil::nonExistingKeys([
			'id'
		], $this->params['medicamento']['laboratorio']);

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

			$medicamento = $this->colecaoMedicamento->getMedicamentoComLaboratorioEComposicao(
				\ParamUtil::value($this->params['medicamento'], 'nomeComercial'),
				\ParamUtil::value($this->params['medicamento'], 'composicao'),
				\ParamUtil::value($this->params['medicamento']['laboratorio'], 'id')
			)[0];

			$validade = new DataUtil(\ParamUtil::value($this->params, 'validade'));

			$medicamentoPessoal = new MedicamentoPessoal(
    			\ParamUtil::value($this->params, 'id'),
				\ParamUtil::value($this->params, 'validade'),
				\ParamUtil::value($this->params, 'quantidadeRecipiente'),
				\ParamUtil::value($this->params, 'quantidadeEstoque'),
				\ParamUtil::value($this->params, 'administracao'),
				\ParamUtil::value($this->params, 'unidadeTipo'),
				\ParamUtil::value($this->params, 'medicamentoForma'),
				$usuario,
				$medicamento
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

	function getAdministracoes()
	{

		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		try
		{
			$administracoes = Administracao::getConstants();

			return $this->geradoraResposta->ok(json_encode($administracoes), GeradoraResposta::TIPO_JSON);
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}

	function getMedicamentosFormas()
	{

		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		try
		{
			$medicamentosFormas = MedicamentoForma::getConstants();

			return $this->geradoraResposta->ok(json_encode($medicamentosFormas), GeradoraResposta::TIPO_JSON);
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}
}

?>