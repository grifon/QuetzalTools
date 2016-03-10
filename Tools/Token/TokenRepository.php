<?php

namespace Quetzal\Tools\Token;

use Quetzal\Exception\Token\NotFoundException;

/**
 * Хранилище токенов
 *
 * Class TokenRepository
 *
 * @package Quetzal\Tools\Token
 */
class TokenRepository implements RepositoryInterface
{
	const TABLE_NAME = 'q_tokens';

	/**
	 * @var \CDatabase
	 */
	protected $db;

	/**
	 * @param \CDatabase $db
	 */
	public function __construct(\CDatabase $db)
	{
		$this->db = $db;
	}

	/**
	 * Удаление токена из хранилища
	 *
	 * @param string $actionClass
	 * @param string $identifierString
	 */
	public function removeByAction($actionClass, $identifierString)
	{
		$this->db->Query(
			sprintf(
				'DELETE FROM %s WHERE actionClass = \'%s\' AND identifierString = \'%s\'',
				self::TABLE_NAME,
				$this->db->ForSql($actionClass),
				$this->db->ForSql($identifierString)
			)
		);
	}

	/**
	 * @param string $tokenString
	 * @param string $identifierString
	 * @param string $actionClass
	 * @param array $data
	 *
	 * @return Token
	 */
	public function add($tokenString, $identifierString, $actionClass, array $data = array())
	{
		$token = new Token($tokenString, $identifierString, $actionClass, $data);

		$tokenData = array(
			'tokenString'      => $token->getTokenString(),
			'identifierString' => $token->getIdentifierString(),
			'actionClass'      => $token->getActionClass(),
			'data'             => json_encode($token->getData()),
		);

		if ($this->db->Add(self::TABLE_NAME, $tokenData)) {
			return $token;
		} else {
			throw new \RuntimeException(
				sprintf('Unable to save token "%s" with data: %s', $token->getTokenString(), json_encode($tokenData))
			);
		}
	}

	/**
	 * @param Token $token
	 *
	 * @return bool
	 */
	function remove(Token $token)
	{
		if ($id = $token->getId()) {
			return $this->db->Query(sprintf('DELETE FROM %s WHERE id = \'%s\'', self::TABLE_NAME, intval($id)));
		}

		return true;
	}

	/**
	 * @param string $tokenString Строка токена
	 *
	 * @return Token
	 */
	public function find($tokenString)
	{
		$dbResult = $this->db->Query(
			sprintf('SELECT * FROM %s WHERE tokenString = \'%s\'', self::TABLE_NAME, $this->db->ForSql($tokenString))
		);

		if ($arToken = $dbResult->Fetch()) {
			return new Token(
				$arToken['tokenString'],
				$arToken['identifierString'],
				$arToken['actionClass'],
				json_decode($arToken['data'], true),
				$arToken['id']
			);
		}

		throw new NotFoundException(sprintf('Token "%s" not found', $tokenString));
	}
}
