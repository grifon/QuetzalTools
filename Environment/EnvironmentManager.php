<?php

namespace Quetzal\Environment;

use Quetzal\Common\SingletonInterface;
use Quetzal\Environment\Configuration\ConfigurationInterface;

/**
 * Менеджер переменных окружения
 *
 * Class EnvironmentManager
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 *
 * @package Quetzal\Environment
 */
class EnvironmentManager implements SingletonInterface
{
	/**
	 * id сайта по умолчанию
	 */
	const DEFAULT_SITE_ID = 's1';

	/**
	 * @var EnvironmentManager
	 */
	protected static $instance = null;

	/**
	 * @var array
	 */
	protected $configurations = array();

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

	/**
	 * Получает список имен файлов (без расширений) с конфигурациями
	 * в порядке их подключения
	 *
	 * @return array
	 */
	public function getConfigFileNames()
	{
		$currentSiteId = $this->getCurrentSiteId();

		return array(
			'common',
			'common_override',
			$currentSiteId,
			sprintf('%s_override', $currentSiteId)
		);
	}

	/**
	 * Получает id текущего сайта
	 *
	 * @return string
	 *
	 * @todo Что, если это запуск cron-скрипта?
	 */
	protected function getCurrentSiteId()
	{
		return defined('SITE_ID') ? SITE_ID : self::DEFAULT_SITE_ID;
	}

	/**
	 * Добавляет конфигурацию в пул
	 *
	 * @param ConfigurationInterface $configuration
	 */
	public function addConfig(ConfigurationInterface $configuration)
	{
		array_unshift($this->configurations, $configuration);
	}

	/**
	 * Получает конфигурацию, добавленную позже всех
	 *
	 * @return ConfigurationInterface|null
	 */
	public function getTopConfig()
	{
		return empty($this->configurations) ? null : $this->configurations[0];
	}

	/**
	 * Получает значение переменной окружения
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get($key)
	{
		foreach ($this->configurations as $config) {
			if ($config->get($key)) {
				return $config->get($key);
			}
		}

		return null;
	}
}
