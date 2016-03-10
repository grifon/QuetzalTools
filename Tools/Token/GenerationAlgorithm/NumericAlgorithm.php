<?php

namespace Quetzal\Tools\Token\GenerationAlgorithm;

/**
 * Генератор цифровой последователности заданной длины
 *
 * Class NumericAlgorithm
 *
 * @package Quetzal\Tools\Token\GenerationAlgorithm
 */
class NumericAlgorithm implements AlgorithmInterface
{
	/**
	 * @var int
	 */
	private $length;

	/**
	 * @param int $length
	 */
	public function __construct($length = 8)
	{
		if ($length < 1) {
			throw new \RuntimeException('Length must be positive value');
		}

		$this->length = $length;
	}

	/**
	 * @return string
	 */
	function generate()
	{
		$raw = '';

		do {
			$raw .= abs(crc32(microtime()));
		} while (strlen($raw) < $this->length);

		return substr($raw, 0, $this->length);
	}
}
