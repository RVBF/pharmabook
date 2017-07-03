<?php

use phputil\Session;

/**
* Serviço de Endereco
*
* @author	Rafael Vinicius barros ferreira
*/

class ServicoEndereco {

	private $colecao;
	private $colecaoEnderecoEntidade;
	private $colecaoEndereco;
	private $colecaoCidade;
	private $colecaoBairro;
	private $colecaoEstado;
	private $colecaoPais;

	function __construct()
	{
		$this->colecao = DI::instance()->create('ColecaoEnderecoEmBDR');
		$this->colecaoEnderecoEntidade = DI::instance()->create('ColecaoEnderecoEntidade');
		$this->colecaoEndereco = DI::instance()->create('ColecaoEndereco');
		$this->colecaoCidade = DI::instance()->create('ColecaoCidade');
		$this->colecaoBairro = DI::instance()->create('ColecaoBairro');
		$this->colecaoEstado = DI::instance()->create('ColecaoEstado');
		$this->colecaoPais = DI::instance()->create('ColecaoPais');
	}

	public function consultarCepOnline($cep)
	{
		$url = 'ceps.json?cep=' . $cep;
		$cepJson = $this->pesquisarCepAberto($url);

		if(!empty($cepJson))
		{
			$endereco = $this->colecaoEndereco->comCep($cepJson->{'cep'});

			if(!empty($endereco))
			{
				return JSON::encode($endereco);
			}
			else
			{
				$estado = $this->colecaoEstado->comUf($cepJson->{'estado'});
				$cidade = $this->colecaoCidade->comEstadoECidade($estado->id, $cepJson->{'cidade'});
				$bairro = $this->colecaoBairro->comBairroECidade($cepJson->{'bairro'}, $cidade->id);
				$endereco = $this->colecaoBairro->comBairroECep($cepJson->{'cep'}, $bairro->id);

				if(!empty($estado))
				{
					if(empty($cidade))
					{
						$cidade = new Cidade(0, $cepJson->{'nome'}, $cepJson->{'estado'});
						$this->colecaoCidade->adicionar($cidade);
						$cidade->setEstado($estado);
						if(empty($bairro))
						{
							$bairro = new Cidade(0, $cepJson->{'bairro'}, $cidade);
							$this->colecaoBairro->adicionar($cidade);

							$bairro->setCidade($cidade);
						}

						if(empty($endereco))
						{
							$endereco = new endereco(0,
								$cepJson->{'cep'},
								$cepJson->{'logradouro'},
								$cepJson->{'latitude'},
								$cepJson->{'longitude'},
								$cepJson->{'ibge'},
								$bairro
							);

							$this->colecaoEndereco->adicionar($endereco);
							$endereco->setBairro($bairro);
						}
					}
				}
				else
				{
					throw new Exception("Estado não cadastrado no sistema.");
				}

				return $endereco;
			}
		}
		else
		{
			throw new Exception("Cep não encontrado.");

		}
	}

	public function consultarCidadesDoEstadoOnline($uf)
	{
		$url = 'cities.json?estado=' . $uf;
		$cidades = $this->pesquisarCepAberto($url);

		$cidadesCollection = new Collection();

		$estado = $this->colecaoEstado->comUf($cepJson->{'uf'});

		if($estado)
		{
			if(!empty($cepJson))
			{
				foreach ($cidades as $cidade)
				{
					$cidadeBD = $this->colecaoCidade->comEstadoECidade($estado->id, $cepJson->{'cidade'});
					if(empty($cidadeBD))
					{
						$cidade = new Cidade(0, $cepJson->{'nome'}, $cepJson->{'estado'});
						$this->colecaoCidade->adicionar($cidade);
						$cidade->setEstado($estado);
					}

					$cidadesCollection->add($cidade);
				}
			}

			return $cidades;
		}
		else
		{
			throw new Exception("Estado não encontrado.");
		}
	}

	public function consultarGeolocalizacaoOnline($latitude, $longitude)
	{
		$endereco = $this->colecaoEndereco->comLatitudeElongitude($latitude, $longitude);

		if(!empty($endereco))
		{
			$bairro = $this->colecaoBairro->comId($endereco->getBairro());

			if(!empty($bairro))
			{
				$cidade = $this->colecaoCidade->comId($bairro->getCidade());

				if(!empty($cidade))
				{
					$estado = $this->colecaoEstado->comId($cidade->getEstado());

					if(!empty($estado)) $cidade->setEstado($estado);
					else throw new Exception("Estado Não encontrado");
				}
				else
				{
					throw new Exception("Cidade não encontrada");
				}
				$bairro->setCidade($cidade);
			}
			else
			{
				throw new Exception("Bairro não encontrado.");
			}

			$endereco->setBairro($bairro);

			return JSON::encode($endereco);
		}
		else
		{
			$url = 'ceps.json?lat='. $latitude .'&lng='.$longitude;
			$cepJson = $this->pesquisarCepAberto($url);

			if(!empty($cepJson))
			{
				$estado = $this->colecaoEstado->comUf($cepJson->{'estado'})[0];

				$cidade = (!empty($estado)) ? $this->colecaoCidade->comEstadoECidade($estado->getId(), $cepJson->{'cidade'}): [];
				$bairro =   (!empty($cidade)) ? $this->colecaoBairro->comBairroECidade($cepJson->{'bairro'}, $cidade[0]->getId()) : [];

				$endereco =  (!empty($bairro)) ?  $this->colecaoEndereco->comBairroECep($cepJson->{'cep'}, $bairro[0]->getId()) : [];

				if(!empty($estado))
				{
					if(empty($cidade))
					{
						$cidade = new Cidade('', $cepJson->{'cidade'}, $cepJson->{'estado'});
						$cidade->setEstado($estado);
						$this->colecaoCidade->adicionar($cidade);
					}

					if(empty($bairro))
					{
						$bairro = new Bairro(0, $cepJson->{'bairro'},(is_array($cidade)) ? $cidade[0] : $cidade);
						$this->colecaoBairro->adicionar($bairro);
					}

					if(empty($endereco))
					{
						$endereco = new Endereco(0,
							$cepJson->{'cep'},
							$cepJson->{'logradouro'},
							$cepJson->{'latitude'},
							$cepJson->{'longitude'},
							$cepJson->{'ibge'},
							(is_array($bairro)) ? $bairro[0] : $bairro
						);

						$this->colecaoEndereco->adicionar($endereco);
					}
				}
				else
				{
					throw new Exception("Estado não cadastrado no sistema.");
				}
			}
			else
			{
				throw new Exception("Cep não encontrado.");
			}

			return $endereco;
		}
	}

	private function pesquisarCepAberto($urlFinal)
	{
		$token = '8cfd3192acdb84f2abd5ac5b7e2af680';
		$url = 'http://www.cepaberto.com/api/v2/'. $urlFinal;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Token token="' . $token . '"'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$saida = curl_exec($ch);

		return json_decode($saida);
	}
}

?>