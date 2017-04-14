<?php

namespace Quetzal\Test\Tools\CDN;

use Quetzal\Tools\CDN\PathGenerator;

/**
 * Тестовый набор для генератора путей в CDN
 *
 * Class PathGeneratorTest
 *
 * @package Quetzal\Test\Tools\CDN
 */
class PathGeneratorTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Тест генерации путей
	 */
	public function testGenerateCDNUri()
	{
		$generator = new PathGenerator(
			array(
				'img1.site.ru',
				'img2.site.ru'
			)
		);

		$this->assertEquals(
			'//img1.site.ru/upload/iblock/000/image.jpg',
			$generator->generateCDNUri('/upload/iblock/000/image.jpg')
		);
	}
}
