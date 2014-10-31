<?php

namespace Quetzal\Tools\Data\Migration\Bitrix;

use Quetzal\Exception\Data\Migration\MigrationException;
use Quetzal\Tools\Data\Migration\Common\MigrationInterface;

/**
 * Абстрактный класс миграции инфоблока
 *
 * Class AbstractIBlockMigration
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 *
 * @package Quetzal\Tools\Data\Migration\Bitrix
 */
abstract class AbstractIBlockMigration implements MigrationInterface
{
	/**
	 * @var \CIBlockType
	 */
	protected $iBlockTypeGateway;

	/**
	 * @var \CIBlock
	 */
	protected $iBlockGateway;

	/**
	 * @var int
	 */
	protected $iblockId;

	public function __construct()
	{
		$this->iBlockTypeGateway = new \CIBlockType();
		$this->iBlockGateway = new \CIBlock();
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

	/**
	 * Создает инфоблок
	 *
	 * @param array $arFields
	 *
	 * @throws MigrationException
	 */
	protected function createIBlock(array $arFields)
	{
		if ($id = $this->iBlockGateway->Add($arFields)) {
			$this->iblockId = $id;
		}

		throw new MigrationException($this->iBlockGateway->LAST_ERROR);
	}

	/**
	 * @param int $id
	 * @param array $arFields
	 *
	 * @throws MigrationException
	 */
	protected function updateIBlock($id, array $arFields)
	{
		if ($id = $this->iBlockGateway->Update($id, $arFields)) {
			$this->iblockId = $id;
		}

		throw new MigrationException($this->iBlockGateway->LAST_ERROR);
	}

	/**
	 * @param int $id
	 *
	 * @throws MigrationException
	 */
	protected function deleteIBlock($id)
	{
		/** @global $APPLICATION \CMain */
		/** @global $DB \CDatabase */
		global $APPLICATION;
		global $DB;

		$DB->StartTransaction();

		if(\CIBlock::Delete($id)) {
			$DB->Commit();
		} else {
			$DB->Rollback();

			throw new MigrationException($APPLICATION->GetException());
		}
	}
}