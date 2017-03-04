<?php

/**
 * Controladora de Possologia
 *
 * @author	Rafael Vinicius Barros Ferreira
 * @version	0.1
 */
class ControladoraPosologia {

	private $geradoraResposta;
	private $params;
	private $sessao;
	private $servicoLogin;
	private $colecaoPosologia;
	private $colecaoUsuario;
	private $colecaoMedicamentoPessoal;
	private $colecaoMedicamento;

	function __construct(GeradoraResposta $geradoraResposta,  $params, $sessaoUsuario)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->params = $params;
		$this->sessao = $sessaoUsuario;
		$this->servicoLogin = new ServicoLogin($this->sessao);
		$this->colecaoPosologia = DI::instance()->create('ColecaoPosologia');
		$this->colecaoUsuario = DI::instance()->create('colecaoUsuario');
		$this->colecaoMedicamentoPessoal = DI::instance()->create('ColecaoMedicamentoPessoal');
		$this->colecaoMedicamento = DI::instance()->create('ColecaoMedicamento');
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

			$this->colecaoPosologia->setDono($usuario);

			$contagem = $this->colecaoPosologia->contagem();

			$objetos = $this->colecaoPosologia->todos($dtr->limit(), $dtr->offset());

			$resposta = array();

			foreach ($objetos as $objeto)
			{
				$usuario = $this->colecaoUsuario->comId($objeto->getUsuario());
				if($usuario !=  null) $objeto->setUsuario($usuario);

				$medicamentoPessoal = $this->colecaoMedicamentoPessoal->comId($objeto->getMedicamentoPessoal());
				if($medicamentoPessoal !=  null)
				{
					$medicamentoPrecificado = $this->colecaoMedicamentoPrecificado->comId($medicamentoPessoal->getMedicamentoPrecificado());

					if($medicamentoPrecificado != null)
					{
						$medicamento = $this->colecaoMedicamento->comId($medicamentoPrecificado->getMedicamento());
						if($medicamento != null ) $medicamentoPrecificado->setMedicamento($medicamento);
					}

					$medicamentoPessoal->setMedicamentoPrecificado($medicamentoPrecificado);

					$objeto->setMedicamentoPessoal($medicamentoPessoal);
				}

				array_push($resposta, $objeto);
			}

			// Debuger::printr($resposta);
		}
		catch (\Exception $e ) {
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}

		$conteudo = new \DataTablesResponse(
			$contagem,
			$contagem, //count($objetos ),
			$objetos,
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
			'dose',
			'descricao',
			'periodicidade',
			'tipoPeriodicidade',
			'medicamentoPessoal'
		], $this->params);

		$inexistentes = \ArrayUtil::nonExistingKeys([
			'id'
		], $this->params['medicamentoPessoal']);

		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		try
		{
			$medicamentoPessoal = $this->colecaoMedicamentoPessoal->comId(\ParamUtil::value($this->params['medicamentoPessoal'], 'id'));
			if($medicamentoPessoal == null)	throw new Exception("Medicamento pessoal não encontrado");

			$posologia = new Posologia(
				\ParamUtil::value($this->params, 'id'),
				\ParamUtil::value($this->params, 'dose'),
				\ParamUtil::value($this->params, 'descricao'),
				\ParamUtil::value($this->params, 'periodicidade'),
				\ParamUtil::value($this->params, 'tipoPeriodicidade'),
				$medicamentoPessoal
			);

			$this->colecaoPosologia->adicionar($posologia);

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
			'dose',
			'descricao',
			'periodicidade',
			'tipoPeriodicidade',
			'medicamentoPessoal'
		], $this->params);

		$inexistentes = \ArrayUtil::nonExistingKeys([
			'id'
		], $this->params['medicamentoPessoal']);

		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		try
		{

			$medicamentoPessoal = $this->colecaoMedicamentoPessoal->comId(\ParamUtil::value($this->params['medicamentoPessoal'], 'id'));
			if($medicamentoPessoal == null)	throw new Exception("Medicamento pessoal não encontrado");

			$posologia = new Posologia(
				\ParamUtil::value($this->params, 'id'),
				\ParamUtil::value($this->params, 'dose'),
				\ParamUtil::value($this->params, 'descricao'),
				\ParamUtil::value($this->params, 'periodicidade'),
				\ParamUtil::value($this->params, 'tipoPeriodicidade'),
				$medicamentoPessoal
			);

			$this->colecaoPosologia->adicionar($posologia);

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

			$this->colecaoPosologia->remover($id);

			return $this->geradoraResposta->semConteudo();
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}

	function comId($id)
	{
		try
		{
			$posologia = $this->colecaoPosologia->comId($id);
			if($posologia != null)
			{
				return $this->geradoraResposta->ok(JSON::encode($posologia), GeradoraResposta::TIPO_JSON);
			}
			else
			{
				throw new Exception("Usuário não encontrado.");
			}
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}

	function getTempoUnidade()
	{
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		try
		{
			$tempoUnidades = TempoUnidade::tempoUnidadePlural();

			return $this->geradoraResposta->ok(json_encode($tempoUnidades), GeradoraResposta::TIPO_JSON);
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}
}

?>