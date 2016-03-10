<?php

namespace Quetzal\Tools\Token;

/**
 * Интерфейс работы с репозиторием токенов
 *
 * Interface RepositoryInterface
 *
 * @package Quetzal\Tools\Token
 */
interface RepositoryInterface
{
	/**
	 * @param string $tokenString Строка токена
	 *
	 * @return Token
	 */
	public function find($tokenString);

	/**
	 * @param string $actionClass Имя класса действия
	 * @param string $identifierString Строка-идентификатор связки действие-данные
	 */
	public function removeByAction($actionClass, $identifierString);

	/**
	 * @param Token $token
	 *
	 * @return mixed
	 */
	public function remove(Token $token);

	/**
	 * @param string $tokenString
	 * @param string $identifierString
	 * @param string $actionClass
	 * @param array $data
	 *
	 * @return Token
	 */
	public function add($tokenString, $identifierString, $actionClass, array $data = array());
}
