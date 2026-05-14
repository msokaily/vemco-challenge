# Vemco Coding Challenge – Laravel Developer

Backend API built with Laravel to manage locations, sensors, and visitor analytics for a simple admin dashboard.

The project implements RESTful APIs, Eloquent ORM, MySQL, Redis caching, API Resources, Feature Tests, and an optional Docker setup.

---

## Requirements

- PHP 8.4+
- Composer
- MySQL 8+
- Redis
- Laravel latest stable version
- Docker & Docker Compose optional

---

## Features

- Manage locations
- Manage sensors
- Filter sensors by status: `active` or `inactive`
- Manage visitor analytics
- Filter visitor records by date
- Summary endpoint for:
  - Total visitor count over the past 7 days
  - Active vs inactive sensor count
- Redis caching using cache tags
- Service + Interface architecture
- Form Request validation
- API Resource responses
- Feature tests
- Optional Docker Compose setup

---

## Architecture

The project uses a clean service-oriented structure:

```text
Controller
    ↓
Service Interface
    ↓
Service Implementation
    ↓
Eloquent Model / Cache / Database
```

Example:

```text
SensorController
    ↓
SensorServiceInterface
    ↓
SensorService
    ↓
Sensor Model + Redis Cache
```

This keeps controllers thin and moves business logic into dedicated services.

---

## Installation Without Docker

### 1. Clone the repository

```bash
git clone <your-repository-url>
cd <project-folder>
```

### 2. Install dependencies

```bash
composer install
```

### 3. Create environment file

```bash
cp .env.example .env
```

### 4. Generate application key

```bash
php artisan key:generate
```

### 5. Configure database and Redis

Update your `.env` file:

```env
APP_NAME="Vemco Challenge"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vemco_challenge
DB_USERNAME=root
DB_PASSWORD=

CACHE_STORE=redis
QUEUE_CONNECTION=sync

REDIS_CLIENT=predis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

Create the database manually:

```sql
CREATE DATABASE vemco_challenge;
```

### 6. Run migrations and seeders

```bash
php artisan migrate:fresh --seed
```

### 7. Start the development server

```bash
php artisan serve
```

The API will be available at:

```text
http://127.0.0.1:8000/api
```

---

## Installation With Docker

Docker setup is optional.

### 1. Start containers

```bash
docker compose up -d --build
```

### 2. Generate app key

```bash
docker compose exec app php artisan key:generate
```

### 3. Run migrations and seeders

```bash
docker compose exec app php artisan migrate:fresh --seed
```

### 4. Access the API

```text
http://localhost:8080/api
```

---

## Docker Services

```text
app      Laravel PHP-FPM application
nginx    Web server
mysql    MySQL database
redis    Redis cache server
```

Docker `.env` example:

```env
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=vemco_challenge
DB_USERNAME=vemco
DB_PASSWORD=secret

CACHE_STORE=redis

REDIS_CLIENT=predis
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```

---

## API Endpoints

### Locations

#### Get all locations

```http
GET /api/locations
```

Example response:

```json
{
  "data": [
    {
      "id": 1,
      "name": "Mall A"
    },
    {
      "id": 2,
      "name": "Mall B"
    }
  ]
}
```

#### Create location

```http
POST /api/locations
```

Request body:

```json
{
  "name": "Mall A"
}
```

---

### Sensors

#### Get all sensors

```http
GET /api/sensors
```

#### Filter sensors by status

```http
GET /api/sensors?status=active
GET /api/sensors?status=inactive
```

#### Pagination

```http
GET /api/sensors?page=1&per_page=10
```

Example response:

```json
{
  "data": [
    {
      "id": 1,
      "name": "Sensor 01",
      "status": "active",
      "location": {
        "id": 1,
        "name": "Mall A"
      }
    }
  ],
  "links": {},
  "meta": {}
}
```

#### Create sensor

```http
POST /api/sensors
```

Request body:

```json
{
  "name": "Sensor 04",
  "status": "active",
  "location_id": 1
}
```

---

### Visitors

#### Get visitor records

```http
GET /api/visitors
```

#### Filter by date

```http
GET /api/visitors?date=2025-05-11
```

Example response:

```json
{
  "data": [
    {
      "id": 1,
      "date": "2025-05-11",
      "count": 450,
      "location": {
        "id": 1,
        "name": "Mall A"
      },
      "sensor": {
        "id": 1,
        "name": "Sensor 01",
        "status": "active"
      }
    }
  ]
}
```

#### Create visitor record

```http
POST /api/visitors
```

Request body:

```json
{
  "location_id": 1,
  "sensor_id": 1,
  "date": "2025-05-11",
  "count": 450
}
```

Validation note:

The selected sensor must belong to the selected location.

---

### Summary

#### Get summary

```http
GET /api/summary
```

Example response:

```json
{
  "data": {
    "period": {
      "from": "2025-05-05",
      "to": "2025-05-11"
    },
    "total_visitors_past_7_days": 750,
    "sensors": {
      "active": 1,
      "inactive": 1
    }
  }
}
```

---

## Redis Caching

Redis caching is used for frequently accessed endpoints:

```text
GET /api/sensors
GET /api/visitors
GET /api/summary
```

Cache tags are used to invalidate related cached data cleanly:

```text
sensors
visitors
summary
locations
```

When a sensor or visitor record is created, related cache groups are flushed to prevent stale data.

Example:

```php
Cache::tags(['sensors', 'summary'])->flush();
```

---

## Database Seeders

The project includes seeders for mocked challenge data:

```bash
php artisan migrate:fresh --seed
```

Seeded data includes:

```text
Locations:
- Mall A
- Mall B

Sensors:
- Sensor 01
- Camera 02
- Sensor 03

Visitors:
- Sample visitor analytics records
```

---

## Running Tests

The project includes Feature Tests for:

```text
Locations API
Sensors API
Visitors API
Summary API
```

Run all tests:

```bash
php artisan test
```

Run a specific test file:

```bash
php artisan test --filter=LocationApiTest
php artisan test --filter=SensorApiTest
php artisan test --filter=VisitorApiTest
php artisan test --filter=SummaryApiTest
```

The testing environment uses SQLite in memory by default:

```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
<env name="CACHE_STORE" value="array"/>
```

---

## Validation Rules

### Location

```text
name: required, string, max:255
```

### Sensor

```text
name: required, string, max:255
status: required, active|inactive
location_id: required, exists in locations table
```

### Visitor

```text
location_id: required, exists in locations table
sensor_id: required, exists in sensors table
date: required, format YYYY-MM-DD
count: required, integer, minimum 0
```

Additional validation:

```text
The selected sensor must belong to the selected location.
```

---

## Useful Commands

Clear Laravel cache:

```bash
php artisan optimize:clear
```

Clear Redis cache:

```bash
php artisan cache:clear
```

Run migrations:

```bash
php artisan migrate
```

Refresh database with seed data:

```bash
php artisan migrate:fresh --seed
```

List routes:

```bash
php artisan route:list --path=api
```

---

## Notes

- Authentication is not required for this challenge.
- The API follows RESTful design.
- The application uses Eloquent ORM.
- Redis is used for caching frequently accessed endpoints.
- API Resources are used for consistent JSON responses.
- Feature tests are included for the main endpoints.
