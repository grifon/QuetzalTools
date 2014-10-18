<?php

namespace Quetzal\Common;

/**
 * Базовый класс исключений
 *
 * Class Exception
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 *
 * @package Quetzal\Common
 */
class Exception extends \Exception
{
	/**
	 * @param string $message
	 * @param int $code
	 * @param \Exception $previous
	 */
	public function __construct($message = '', $code = 0, \Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
