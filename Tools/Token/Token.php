<?php

namespace Quetzal\Tools\Token;

use Quetzal\Data\Common\Model;

/**
 * Модель токена
 *
 * Class Token
 *
 * @package Quetzal\Tools\Token
 */
class Token extends Model
{
	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	protected $tokenString;

	/**
	 * Идентификатор сущности, с которой связан токен
	 *
	 * @var string
	 */
	protected $identifierString;

	/**
	 * @var array
	 */
	protected $data;

	/**
	 * @var string
	 */
	protected $actionClass;

	/**
	 * @param string $tokenString строка токена
	 * @param string $identifierString
	 * @param string $actionClass класс действия (наследник \Kotomka\TokenActionBundle\Token\Action)
	 * @param array $data данные для действия
	 * @param int $id
	 */
	public function __construct(
		$tokenString = null,
		$identifierString = null,
		$actionClass = null,
		$data = array(),
		$id = null
	) {
		$this->id = $id;
		$this->tokenString = (string)$tokenString;
		$this->identifierString = $identifierString;
		$this->data = $data;
		$this->actionClass = (string)$actionClass;
	}

	/**
	 * @return string
	 */
	public function getTokenString()
	{
		return $this->tokenString;
	}

	/**
	 * @param $tokenString
	 */
	public function setTokenString($tokenString)
	{
		$this->tokenString = $tokenString;
	}

	/**
	 * @return string
	 */
	public function getIdentifierString()
	{
		return $this->identifierString;
	}

	/**
	 * @param string $identifierString
	 */
	public function setIdentifierString($identifierString)
	{
		$this->identifierString = $identifierString;
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @param $data
	 */
	public function setData($data)
	{
		$this->data = $data;
	}

	/**
	 * @return string
	 */
	public function getActionClass()
	{
		return $this->actionClass;
	}

	/**
	 * @param $actionClass
	 */
	public function setActionClass($actionClass)
	{
		$this->actionClass = $actionClass;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}
}
