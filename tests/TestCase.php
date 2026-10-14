<?php

namespace distantnative\CsvField;

use Kirby\Cms\App;
use Kirby\Filesystem\Dir;
use Kirby\Filesystem\F;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
	public const string FIXTURES = __DIR__ . '/fixtures';

	protected string $tmp;

	/**
	 * Creates an app with a `test` page that has all
	 * fixtures as files and a `csv` field in its blueprint
	 *
	 * @param $files Additional props per filename
	 */
	protected function app(
		array $field = [],
		array $content = [],
		array $files = [],
		array $props = []
	): App {
		$children = [];

		foreach (Dir::files(static::FIXTURES) as $filename) {
			$children[] = [
				'filename' => $filename,
				'content'  => ['uuid' => F::name($filename)],
				...$files[$filename] ?? []
			];
		}

		return new App([
			'roots'   => ['index' => $this->tmp],
			'options' => ['api.allowImpersonation' => true],
			'site'    => [
				'children' => [
					[
						'slug'      => 'test',
						'files'     => $children,
						'content'   => $content,
						'blueprint' => [
							'title'  => 'Test',
							'name'   => 'test',
							'fields' => [
								'csv' => ['type' => 'csv', ...$field]
							]
						]
					]
				]
			],
			...$props
		]);
	}

	protected function field(array $props = [], App|null $app = null): CsvField
	{
		$app ??= $this->app();

		return new CsvField(...[
			'model' => $app->page('test'),
			'name'  => 'csv',
			...$props
		]);
	}

	protected function setUp(): void
	{
		// separate folder for each ParaTest process
		// so parallel tests don't remove each other's files
		$this->tmp = __DIR__ . '/tmp/' . (getenv('TEST_TOKEN') ?: '0');

		// the fixtures are the files of the `test` page
		Dir::copy(static::FIXTURES, $this->tmp . '/content/test');
	}

	protected function tearDown(): void
	{
		Dir::remove($this->tmp);
	}
}
