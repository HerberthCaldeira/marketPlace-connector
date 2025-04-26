
# MarketPlace Connector

## Installation

```bash
composer install
```

## Configuration

```bash
cp .env.example .env
```

Set the following environment variables in the .env file if you are not using docker:

```bash
MARKETPLACE_URL=
HUB_URL=
```

## Running

```bash
sail up
```

Run queue worker:

```bash
sail artisan horizon
```

Migrate database:

```bash
sail artisan migrate
```




