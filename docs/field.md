# Panel field

```yml
myCsvField:
  type: csv
  label: My CSV field
```

![CSV field in the Panel](/csv-field.png)

The field only lets editors select and upload CSV files.

## Delimiter

Define which character the CSV file uses to separate columns (default: `,`):

```yml
myCsvField:
  type: csv
  delimiter: ;
```

## Pagination

```yml
myCsvField:
  type: csv
  limit: 10
```

## Custom columns

```yml
myCsvField:
  type: csv
  columns:
    Username:
      label: User
    Date:
      label: Last seen
      type: date
```

If the column name/key from the CSV file cannot be used as key inside the YAML file you can write instead:

```yml
myCsvField:
  type: csv
  columns:
    a:
      key: User names
      label: User
```

All columns stay visible when the field is narrow. To hide a column in narrow fields, set `mobile: false`:

```yml
myCsvField:
  type: csv
  columns:
    Username:
      label: User
    Date:
      label: Last seen
      mobile: false
```

## Uploads

### Assign template on upload

```yml
myCsvField:
  type: csv
  uploads:
    template: my-file-template
```

### Prevent uploads

```yml
myCsvField:
  type: csv
  uploads: false
```
