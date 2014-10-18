<?php

namespace Quetzal\Environment\Configuration;

/**
 * Интерфейс конфигурации
 *
 * Interface ConfigurationInterface
 *
 * @package Quetzal\Environment
 */
interface ConfigurationInterface
{
	/**
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get($key);

	/**
	 * @param mixed $key
	 * @param mixed $value
	 */
	public function set($key, $value);

	/**
	 * @return array
	 */
	public function all();
}