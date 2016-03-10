<?php

namespace Quetzal\Tools\Token;

use Quetzal\Exception\Token\BreakingException;
use Quetzal\Exception\Token\ExpiredException;

/**
 * Абстрактный класс действия.
 *
 * В докблоке конкретного класса действия необходимо указать
 * список необходимых параметров $data.
 *
 * Class Action
 *
 * @package Quetzal\Tools\Token
 */
abstract class Action
{
	const REDIRECT_URL_KEY = '__redirectUrl';

	/**
	 * @var array
	 */
	protected $data;

	/**
	 * @param array $data
	 */
	public function __construct(array $data = array())
	{
		$this->data = $data;
	}

	/**
	 * Исполнение действия
	 *
	 * @throws ExpiredException  Если указанный токен не действителен
	 * @throws BreakingException Если действие не выполнено, но токен не устарел
	 */
	abstract public function execute();

	/**
	 * @return mixed
	 */
	public function getRedirectUrl()
	{
		return isset($this->data[self::REDIRECT_URL_KEY]) ? $this->data[self::REDIRECT_URL_KEY] : null;
	}
}
