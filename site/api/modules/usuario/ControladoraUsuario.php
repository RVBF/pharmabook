<?php

/**
 * Controladora de Usuario
 *
 * @author	Rafael Vinicius Barros Ferreira
 */
class ControladoraUsuario {

	private $geradoraResposta;
	private $params;
	private $colecaoUsuario;
	private $colecaoEstoque;
	private $pdoW;

	function __construct(GeradoraResposta $geradoraResposta,  $params)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->params = $params;

		$this->colecaoUsuario = DI::instance()->create('ColecaoUsuario');
		$this->colecaoEstoque = DI::instance()->create('ColecaoEstoque');
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
			'sobrenome',
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

		$dataCriacao = new DataUtil(\ParamUtil::value($this->params, 'dataCriacao'));
		$dataAtualizacao = new DataUtil(\ParamUtil::value($this->params, 'dataAtualizacao'));
		
		$objUsuario = new Usuario(
			\ParamUtil::value($this->params, 'id'),
			\ParamUtil::value($this->params, 'nome'),
			\ParamUtil::value($this->params, 'sobrenome'),
			\ParamUtil::value($this->params, 'email'),
			\ParamUtil::value($this->params, 'login'),
			\ParamUtil::value($this->params, 'senha'),
			$dataCriacao->formatarDataParaBanco(),
			$dataAtualizacao->formatarDataParaBanco()
		);

		$objEstoque  = new Estoque(0, $objUsuario);

		try
		{
			$this->colecaoUsuario->adicionar($objUsuario);

			$this->colecaoEstoque->adicionar($objEstoque);

		} 
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}		
		
		return $this->geradoraResposta->resposta(JSON::encode($objUsuario), GeradoraResposta::CRIADO, GeradoraResposta::TIPO_JSON);
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
			$this->colecaoUsuario->atualizar($obj);
			return $this->geradoraResposta->semConteudo();
		} 
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}		
	}
}

?>