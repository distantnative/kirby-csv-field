<?php

namespace distantnative\CsvField;

use Kirby\Cms\App;
use Kirby\Content\Field;

function csv(string $file, string $delimiter = ','): array
{
    $lines    = file($file);
    $lines[0] = str_replace("\xEF\xBB\xBF", '', $lines[0]);

    $csv = array_map(fn ($d) => str_getcsv($d, $delimiter), $lines);

    array_walk($csv, function(&$a) use ($csv) {
       $a = array_combine($csv[0], $a);
    });

    array_shift($csv);

    return $csv;
}

App::plugin('distantnative/kirby-csv-field', [
	'fields' => [
		'csv' => [
			'extends' => 'files',
			'props' => [
				/**
				 * Unset inherited props
				 */
				'default' => null,
				'empty'   => null,
				'image'   => null,
				'info'    => null,
				'layout'  => null,
				'link'    => null,
				'max'     => null,
				'query'   => null,
				'search'  => null,
				'size'    => null,
				'text'    => null,

				'columns' => function (array $columns = null) {
					return $columns;
				},
				'delimiter' => function (string $delimiter = ',') {
					return $delimiter;
				},
				'limit' => function (int $limit = null) {
					return $limit;
				},
				'multiple' => function (bool $multiple = null) {
					return false;
				},
				'uploads' => function ($uploads = []) {
					if ($uploads === false) {
						return false;
					}

					if (is_string($uploads) === true) {
						$uploads = ['template' => $uploads];
					}

					if (is_array($uploads) === false) {
						$uploads = [];
					}

					$uploads['accept'] = '.csv';

					return $uploads;
				},
			],
			'api' => fn () => [
				...(require $this->kirby()->core()->fields()['files'])['api'](),
				[
					'pattern' => 'preview',
					'method'  => 'GET',
					'action'  => function () {
						$file  = $this->requestQuery('file');
						$file  = $this->field()->model()->file($file);
						return csv($file->root(), $this->field()->delimiter());
					}
				]
			]
		]
	],
	'fieldMethods' => [
		'toCsv' => function (Field $field, string $delimiter = ','): array {
			$files = $field->toFiles();

            if ($file = $files->first()) {
				return csv($file->root(), $delimiter);
			}

			return [];
        }
	],
	'translations' => require_once __DIR__ . '/i18n/index.php'
]);
