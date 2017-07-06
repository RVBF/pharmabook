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
	private $colecaoEndereco;
	private $colecaoMedicamento;
	private $colecaoMedicamentoPrecificado;
	private $colecaoLaboratorio;
	private $colecaoClasseTerapeutica;
	private $colecaoPrincipioAtivo;

	function __construct(GeradoraResposta $geradoraResposta,  $params, $sessaoUsuario)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->params = $params;
		$this->sessao = $sessaoUsuario;
		$this->servicoLogin = new ServicoLogin($this->sessao);
		$this->colecaoUsuario = DI::instance()->create('ColecaoUsuario');
		$this->colecaoMedicamento = DI::instance()->create('ColecaoMedicamento');
		$this->colecaoFarmacia = DI::instance()->create('ColecaoFarmacia');
		$this->colecaoEndereco = DI::instance()->create('ColecaoEndereco');
		$this->colecaoMedicamentoPrecificado = DI::instance()->create('ColecaoMedicamentoPrecificado');
		$this->colecaoLaboratorio = DI::instance()->create('ColecaoLaboratorio');
		$this->colecaoClasseTerapeutica = DI::instance()->create('ColecaoClasseTerapeutica');
		$this->colecaoPrincipioAtivo = DI::instance()->create('ColecaoPrincipioAtivo');
	}

	function todos()
	{
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		$dtr = new \DataTablesRequest($this->params);
		$contagem = 0;
		$objetos = [];
		$erro = null;
		try
		{
			$contagem = $this->colecaoMedicamentoPrecificado->contagem();

			$objetos = $this->colecaoMedicamentoPrecificado->todos($dtr->limit(), $dtr->offset());

			$resposta = [];

			foreach ($objetos as $objeto)
			{
				$objeto->setDataCriacao($objeto->getDataCriacao()->toBrazilianString());
				$objeto->setDataAtualizacao($objeto->getDataAtualizacao()->toBrazilianString());


				$farmacia = $this->colecaoFarmacia->comId($objeto->getFarmacia());
				if($farmacia !=  null)
				{
					$endereco = $this->colecaoEndereco->comId($farmacia->getEndereco());
					$farmacia->setEndereco($endereco);
					$objeto->setFarmacia($farmacia);
				}

				$medicamento = $this->colecaoMedicamento->comId($objeto->getMedicamento());
				if($medicamento !=  null) 	$objeto->setMedicamento($medicamento);

				$laboratorio = $this->colecaoLaboratorio->comId($objeto->getMedicamento()->getLaboratorio());
				if($laboratorio != null ) $objeto->getMedicamento()->setLaboratorio($laboratorio);

				$classeTerapeutica = $this->colecaoClasseTerapeutica->comId($objeto->getMedicamento()->getClasseTerapeutica());
				if($classeTerapeutica != null ) $objeto->getMedicamento()->setClasseTerapeutica($classeTerapeutica);

				$principioAtivo = $this->colecaoPrincipioAtivo->comId($objeto->getMedicamento()->getPrincipioAtivo());
				if($principioAtivo != null ) $objeto->getMedicamento()->setPrincipioAtivo($principioAtivo);

				$criador = $this->colecaoUsuario->comId($objeto->getCriador());
				if($criador !=  null) $objeto->setCriador($criador);

				$atualizador = $this->colecaoUsuario->comId($objeto->getAtualizador());
				if($atualizador !=  null) $objeto->setAtualizador($atualizador);

				$resposta[] = $objeto;
			}
		}
		catch (\Exception $e )
		{
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

	function timeline()
	{
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		$inexistentes = \ArrayUtil::nonExistingKeys([
			'limit',
			'offset',
			'quantidade'
		], $this->params);

		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes parâmetros não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		try
		{
			$contagem = $this->colecaoMedicamentoPrecificado->contagem();

			$objetos = $this->colecaoMedicamentoPrecificado->timeline(	\ParamUtil::value($this->params, 'limit'), ParamUtil::value($this->params, 'offset'));

			$resposta = [];

			foreach ($objetos as $objeto)
			{
				// $objeto->setDataCriacao($objeto->getDataCriacao()->toBrazilianString());
				// $objeto->setDataAtualizacao($objeto->getDataAtualizacao()->toBrazilianString());

				$farmacia = $this->colecaoFarmacia->comId($objeto->getFarmacia());
				if($farmacia !=  null)
				{
					$endereco = $this->colecaoEndereco->comId($farmacia->getEndereco());
					$farmacia->setEndereco($endereco);
					$objeto->setFarmacia($farmacia);
				}

				$medicamento = $this->colecaoMedicamento->comId($objeto->getMedicamento());
				if($medicamento !=  null) 	$objeto->setMedicamento($medicamento);

				$laboratorio = $this->colecaoLaboratorio->comId($objeto->getMedicamento()->getLaboratorio());
				if($laboratorio != null ) $objeto->getMedicamento()->setLaboratorio($laboratorio);

				$classeTerapeutica = $this->colecaoClasseTerapeutica->comId($objeto->getMedicamento()->getClasseTerapeutica());
				if($classeTerapeutica != null ) $objeto->getMedicamento()->setClasseTerapeutica($classeTerapeutica);

				$principioAtivo = $this->colecaoPrincipioAtivo->comId($objeto->getMedicamento()->getPrincipioAtivo());
				if($principioAtivo != null ) $objeto->getMedicamento()->setPrincipioAtivo($principioAtivo);

				$criador = $this->colecaoUsuario->comId($objeto->getCriador());
				if($criador !=  null) $objeto->setCriador($criador);

				$atualizador = $this->colecaoUsuario->comId($objeto->getAtualizador());
				if($atualizador !=  null) $objeto->setAtualizador($atualizador);

				$resposta[] = $objeto;
			}
		}
		catch (\Exception $e )
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
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		$inexistentes = \ArrayUtil::nonExistingKeys([
			'id',
			'preco',
			'medicamento'
		], $this->params);

		$inexistentes += \ArrayUtil::nonExistingKeys([
			'id'
		], $this->params['farmacia']);

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
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentesb);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		try
		{
			$criador = $this->colecaoUsuario->comId($this->servicoLogin->getIdUsuario());

			if($criador == null)
			{
				throw new Exception("Usuário não encontrado");
			}

			$medicamento = $this->colecaoMedicamento->getMedicamentoComLaboratorioEComposicao(
				\ParamUtil::value($this->params['medicamento'], 'nomeComercial'),
				\ParamUtil::value($this->params['medicamento'], 'composicao'),
				\ParamUtil::value($this->params['medicamento']['laboratorio'], 'id')
			)[0];

			$objFarmacia = $this->colecaoFarmacia->comId(\ParamUtil::value($this->params['farmacia'], 'id'));

			$medicamentoPrecificado = new MedicamentoPrecificado(
				\ParamUtil::value($this->params, 'id'),
				floatval(\ParamUtil::value($this->params, 'preco')),
				$objFarmacia,
				$medicamento,
				$criador,
				$criador,
				$this->params['imagem']
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
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		$inexistentes = \ArrayUtil::nonExistingKeys([
			'id',
			'preco',
			'medicamento'
		], $this->params);

		$inexistentes += \ArrayUtil::nonExistingKeys([
			'id'
		], $this->params['farmacia']);

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
			$atualizador = $this->colecaoUsuario->comId($this->servicoLogin->getIdUsuario());

			if($atualizador == null)
			{
				throw new Exception("Usuário não encontrado");
			}

			$medicamento = $this->colecaoMedicamento->getMedicamentoComLaboratorioEComposicao(
				\ParamUtil::value($this->params['medicamento'], 'nomeComercial'),
				\ParamUtil::value($this->params['medicamento'], 'composicao'),
				\ParamUtil::value($this->params['medicamento']['laboratorio'], 'id')
			)[0];

			$objFarmacia = $this->colecaoFarmacia->comId(\ParamUtil::value($this->params['farmacia'], 'id'));

			$medicamentoPrecificado = new MedicamentoPrecificado(
				\ParamUtil::value($this->params, 'id'),
				floatval(\ParamUtil::value($this->params, 'preco')),
				$objFarmacia,
				$medicamento,
				null,
				$atualizador
			);

			$this->colecaoMedicamentoPrecificado->atualizar($medicamentoPrecificado);

			return $this->geradoraResposta->semConteudo();
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}

	function comId()
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

			$medicamentoPrecificado = $this->colecaoMedicamentoPrecificado->comId($id);

			if(!empty($medicamentoPrecificado))
			{
				$medicamentoPrecificado->setDataCriacao($medicamentoPrecificado->getDataCriacao()->toBrazilianString());
				$medicamentoPrecificado->setDataAtualizacao($medicamentoPrecificado->getDataAtualizacao()->toBrazilianString());

				$farmacia = $this->colecaoFarmacia->comId($medicamentoPrecificado->getFarmacia());
				if($farmacia !=  null) $medicamentoPrecificado->setFarmacia($farmacia);

				$medicamento = $this->colecaoMedicamento->comId($medicamentoPrecificado->getMedicamento());
				if($medicamento !=  null) 	$medicamentoPrecificado->setMedicamento($medicamento);

				$laboratorio = $this->colecaoLaboratorio->comId($medicamentoPrecificado->getMedicamento()->getLaboratorio());
				if($laboratorio != null ) $medicamentoPrecificado->getMedicamento()->setLaboratorio($laboratorio);

				$classeTerapeutica = $this->colecaoClasseTerapeutica->comId($medicamentoPrecificado->getMedicamento()->getClasseTerapeutica());
				if($classeTerapeutica != null ) $medicamentoPrecificado->getMedicamento()->setClasseTerapeutica($classeTerapeutica);

				$principioAtivo = $this->colecaoPrincipioAtivo->comId($medicamentoPrecificado->getMedicamento()->getPrincipioAtivo());
				if($principioAtivo != null ) $medicamentoPrecificado->getMedicamento()->setPrincipioAtivo($principioAtivo);

				$criador = $this->colecaoUsuario->comId($medicamentoPrecificado->getCriador());
				if($criador !=  null) $medicamentoPrecificado->setCriador($criador);

				$atualizador = $this->colecaoUsuario->comId($medicamentoPrecificado->getAtualizador());
				if($atualizador !=  null) $medicamentoPrecificado->setAtualizador($atualizador);
			}
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}

		return $this->geradoraResposta->resposta(JSON::encode($medicamentoPrecificado), GeradoraResposta::OK, GeradoraResposta::TIPO_JSON);
	}
}

?>