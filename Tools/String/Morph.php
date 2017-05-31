<?php

namespace Quetzal\Tools\String;

/**
 * Класс для морфинга различных значений
 *
 * Class Morph
 *
 * @package Quetzal\Tools\String
 *
 * @author Sergey Starovoyt <starovoyt.s@gmail.com>
 */
class Morph
{
	/**
	 * Выбирает подходящую форму слова для количества (1 товар, 2 товара, 5 товаров)
	 *
	 * @param int $value
	 * @param array $forms Список с формами для 1, 2 и 5 объектов
	 *
	 * @return string
	 */
	public function pluralForm($value, array $forms)
	{
		return $value % 10 == 1 && $value % 100 != 11
			? $forms[0]
			: ($value % 10 >= 2 && $value %10 <= 4 && ($value % 100 < 10 || $value % 100 >= 20) ? $forms[1] : $forms[2]);
	}
}
