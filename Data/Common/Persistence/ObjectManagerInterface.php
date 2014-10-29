<?php

namespace Quetzal\Data\Common\Persistence;

use Quetzal\Data\Common\Model;
use Quetzal\Exception\Data\Common\DeleteException;
use Quetzal\Exception\Data\Common\SaveException;

/**
 * Интерфейс менеджера объектов хранилища
 *
 * Interface ObjectManagerInterface
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 *
 * @package Quetzal\Data\Common\Persistence
 */
interface ObjectManagerInterface
{
	/**
	 * Получает репозиторий текущего менеджера
	 *
	 * @return ObjectRepositoryInterface
	 */
	public function getRepository();

	/**
	 * Получает один элемент инфоблока по id
	 *
	 * @param int $id
	 *
	 * @return array|null
	 */
	public function find($id);

	/**
	 * Выбирает элементы по фильтру
	 *
	 * @param array $filter
	 * @param array $orderBy
	 *
	 * @return array
	 */
	public function findBy(array $filter = array(), array $orderBy = array());

	/**
	 * Получает один элемент инфоблока по фильтру
	 *
	 * @param array $filter
	 *
	 * @return array|null
	 */
	public function findOneBy(array $filter = array());

	/**
	 * Сохранаяет элемент
	 *
	 * @param Model $item
	 *
	 * @throws SaveException
	 */
	public function save(Model $item);

	/**
	 * Удаляет элемент
	 *
	 * @param int $id
	 *
	 * @throws DeleteException
	 */
	public function delete($id);
}
