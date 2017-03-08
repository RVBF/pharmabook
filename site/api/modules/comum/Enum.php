<?php
/**
 *	Tipo Enumerado
 *
 *  @author Rafael Vinicius Barros Ferreira
 *  @version	1.0
 */
abstract class Enum {

	private static $constCacheArray = NULL;

	public static function getConstants()
	{
		if(self::$constCacheArray == NULL)
		{
			self::$constCacheArray = [];
		}

		$calledClass = get_called_class();

		if(!array_key_exists($calledClass, self::$constCacheArray))
		{
			$reflect = new ReflectionClass($calledClass);
			self::$constCacheArray[$calledClass] = $reflect->getConstants();
		}

		return self::$constCacheArray[$calledClass];
	}

	public static function eUmaChaveValida($name, $strict = false)
	{
		$constants = self::getConstants();

		if($strict)
		{
			return array_key_exists($name, $constants);
		}

		$keys = array_map('strtolower', array_keys($constants));
		return in_array(strtolower($name), $keys);
	}

	public static function eUmValorValido($value, $strict = true)
	{
		$values = array_values(self::getConstants());
		return in_array($value, $values, $strict);
	}

	public static function getValor($chave)
	{
		$constants = self::getConstants();

		return $constants[$chave];
	}

	public static function getChave($valor)
	{
		return array_search($valor, self::getConstants());
	}
}
 ?>