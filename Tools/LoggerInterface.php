<?php

namespace Quetzal\Tools;

/**
 * Интерфейс логгера
 *
 * Interface LoggerInterface
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 *
 * @package Quetzal\Tools
 */
interface LoggerInterface
{
	/**
	 * @param $message
	 */
	public function log($message);
}
