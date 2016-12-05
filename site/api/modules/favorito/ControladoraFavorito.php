<?php

/**
 * Controladora de Favorito
 *
 * @author	Rafael Vinicius Barros Ferreira
 * @version	0.1
 */
class ControladoraFavorito {

	private $geradoraResposta;
	private $params;
	private $colecaoFavorito;
	private $colecaoMedicamentoPrecificado;
	private $colecaoUsuario;

	function __construct(GeradoraResposta $geradoraResposta,  $params, $sessaoUsuario)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->params = $params;
		$this->sessao = $sessaoUsuario;
		$this->servicoLogin = new ServicoLogin($this->sessao);
		$this->colecaoFavorito = DI::instance()->create('ColecaoFavorito');
		$this->colecaoMedicamentoPrecificado = DI::instance()->create('ColecaoMedicamentoPrecificado');
		$this->colecaoUsuario = DI::instance()->create('ColecaoUsuario');
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

			$this->colecaoFavorito->setDono($usuario);

			$contagem = $this->colecaoFavorito->contagem();

			$objetos = $this->colecaoFavorito->todos($dtr->limit(), $dtr->offset());

			$resposta = array();

			foreach ($objetos as $objeto)
			{
				$medicamentoPrecificado = $this->colecaoMedicamentoPrecificado->comId($objeto->getMedicamentoPrecificado());
				if(!empty($medicamentoPrecificado))  $objeto->setMedicamentoPrecificado($medicamentoPrecificado);

				$usuario = $this->colecaoUsuario->comId($objeto->getUsuario());
				if(!empty($usuario))  $objeto->setUsuario($usuario);

				array_push($resposta, $objeto);
 			}
		} 
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
		
		$conteudo = new \DataTablesResponse(
			$contagem,
			$contagem, //contagem dos objetos
			$resposta,
			$dtr->draw(),
			$erro
		);

		$this->geradoraResposta->ok($conteudo, GeradoraResposta::TIPO_JSON);
	}

	function remover()
	{
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}
		
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
		if($this->servicoLogin->verificarSeUsuarioEstaLogado()  == false)
		{
			return $this->geradoraResposta->naoAutorizado('Erro ao acessar página.', GeradoraResposta::TIPO_TEXTO);
		}

		$inexistentes = \ArrayUtil::nonExistingKeys([
			'id',
			'medicamentoPrecificado'
		], $this->params);

		if (count($inexistentes) > 0)
		{
			$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
			return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
		}

		$usuario = $this->colecaoUsuario->comId($this->servicoLogin->getIdUsuario());
		
		if($usuario == null)
		{
			throw new Exception("Usuário não encontrado");
		}

		$medicamentoPrecificado = $this->colecaoMedicamentoPrecificado->comId(\ParamUtil::value($this->params['medicamentoPrecificado'], 'id'));
		
		if($medicamentoPrecificado == null or $medicamentoPrecificado == '')
		{
			throw new Exception("Medicamento precificado nãoe encontrado.");
		}
	
		$favorito = new Favorito(
			\ParamUtil::value($this->params,'id'),
			$medicamentoPrecificado,
			$usuario
		);
		
		try
		{
			$this->colecaoFavorito->adicionar($favorito);

			return $this->geradoraResposta->semConteudo();
		} 
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}		
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
}

?>