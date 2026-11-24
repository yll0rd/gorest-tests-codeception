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

Create a `.env` file in the project root with your GoREST API token:

```bash
GOREST_TOKEN=your-token
```

The `.env` file is ignored by Git. Load it before running the tests:

```bash
set -a
source .env
set +a
```

## Running the tests

Run the API suite:

```bash
vendor/bin/codecept run Api
```

The tests use `https://gorest.co.in/public/v3` as the API base URL. Tests that
create users clean them up after each scenario.
