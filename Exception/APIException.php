<?php

namespace Quetzal\Exception;

use Quetzal\Common\Exception;

/**
 * Базовый класс исключений для API
 *
 * Class APIException
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 *
 * @package Quetzal\Exception
 */
class APIException extends Exception
{
	/**
	 * Метод, с которым связано исключение
	 *
	 * @var string
	 */
	protected $method;

	/**
	 * @param string $method
	 * @param string $message
	 * @param int $code
	 * @param \Exception $previous
	 */
	public function __construct($method, $message = '', $code = 0, \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);

		$this->method = $method;
	}

	/**
	 * Возвращает название метода, в котором было брошено исключение
	 *
	 * @return string
	 */
	public function getMethod()
	{
		return $this->method;
	}
}
