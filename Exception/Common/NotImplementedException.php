<?php

namespace Quetzal\Exception\Common;

use Quetzal\Common\Exception;

/**
 * Class NotImplementedException
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 *
 * @package Quetzal\Exception\Common
 */
class NotImplementedException extends Exception
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
