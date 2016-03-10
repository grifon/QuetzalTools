<?php

namespace Quetzal\Tools\Token\GenerationAlgorithm;

/**
 * Алгоритм формирования токена
 *
 * Interface AlgorithmInterface
 *
 * @package Quetzal\Tools\Token\GenerationAlgorithm
 */
interface AlgorithmInterface
{
	/**
	 * @return string идентификатор токена
	 */
	public function generate();
}
