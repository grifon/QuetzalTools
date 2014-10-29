<?php

namespace Quetzal\Exception\Data\Common;

use Quetzal\Common\Exception;

/**
 * Исключение процесса сохранения объекта
 *
 * Class SaveException
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 *
 * @package Quetzal\Exception\Data\Common
 */
class SaveException extends Exception
{
	/**
	 * @var int
	 */
	protected $id;

	/**
	 * @param string $message
	 * @param int $id
	 * @param int $code
	 * @param Exception $previous
	 */
	public function __construct($message, $id = null, $code = 0, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);

		$this->id = $id;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}
} 