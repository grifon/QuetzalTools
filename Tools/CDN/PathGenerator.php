<?php

namespace Quetzal\Tools\CDN;

/**
 * Генерирует путь к файлам с CDN
 *
 * Class PathGenerator
 *
 * @package Quetzal\Tools\CDN
 *
 * @author GrigoryBychek <gbychek@gmail.com>
 */
class PathGenerator
{
	/**
	 * Домены CDN
	 *
	 * @var array
	 */
	protected $domains = array();

	/**
	 * Указатель позиции очередного домена
	 *
	 * @var int
	 */
	protected $index = 0;

	/**
	 * PathGenerator constructor
	 *
	 * @param array $domains
	 */
	public function __construct(array $domains)
	{
		if (empty($domains)) {
			throw new \InvalidArgumentException('Domains list should be not empty');
		}

		$this->domains = $domains;
	}

	/**
	 * Строит uri из CDN по простому uri
	 *
	 * @param string $uri Адрес относительно корня сайта
	 *
	 * @return string
	 */
	public function generateCDNUri($uri)
	{
		return sprintf('//%s%s', $this->getDomain(), $uri);
	}

	/**
	 * Получает очередной домен, с которого должен отдаваться файл
	 *
	 * @return string
	 */
	protected function getDomain()
	{
		$domain = $this->domains[$this->index];

		$this->incIndex();

		return $domain;
	}

	/**
	 * Циклически увеличивает индекс указателя списка доменов
	 */
	protected function incIndex()
	{
		if ($this->index == count($this->domains) - 1) {
			$this->index = 0;
		} else {
			$this->index++;
		}
	}
}
