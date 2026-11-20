# GoREST API Tests

Codeception tests for the [GoREST API](https://gorest.co.in/), covering user
listing, filtering, validation, and CRUD behavior.

## Requirements

- PHP
- Composer
- A GoREST API token for the authenticated tests

## Setup

Install the development dependencies:

```bash
composer install
```

Set the API token in the environment:

```bash
export GOREST_TOKEN=your-token
```

## Running the tests

Run the API suite:

```bash
vendor/bin/codecept run Api
```

The tests use `https://gorest.co.in/public/v3` as the API base URL. Tests that
create users clean them up after each scenario.
