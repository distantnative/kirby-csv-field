# Panel field

```yml
myCsvField:
  type: csv
  label: My CSV field
```

![CSV field in the Panel](/csv-field.png)

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

If the column name/key from the CSV file cannot be used as key inside teh YAML file you can write instead:

```yml
myCsvField:
  type: csv
  columns:
    a:
      key: User names
      label: User
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
