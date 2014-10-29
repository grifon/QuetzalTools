<?php

namespace Quetzal\Tools;

/**
 * Генератор XML-кода
 *
 * Class XMLGenerator
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 *
 * @package Quetzal\Tools
 */
class XMLGenerator
{
	/**
	 * Тип для непустого XML-элемента
	 */
	const ELEMENT_REGULAR = 'regular';

	/**
	 * Тип для пустого XML-элемента
	 */
	const ELEMENT_EMPTY = 'empty';

	/**
	 * Создает элемент для XML-документа
	 *
	 * @param string $name
	 * @param string $type
	 * @param string $content
	 * @param array $attributes
	 *
	 * @return string
	 */
	public function generateElement($name, $type = self::ELEMENT_REGULAR, $content = '', array $attributes = array())
	{
		return $type == self::ELEMENT_REGULAR
			? $this->generateRegularElement($name, $content, $attributes)
			: $this->generateEmptyElement($name, $attributes);
	}

	/**
	 * Создает непустой элемент
	 *
	 * @param string $name
	 * @param string $content
	 * @param array $attributes
	 *
	 * @return string
	 */
	public function generateRegularElement($name, $content = '', array $attributes = array())
	{
		return sprintf(
			"<%s%s>%s</%s>\n",
			$name,
			empty($attributes) ? '' : ' ' . implode(' ', $this->attributesToStrings($attributes)),
			$content,
			$name
		);
	}

	/**
	 * Создает пустой элемент
	 *
	 * @param string $name
	 * @param array $attributes
	 *
	 * @return string
	 */
	public function generateEmptyElement($name, array $attributes = array())
	{
		return sprintf(
			"<%s%s />\n",
			$name,
			empty($attributes) ? '' : ' ' . implode(' ', $this->attributesToStrings($attributes))
		);
	}

	/**
	 * @param array $attributes
	 *
	 * @return array
	 */
	protected function attributesToStrings(array $attributes)
	{
		$attributesAsStrings = array();

		foreach ($attributes as $key => $value) {
			$attributesAsStrings[] = sprintf('%s="%s"', $key, $value);
		}

		return $attributesAsStrings;
	}
}
