# Getting started

## Requirements

The CSV field requires Kirby 4.

## Install

There are two main ways to install this plugin:

1. [<Badge type="tip" text="Download" />](https://api.github.com/repos/distantnative/kirby-csv-field/zipball) & unzip
2. Copy this repository to `/site/plugins/csv-field`.
3. Add the field to your blueprint

```yml
fields:
  myCsvField:
    type: csv
    label: My CSV field
```

See all [blueprint options](/field).

### With `composer`

Alternatively, you can install it with composer:

```bash
composer require distantnative/kirby-csv-field
```

## Updates

Make sure to read the [release notes](https://github.com/distantnative/kirby-csv-fieldy/releases) for breaking changes before you start the update.

1. [<Badge type="tip" text="Download" />](https://api.github.com/repos/distantnative/kirby-csv-fieldy/zipball) & unzip the new version
2. Replace the `/site/plugins/csv-field` folder

Or, if you installed the plugin via composer, run:

```bash
composer update distantnative/kirby-csv-fieldy
```
