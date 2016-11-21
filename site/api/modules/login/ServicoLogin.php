<?php
/**
 *  Serviço de login.
 *  
 *  @author	Rafael Vinicius Barros Ferreira
 */

class ServicoLogin {
	
	private $inatividadeEmMinutos = 15;
	
	private $sessaoUsuario;
	private $colecaoUsuario;
	
	function __construct(
		Sessao $sessaoUsuario,
		ColecaoUsuario $colecaoUsuario) 
	{
		$this->sessaoUsuario = $sessaoUsuario;
		$this->colecaoUsuario = $colecaoUsuario;
	}
	
	function login($login, $senha)
	{
		$hashSenha = new HashSenha($senha);
		$hashSenha = $hashSenha->gerarHashDeSenhaComSaltEmMD5($senha);
		$usuario = (count($this->colecaoUsuario->comEmail($login)) > 0) ? $this->colecaoUsuario->comEmail($login) : $this->colecaoUsuario->comLogin($login);
		
		$validacaoUsuario = new UsuarioValidate($usuario);

		if($validacaoUsuario->validarFormatoDeEmail())
		{
			if(count($usuario) === 1)
			{
				if($usuario->getSenha() === $hashSenha)
				{
					$this->sessaoUsuario->criar(
						$pessoa->id,
						$login, 
						$pessoa->nome,
						$ultimaRequisicao = time()
					);
				}
				else
				{
					throw new Exception("Senhas não correspondem.");
				}
			}
			else
			{
				throw new Exception("O e-mail inserido não corresponde a nenhuma conta cadastrada no sistema.");
			}
		}
		elseif($validacaoUsuario->validarFormatoLogin())
		{
			$usuario = $this->comLogin($login);

			if(count($login) == 1)
			{
				if($usuario->getSenha() === $hashSenha)
				{
					$this->sessaoUsuario->criar(
						$pessoa->id,
						$login, 
						$pessoa->nome,
						$ultimaRequisicao = time()
					);
				}
				else
				{
					throw new Exception("Senhas não correspondem.");
				}
			}
			else
			{
				throw new Exception("O login inserido não corresponde a nenhuma conta cadastrada no sistema.");
			}
		}
			
		return $usuario;
	}
	
	/**
	 *  Realiza o logout de um usuário.
	 */
	function logout()
	{
		$this->sessaoUsuario->destruir();
	}
	
	/**
	 *  Realiza o logout se o usuário estiver logado e inativo.
	 *  Retorna true se realizou o logout.
	 *  
	 *  @return bool
	 */
	function sairPorInatividade()
	{
		$estado = $this->estaLogado() && $this->estaInativo();
		
		if($estado)
		{
			$this->logout();
		}

		return $estado;
	}
	
	/**
	 *  Registra atividade do usuário, para que não seja considerado inativo.
	 */
	function atualizaAtividadeUsuario() {
		$this->sessao->atualizarUltimaRequisicao();
	}	
	
	/**
	 *	Retorna true se o tempo de inatividade for maior ou igual ao limite.  
	 *
	 *  @return bool
	 */
	function estaInativo() {
		$decorrido = time() - $this->sessaoUsuario->ultimaRequisicao();
		return $decorrido >= ( $this->inatividadeEmMinutos * 60 );
	}
	
	/**
	 *  Retorna true se o usuário estiver logado.
	 */
	function estaLogado() {
		return $this->sessaoUsuario->existe();
	}
}
?>