<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Invoice App

This is a Laravel-based invoice application. The project is set up to run using Docker Compose.

## Prerequisites

- Docker
- Docker Compose

## Getting Started

Follow these steps to get the project up and running:

### 1. Clone the Repository

```sh
git clone https://github.com/yourusername/invoice-app.git
cd invoice-app
```

### 2. Set Up Environment Variables

Copy the `.env.example` file to `.env` and update the environment variables as needed:

```sh
cp .env.example .env
```

### 3. Build and Start the Containers

Use the Makefile to build and start the Docker containers:

```sh
make up
```

### 4. Install PHP Dependencies

Install the PHP dependencies using Composer:

```sh
make install
```

### 5. Run Database Migrations

Run the database migrations to set up the database schema:

```sh
make migrate
```

### 6. Seed the Database

(Optional) Seed the database with initial data:

```sh
make seed
```

### 7. Access the Application

The application should now be running and accessible at [http://localhost:8080](http://localhost:8080).

## Useful Commands

Here are some useful commands you can use with the Makefile:

- **Start the containers**: `make up`
- **Stop the containers**: `make down`
- **Restart the containers**: `make restart`
- **Restart the app container**: `make restart-app`
- **Restart the web container**: `make restart-web`
- **Restart the db container**: `make restart-db`
- **Run database migrations**: `make migrate`
- **Seed the database**: `make seed`
- **Install PHP dependencies**: `make install`
- **Run tests**: `make test`
- **View logs for all containers**: `make logs`
- **View logs for the app container**: `make logs-app`
- **View logs for the web container**: `make logs-web`
- **View logs for the db container**: `make logs-db`
- **Access the app container via bash**: `make bash`
- **Access the MySQL shell**: `make db`
- **Inspect the db container**: `make inspect-db`

## Exiting the MySQL Shell

To exit the MySQL shell, type `exit` or `quit` and press Enter:

```sql
mysql> exit;
Bye
```

or

```sql
mysql> quit;
Bye
```

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

This project is licensed under the MIT License.
