<?php

namespace Quetzal\Common;

/**
 * Интерфейс паттерна «Одиночка»
 *
 * Interface SingletonInterface
 *
 * @package Quetzal\Common
 */
interface SingletonInterface
{
	/**
	 * @return self
	 */
	public static function getInstance();
}
