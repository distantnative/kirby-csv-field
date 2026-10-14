<?php

namespace distantnative\CsvField;

use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Csv::class)]
class CsvTest extends TestCase
{
	public function testColumns(): void
	{
		$csv = Csv::for(static::FIXTURES . '/users.csv');

		$this->assertSame(
			['Username', 'Identifier', 'First name', 'Last name'],
			$csv->columns()
		);
	}

	public function testColumnsWithoutRows(): void
	{
		$csv = Csv::for(static::FIXTURES . '/header.csv');

		$this->assertSame([], $csv->columns());
		$this->assertSame([], $csv->rows());
	}

	public function testFor(): void
	{
		$csv = Csv::for(static::FIXTURES . '/users.csv');

		$this->assertCount(5, $csv);
		$this->assertSame([
			'Username'   => 'booker12',
			'Identifier' => '9012',
			'First name' => 'Rachel',
			'Last name'  => 'Booker'
		], $csv->first());
	}

	public function testForWithBom(): void
	{
		$csv = Csv::for(static::FIXTURES . '/bom.csv');

		$this->assertSame(['Username', 'Identifier'], $csv->columns());
	}

	public function testForWithDelimiter(): void
	{
		$comma     = Csv::for(static::FIXTURES . '/users.csv');
		$semicolon = Csv::for(static::FIXTURES . '/semicolon.csv', ';');

		$this->assertSame($comma->rows(), $semicolon->rows());
	}

	public function testForWithQuotedValues(): void
	{
		$csv = Csv::for(static::FIXTURES . '/quoted.csv');

		$this->assertSame([
			[
				'Name'  => 'Booker, Rachel',
				'Quote' => 'She said "hi"'
			]
		], $csv->rows());
	}

	public function testRowsPaginated(): void
	{
		$csv = Csv::for(static::FIXTURES . '/users.csv');
		$csv = $csv->paginate(['page' => 2, 'limit' => 2]);

		$rows = $csv->rows();

		$this->assertTrue(array_is_list($rows));
		$this->assertSame('johnson81', $rows[0]['Username']);
		$this->assertSame('jenkins46', $rows[1]['Username']);
	}
}
