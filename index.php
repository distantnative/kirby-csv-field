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
						$field = $this->field();
						$file  = $this->requestQuery('file');

						// read file and turn into Csv collection
						$csv = Csv::for(
							$field->model()->file($file)->root(),
							$field->delimiter()
						);

						// gather columns or use
						// manually configured columns
						$columns = [];

						foreach ($field->columns() ?? $csv->columns() as $key => $column) {
							$key = match (true) {
								is_array($column) => $column['key'] ?? $key,
								is_string($key)   => $key,
								default           => $column
							};

							$columns[$key] = match (true) {
								is_array($column) => $column,
								default           => ['label' => $column]
							};
						}

						// paginate rows
						if ($limit = $field->limit()) {
							$csv = $csv->paginate([
								'page'  => $this->requestQuery('page', 1),
								'limit' => $limit
							]);
						}

						return [
							'columns'    => $columns,
							'rows'       => $csv->rows(),
							'pagination' => $csv->pagination()?->toArray() ?? false
						];
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
