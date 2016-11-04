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
		$container->addRule('\ColecaoUsuario', ['instanceOf' => 'ColecaoUsuario']);
		$container->addRule('\ControladoraLoginUsuario', ['instanceOf' => 'ServicoUsuario']);
		$container->addRule('\ControladoraUsuario', ['instanceOf' => 'ServicoUsuario']);
		$container->addRule('\ControladoraUsuario', ['instanceOf' => 'SevicoEstoque']);
		$container->addRule('\ColecaoEstoque', ['instanceOf' => 'ColecaoEstoque']);
		$container->addRule('\SevicoEstoque', ['instanceOf' => 'ColecaoEstoque']);
		$container->addRule('\ControladoraEstoque', ['instanceOf' => 'ColecaoEstoque']);
		$container->addRule('\ColecaoMedicamento', ['instanceOf' => 'ColecaoMedicamento']);
		$container->addRule('\ControladoraFarmacia', ['instanceOf' => 'ServicoEndereco']);
		$container->addRule('\ServicoEndereco', ['instanceOf' => 'ColecaoEndereco']);
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