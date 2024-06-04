
---

# Laravel Application with Docker

This repository contains a Laravel application set up to run with Docker containers for local development. Follow the instructions below to set up and run the application.

## Prerequisites

Before you begin, ensure you have the following installed on your local machine:

- Docker: [Installation Guide](https://docs.docker.com/get-docker/)
- Docker Compose: [Installation Guide](https://docs.docker.com/compose/install/)

## Getting Started

1. Clone this repository to your local machine:

    ```bash
    git clone <repository-url>
    ```

2. Navigate to the project directory:

    ```bash
    cd <project-directory>
    ```

3. Create a copy of the `.env.example` file and rename it to `.env`:

    ```bash
    cp .env.example .env
    ```

4. Update the `.env` file with your desired configurations, including database settings.

5. Build and start the Docker containers:

    ```bash
    docker-compose up -d
    ```

6. Once the containers are up and running, you can access the Laravel application at [http://localhost:8000](http://localhost:8000).

## Database Migration

To migrate the database, run the following command:

```bash
php artisan migrate
```

## Seeding Data

To seed the database with dummy data, run the following command:

```bash
php artisan db:seed
```

## Running Unit Tests

To run unit tests, execute the following command:

```bash
php artisan test
```

## Additional Commands

- To stop the containers:

    ```bash
    docker-compose down
    ```

- To run Artisan commands inside the app container:

    ```bash
    docker-compose exec app php artisan <command>
    ```

- To access the MySQL database from the command line:

    ```bash
    docker-compose exec db mysql -u<username> -p<password> <database>
    ```

## License

This project is licensed under the [MIT License](LICENSE).
