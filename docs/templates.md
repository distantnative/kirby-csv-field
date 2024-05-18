# Use in templates

To use your `csv` field in your templates, the plugin also ships with a `->toCsv()` field method.

```php
$csv = $page->myCsvField()->toCsv(';');
```

## `Csv` object

The field method returns a `distantnative\CsvField\Csv` object. The `Csv` object is basically a [Kirby collection](https://getkirby.com/docs/reference/objects/toolkit/collection):

```php
$csv->first();
$csv->last();
$csv->paginate();
// ...
```

With a little added sugar for rows and columns:

```php
var_dump($csv->columns());
```

```
array (size=4)
  0 => string 'Username' (length=8)
  1 => string 'Identifier' (length=10)
  2 => string 'First name' (length=10)
  3 => string 'Last name' (length=9)
```

```php
var_dump($csv->rows());
```

```
array (size=5)
  0 =>
    array (size=4)
      'Username' => string 'booker12' (length=8)
      'Identifier' => string '9012' (length=4)
      'First name' => string 'Rachel' (length=6)
      'Last name' => string 'Booker' (length=6)
  1 =>
    array (size=4)
      'Username' => string 'grey07' (length=6)
      'Identifier' => string '2070' (length=4)
      'First name' => string 'Laura' (length=5)
      'Last name' => string 'Grey' (length=4)
  2 =>
    array (size=4)
      'Username' => string 'johnson81' (length=9)
      'Identifier' => string '4081' (length=4)
      'First name' => string 'Craig' (length=5)
      'Last name' => string 'Johnson' (length=7)
  3 =>
    array (size=4)
      'Username' => string 'jenkins46' (length=9)
      'Identifier' => string '9346' (length=4)
      'First name' => string 'Mary' (length=4)
      'Last name' => string 'Jenkins' (length=7)
  4 =>
    array (size=4)
      'Username' => string 'smith79' (length=7)
      'Identifier' => string '5079' (length=4)
      'First name' => string 'Jamie' (length=5)
      'Last name' => string 'Smith' (length=5)

```

## Example

```php
<?php if ($csv = $page->myCsvField()->toCsv(';')): ?>
<table>
  <thead>
    <?php foreach ($csv->columns() as $column): ?>
    <th><?= $column ?></th>
    <?php endforeach ?>
  </thead>
  <tbody>
    <?php foreach ($csv->rows() as $row): ?>
    <tr>
      <?php foreach ($row as $cell): ?>
      <td><?= $cell ?></td>
      <?php endforeach ?>
    </tr>
    <?php endforeach ?>
  </tbody>
</table>
<?php endif ?>
```
