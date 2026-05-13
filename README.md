## Installation
composer require laravel/laravel

## Note
The database schema was designed around three main entities:
locations, sensors, and visitors.

Each sensor belongs to one location, and each visitor record belongs to both a location and a sensor.

Indexes were added on frequently queried fields such as status, date, location_id, and sensor_id to improve filtering and aggregation performance.