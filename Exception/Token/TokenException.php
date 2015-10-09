<?php

namespace Quetzal\Exception\Token;

/**
 * Исключения, которые могут быть выброшены в случае неудачного выполнения TokenAction
 *
 * Class TokenException
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 *
 * @package Quetzal\Exception\Token
 */
abstract class TokenException extends \RuntimeException
{
	/**
	 * @param string $message
	 * @param int $code
	 */
	public function __construct($message = '', $code = 0)
	{
		parent::__construct($message, $code);
	}
}
