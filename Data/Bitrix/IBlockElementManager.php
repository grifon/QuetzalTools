<?php

namespace Quetzal\Data\Bitrix;

use Quetzal\Common\SingletonInterface;
use Quetzal\Data\Common\Model;
use Quetzal\Data\Common\Persistence\ObjectManagerInterface;
use Quetzal\Exception\Common\NotImplementedException;
use Quetzal\Exception\Data\Common\DeleteException;
use Quetzal\Exception\Data\Common\SaveException;

/**
 * Менеджер элементов инфоблоков
 *
 * Class IBlockElementManager
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 *
 * @package Quetzal\Data\Bitrix
 */
final class IBlockElementManager implements ObjectManagerInterface, SingletonInterface
{
	/**
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * @var \CIBlockElement
	 */
	protected $iBEGateway;

	/**
	 * @var IBlockElementRepository
	 */
	protected $repository;

	/**
	 * @return self
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 *
	 */
	protected function __construct()
	{
		$this->injectDependencies();

		$this->iBEGateway = new \CIBlockElement();
		$this->repository = new IBlockElementRepository($this->iBEGateway);
	}

	private function __clone()
	{}

	/**
	 * Подключает необходимые зависимости
	 */
	private function injectDependencies()
	{
		\CModule::IncludeModule('iblock');
	}

	/**
	 * Получает репозиторий менеджера
	 *
	 * @return IBlockElementRepository
	 */
	public function getRepository()
	{
		return $this->repository;
	}

	/**
	 * Получает один элемент инфоблока по id
	 *
	 * @param int $id
	 * @param array $selectedFields
	 * @param bool $selectProperties
	 *
	 * @return array|null
	 */
	public function find($id, array $selectedFields = array('*', 'PROPERTY_*'), $selectProperties = true)
	{
		return $this->repository->find($id, $selectedFields, $selectProperties);
	}

	/**
	 * Выбирает элементы по фильтру
	 *
	 * @param array $filter
	 * @param array $orderBy
	 * @param array $selectedFields Список выбираемых полей (в формате BX)
	 * @param bool $selectProperties Нужно ли выбирать свойсвта
	 * @param mixed $navStartParams Ограничения выборки
	 *
	 * @return array
	 */
	public function findBy(
		array $filter = array(),
		array $orderBy = array('sort' => 'asc'),
		array $selectedFields = array('*', 'PROPERTY_*'),
		$selectProperties = true,
		$navStartParams = null
	) {

		return $this->repository->findBy($filter, $orderBy, $selectedFields, $selectProperties, $navStartParams);
	}

	/**
	 * Получает один элемент инфоблока по фильтру
	 *
	 * @param array $filter
	 * @param array $selectedFields
	 * @param bool $selectProperties
	 *
	 * @return array|null
	 */
	public function findOneBy(
		array $filter = array(),
		array $selectedFields = array('*', 'PROPERTY_*'),
		$selectProperties = true
	) {
		return $this->repository->findOneBy($filter, $selectedFields, $selectProperties);
	}

	/**
	 * Сохранаяет элемент
	 *
	 * @param Model $item
	 *
	 * @throws NotImplementedException
	 * @throws SaveException
	 */
	public function save(Model $item)
	{
		throw new NotImplementedException('Method not implemented');
	}

	/**
	 * Добавляет элемент
	 *
	 * @param array $fields
	 * @param array $properties
	 *
	 * @return int|null
	 */
	public function add(array $fields, array $properties = array())
	{
		if ($id = $this->iBEGateway->Add($fields)) {
			if (!empty($properties)) {
				\CIBlockElement::SetPropertyValuesEx($id, $fields['IBLOCK_ID'], $properties);
			}

			return $id;
		}

		return null;
	}

	/**
	 * Обновляет элемент
	 *
	 * @param int $id
	 * @param array $fields
	 * @param array $properties
	 *
	 * @return bool
	 */
	public function update($id, array $fields, array $properties = array())
	{
		if ($this->iBEGateway->Update($id, $fields)) {
			if (!empty($properties)) {
				$item = $this->find($id, array('ID', 'IBLOCK_ID'), false);

				\CIBlockElement::SetPropertyValuesEx($id, $item['IBLOCK_ID'], $properties);
			}

			return true;
		}

		return false;
	}

	/**
	 * Удаляет элемент
	 *
	 * @param int $id
	 *
	 * @return bool
	 *
	 * @throws DeleteException
	 */
	public function delete($id)
	{
		global $APPLICATION;

		if ($this->iBEGateway->Delete($id)) {
			return true;
		}

		$internalException = $APPLICATION->GetException();

		throw new DeleteException(
			$internalException ? $internalException->GetString() : 'Undefined exception',
			$id,
			$internalException ? $internalException->GetID() : 0
		);
	}
}
