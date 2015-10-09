<?php

namespace Quetzal\Exception\Token;

/**
 * Исключение прерывающее выполнение действия токена с сохранением токена в хранилище
 *
 * Class BreakingException
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 *
 * @package Quetzal\Exception\Token
 */
class BreakingException extends TokenException
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
