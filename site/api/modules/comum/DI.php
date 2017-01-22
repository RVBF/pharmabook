<?php

/**
 *  Envólucro para container de injeção de dependência.
 *
 *  @author	Thiago Delgado Pinto
 */
class DI {

	private $container = null;

	private function __construct()	{}
	private function __clone()	{}
	private function __wakeup()	{}

	static function instance()
	{
		static $singleton = null;
		if (null == $singleton)
		{
			return new static; // new DI();
		}
		return $singleton;
	}

	/**
	 *  Retorna um novo objeto para a classe informada.
	 */
	function create($className)
	{
		return $this->getContainer()->create($className);
	}

	private function getContainer()
	{
		if (null === $this->container)
		{ // once
			$this->container = $this->makeContainer();
		}
		return $this->container;
	}

	private function makeContainer()
	{
		$container = new \Dice\Dice();

		$container->addRule('\PDOWrapper', $this->makePDOWrapper());
		$container->addRule('\Session', ['instanceOf' => 'Session']);
		$container->addRule('\ColecaoUsuario', ['instanceOf' => 'ColecaoUsuarioEmBDR']);
		$container->addRule('\ColecaoLogin', ['instanceOf' => 'ColecaoLoginEmBDR']);
		$container->addRule('\ColecaoEstoque', ['instanceOf' => 'ColecaoEstoqueEmBDR']);
		$container->addRule('\ColecaoMedicamento', ['instanceOf' => 'ColecaoMedicamentoEmBDR']);
		$container->addRule('\ColecaoMedicamento', ['instanceOf' => 'ColecaoMedicamentoEmBDR']);
		$container->addRule('\ColecaoLaboratorio', ['instanceOf' => 'ColecaoLaboratorioEmBDR']);
		$container->addRule('\ColecaoMedicamentoPrecificado', ['instanceOf' => 'ColecaoMedicamentoPrecificadoEmBDR']);
		$container->addRule('\ColecaoMedicamentoPessoal', ['instanceOf' => 'ColecaoMedicamentoPessoalEmBDR']);
		$container->addRule('\ColecaoTipoMedicamento', ['instanceOf' => 'ColecaoTipoMedicamentoEmBDR']);
		$container->addRule('\ColecaoPosologia', ['instanceOf' => 'ColecaoPosologiaEmBDR']);
		$container->addRule('\ColecaoFarmacia', ['instanceOf' => 'ColecaoFarmaciaEmBDR']);
		$container->addRule('\ColecaoEndereco', ['instanceOf' => 'ColecaoEnderecoEmBDR']);
		$container->addRule('\ColecaoFavorito', ['instanceOf' => 'ColecaoFavoritoEmBDR']);

		return $container;
	}

	private function makePDOWrapper()
	{
		$db = Servidor::bancoDadosNome();
		$host = Servidor::bancoDadosURL();
		$u = Servidor::bancoDadosUsuario();
		$p = Servidor::bancoDadosSenha();
		$dsn = "mysql:dbname=$db;host=$host";

		$options[ PDO::ATTR_PERSISTENT ] = true;
		$options[ PDO::MYSQL_ATTR_INIT_COMMAND ] = 'SET NAMES utf8';

		$pdo = PDOWrapper::createInModeException($dsn, $u, $p, $options);
		return ['shared' => true, 'constructParams' => [$pdo] ];
	}
}

?>