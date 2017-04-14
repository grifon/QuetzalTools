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
		$this->domains = $domains;
	}

	/**
	 * Строит uri из CDN по простому uri
	 *
	 * @param string $uri
	 *
	 * @return string
	 */
	public function generateCDNUri($uri)
	{
		return '';
	}
}
