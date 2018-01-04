<?php
final class HomeAction {

	private $container;

	function __construct($container)
	{
		$this->container = $container;
	}

	public function index($request, $response)
	{
		Debuger::printr($request);

	}
}
