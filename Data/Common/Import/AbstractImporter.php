<?php

namespace Quetzal\Data\Common\Import;

use Quetzal\Tools\LoggerInterface;

/**
 * Абстрактный импортер сущностей
 *
 * Class AbstractImporter
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 *
 * @package Quetzal\Data\Common\Import
 */
abstract class AbstractImporter implements ImporterInterface
{
	/**
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * @param LoggerInterface $logger
	 */
	public function __construct(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}

	/**
	 * @param $message
	 */
	protected function log($message)
	{
		$this->logger->log($message);
	}

	/**
	 * Проверяет необходимость импорта сущности
	 *
	 * @param mixed $item
	 *
	 * @return bool
	 */
	protected function canItemImport($item)
	{
		return true;
	}
}
