<?php

namespace Quetzal\Tools\Data\Migration\Bitrix;

use Quetzal\Exception\Data\Migration\MigrationException;
use Quetzal\Tools\Data\Migration\Common\MigrationInterface;

/**
 * Абстрактный класс миграции почтового шаблона
 *
 * Class AbstractEventMessageMigration
 *
 * @author Alexander Shtepa <rocko61rus@gmail.com>
 *
 * @package Quetzal\Tools\Data\Migration\Bitrix
 */
abstract class AbstractEventMessageMigration implements MigrationInterface
{
	/**
	 * @var \CEventMessage
	 */
	protected $eventMessageGateway;

	/**
	 * @var int
	 */
	protected $eventMessageId;

	/**
	 * @var
	 */
	protected $duplicate;

	public function __construct($duplicate = false)
	{
		$this->eventMessageGateway = new \CEventMessage();
		$this->duplicate = $duplicate;
	}

	/**
	 * Создает почтовый шаблон
	 *
	 * @param string $eventType
	 * @param string $subject
	 * @param string $message
	 * @param array $arFields
	 *
	 * @throws \Quetzal\Exception\Data\Migration\MigrationException
	 */
	protected function createEventMessage($eventName, $subject, $message = '', array $arFields = array())
	{
		$arDefaultFields = array(
			'EVENT_NAME' => $eventName,
			'SUBJECT'    => $subject,
			'MESSAGE'    => $message,
			'ACTIVE'     => 'Y',
			'LID'        => array(SITE_ID),
			'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
			'EMAIL_TO'   => '#EMAIL_TO#',
			'BODY_TYPE'  => 'text'
		);

		if ($this->isEventMessageExists($eventName) && !$this->duplicate) {
			throw new MigrationException(sprintf('Event message for type "%s" already exists', $eventName));
		}

		if (!$id = $this->eventMessageGateway->Add(array_merge($arDefaultFields, $arFields))) {
			throw new MigrationException($this->eventTypeGateway->LAST_ERROR);
		}

		$this->eventMessageId = $id;
	}

	/**
	 * Обновляет почтовый шаблон
	 *
	 * @param string $id
	 * @param array $arFields
	 *
	 * @throws \Quetzal\Exception\Data\Migration\MigrationException
	 */
	protected function updateEventMessage($id, array $arFields)
	{
		if (!$this->eventMessageGateway->Update($id, $arFields)) {
			$this->eventMessageId = $id;

			return true;
		}

		throw new MigrationException($this->eventMessageGateway->LAST_ERROR);
	}

	/**
	 * Удаляет почтовый шаблон по ID
	 *
	 * @param string $eventName
	 *
	 * @throws \Quetzal\Exception\Data\Migration\MigrationException
	 */
	protected function deleteEventMessage($eventMessageId)
	{
		/** @global $APPLICATION \CMain */
		/** @global $DB \CDatabase */
		global $APPLICATION;
		global $DB;

		$DB->StartTransaction();

		if(\CEventMessage::Delete($eventMessageId)) {
			$DB->Commit();
		} else {
			$DB->Rollback();

			throw new MigrationException($APPLICATION->GetException());
		}
	}

	/**
	 * Создает почтовый шаблон текстового типа
	 *
	 * @param $eventType
	 * @param $subject
	 * @param string $message
	 * @param array $arFields
	 */
	protected function createTextEventMessage($eventName, $subject, $message = '', array $arFields = array())
	{
		$arFields['BODY_TYPE'] = 'text';

		$this->createEventMessage($eventName, $subject, $message, $arFields);
	}

	protected function createHtmlEventMessage($eventName, $subject, $message = '', array $arFields = array())
	{
		$arFields['BODY_TYPE'] = 'html';

		$this->createEventMessage($eventName, $subject, $message, $arFields);
	}

	/**
	 * Метод проверяет существование почтового шаблона с указанным $eventName
	 *
	 * @param string $eventName
	 *
	 * @return bool
	 */
	protected function isEventMessageExists($eventName)
	{
		$arEventMessage = \CEventMessage::GetList($by='site_id', $order='desc', array('TYPE_ID' => $eventName))->Fetch();

		return !!$arEventMessage['ID'];
	}
}
