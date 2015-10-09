<?php

namespace Quetzal\Data\Bitrix;

use Quetzal\Data\Common\Persistence\ObjectRepositoryInterface;

/**
 * Репозиторий, работающий с разделами внутри инфоблоков
 *
 * Class IBlockSectionRepository
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 *
 * @package Quetzal\Data\Bitrix
 */
class IBlockSectionRepository implements ObjectRepositoryInterface
{
	/**
	 * @var \CIBlockSection
	 */
	protected $iBSGateway;

	/**
	 * @var int
	 */
	protected $iBlockId;

	/**
	 * @param \CIBlockSection $iBSGateway
	 * @param int $iBlockId
	 */
	public function __construct(\CIBlockSection $iBSGateway, $iBlockId = null)
	{
		$this->iBSGateway = $iBSGateway;
		$this->iBlockId = $iBlockId;
	}

	/**
	 * Получает результат запроса для поиска списка разделов
	 *
	 * @param array $filter
	 * @param array $orderBy
	 * @param bool $calculateCount
	 * @param array $selectedFields
	 *
	 * @return \CIBlockResult
	 */
	public function rawFindBy(
		array $filter = array(),
		array $orderBy = array('sort' => 'asc'),
		$calculateCount = false,
		array $selectedFields = array('*', 'UF_*')
	) {
		if (!isset($filter['IBLOCK_ID']) && $this->iBlockId) {
			$filter['IBLOCK_ID'] = $this->iBlockId;
		}

		return $this->iBSGateway->GetList($orderBy, $filter, $calculateCount, $selectedFields);
	}

	/**
	 * Получает результат запроса одного раздела инфоблока по фильтру
	 *
	 * @param array $filter
	 * @param bool $calculateCount
	 *
	 * @return \CIBlockResult
	 */
	public function rawFindOneBy(array $filter = array(), $calculateCount = false)
	{
		return $this->rawFindBy($filter, array(), $calculateCount);
	}

	/**
	 * Получает результат запроса одного раздела инфоблока по id
	 *
	 * @param int $id
	 * @param bool $calculateCount
	 *
	 * @return \CIBlockResult
	 */
	public function rawFind($id, $calculateCount = false)
	{
		return $this->rawFindOneBy(array('ID' => $id), $calculateCount);
	}

	/**
	 * Ищет элемент по его id
	 *
	 * @param int $id
	 * @param bool $calculateCount
	 *
	 * @return array|null
	 */
	public function find($id, $calculateCount = false)
	{
		return $this->findOneBy(array('ID' => $id), $calculateCount);
	}

	/**
	 * Ищет все объекты в репозитории
	 *
	 * @param array $orderBy
	 * @param bool $calculateCount
	 * @param array $selectedFields
	 *
	 * @return array
	 */
	public function findAll(
		array $orderBy = array('sort' => 'asc'),
		$calculateCount = false,
		array $selectedFields = array('*', 'UF_*')
	) {
		return $this->findBy(array(), $orderBy, $calculateCount, $selectedFields);
	}

	/**
	 * Выбирает элементы по фильтру
	 *
	 * @param array $filter
	 * @param array $orderBy
	 * @param bool $calculateCount
	 * @param array $selectedFields Список выбираемых полей (в формате BX)
	 *
	 * @return array
	 */
	public function findBy(
		array $filter = array(),
		array $orderBy = array('sort' => 'asc'),
		$calculateCount = false,
		array $selectedFields = array('*', 'UF_*')
	) {
		$sections = array();

		$dbSections = $this->rawFindBy($filter, $orderBy, $calculateCount, $selectedFields);

		while ($arSection = $dbSections->GetNext()) {
			$sections[] = $arSection;
		}

		return $sections;
	}

	/**
	 * Ищет один элемент по фильтру
	 *
	 * @param array $filter
	 * @param bool $calculateCount
	 * @param array $selectedFields
	 *
	 * @return array|null
	 */
	public function findOneBy(
		array $filter = array(),
		$calculateCount = false,
		array $selectedFields = array('*', 'UF_*')
	) {
		$sections = $this->findBy($filter, array(), $calculateCount, $selectedFields);

		return isset($sections[0]) ? $sections[0] : null;
	}

	/**
	 * Строит дерево разделов полной глубины от указанного раздела или от корня
	 *
	 * @param int $rootId
	 * @param array $filter
	 *
	 * @return array
	 */
	public function generateSectionsTree($rootId = null, $filter = array())
	{
		$tree = array();
		$sections = $this->findBy(
			array_merge(
				$filter,
				array('SECTION_ID' => is_null($rootId) ? false : $rootId)
			)
		);

		foreach ($sections as $arSection) {
			$arSection['children'] = $this->generateSectionsTree($arSection['ID'], $filter);

			$tree[] = $arSection;
		}

		return $tree;
	}

	/**
	 * Получает доступные разделы
	 *
	 * @param array $arOrder
	 *
	 * @return array
	 */
	public function findAvailableSections(array $arOrder = array('sort' => 'asc'))
	{
		return $this->findBy(
			array(
				'ACTIVE' => 'Y',
				'IBLOCK_ACTIVE' => 'Y',
				'GLOBAL_ACTIVE' => 'Y',
			),
			$arOrder
		);
	}
}
