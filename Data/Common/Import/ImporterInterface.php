<?php

namespace Quetzal\Data\Common\Import;

/**
 * Интерфейс для различных импортеров сущностей
 *
 * Interface ImporterInterface
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 *
 * @package Quetzal\Data\Common\Import
 */
interface ImporterInterface
{
	/**
	 * Импортирует одну сущность
	 *
	 * @param mixed $item
	 *
	 * @return bool
	 */
	public function import($item);
}
