<?php

namespace Quetzal\Service\Tools\Token;

use Quetzal\Common\SingletonInterface;
use Quetzal\Exception\Token\BreakingException;
use Quetzal\Exception\Token\ExpiredException;
use Quetzal\Exception\Token\NotFoundException;
use Quetzal\Tools\Token\Action;
use Quetzal\Tools\Token\ActionFactory;
use Quetzal\Tools\Token\GenerationAlgorithm\AlgorithmInterface;
use Quetzal\Tools\Token\RepositoryInterface;
use Quetzal\Tools\Token\Token;
use Quetzal\Tools\Token\TokenRepository;

/**
 * Сервис для создания и исполнения действий пользователя по токену
 *
 * Class TokenActionService
 *
 * @package Quetzal\Service\Tools\Token
 */
class TokenActionService implements SingletonInterface
{
	/**
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * Хранилище токенов
	 *
	 * @var RepositoryInterface
	 */
	protected $tokens;

	/**
	 * Фабрика для создания конкретного действия
	 *
	 * @var ActionFactory
	 */
	protected $actionFactory;

	/**
	 * @return self
	 */
	public static function getInstance()
	{
		global $DB;

		if (is_null(self::$instance)) {
			self::$instance = new self(new ActionFactory(), $DB);
		}

		return self::$instance;
	}

	/**
	 * @param ActionFactory $actionFactory
	 */
	protected function __construct(ActionFactory $actionFactory, \CDatabase $db)
	{
		$this->actionFactory = $actionFactory;
		$this->tokens = new TokenRepository($db);
	}

	private function __clone()
	{
	}

	/**
	 * Генерация идентификатора и сохранение действия токена
	 *
	 * @param AlgorithmInterface $algorithm
	 * @param string $identifierString Идентифицирующая строка (например id пользователя)
	 * @param string $actionClass Класс действия
	 * @param array $data Параметры действия
	 * @param string $redirectUrl Url, на который производится редирект после выполнения действия
	 *
	 * @return string
	 * @throws \RuntimeException
	 */
	public function generateToken(
		AlgorithmInterface $algorithm,
		$identifierString,
		$actionClass,
		$data = array(),
		$redirectUrl = null
	) {
		if (!class_exists($actionClass)) {
			throw new \RuntimeException('Action class not found');
		}

		if (!self::isSubclassOf($actionClass, 'Quetzal\Tools\Token\Action')) {
			throw new \RuntimeException('Action class must be derived from "Quetzal\Tools\Token\Action"');
		}

		$data[Action::REDIRECT_URL_KEY] = $redirectUrl;
		$tokenString = $algorithm->generate();
		$this->tokens->removeByAction($actionClass, $identifierString);
		$this->tokens->add($tokenString, $identifierString, $actionClass, $data);

		return $tokenString;
	}

	/**
	 * Выполнение действия по строке токена
	 *
	 * @param string $tokenString
	 *
	 * @return mixed Url для редиректа
	 *
	 * @throws NotFoundException Если не найден указанный токен
	 * @throws ExpiredException  Если указанный токен не действителен
	 * @throws BreakingException Если действие не выполнено, но токен не удален и может быть запрошен повторно
	 */
	public function execActionByToken($tokenString)
	{
		$token = $this->findToken($tokenString);
		$data = $token->getData();

		try {
			$this->actionFactory->createAction($token->getActionClass(), $data)->execute();
			$this->tokens->remove($token);
		} catch (ExpiredException $e) {
			$this->tokens->remove($token);
			throw $e;
		}

		return !empty($data[Action::REDIRECT_URL_KEY]) ? (string)$data[Action::REDIRECT_URL_KEY] : null;
	}

	/**
	 * @param string $tokenString
	 *
	 * @return Token
	 *
	 * @throws NotFoundException
	 */
	public function findToken($tokenString)
	{
		$token = $this->tokens->find($tokenString);

		if (!$token) {
			throw new NotFoundException('Requested token "' . $tokenString . '" not found');
		}

		return $token;
	}

	/**
	 * @param string $class
	 * @param string $baseClass
	 *
	 * @return bool
	 */
	private static function isSubclassOf($class, $baseClass)
	{
		try {
			$actionClassReflection = new \ReflectionClass($class);
		} catch (\ReflectionException $e) {
			$e = null;

			return false;
		}

		return $actionClassReflection->isSubclassOf($baseClass);
	}

	/**
	 * @return TokenRepository
	 */
	public function getRepository()
	{
		return $this->tokens;
	}
}
