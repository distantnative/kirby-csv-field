<?php

namespace distantnative\CsvField;

use Kirby\Exception\NotFoundException;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(CsvField::class)]
class CsvFieldTest extends TestCase
{
	public function testApiPreview(): void
	{
		$app = $this->app();
		$app->impersonate('kirby');

		$preview = $app->api()->call('pages/test/fields/csv/preview', 'GET', [
			'query' => ['file' => 'file://users']
		]);

		$this->assertSame([
			'Username'   => ['label' => 'Username'],
			'Identifier' => ['label' => 'Identifier'],
			'First name' => ['label' => 'First name'],
			'Last name'  => ['label' => 'Last name']
		], $preview['columns']);
		$this->assertCount(5, $preview['rows']);
		$this->assertFalse($preview['pagination']);
	}

	public function testApiPreviewWithDelimiter(): void
	{
		$app = $this->app(field: ['delimiter' => ';']);
		$app->impersonate('kirby');

		$preview = $app->api()->call('pages/test/fields/csv/preview', 'GET', [
			'query' => ['file' => 'file://semicolon']
		]);

		$this->assertSame('booker12', $preview['rows'][0]['Username']);
	}

	public function testApiPreviewWithFilename(): void
	{
		$app = $this->app();
		$app->impersonate('kirby');

		$preview = $app->api()->call('pages/test/fields/csv/preview', 'GET', [
			'query' => ['file' => 'users.csv']
		]);

		$this->assertCount(5, $preview['rows']);
	}

	public function testApiPreviewWithMissingFile(): void
	{
		$app = $this->app();
		$app->impersonate('kirby');

		$this->expectException(NotFoundException::class);

		$app->api()->call('pages/test/fields/csv/preview', 'GET', [
			'query' => ['file' => 'file://missing']
		]);
	}

	public function testApiPreviewWithPage(): void
	{
		$app = $this->app(field: ['limit' => 2]);
		$app->impersonate('kirby');

		$preview = $app->api()->call('pages/test/fields/csv/preview', 'GET', [
			'query' => ['file' => 'file://users', 'page' => 3]
		]);

		$this->assertCount(1, $preview['rows']);
		$this->assertSame('smith79', $preview['rows'][0]['Username']);
		$this->assertSame(3, $preview['pagination']['page']);
		$this->assertSame(2, $preview['pagination']['limit']);
		$this->assertSame(5, $preview['pagination']['total']);
	}

	public function testApiPreviewWithProtectedFile(): void
	{
		// the permission cache is keyed by template and role,
		// so both need to be unique for this test
		$uuid = uuid();

		$app = $this->app(
			files: [
				'users.csv' => ['template' => 'secret-' . $uuid]
			],
			props: [
				'blueprints' => [
					'files/secret-' . $uuid => [
						'options' => ['list' => false]
					]
				],
				'roles' => [
					['name' => 'editor-' . $uuid]
				],
				'users' => [
					[
						'email' => 'editor@getkirby.com',
						'role'  => 'editor-' . $uuid
					]
				]
			]
		);

		// the field must not disclose the content
		// of a file the user must not list
		$app->impersonate('editor@getkirby.com');

		$this->expectException(NotFoundException::class);

		$app->api()->call('pages/test/fields/csv/preview', 'GET', [
			'query' => ['file' => 'file://users']
		]);
	}

	public function testColumns(): void
	{
		$field = $this->field();
		$this->assertNull($field->columns());

		$field = $this->field(['columns' => ['Username']]);
		$this->assertSame(['Username'], $field->columns());
	}

	public function testDelimiter(): void
	{
		$field = $this->field();
		$this->assertSame(',', $field->delimiter());

		$field = $this->field(['delimiter' => ';']);
		$this->assertSame(';', $field->delimiter());
	}

	public function testLimit(): void
	{
		$field = $this->field();
		$this->assertNull($field->limit());

		$field = $this->field(['limit' => 10]);
		$this->assertSame(10, $field->limit());
	}

	public function testPreview(): void
	{
		$field   = $this->field();
		$file    = $field->model()->file('users.csv');
		$preview = $field->preview($file);

		$this->assertSame(
			['Username', 'Identifier', 'First name', 'Last name'],
			array_keys($preview['columns'])
		);
		$this->assertSame('booker12', $preview['rows'][0]['Username']);
		$this->assertFalse($preview['pagination']);
	}

	public function testPreviewWithColumns(): void
	{
		$field = $this->field([
			'columns' => [
				'First name',
				'Username'   => 'User',
				'Identifier' => [
					'label' => 'ID',
					'type'  => 'number'
				],
				'a' => [
					'key'   => 'Last name',
					'label' => 'Name'
				]
			]
		]);

		$file    = $field->model()->file('users.csv');
		$preview = $field->preview($file);

		$this->assertSame([
			'First name' => ['label' => 'First name'],
			'Username'   => ['label' => 'User'],
			'Identifier' => ['label' => 'ID', 'type' => 'number'],
			'Last name'  => ['key' => 'Last name', 'label' => 'Name']
		], $preview['columns']);
	}

	public function testPreviewWithLimit(): void
	{
		$field   = $this->field(['limit' => 2]);
		$file    = $field->model()->file('users.csv');
		$preview = $field->preview($file, 2);

		$this->assertSame(
			['johnson81', 'jenkins46'],
			array_column($preview['rows'], 'Username')
		);
		$this->assertSame(2, $preview['pagination']['page']);
	}

	public function testProps(): void
	{
		$props = $this->field()->props();

		$this->assertSame('csv', $props['type']);
		$this->assertSame(',', $props['delimiter']);
		$this->assertNull($props['columns']);
		$this->assertNull($props['limit']);
		$this->assertFalse($props['multiple']);
		$this->assertSame(['accept' => '.csv'], $props['uploads']);
	}

	public function testType(): void
	{
		$this->assertSame('csv', $this->field()->type());
	}

	public function testUnsupportedProps(): void
	{
		$field = $this->field([
			'empty'    => 'No file yet',
			'layout'   => 'cards',
			'max'      => 3,
			'multiple' => true,
			'query'    => 'site.files',
			'search'   => false,
			'text'     => '{{ file.name }}'
		]);

		$this->assertNull($field->empty());
		$this->assertSame('list', $field->layout());
		$this->assertNull($field->max());
		$this->assertFalse($field->multiple());
		$this->assertNull($field->query());
		$this->assertTrue($field->search());
		$this->assertNull($field->text());
	}

	public function testUploads(): void
	{
		$field = $this->field();
		$this->assertSame(['accept' => '.csv'], $field->uploads());
	}

	public function testUploadsDisabled(): void
	{
		$field = $this->field(['uploads' => false]);
		$this->assertFalse($field->uploads());
	}

	public function testUploadsWithTemplate(): void
	{
		// even if the file template accepts other
		// files, only CSV files can be uploaded
		$app = $this->app(props: [
			'blueprints' => [
				'files/image' => ['accept' => 'image/jpeg']
			]
		]);

		$field = $this->field(['uploads' => 'image'], $app);

		$this->assertSame([
			'accept'   => '.csv',
			'template' => 'image'
		], $field->uploads());
	}
}
