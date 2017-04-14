<?php

namespace Quetzal\Service\Tools\CDN;

use Quetzal\Common\Exception;
use Quetzal\Common\SingletonInterface;
use Quetzal\Tools\CDN\PathGenerator;

final class FilePathGenerator implements SingletonInterface
{
	/**
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * @var PathGenerator
	 */
	protected $generator = null;

	/**
	 * @return self
	 */
	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct()
	{}

	private function __clone()
	{}

	/**
	 * @param array $domains
	 */
	public function setDomains(array $domains)
	{
		if (is_null($this->generator)) {
			$this->generator = new PathGenerator($domains);
		} else {
			$this->generator->setDomains($domains);
		}
	}

	/**
	 * Генерирует путь к переданному файлу через CDN
	 *
	 * @param string $uri
	 *
	 * @return string
	 *
	 * @throws Exception
	 */
	public function generateCDNUri($uri)
	{
		if (is_null($this->generator)) {
			throw new Exception('You should define domains list first');
		}

		return $this->generator->generateCDNUri($uri);
	}
}
