<?php

namespace distantnative\CsvField;

use Kirby\Cms\App;
use Kirby\Content\Field;

load([
	'distantnative\CsvField\Csv' => __DIR__ . '/Csv.php'
]);

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
						$file = $this->requestQuery('file');
						$csv  = Csv::for(
							$this->field()->model()->file($file)->root(),
							$this->field()->delimiter()
						);
						return $csv->rows();
					}
				]
			]
		]
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
