<?php

namespace Quetzal\Environment;

use Quetzal\Common\SingletonInterface;

/**
 * Менеджер переменных окружения
 *
 * Class EnvironmentManager
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 */
class EnvironmentManager implements SingletonInterface
{
	/**
	 * @var EnvironmentManager
	 */
	protected static $instance = null;

	/**
	 * @return EnvironmentManager
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	protected function __construct()
	{}

	private function __clone()
	{}
}
