# Laravel 10 Currency Converter App

This Laravel application is a simple API that allows you to manage users with their hourly rate information. 
Additionally, it provides a currency conversion feature for displaying hourly rates in different currencies (EUR, USD, GBP).


## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Endpoints](#endpoints)
- [Configuration](#configuration)
- [Testing](#testing)
- [Close Server](#ending)

## Requirements

- Docker (https://docs.docker.com/get-docker/)
- Postman (https://www.postman.com/downloads/)

## Installation

1. Clone the repository:

    ```bash
    git clone https://github.com/iuliancmoldovanu/ezekia-test-api.git
    ```

2. Navigate to the project folder:

    ```bash
    cd ezekia-test-api
    ```

3. Install dependencies:
   - The following bash script requires the installation of Docker at this stage for proper execution.

    ```bash
    docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php83-composer:latest \
    composer install --ignore-platform-reqs
    ```

4. Create a copy of the `.env` file:

    ```bash
    cp .env.example .env
    ```

5. Configure your database and currency converter driver settings in the `.env` file.

6. Serve the application:

    ```bash
    alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
    sail up -d
    ```

7. Run migrations and Seeder:

    ```bash
    sail php artisan migrate
    sail php artisan db:seed --class=DatabaseSeeder
    ```

8. Generate application key:

    ```bash
    sail php artisan key:generate
    ```

## Usage

The API is designed to manage users and convert their hourly rates to different currencies. 
You can interact with the API using tools like `curl`, `Postman`, or any API client.

## Configuration

Configure the application settings in the `.env` file. Important configurations include:

- `DB_CONNECTION`: Database connection (e.g., `mysql`).
- `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`: Database connection details.
- `CURRENCY_CONVERTER_DRIVER`: Specify the currency converter driver (`local` or `api`).
- `EXCHANGE_API_KEY`: The API KEY is already added and can be used when the currency `api` converter driver selected.


## Endpoints
 - To validate the endpoints, import into Postman the JSON file "ezekia-test-api.postman_collection" located at the project's root.

- **GET /api/users**: Get a list of all users.
- **GET /api/users/{id}?currency={currency}**: Get user details and converted hourly rate in the specified currency.
- **POST /api/users**: Create a new user.
- **PUT /api/users/{id}**: Update user information.
- **DELETE /api/users/{id}**: Delete a user.

## Testing

Run PHPUnit tests:

    ✓ can list users                                                                                                                                                                          2.76s  
    ✓ can show user                                                                                                                                                                                          0.05s  
    ✓ can create user                                                                                                                                                                                          0.14s  
    ✓ can update user                                                                                                                                                                                             0.06s  
    ✓ can delete user                                                                                                                                                                                         0.05s  
    ✓ invalid hourly rate validation

```bash
   sail php artisan test --filter UserControllerTest
```

## Ending

```bash
   sail down
```