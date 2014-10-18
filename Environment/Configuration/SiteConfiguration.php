<?php

namespace Quetzal\Environment\Configuration;

/**
 * Конфигурация для конкретного сайта
 *
 * Class SiteConfiguration
 *
 * @author Grigory Bychek <gbychek@gmail.com>
 */
class SiteConfiguration extends CommonConfiguration
{
	/**
	 * id сайта, для которого эта конфигурация
	 * @var string
	 */
	protected $site;

	public function __construct($siteId, array $config = array())
	{
		parent::__construct($config);

		$this->site = $siteId;
	}

	/**
	 * @return string
	 */
	public function getSiteId()
	{
		return $this->site;
	}
} 