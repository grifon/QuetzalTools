<?php

namespace Quetzal\Test\Service\Tools\CDN;

use Quetzal\Service\Tools\CDN\FilePathGenerator;

/**
 * Тестовый набор для сервиса генератора путей в CDN
 *
 * Class PathGeneratorTest
 *
 * @package Quetzal\Test\Service\Tools\CDN
 */
class FilePathGeneratorTest extends \PHPUnit_Framework_TestCase
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
		$generator = FilePathGenerator::getInstance();
		$generator->setDomains($domains);

		foreach ($results as $result) {
			$this->assertEquals($result, $generator->generateCDNUri($uri));
		}
	}
}
