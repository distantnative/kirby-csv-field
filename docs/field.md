# Panel field

```yml
myCsvField:
  type: csv
  label: My CSV field
  delimiter: ;
```

![CSV field in the Panel](/csv-field.png)

## Custom columns

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
