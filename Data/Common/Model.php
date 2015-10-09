<?php

namespace Quetzal\Data\Common;

/**
 * Абстрактный класс модели
 *
 * Class Model
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 *
 * @package Quetzal\Data
 */
abstract class Model
{
	/**
	 * @return mixed
	 */
	abstract public function getId();
}
