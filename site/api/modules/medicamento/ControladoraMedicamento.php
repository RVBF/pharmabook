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
	private $colecaoMedicamento;
	private $colecaoLaboratorio;

	function __construct(GeradoraResposta $geradoraResposta, $params, $sessaoUsuario)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->sessao = $sessaoUsuario;
		$this->servicoLogin = new ServicoLogin($this->sessao);
		$this->params = $params;
		$this->colecaoMedicamento = DI::instance()->create('ColecaoMedicamentoEmBDR');
		$this->colecaoLaboratorio = DI::instance()->create('ColecaoLaboratorio');
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
			$contagem = $this->colecaoMedicamento->contagem();
			$objetos = $this->colecaoMedicamento->todos($dtr->limit(), $dtr->offset());
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

		$this->geradoraResposta->ok($conteudo, GeradoraResposta::TIPO_JSON);
	}

	function comId($id)
	{
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		try
		{
			$obj = $this->colecaoMedicamento->comId($id);

			if($obj == null)
			{
				throw new Exception("Medicamento não encontrado.");
			}

			return $this->geradoraResposta->ok(JSON::encode($obj), GeradoraResposta::TIPO_JSON);
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}

	function pesquisarMedicamentoParaAutoComplete()
	{
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		$inexistentes = \ArrayUtil::nonExistingKeys([
			'medicamento'
		], $this->params);

		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		try
		{
			$resultados = $this->colecaoMedicamento->pesquisarMedicamentoParaAutoComplete(\ParamUtil::value($this->params, 'medicamento'));

			$conteudo = array();

			foreach ($resultados as $resultado)
			{
				array_push($conteudo, [
					'label' => $resultado['nome_comercial']. " " .  $resultado['composicao'],
					'value' => $resultado['nome_comercial']. " " .  $resultado['composicao'],
					'nomeComercial' => $resultado['nome_comercial'],
					'composicao' => $resultado['composicao']
				]);
			}
		}
		catch (\Exception $e )
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}

		return $this->geradoraResposta->resposta(json_encode($conteudo), GeradoraResposta::OK, GeradoraResposta::TIPO_JSON);
	}
}

?>