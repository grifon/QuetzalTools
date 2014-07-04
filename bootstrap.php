<?php
spl_autoload_register(
	function($class) {
		$catalogs = array('Configuration', 'Helper');

		foreach ($catalogs as $catalog) {
			$fileName = sprintf('%s/%s.php', $catalog, $class);

			if (file_exists($fileName)) {
				require_once $fileName;
			}
		}
	}
);

use EnvironmentHelper as EH;
