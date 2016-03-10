<?php

namespace Quetzal\Tools\Token;

/**
 * Фабрика действий токена
 *
 * Class ActionFactory
 *
 * @package Quetzal\Tools\Token
 */
class ActionFactory
{
	/**
	 */
	public function __construct()
	{
	}

	/**
	 * Инстанцирует экземляр TokenAction
	 *
	 * @param string $actionClass Имя класса для создания действия
	 * @param array $data Параметры действия
	 *
	 * @return Action
	 *
	 * @throws \RuntimeException
	 */
	public function createAction($actionClass, $data = array())
	{
		if (!class_exists($actionClass)) {
			throw new \RuntimeException('Action class not found');
		}

		$class = new \ReflectionClass($actionClass);

		if (!$class->isSubclassOf('Quetzal\Tools\Token\Action')) {
			throw new \RuntimeException('Action class isn\'t descendant of Token\Action');
		}

		if (!is_array($data)) {
			throw new \RuntimeException('Data must be array');
		}

		return new $actionClass($data);
	}
}
