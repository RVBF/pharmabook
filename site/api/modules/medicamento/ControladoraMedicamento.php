<?php

/**
 * Controladora de Usuario
 *
 * @author	Rafael Vinicius Barros Ferreira
 */
class ControladoraMedicamento {

	private $geradoraResposta;
	private $params;
	private $colecao;
	private $pdoW;

	function __construct(GeradoraResposta $geradoraResposta,  $params)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->params = $params;
		$this->colecao = DI::instance()->create('ColecaoMedicamentoEmBDR');
	}

	function todos()
	{
		$dtr = new \DataTablesRequest($this->params);
		$contagem = 0;
		$objetos = [];
		$erro = null;
		try
		{
			$contagem = $this->colecao->contagem();
			$objetos = $this->colecao->todos($dtr->limit(), $dtr->offset());
		} 
		catch (\Exception $e)
		{
			$erro = $e->getMessage();
		}
		
		$conteudo = new \DataTablesResponse(
			$contagem,
			$contagem, //contagem dos objetos
			$objetos,
			$dtr->draw(),
			$erro
		);

		$this->geradoraResposta->ok($conteudo, GeradoraResposta::TIPO_JSON);
	}

	function pesquisarMedicamentos()
	{
		$dtr = new \DataTablesRequest( $this->params);
		$contagem = 0;
		$objetos = array();
		$erro = null;

		try
		{
			$contagem = $this->colecao->contagem();
			$objetos = $this->colecao->pesquisarMedicamentos($this->params);
		} 
		catch (\Exception $e)
		{
			$erro = $e->getMessage();
		}
			
		$conteudo = new \DataTablesResponse(
			$contagem,
			$contagem, //contagem dos objetos
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
			'nome',
			'email',
			'login',
			'senha',
			'telefone',
			'criacao',
			'atualizacao'
		], $this->params);

		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}
		
		$obj = new Usuario(
			\ParamUtil::value($this->params, 'nome'),
			\ParamUtil::value($this->params, 'email'),
			\ParamUtil::value($this->params, 'login'),
			\ParamUtil::value($this->params, 'senha'),
			\ParamUtil::value($this->params, 'telefone'),
			\ParamUtil::value($this->params, 'criacao'),
			\ParamUtil::value($this->params, 'atualizacao')
		);
		try
		{
			$this->colecao->adicionar($obj);
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
			'nome',
			'email',
			'login',
			'senha',
			'telefone',
			'criacao',
			'atualizacao'
		], $this->params);
		
		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		$obj = new Usuario(
			\ParamUtil::value($this->params, 'id'),
			\ParamUtil::value($this->params, 'nome'),
			\ParamUtil::value($this->params, 'email'),
			\ParamUtil::value($this->params, 'login'),
			\ParamUtil::value($this->params, 'senha'),
			\ParamUtil::value($this->params, 'telefone'),
			\ParamUtil::value($this->params, 'criacao'),
			\ParamUtil::value($this->params, 'atualizacao')
		);
		try
		{
			$this->colecao->atualizar($obj);
			return $this->geradoraResposta->semConteudo();
		} 
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}		
	}


}

?>