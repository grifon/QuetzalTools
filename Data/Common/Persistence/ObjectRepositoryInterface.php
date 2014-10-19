<?php

namespace Quetzal\Data\Common\Persistence;

/**
 * Интерфейс репозитория объектов
 *
 * Interface ObjectRepositoryInterface
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 *
 * @package Quetzal\Data\Common\Persistence
 */
interface ObjectRepositoryInterface
{
	/**
	 * Ищет элемент по его id
	 *
	 * @param int $id
	 *
	 * @return mixed
	 */
	public function find($id);

	/**
	 * Ищет все объекты в репозитории
	 *
	 * @return mixed
	 */
	public function findAll();

	/**
	 * Ищет элементы, удовлетворяющие фильтру поиска
	 *
	 * @param array $filter
	 * @param array $orderBy
	 *
	 * @return mixed
	 */
	public function findBy(array $filter, array $orderBy = array());

	/**
	 * Ищет один элемент по фильтру
	 *
	 * @param array $filter
	 *
	 * @return mixed
	 */
	public function findOneBy(array $filter);
}
