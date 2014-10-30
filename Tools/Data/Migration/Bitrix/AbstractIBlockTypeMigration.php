<?php

namespace Quetzal\Tools\Data\Migration\Bitrix;

use Quetzal\Exception\Data\Migration\MigrationException;
use Quetzal\Tools\Data\Migration\Common\MigrationInterface;

/**
 * Абстрактный класс миграции типа инфоблока
 *
 * Class AbstractIBlockTypeMigration
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 *
 * @package Quetzal\Tools\Data\Migration\Bitrix
 */
abstract class AbstractIBlockTypeMigration implements MigrationInterface
{
	/**
	 * @var \CIBlockType
	 */
	protected $iBlockTypeGateway;

	public function __construct()
	{
		$this->iBlockTypeGateway = new \CIBlockType();
	}

	/**
	 * Создает тип инфоблока
	 *
	 * @param array $arFields
	 *
	 * @throws MigrationException
	 */
	protected function createIBlockType(array $arFields)
	{
		if (!$this->iBlockTypeGateway->Add($arFields)) {
			throw new MigrationException($this->iBlockTypeGateway->LAST_ERROR);
		}
	}

	/**
	 * @param string $id
	 * @param array $arFields
	 *
	 * @throws MigrationException
	 */
	protected function updateIBlockType($id, array $arFields)
	{
		if (!$this->iBlockTypeGateway->Update($id, $arFields)) {
			throw new MigrationException($this->iBlockTypeGateway->LAST_ERROR);
		}
	}

	/**
	 * @param string $id
	 *
	 * @throws MigrationException
	 */
	protected function deleteIBlockType($id)
	{
		/** @global $APPLICATION \CMain */
		/** @global $DB \CDatabase */
		global $APPLICATION;
		global $DB;

		$DB->StartTransaction();

		if(\CIBlockType::Delete($id)) {
			$DB->Commit();
		} else {
			$DB->Rollback();

			throw new MigrationException($APPLICATION->GetException());
		}
	}
}
