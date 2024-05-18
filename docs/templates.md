# Use in templates

```php
$table = $page->myCsvField()->toCsv(';');

var_dump($table);
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
