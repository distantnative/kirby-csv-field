<?php

namespace distantnative\CsvField;

use PHPUnit\Framework\Attributes\CoversNothing;

#[CoversNothing]
class FieldMethodsTest extends TestCase
{
	public function testToCsv(): void
	{
		$app = $this->app(content: ['csv' => '- file://users']);
		$csv = $app->page('test')->csv()->toCsv();

		$this->assertInstanceOf(Csv::class, $csv);
		$this->assertSame('booker12', $csv->first()['Username']);
	}

	public function testToCsvWithDelimiter(): void
	{
		$app = $this->app(content: ['csv' => '- file://semicolon']);
		$csv = $app->page('test')->csv()->toCsv(';');

		$this->assertSame('booker12', $csv->first()['Username']);
	}

	public function testToCsvWithFilename(): void
	{
		$app = $this->app(content: ['csv' => '- users.csv']);
		$csv = $app->page('test')->csv()->toCsv();

		$this->assertSame('booker12', $csv->first()['Username']);
	}

	public function testToCsvWithoutFile(): void
	{
		$app = $this->app();

		$this->assertNull($app->page('test')->csv()->toCsv());
	}
}
