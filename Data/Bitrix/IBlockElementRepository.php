<?php

namespace Quetzal\Data\Bitrix;

use Quetzal\Data\Common\Persistence\ObjectRepositoryInterface;

/**
 * Репозиторий, работающий с элементами инфоблоков
 *
 * Class IBlockElementRepository
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 *
 * @package Quetzal\Data\Bitrix
 */
class IBlockElementRepository implements ObjectRepositoryInterface
{
	/**
	 * @var \CIBlockElement
	 */
	protected $iBEGateway;

	/**
	 * @var int
	 */
	protected $iBlockId;

	/**
	 * @param \CIBlockElement $iBEGateway
	 * @param int $iBlockId
	 */
	public function __construct(\CIBlockElement $iBEGateway, $iBlockId = null)
	{
		$this->iBEGateway = $iBEGateway;
		$this->iBlockId = $iBlockId;
	}

	/**
	 * Получает результат запроса для поиска списка элементов
	 *
	 * @param array $filter
	 * @param array $orderBy
	 * @param array $selectedFields
	 * @param mixed $navStartParams
	 *
	 * @return \CIBlockResult
	 */
	public function rawFindBy(
		array $filter = array(),
		array $orderBy = array('sort' => 'asc'),
		array $selectedFields = array('*', 'PROPERTY_*'),
		$navStartParams = null
	) {
		if (!isset($filter['IBLOCK_ID']) && $this->iBlockId) {
			$filter['IBLOCK_ID'] = $this->iBlockId;
		}

		return $this->iBEGateway->GetList(
			$orderBy,
			$filter,
			false,
			is_null($navStartParams) ? false : $navStartParams,
			$selectedFields
		);
	}

	/**
	 * Получает результат запроса одного элемента инфоблока по фильтру
	 *
	 * @param array $filter
	 * @param array $selectedFields
	 *
	 * @return \CIBlockResult
	 */
	public function rawFindOneBy(array $filter = array(), array $selectedFields = array('*', 'PROPERTY_*'))
	{
		return $this->rawFindBy($filter, array(), $selectedFields, array('nTopCount' => 1));
	}

	/**
	 * Получает результат запроса одного элемент инфоблока по id
	 *
	 * @param int $id
	 * @param array $selectedFields
	 *
	 * @return \CIBlockResult
	 */
	public function rawFind($id, array $selectedFields = array('*', 'PROPERTY_*'))
	{
		return $this->rawFindOneBy(array('ID' => $id), $selectedFields);
	}

	/**
	 * Ищет элемент по его id
	 *
	 * @param int $id
	 * @param array $selectedFields
	 * @param bool $selectProperties
	 *
	 * @return array|null
	 */
	public function find($id, array $selectedFields = array('*', 'PROPERTY_*'), $selectProperties = true)
	{
		return $this->findOneBy(array('ID' => $id), $selectedFields, $selectProperties);
	}

	/**
	 * Ищет все объекты в репозитории
	 *
	 * @param array $selectedFields
	 * @param bool $selectProperties
	 *
	 * @return mixed
	 */
	public function findAll($selectedFields = array('*', 'PROPERTY_*'), $selectProperties = true)
	{
		return $this->findBy(array(), array(), $selectedFields, $selectProperties);
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
		$items = array();

		$dbItems = $this->rawFindBy($filter, $orderBy, $selectedFields, $navStartParams);

		while ($item = $dbItems->GetNextElement()) {
			$arItem = $item->GetFields();

			if ($selectProperties) {
				$arItem['PROPERTIES'] = $item->GetProperties();
			}

			$items[] = $arItem;
		}

		return $items;
	}

	/**
	 * Ищет один элемент по фильтру
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
		$items = $this->findBy($filter, array(), $selectedFields, $selectProperties, array('nTopCount' => 1));

		return isset($items[0]) ? $items[0] : null;
	}

	/**
	 * Преобразует результат запроса (массив) в объект-модель
	 *
	 * @param array $arItem
	 *
	 * @return object
	 */
	public function hydrateOne(array $arItem)
	{
		return (object)$arItem;
	}

	/**
	 * Преобразует список массивов в список объектов-моделей
	 *
	 * @param array $items
	 *
	 * @return array
	 */
	public function hydrateList(array $items)
	{
		$hydrated = array();

		foreach ($items as $item) {
			$hydrated[] = $this->hydrateOne($item);
		}

		return $hydrated;
	}
}
