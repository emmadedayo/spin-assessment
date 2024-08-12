
---

# ------------ Spin Career Assessment 

## Overview

This project is a task management system with user authentication, task creation, reading, updating, and deletion functionalities. It is built using Laravel for the backend and Docker for containerization. The project employs design patterns like Repository Pattern and uses Docker for consistent development environments.

## Table of Contents

1. [Prerequisites](#prerequisites)
2. [Setup](#setup)
3. [Running the Project](#running-the-project)
4. [Testing](#testing)
5. [Endpoints](#endpoints)
6. [Design Patterns Used](#design-patterns-used)
7. [Docker Setup](#docker-setup)
8. [Makefile Commands](#makefile-commands)
9. [Contributing](#contributing)
10. [License](#license)

## Prerequisites

Before running this project, ensure you have the following installed:

- [Docker](https://www.docker.com/)
- [Make](https://www.gnu.org/software/make/)
- [PHP](https://www.php.net/) (if running outside Docker)

## Setup

### Clone the Repository

```bash
git clone git@github.com:emmadedayo/spin-assessment.git
cd spin-assessment
```

### Install Dependencies

If using Docker, skip to the Docker Setup section. If running locally, you need to install the PHP dependencies:

```bash
composer install
```

### Configure Environment

Create a `.env` file by copying the example file:

```bash
cp .env.example .env
```

Update the `.env` file with your environment settings, including database credentials and other configurations.

## Running the Project

### Using Docker

1. **Build Docker Images**

   ```bash
   make build
   ```

2. **Run Docker Containers**

   ```bash
   make start
   ```

   This will start your application, database, and any other required services.

3. **Run Migrations**

   ```bash
   make migrate
   ```

4. **Access the Application**

   Visit `http://localhost:8000` in your browser.

### Without Docker

1. **Start PHP Server**

   ```bash
   php artisan serve
   ```

   The application will be available at `http://localhost:8000`.

2. **Run Migrations**

   ```bash
   php artisan migrate
   ```

## Testing

### Unit Tests

Run unit tests using:

```bash
php artisan test --testsuite=unit
```

### Feature Tests

Run feature tests using:

```bash
php artisan test --testsuite=feature
```

### Using Docker

To run tests inside the Docker container:

```bash
make test
```

## Endpoints
This project has the following endpoints and it can be accessed via Postman or any other API testing tool.
https://documenter.getpostman.com/view/3080167/2sA3s4k9z1


## Design Patterns Used

### Repository Pattern

- **Interfaces**:
    - `UserRepositoryInterface`
    - `TaskRepositoryInterface`

- **Repositories**:
    - `UserRepository`
    - `TaskRepository`

The Repository Pattern helps in abstracting the data layer and provides a clean API for interacting with the data source.

### Factory Pattern

- **UserFactory**: Generates test users.
- **TaskFactory**: Generates test tasks.

### Service Layer

- `UserService` handles user-related operations.
- `TaskService` handles task-related operations.

## Docker Setup

The project uses Docker to ensure consistent environments for development and testing. The `docker-compose.yml` file includes:

- `app`: The PHP application container.
- `db`: The MySQL database container.

### Docker Commands

- **Build Docker Images**: `docker-compose build`
- **Start Containers**: `docker-compose up -d`
- **Stop Containers**: `docker-compose down`
- **Run Migrations**: `docker-compose exec app php artisan migrate`

## Makefile Commands

The `Makefile` includes several commands for development and testing:

- **Install Dependencies**: `make install`
- **Run Tests**: `make test`
- **Run Migrations**: `make migrate`

### Example Usage

```bash
make install
make migrate
make test
```

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---
# spin-assessment
# spin-assessment
# spin-assessment
# spin-assessment
