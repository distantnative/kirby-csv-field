<?php

use Kirby\Filesystem\Dir;
use Kirby\Filesystem\F;

$translations = [];

foreach (Dir::files(__DIR__) as $file) {
	$locale = basename($file, '.json');
	if ($content = F::read(__DIR__ . '/' . $file)) {
		$translations[$locale] = json_decode($content, true);
	}
}

return $translations;
