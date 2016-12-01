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
		ColecaoUsuario $colecaoUsuario = null) 
	{
		$this->sessaoUsuario = $sessaoUsuario;
		$this->colecaoUsuario = $colecaoUsuario;
	}
	
	function login($login, $senha)
	{
		$usuario = null;
		$hashSenha = new HashSenha($senha);
		$hashSenha = $hashSenha->gerarHashDeSenhaComSaltEmMD5();		

		if($resultado = $this->colecaoUsuario->comEmail($login))
		{
			if(count($resultado) === 1)
			{
				$usuario = $resultado[0];

				if($usuario->getSenha() === $hashSenha || $usuario->getSenha() == $senha)
				{
					$this->sessaoUsuario->criar(
						$usuario->getId(),
						$login, 
						$usuario->getNome(),
						$ultimaRequisicao = time()
					);
				}
				else
				{
					throw new Exception("A senha digitada está incorreta.");
				}
			}
			else
			{
				throw new Exception("O e-mail inserido não corresponde a nenhuma conta cadastrada no sistema.");
			}
		}
		elseif($resultado = $this->colecaoUsuario->comLogin($login))
		{
			if(count($resultado) === 1)
			{
				$usuario = $resultado[0];

				if($usuario->getSenha() === $hashSenha || $usuario->getSenha() == $senha)
				{
					$this->sessaoUsuario->criar(
						$usuario->getId(),
						$login, 
						$usuario->getNome(),
						$ultimaRequisicao = time()
					);
				}
				else
				{
					throw new Exception("A senha digitada está incorreta.");
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
		$this->sessaoUsuario->atualizarUltimaRequisicao();
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

	function getIdUsuario()
	{
		return $this->sessaoUsuario->idUsuario();
	}
}
?>