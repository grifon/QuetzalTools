Пример использования менеджера кеша:

```php
<?
use Quetzal\Tools\Data\Cache\CacheManager;

class CacheTest
{
	public static function getData($integer)
	{
		return rand($integer, $integer * 2);
	}

	public static function getDataCached($integer)
	{
		$cache = new CacheManager;

		$cacheId = sprintf('%s/%s', $cache->getCacheDirByMethod(__METHOD__), $integer);

		if (!($result = $cache->get($cacheId))) {
			$result = self::getData($integer);

			$tags = array();

			if (!empty($iblockId)) {
				$tags[] = sprintf('iblock_id_%s', $iblockId);
			}

			$cache->set($cacheId, $result, $tags);
		}

		return $result;
	}
}

$result = CacheTest::getDataCached(123);

echo '<pre>';
var_dump($result);
echo '</pre>';
```
