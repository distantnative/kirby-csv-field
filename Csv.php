<?php

namespace distantnative\CsvField;

use Kirby\Toolkit\Collection;

/**
 * @package   CSV Field
 * @author    Nico Hoffmann <nico@getkirby.com>
 * @link      https://github.com/distantnative/kirby-csv-field
 * @copyright Nico Hoffmann
 * @license   https://opensource.org/licenses/MIT
 */
class Csv extends Collection
{
	public function columns(): array
	{
		if ($first = $this->first()) {
			return array_keys($first);
		}

		return [];
	}

	public static function for(string $file, string $delimiter = ','): static
	{
		$lines    = file($file);
		$lines[0] = str_replace("\xEF\xBB\xBF", '', $lines[0]);
		$csv      = array_map(fn ($d) => str_getcsv($d, $delimiter), $lines);

		array_walk($csv, fn (&$a) => $a = array_combine($csv[0], $a));
		array_shift($csv);

		return new static($csv);
	}

	public function rows(): array
	{
		return $this->toArray();
	}
}
