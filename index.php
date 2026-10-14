<?php

namespace distantnative\CsvField;

use Kirby\Cms\App;
use Kirby\Content\Field;

load([
	'distantnative\CsvField\Csv'      => __DIR__ . '/Csv.php',
	'distantnative\CsvField\CsvField' => __DIR__ . '/CsvField.php'
]);

App::plugin('distantnative/kirby-csv-field', [
	'fields' => [
		'csv' => CsvField::class
	],
	'fieldMethods' => [
		'toCsv' => function (Field $field, string $delimiter = ','): Csv|null {
			if ($file = $field->toFiles()->first()) {
				return Csv::for($file->root(), $delimiter);
			}

			return null;
		}
	],
	'translations' => require_once __DIR__ . '/i18n/index.php'
]);
