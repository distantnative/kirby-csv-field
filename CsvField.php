<?php

namespace distantnative\CsvField;

use Kirby\Api\Api;
use Kirby\Cms\File;
use Kirby\Exception\NotFoundException;
use Kirby\Form\Field\FilePickerField;
use Kirby\Reflection\Attributes\Prop;

/**
 * Panel field to upload, select and preview a single CSV file
 *
 * @link      https://github.com/distantnative/kirby-csv-field
 * @license   https://opensource.org/licenses/MIT
 */
class CsvField extends FilePickerField
{
	/**
	 * Inherited props that cannot be set for the CSV field
	 */
	protected const array UNSUPPORTED = [
		'default',
		'empty',
		'image',
		'info',
		'layout',
		'link',
		'max',
		'multiple',
		'query',
		'search',
		'size',
		'text'
	];

	/**
	 * Columns to show in the table preview,
	 * by default all columns of the CSV file
	 */
	#[Prop]
	protected array|null $columns = null;

	/**
	 * Character that separates the columns in the CSV file
	 */
	#[Prop]
	protected string $delimiter = ',';

	/**
	 * Number of rows per page in the table preview
	 */
	#[Prop]
	protected int|null $limit = null;

	/**
	 * The CSV field always links a single file
	 */
	protected bool $multiple = false;

	public function api(): array
	{
		$field = $this;

		return [
			...parent::api(),
			[
				'pattern' => 'preview',
				'method'  => 'GET',
				'action'  => function () use ($field): array {
					/** @var Api $api */
					$api  = $this;
					$id   = $api->requestQuery('file', '');
					$file = $field->toModel($id);

					// never disclose a file the current user must not list
					if ($file?->isListable() !== true) {
						throw new NotFoundException(
							key: 'file.notFound',
							data: ['filename' => $id]
						);
					}

					return $field->preview(
						$file,
						(int)$api->requestQuery('page', 1)
					);
				}
			]
		];
	}

	public function columns(): array|null
	{
		return $this->columns;
	}

	public function delimiter(): string
	{
		return $this->delimiter;
	}

	public function limit(): int|null
	{
		return $this->limit;
	}

	/**
	 * Returns the columns and rows of the CSV file
	 * for the table preview, paginated if a limit is set
	 */
	public function preview(File $file, int $page = 1): array
	{
		$csv     = Csv::for($file->root(), $this->delimiter());
		$columns = [];

		// gather columns from the file or
		// use the manually configured columns
		foreach ($this->columns() ?? $csv->columns() as $key => $column) {
			$key = match (true) {
				is_array($column) => $column['key'] ?? $key,
				is_string($key)   => $key,
				default           => $column
			};

			$columns[$key] = [
				// keep all columns visible when the field is narrow
				'mobile' => true,
				...match (true) {
					is_array($column) => $column,
					default           => ['label' => $column]
				}
			];
		}

		if ($limit = $this->limit()) {
			$csv = $csv->paginate([
				'page'  => $page,
				'limit' => $limit
			]);
		}

		return [
			'columns'    => $columns,
			'rows'       => $csv->rows(),
			'pagination' => $csv->pagination()?->toArray() ?? false
		];
	}

	protected function register(array $args): void
	{
		parent::register(
			array_diff_key($args, array_flip(static::UNSUPPORTED))
		);
	}

	public function uploads(): array|false
	{
		$uploads = parent::uploads();

		if ($uploads === false) {
			return false;
		}

		// only allow CSV files to be uploaded,
		// even if a file template accepts more
		return [...$uploads, 'accept' => '.csv'];
	}
}
