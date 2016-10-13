<?php

/**
 * Controladora de Usuario
 *
 * @author	Rafael Vinicius Barros Ferreira
 */
class ControladoraUsuario {

	private $geradoraResposta;
	private $params;
	private $colecao;
	private $servico;
	private $servicoEstoque;
	private $pdoW;

	function __construct(GeradoraResposta $geradoraResposta,  $params)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->params = $params;

		$this->colecao = DI::instance()->create('ColecaoUsuarioEmBDR');
		$this->servico = DI::instance()->create('ServicoUsuario');
		// $this->servico = DI::instance()->create('ServicoEstoque');
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
			'id',
			'nome',
			'email',
			'login',
			'senha',
			'confirmacaoSenha'
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
			\ParamUtil::value($this->params, 'senha')
			// \ParamUtil::value($this->params, 'dataCriacao'),
			// \ParamUtil::value($this->params, 'dataAtualizacao')
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
			'dataCriacao',
			'dataAtualizacao'
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
			\ParamUtil::value($this->params, 'dataCriacao'),
			\ParamUtil::value($this->params, 'dataAtualizacao')
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