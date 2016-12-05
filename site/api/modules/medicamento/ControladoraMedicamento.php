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
			$contagem = $this->colecao->contagem();
			$objetos = $this->colecao->todos($dtr->limit(), $dtr->offset());
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
			$obj = $this->colecao->comId($id);

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

	function autoCompleteMedicamento()
	{
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		$inexistentes = \ArrayUtil::nonExistingKeys([
			'medicamento',
			'laboratorioId'
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
				\ParamUtil::value($this->params, 'laboratorioId')
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
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}

		return $this->geradoraResposta->resposta(json_encode($conteudo), GeradoraResposta::OK, GeradoraResposta::TIPO_JSON);
	}

	function getMedicamentoDoSistema()
	{
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		$inexistentes = \ArrayUtil::nonExistingKeys([
			'medicamento',
			'laboratorioId',
		], $this->params);

		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		try 
		{
			$resultado = $this->colecao->getMedicamentoDoSistema(
				\ParamUtil::value($this->params, 'medicamento'),
				\ParamUtil::value($this->params, 'laboratorioId')
			);
			
			return $this->geradoraResposta->resposta(JSON::encode($resultado), GeradoraResposta::OK, GeradoraResposta::TIPO_JSON);
		} 
		catch (\Exception $e )
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}
}

?>