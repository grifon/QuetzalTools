<?php

namespace Quetzal\Exception\Tools\Data\Cache;

use Quetzal\Common\Exception;

/**
 * Class CacheException
 */
class CacheException extends Exception
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
