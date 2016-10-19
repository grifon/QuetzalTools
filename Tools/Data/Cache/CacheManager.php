<?php

namespace Quetzal\Tools\Data\Cache;

use CPHPCache;
use Quetzal\Exception\Tools\Data\Cache\CacheException;

/**
 * Менеджер кеша
 *
 * Class CacheManager
 *
 * @package Quetzal\Data\Cache
 */
class CacheManager
{
	/**
	 * Корневая папка кеша
	 */
	const STORE_DIR = 'quetzal';

	/**
	 * Формирует путь сохранения кеша
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	protected function getCachePath($key)
	{
		return sprintf('/%s/%s', self::STORE_DIR, $key);
	}

	/**
	 * Получает данные из кеша
	 *
	 * @param string $key
	 * @param int $ttl
	 *
	 * @return mixed
	 */
	public function get($key, $ttl = 86400)
	{
		$cache = new CPHPCache;

		if ($cache->InitCache($ttl, $key, $this->getCachePath($key))) {
			$data = $cache->GetVars();

			return isset($data[$key]) ? $data[$key] : null;
		}

		return null;
	}

	/**
	 * Сохраняет данные в кеш
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param array $tags
	 * @param int $ttl
	 */
	public function set($key, $value, array $tags = array(), $ttl = 86400)
	{
		$cache = new CPHPCache;

		$path = $this->getCachePath($key);

		$cache->StartDataCache($ttl, $key, $path);

		if (!empty($tags)) {
			global $CACHE_MANAGER;

			$CACHE_MANAGER->StartTagCache($path);

			foreach ($tags as $tag) {
				$CACHE_MANAGER->RegisterTag($tag);
			}

			$CACHE_MANAGER->EndTagCache();
		}

		$cache->EndDataCache(array($key => $value));
	}

	/**
	 * Формирует путь для сохранения кеша из названия метода
	 *
	 * @param string $method - __METHOD__ (например: 'Quetzal\Tools\Data\Cache\CacheManager::getCacheDirByMethod')
	 * @return string
	 * @throws \Quetzal\Exception\Tools\Data\Cache\CacheException
	 */
	public function getCacheDirByMethod($method)
	{
		if (empty($method)) {
			throw new CacheException('Empty $method');
		}

		$cacheDir = preg_replace('~::|\\\\~', '/', $method);

		if (empty($cacheDir)) {
			throw new CacheException('Empty $cacheDir');
		}

		return $cacheDir;
	}

	/**
	 * Сбрасывает кеш раздела инфоблока по тегу
	 *
	 * @param $iblockId
	 * @throws \Quetzal\Exception\Tools\Data\Cache\CacheException
	 */
	public function clearSectionCacheByIblockId($iblockId)
	{
		if (empty($iblockId)) {
			throw new CacheException('Empty $iblockId');
		}

		global $CACHE_MANAGER;

		$CACHE_MANAGER->ClearByTag($this->generateSectionTag($iblockId));
	}

	/**
	 * Формирует тег кеша для раздела инфоблока
	 *
	 * @param $iblockId
	 * @return string
	 * @throws \Quetzal\Exception\Tools\Data\Cache\CacheException
	 */
	public function generateSectionTag($iblockId)
	{
		if (empty($iblockId)) {
			throw new CacheException('Empty $iblockId');
		}

		return sprintf('section_iblock_id_%s', $iblockId);
	}

	/**
	 * Сброс кеша на событиях OnBeforeIBlockSectionAdd и OnBeforeIBlockSectionUpdate
	 *
	 * Включить сброс кеша при добавлении и изменении разделов можно добавив в init.php код:
	 *
	 * AddEventHandler(
	 * 	'iblock',
	 * 	'OnBeforeIBlockSectionAdd',
	 * 	array('\Quetzal\Tools\Data\Cache\CacheManager', 'clearSectionCache')
	 * );
	 * AddEventHandler(
	 * 	'iblock',
	 * 	'OnBeforeIBlockSectionUpdate',
	 * 	array('\Quetzal\Tools\Data\Cache\CacheManager', 'clearSectionCache')
	 * );
	 */
	public function clearSectionCache(array &$arParams)
	{
		if (!empty($arParams['IBLOCK_ID'])) {
			$cache = new CacheManager;

			$cache->clearSectionCacheByIblockId($arParams['IBLOCK_ID']);
		}
	}
}
