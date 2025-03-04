<?php
use Carbon\Carbon;
use phputil\TDateTime;
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
		$this->colecaoMedicamentoPessoal = DI::instance()->create('ColecaoMedicamentoPessoal');
		$this->colecaoLaboratorio = DI::instance()->create('ColecaoLaboratorio');
		$this->colecaoClasseTerapeutica = DI::instance()->create('ColecaoClasseTerapeutica');
		$this->colecaoPrincipioAtivo = DI::instance()->create('ColecaoPrincipioAtivo');
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
		$objetos = [];
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

			$resposta = [];

			foreach ($objetos as $objeto)
			{
				$objeto->setValidade($objeto->getValidade()->toBrazilianDateString());
				$objeto->setDataCriacao($objeto->getDataCriacao()->toBrazilianString());
				$objeto->setDataAtualizacao($objeto->getDataAtualizacao()->toBrazilianString());

				$medicamento = $this->colecaoMedicamento->comId($objeto->getMedicamento());
				if($medicamento !=  null) $objeto->setMedicamento($medicamento);

				$laboratorio = $this->colecaoLaboratorio->comId($objeto->getMedicamento()->getLaboratorio());
				if($laboratorio != null ) $objeto->getMedicamento()->setLaboratorio($laboratorio);

				$classeTerapeutica = $this->colecaoClasseTerapeutica->comId($objeto->getMedicamento()->getClasseTerapeutica());
				if($classeTerapeutica != null ) $objeto->getMedicamento()->setClasseTerapeutica($classeTerapeutica);

				$principioAtivo = $this->colecaoPrincipioAtivo->comId($objeto->getMedicamento()->getPrincipioAtivo());
				if($principioAtivo != null ) $objeto->getMedicamento()->setPrincipioAtivo($principioAtivo);

				$resposta[] = $objeto;
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
			'tipoUnidade',
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

			$this->colecaoMedicamentoPessoal->setDono($usuario);

			$medicamento = $this->colecaoMedicamento->getMedicamentoComLaboratorioEComposicao(
				\ParamUtil::value($this->params['medicamento'], 'nomeComercial'),
				\ParamUtil::value($this->params['medicamento'], 'composicao'),
				\ParamUtil::value($this->params['medicamento']['laboratorio'], 'id')
			)[0];

			$medicamentoPessoal = new MedicamentoPessoal(
    			\ParamUtil::value($this->params, 'id'),
				\ParamUtil::value($this->params, 'validade'),
				\ParamUtil::value($this->params, 'quantidadeRecipiente'),
				\ParamUtil::value($this->params, 'quantidadeEstoque'),
				\ParamUtil::value($this->params, 'administracao'),
				\ParamUtil::value($this->params, 'tipoUnidade'),
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
			'quantidadeRecipiente',
			'quantidadeEstoque',
			'administracao',
			'tipoUnidade',
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

			if($usuario == null) throw new Exception("Usuário não encontrado");

			$this->colecaoMedicamentoPessoal->setDono($usuario);

			$medicamentoPessoal = new MedicamentoPessoal(
    			\ParamUtil::value($this->params, 'id'),
				\ParamUtil::value($this->params, 'validade'),
				\ParamUtil::value($this->params, 'quantidadeRecipiente'),
				\ParamUtil::value($this->params, 'quantidadeEstoque'),
				\ParamUtil::value($this->params, 'administracao'),
				\ParamUtil::value($this->params, 'tipoUnidade'),
				\ParamUtil::value($this->params, 'medicamentoForma')
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

			$medicamentoPessoal = $this->colecaoMedicamentoPessoal->comId($id);

			if(!empty($medicamentoPessoal))
			{
				$medicamentoPessoal->setValidade($medicamentoPessoal->getValidade()->toBrazilianDateString());
				$medicamentoPessoal->setDataCriacao($medicamentoPessoal->getDataCriacao()->toBrazilianString());
				$medicamentoPessoal->setDataAtualizacao($medicamentoPessoal->getDataAtualizacao()->toBrazilianString());

				$medicamento = $this->colecaoMedicamento->comId($medicamentoPessoal->getMedicamento());
				if($medicamento !=  null) $medicamentoPessoal->setMedicamento($medicamento);

				$laboratorio = $this->colecaoLaboratorio->comId($medicamentoPessoal->getMedicamento()->getLaboratorio());
				if($laboratorio != null ) $medicamentoPessoal->getMedicamento()->setLaboratorio($laboratorio);

				$classeTerapeutica = $this->colecaoClasseTerapeutica->comId($medicamentoPessoal->getMedicamento()->getClasseTerapeutica());
				if($classeTerapeutica != null ) $medicamentoPessoal->getMedicamento()->setClasseTerapeutica($classeTerapeutica);

				$principioAtivo = $this->colecaoPrincipioAtivo->comId($medicamentoPessoal->getMedicamento()->getPrincipioAtivo());
				if($principioAtivo != null ) $medicamentoPessoal->getMedicamento()->setPrincipioAtivo($principioAtivo);
			}
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}

		return $this->geradoraResposta->resposta(JSON::encode($medicamentoPessoal), GeradoraResposta::OK, GeradoraResposta::TIPO_JSON);
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

	function getUnidadesInteiras()
	{
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		try
		{
			$unidades = UnidadeTipo::unidadesInteiras();

			return $this->geradoraResposta->ok(json_encode($unidades), GeradoraResposta::TIPO_JSON);
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}

	function getUnidadesSolidas()
	{
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		try
		{
			$unidades = UnidadeTipo::unidadesSolidas();

			return $this->geradoraResposta->ok(json_encode($unidades), GeradoraResposta::TIPO_JSON);
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}

	function getUnidadesLiquidas()
	{
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		try
		{
			$unidades = UnidadeTipo::unidadesLiquidas();

			return $this->geradoraResposta->ok(json_encode($unidades), GeradoraResposta::TIPO_JSON);
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