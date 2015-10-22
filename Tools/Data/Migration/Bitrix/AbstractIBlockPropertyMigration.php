<?php

namespace Quetzal\Tools\Data\Migration\Bitrix;

use Quetzal\Exception\Data\Migration\MigrationException;
use Quetzal\Tools\Data\Migration\Common\MigrationInterface;

/**
 * Абстрактный класс миграции свойства инфоблока
 *
 * Class AbstractIBlockPropertyMigration
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 *
 * @package Quetzal\Tools\Data\Migration\Bitrix
 */
abstract class AbstractIBlockPropertyMigration implements MigrationInterface
{
	const STORE_IN_COMMON = 1;
	const STORE_IN_PARTICULAR = 2;

	const LIST_TYPE_SELECT = 'L';
	const LIST_TYPE_CHECKBOX = 'C';

	/**
	 * @var \CIBlockProperty
	 */
	protected $iBlockPropertyGateway;

	/**
	 * @var int
	 */
	protected $iBlockId;

	/**
	 * @var int
	 */
	protected $storageType;

	/**
	 * @param int $iBlockId
	 * @param int $storageType
	 */
	public function __construct($iBlockId, $storageType = self::STORE_IN_PARTICULAR)
	{
		$this->iBlockPropertyGateway = new \CIBlockProperty();
		$this->iBlockId = $iBlockId;
		$this->storageType = $storageType;
	}

	/**
	 * @param string $name
	 * @param string $code
	 * @param array $arFields
	 *
	 * @throws MigrationException
	 */
	protected function createProperty($name, $code, array $arFields = array())
	{
		$arFields['NAME'] = $name;
		$arFields['CODE'] = $code;
		$arFields['IBLOCK_ID'] = $this->iBlockId;
		$arFields['VERSION'] = $this->storageType;

		if (!empty($code) && $this->isIBlockPropertyExists($code)) {
			throw new MigrationException(sprintf('IBlock property with code "%s" already exists', $code));
		}

		if (!$this->iBlockPropertyGateway->Add($arFields)) {
			throw new MigrationException($this->iBlockPropertyGateway->LAST_ERROR);
		}
	}

	/**
	 * @param int $id
	 * @param array $arFields
	 *
	 * @throws MigrationException
	 */
	protected function updateProperty($id, array $arFields)
	{
		if (!$this->iBlockPropertyGateway->Update($id, $arFields)) {
			throw new MigrationException($this->iBlockPropertyGateway->LAST_ERROR);
		}
	}

	/**
	 * @param int $id
	 *
	 * @throws MigrationException
	 */
	protected function deleteProperty($id)
	{
		/** @global $APPLICATION \CMain */
		/** @global $DB \CDatabase */
		global $APPLICATION;
		global $DB;

		$DB->StartTransaction();

		if(\CIBlockProperty::Delete($id)) {
			$DB->Commit();
		} else {
			$DB->Rollback();

			throw new MigrationException($APPLICATION->GetException());
		}
	}

	/**
	 * @param string $name
	 * @param string $code
	 * @param array $arFields
	 *
	 * @throws MigrationException
	 */
	protected function createStringProperty($name, $code, array $arFields = array())
	{
		$arFields['PROPERTY_TYPE'] = 'S';

		$this->createProperty($name, $code, $arFields);
	}

	/**
	 * @param string $name
	 * @param string $code
	 * @param array $arFields
	 *
	 * @throws MigrationException
	 */
	protected function createNumericProperty($name, $code, array $arFields = array())
	{
		$arFields['PROPERTY_TYPE'] = 'N';

		$this->createProperty($name, $code, $arFields);
	}

	/**
	 * @param string $name
	 * @param string $code
	 * @param array $arFields
	 * @param string $fileTypes
	 *
	 * @throws MigrationException
	 */
	protected function createFileProperty($name, $code, array $arFields = array(), $fileTypes = '')
	{
		$arFields['PROPERTY_TYPE'] = 'F';

		if ($fileTypes) {
			$arFields['FILE_TYPE'] = $fileTypes;
		}

		$this->createProperty($name, $code, $arFields);
	}

	/**
	 * @param string $name
	 * @param string $code
	 * @param array $arFields
	 * @param array $values
	 * @param int $rowsCount
	 *
	 * @throws MigrationException
	 */
	protected function createSelectProperty($name, $code, array $arFields = array(), array $values, $rowsCount = 1)
	{
		$arFields['PROPERTY_TYPE'] = 'L';
		$arFields['LIST_TYPE'] = self::LIST_TYPE_SELECT;
		$arFields['MULTIPLE_CNT'] = $rowsCount;
		$arFields['VALUES'] = $values;

		$this->createProperty($name, $code, $arFields);
	}

	/**
	 * @param string $name
	 * @param string $code
	 * @param array $arFields
	 * @param bool $isCheckedByDefault
	 *
	 * @throws MigrationException
	 */
	protected function createCheckboxProperty($name, $code, array $arFields = array(), $isCheckedByDefault = false)
	{
		$arFields['PROPERTY_TYPE'] = 'L';
		$arFields['LIST_TYPE'] = self::LIST_TYPE_CHECKBOX;
		$arFields['MULTIPLE_CNT'] = 1;
		$arFields['VALUES'] = array(
			$this->generateListPropertyValue('Y', 100, $isCheckedByDefault)
		);

		$this->createProperty($name, $code, $arFields);
	}

	/**
	 * @param string $name
	 * @param string $code
	 * @param array $arFields
	 * @param int $iBlockId
	 *
	 * @throws MigrationException
	 */
	protected function createLinkProperty($name, $code, array $arFields = array(), $iBlockId = null)
	{
		$arFields['PROPERTY_TYPE'] = 'E';

		if ($iBlockId) {
			$arFields['LINK_IBLOCK_ID'] = $iBlockId;
		}

		$this->createProperty($name, $code, $arFields);
	}

	/**
	 * @param string $value
	 * @param int $sort
	 * @param bool $isDefault
	 *
	 * @return array
	 */
	protected function generateListPropertyValue($value, $sort = 500, $isDefault = false)
	{
		return array(
			'VALUE' => $value,
			'DEF'   => $isDefault ? 'Y' : 'N',
			'SORT'  => $sort,
		);
	}

	/**
	 * Method checks the condition of the existence of property
	 *
	 * @param string $code
	 * @return bool
	 */
	protected function isIBlockPropertyExists($code)
	{
		$arResult = $this->iBlockPropertyGateway->GetList(array(), array('IBLOCK_ID' => $this->iBlockId, 'CODE' => $code))->Fetch();

		return !!$arResult;
	}
}
