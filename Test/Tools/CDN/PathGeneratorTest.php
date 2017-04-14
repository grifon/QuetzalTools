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
	 * Провайдер для метода testGenerateCDNUri
	 *
	 * @return array
	 */
	public function generateCDNUriProvider()
	{
		return array(
			array(
				array(
					'img1.site.ru',
					'img2.site.ru',
				),
				'/upload/iblock/000/image.jpg',
				array(
					'//img1.site.ru/upload/iblock/000/image.jpg',
				)
			),
			array(
				array(
					'img1.site.ru',
				),
				'/upload/iblock/000/image.jpg',
				array(
					'//img1.site.ru/upload/iblock/000/image.jpg',
				)
			),
			array(
				array(
					'cdn.ru',
					'img2.site.ru',
				),
				'/upload/iblock/000/image.jpg',
				array(
					'//cdn.ru/upload/iblock/000/image.jpg',
				)
			),
			array(
				array(
					'img1.site.ru',
					'img2.site.ru',
				),
				'/upload/iblock/000/image.jpg',
				array(
					'//img1.site.ru/upload/iblock/000/image.jpg',
					'//img2.site.ru/upload/iblock/000/image.jpg',
				)
			),
		);
	}

	/**
	 * Тест генерации путей
	 *
	 * @dataProvider generateCDNUriProvider
	 *
	 * @param array $domains
	 * @param string $uri
	 * @param array $results
	 */
	public function testGenerateCDNUri(array $domains, $uri, array $results)
	{
		$generator = new PathGenerator($domains);

		foreach ($results as $result) {
			$this->assertEquals($result, $generator->generateCDNUri($uri));
		}
	}
}
