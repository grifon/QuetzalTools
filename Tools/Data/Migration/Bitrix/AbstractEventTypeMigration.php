<?php

namespace Quetzal\Tools\Data\Migration\Bitrix;

use Quetzal\Exception\Data\Migration\MigrationException;
use Quetzal\Tools\Data\Migration\Common\MigrationInterface;

/**
 * Абстрактный класс миграции типа почтового события
 *
 * Class AbstractEventTypeMigration
 *
 * @author Alexander Shtepa <rocko61rus@gmail.com>
 *
 * @package Quetzal\Tools\Data\Migration\Bitrix
 */
abstract class AbstractEventTypeMigration implements MigrationInterface
{
	/**
	 * @var \CEventType
	 */
	protected $eventTypeGateway;

	/**
	 * @var int
	 */
	protected $eventTypeId;

	/**
	 * Конструктор класса AbstractEventTypeMigration
	 */
	public function __construct()
	{
		$this->eventTypeGateway = new \CEventType();
	}

	/**
	 * Создает тип почтового события
	 *
	 * @param string $code - код типа
	 * @param string $name - название типа
	 * @param array $arFields
	 *
	 * @throws \Quetzal\Exception\Data\Migration\MigrationException
	 */
	protected function createEventType($code, $name, array $arFields = array())
	{
		$arDefaultFields = array(
			'LID'        => 'ru',
			'EVENT_NAME' => $code,
			'NAME'       => $name
		);

		if ($this->isEventTypeExists($code)) {
			throw new MigrationException(sprintf('Event type with code "%s" already exists', $code));
		}

		if (!$id = $this->eventTypeGateway->Add(array_merge($arDefaultFields, $arFields))) {
			throw new MigrationException($this->eventTypeGateway->LAST_ERROR);
		}

		$this->eventTypeId = $id;
	}

	/**
	 * Обновляет тип почтового события
	 *
	 * @param string $id
	 * @param array $arFields
	 *
	 * @throws \Quetzal\Exception\Data\Migration\MigrationException
	 */
	protected function updateEventType($id, array $arFields)
	{
		if (!$this->eventTypeGateway->Update($id, $arFields)) {
			$this->eventTypeId = $id;

			return true;
		}

		throw new MigrationException($this->eventTypeGateway->LAST_ERROR);
	}

	/**
	 * Удаляет тип почтового события
	 *
	 * @param string $eventName
	 *
	 * @throws \Quetzal\Exception\Data\Migration\MigrationException
	 */
	protected function deleteEventType($eventName)
	{
		/** @global $APPLICATION \CMain */
		/** @global $DB \CDatabase */
		global $APPLICATION;
		global $DB;

		$DB->StartTransaction();

		if(\CEventType::Delete($eventName)) {
			$DB->Commit();
		} else {
			$DB->Rollback();

			throw new MigrationException($APPLICATION->GetException());
		}
	}

	/**
	 * Метод проверяет существование типа почтового события
	 *
	 * @param string $code
	 * @return bool
	 */
	protected function isEventTypeExists($eventName, $lang = 'ru')
	{
		$arEventType = \CEventType::GetByID($eventName, $lang)->Fetch();

		return !!$arEventType['ID'];
	}
}
