<?php

/**
 * Controladora de Farmácia
 *
 * @author	Rafael Vinicius Barros Ferreira
 *  @version	0.1
 */
class ControladoraFarmacia {

	private $geradoraResposta;
	private $params;
	private $colecaoFarmacia;
	private $colecaoEndereco;
	private $servicoLogin;
	private $sessao;
	private $colecaoEnderecoEntidade;
	private $colecaoEndereco;
	private $colecaoCidade;
	private $colecaoBairro;
	private $colecaoEstado;
	private $colecaoPais;

	function __construct(GeradoraResposta $geradoraResposta,  $params, $sessao)
	{
		$this->geradoraResposta = $geradoraResposta;
		$this->params = $params;
		$this->sessao = $sessao;
		$this->servicoLogin = new ServicoLogin($this->sessao);
		$this->colecaoFarmacia = DI::instance()->create('ColecaoFarmacia');
		$this->colecaoEnderecoEntidade = DI::instance()->create('ColecaoEnderecoEntidade');
		$this->colecaoEndereco = DI::instance()->create('ColecaoEndereco');
		$this->colecaoCidade = DI::instance()->create('ColecaoCidade');
		$this->colecaoBairro = DI::instance()->create('ColecaoBairro');
		$this->colecaoEstado = DI::instance()->create('ColecaoEstado');
		$this->colecaoPais = DI::instance()->create('ColecaoPais');
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

		$dtr = new \DataTablesRequest($this->params);
		$contagem = 0;
		$objetos = [];
		$erro = null;

		try
		{
			$contagem = $this->colecaoFarmacia->contagem();

			$objetos = $this->colecaoFarmacia->todos($dtr->limit(), $dtr->offset());

			$resposta = [];

			foreach ($objetos as $objeto)
			{
				$objeto->setDataCriacao($objeto->getDataCriacao()->toBrazilianString());
				$objeto->setDataAtualizacao($objeto->getDataAtualizacao()->toBrazilianString());

				$endereco = $this->colecaoEndereco->comId($objeto->getEndereco());
				if($endereco !=  null)
				{
					$endereco->setDataCriacao($endereco->getDataCriacao()->toBrazilianString());
					$endereco->setDataAtualizacao($endereco->getDataAtualizacao()->toBrazilianString());

					$objeto->setEndereco($endereco);
				}

				array_push($resposta, $objeto);
			}
		}
		catch (\Exception $e ) {
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}

		$conteudo = new \DataTablesResponse(
			$contagem,
			$contagem, //count($objetos ),
			$resposta,
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

		try
		{
			$inexistentes = \ArrayUtil::nonExistingKeys([
				'id',
				'cep',
				'logradouro',
				'numero',
				'complemento',
				'referencia',
				'bairro',
				'cidade',
				'estado',
				'latitude',
				'longitude',
				'codigoIbge'
			], $this->params);

			if (count($inexistentes) > 0)
			{
				$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
				return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
			}

			if(empty($endereco = $this->colecaoEnderecoSistema->comCep(\ParamUtil::value($this->params['endereco']), 'cep')))
			{

				$cidade = new Cidade(
					\ParamUtil::value($this->params['endereco']), 'id'),
					\ParamUtil::value($this->params['endereco']), 'nome'),
					\ParamUtil::value($this->params['endereco']), 'estado')
				);

				$this->colecaoCidade->adicionar($cidade);

				$bairro = new Bairro(
					\ParamUtil::value($this->params['endereco']), 'id'),
					\ParamUtil::value($this->params['endereco']), 'nome'),
					$cidade
				)

				$this->colecaoBairro->adicionar($bairro);

				$endereco = new Endereco(
					\ParamUtil::value($this->params['endereco'], 'id'),
					\ParamUtil::value($this->params['endereco'], 'cep'),
					\ParamUtil::value($this->params['endereco'], 'logradouro'),
					\ParamUtil::value($this->params['endereco'], 'latitude'),
					\ParamUtil::value($this->params['endereco'], 'longitude'),
					\ParamUtil::value($this->params['endereco'], 'codigoIbge'),
					$bairro,
				);

				$this->colecaoEndereco->adicionar($endereco);
			}


			$enderecoEntidade = new EnderecoEntidade(
				\ParamUtil::value($this->params['endereco'], 'id'),
				\ParamUtil::value($this->params['endereco'], 'numero'),
				\ParamUtil::value($this->params['endereco'], 'complemento'),
				\ParamUtil::value($this->params['endereco'], 'referencia'),
				$endereco
			);

			$this->colecaoEnderecoEntidade->adicionar($enderecoEntidade);

			$objFarmacia = new Farmacia(
				\ParamUtil::value($this->params,'id'),
				\ParamUtil::value($this->params,'nome'),
				\ParamUtil::value($this->params,'telefone'),
				$enderecoEntidade
			);

			$this->colecaoFarmacia->adicionar($objFarmacia);

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

		try
		{
			$inexistentes = \ArrayUtil::nonExistingKeys([
				'id',
				'cep',
				'logradouro',
				'numero',
				'complemento',
				'referencia',
				'bairro',
				'cidade',
				'estado',
				'latitude',
				'longitude',
				'codigoIbge'
			], $this->params);

			if (count($inexistentes) > 0)
			{
				$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
				return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
			}

			if (count($inexistentes) > 0)
			{
				$msg = 'Os seguintes campos não foram enviados: ' . implode(', ', $inexistentes);
				return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
			}

			$cidade = new Cidade(
				\ParamUtil::value($this->params['endereco']['bairro']['cidade']), 'id'),
				\ParamUtil::value($this->params['endereco']['bairro']['cidade']), 'nome'),
				\ParamUtil::value($this->params['endereco']['bairro']['cidade']), 'estado')
			);

			$this->colecaoCidade->atualizar($cidade);

			$bairro = new Bairro(
				\ParamUtil::value($this->params['endereco']['bairro']), 'id'),
				\ParamUtil::value($this->params['endereco']['bairro']), 'nome'),
				$cidade
			)

			$this->colecaoBairro->atualizar($bairro);

			$endereco = new Endereco(
				\ParamUtil::value($this->params['endereco'], 'id'),
				\ParamUtil::value($this->params['endereco'], 'cep'),
				\ParamUtil::value($this->params['endereco'], 'logradouro'),
				\ParamUtil::value($this->params['latitude'] = 'latitude'),
				\ParamUtil::value($this->params['longitude'] = 'longitude'),
				\ParamUtil::value($this->params['codigoIbge'] = 'codigoIbge'),
				$bairro,
			);

			$this->colecaoEndereco->atualizar($endereco);

			$enderecoEntidade = new EnderecoEntidade(
				\ParamUtil::value($this->params['endereco'], 'id'),
				\ParamUtil::value($this->params['endereco'], 'numero'),
				\ParamUtil::value($this->params['endereco'], 'complemento'),
				\ParamUtil::value($this->params['endereco'], 'referencia'),
				$endereco
			);

			$this->colecaoEnderecoEntidade->atualizar($enderecoEntidade);

			$objFarmacia = new Farmacia(
				\ParamUtil::value($this->params,'id'),
				\ParamUtil::value($this->params,'nome'),
				\ParamUtil::value($this->params,'telefone'),
				$objEndereco
			);

			$this->colecaoFarmacia->atualizar($objFarmacia);

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
			$id = \ParamUtil::value($this->params, 'id');

			if (!is_numeric($id))
			{
				$msg = 'O id informado não é numérico.';
				return $this->geradoraResposta->erro($msg, GeradoraResposta::TIPO_TEXTO);
			}

			$farmacia = $this->colecaoFarmacia->comId($id);

			if(!$this->colecaoFarmacia->remover($farmacia->getId())) throw new Exception("Não foi possível deletar a farmácia.");

			if(!$this->colecaoEnderecoEntidade->remover($farmacia->getEndereco())) throw new Exception("Não foi possível deletar o endereço");

			return $this->geradoraResposta->semConteudo();
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}
	}

	function comId()
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

			$farmacia = $this->colecaoFarmacia->comId($id);

			if(!empty($farmacia))
			{
				$farmacia->setDataCriacao($farmacia->getDataCriacao()->toBrazilianString());
				$farmacia->setDataAtualizacao($farmacia->getDataAtualizacao()->toBrazilianString());

				$enderecoEntidade = $this->colecaoEnderecoEntidade->comId($farmacia->getEndereco());

				if(!empty($enderecoEntidade))
				{
					$enderecoEntidade->setDataCriacao($enderecoEntidade->getDataCriacao()->toBrazilianString());

					$enderecoEntidade->setDataAtualizacao($enderecoEntidade->getDataAtualizacao()->toBrazilianString());

					$enderecoSistema = $this->colecaoEnderecoSistema->comId($enderecoEntidade->getEndereco());

					if(!empty($enderecoSistema))
					{
						$bairro = $this->colecaoBairro->comId($enderecoSistema->getBairro());

						if(!empty($bairro))
						{
							$cidade = $this->colecaoCidade->comId($bairro->getCidade());

							if(!empty($cidade))
							{
								$estado = $this->colecaoEstado->comId($cidade->getEstado()));

								if(!empty($estado))
								{
									$pais = $this->colecaoPais->comId($estado->getPais()));
									if(!empty($estado)) $pais->setEstado($estado);
								}

								$bairro->setCidade($cidade);
							}

							$enderecoSistema->setBairro($bairro);
						}

						$enderecoEntidade->setEndereco($enderecoSistema);
					}

					$farmacia->setEndereco($enderecoEntidade);
				}
			}
		}
		catch (\Exception $e)
		{
			return $this->geradoraResposta->erro($e->getMessage(), GeradoraResposta::TIPO_TEXTO);
		}

		return $this->geradoraResposta->resposta(JSON::encode($farmacia), GeradoraResposta::OK, GeradoraResposta::TIPO_JSON);
	}
}
?>