<?php

/**
 * Controladora de login
 *
 * @author	Rafael Vinicicus Barros Ferreira
 */

class ControladoraLogin {

	private $geradoraResposta;
	private $params;
	private $servico;
	private $usarioValidate;
	private $hashSenha;
	private $colecaoUsuario;
	private $sessao;

	function __construct(GeradoraResposta $geradoraResposta, $params, Sessao $sessao)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->params = $params;
		$this->sessao = $sessao;
		$this->colecaoUsuario = DI::instance()->create('ColecaoUsuario');
		$this->servico = new ServicoLogin($this->sessao, $this->colecaoUsuario);
	}

	/**
	 *	Método que pega os parâmetros login e senha da requisição
	 * e os utiliza no método logar do serviço do usuario.
	 *
	 * @return geradoraResposta->erro 			Caso o array de parâmetros esteja vazio.
	 * @return geradoraResposta->semConteudo 	Caso o login seja efetuado corretamente.
	 * @throws Exception
	 */

	function logar()
	{

		$inexistentes = \ArrayUtil::nonExistingKeys([ 'identificador', 'senha' ], $this->params);

		if(count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		try
		{
			$usuario = $this->servico->login(
				\ParamUtil::value($this->params, 'identificador'),
				\ParamUtil::value($this->params, 'senha')
			);

			$conteudo = ['id' => $usuario->getId(), 'nome'=> $usuario->getNome()];

			return $this->geradoraResposta->ok(json_encode($conteudo), GeradoraResposta::TIPO_JSON);
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}
	/**
	 *	Método de Logout  que utiliza o método sair do serviço.
	 *
	 * @throws Exception
	 */
	function sair()
	{
		try
		{
			if($this->servico->estaLogado())
			{
				$this->servico->logout();
			}

			return $this->geradoraResposta->semConteudo();
		}
		catch(\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}
}
