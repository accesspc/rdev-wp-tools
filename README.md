# RDev Wordpress Tools

A Wordpress plugin to provide some bloat-less tools for day-to-day work.

## Includes

* Google Analytics tracking code
* Password Generator via shortcode

## Development

To build and test locally, run:

```bash
docker compose up
```

* Website is available on port `8080`: [here](http://localhost:8080/)
* MySQL database is available on port `3306`

## Language / Translations

```bash
cd rdev-wp-tools
composer require --dev wp-cli/wp-cli-bundle
composer make-pot

composer remove --dev wp-cli/wp-cli-bundle
```
