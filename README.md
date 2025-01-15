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

### Installing Docker and Docker Compose

#### Docker

Follow the instructions on the [Docker website](https://docs.docker.com/get-docker/) to install Docker for your operating system.

#### Docker Compose

Docker Compose is included in Docker Desktop for Windows and macOS. For Linux, follow the instructions on the [Docker Compose website](https://docs.docker.com/compose/install/) to install Docker Compose.

## Getting Started

#### Invoice App Database Schema (ERD)
![erd](https://github.com/user-attachments/assets/4c1a37f3-257f-457f-8bde-2aa66c7ffc23)

Follow these steps to get the project up and running:

### 1. Clone the Repository

```sh
git clone https://github.com/hotaryuzaki/laravel-invoice-app.git
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

### 6. Install API Routes

Run the `install:api` command to set up the API routes:

```sh
make install-api
```
**NOTE:** If you encounter the permission error (`Permission denied` while writing to the log file), please run the below command and do install API again.

please run the below command:
```sh
make cache-clear
docker-compose exec app chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
docker-compose exec app chmod -R 775 /var/www/storage /var/www/bootstrap/cache
docker-compose exec app ls -l /var/www/storage
docker-compose exec app ls -l /var/www/bootstrap/cache
make restart
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
- **Install PHP dependencies**: `make install`
- **Run tests**: `make test`
- **View logs for all containers**: `make logs`
- **View logs for the app container**: `make logs-app`
- **View logs for the web container**: `make logs-web`
- **View logs for the db container**: `make logs-db`
- **Access the app container via bash**: `make bash`
- **Access the MySQL shell**: `make db`
- **Inspect the db container**: `make inspect-db`
- **Install API routes**: `make install-api`

## API Endpoints

The following API endpoints are available:

- **Companies**
  - `GET /api/companies`
  - `POST /api/companies`
  - `GET /api/companies/{company}`
  - `PUT /api/companies/{company}`
  - `DELETE /api/companies/{company}`

- **Customers**
  - `GET /api/customers`
  - `POST /api/customers`
  - `GET /api/customers/{customer}`
  - `PUT /api/customers/{customer}`
  - `DELETE /api/customers/{customer}`

- **Items**
  - `GET /api/items`
  - `POST /api/items`
  - `GET /api/items/{item}`
  - `PUT /api/items/{item}`
  - `DELETE /api/items/{item}`

- **Invoices**
  - `GET /api/invoices`
  - `POST /api/invoices`
  - `GET /api/invoices/{invoice}`
  - `PUT /api/invoices/{invoice}`
  - `DELETE /api/invoices/{invoice}`

## Testing the API Endpoints

Below are `curl` examples for testing CRUD operations for `Companies`, `Customers`, `Items`, and `Invoices`.

---

### **Companies CRUD**

#### 1. **Create a Company** (POST)
```bash
curl -X POST "http://localhost:8080/api/companies" \
-H "Content-Type: application/json" \
-d '{
  "name": "TechCorp Ltd.",
  "address": "123 Silicon Valley, CA",
  "email": "info@techcorp.com"
}'
```

#### 2. **Retrieve All Companies** (GET)
```bash
curl -X GET "http://localhost:8080/api/companies?limit=10&offset=0" \
-H "Accept: application/json"
```

#### 3. **Retrieve a Specific Company** (GET)
```bash
curl -X GET "http://localhost:8080/api/companies/1" \
-H "Accept: application/json"
```

#### 4. **Update a Company** (PATCH)
```bash
curl -X PATCH "http://localhost:8080/api/companies/1" \
-H "Content-Type: application/json" \
-d '{
  "name": "TechCorp International",
  "address": "456 Tech Avenue, NY",
  "email": "contact@techcorp.com"
}'
```

#### 5. **Delete a Company** (DELETE)
```bash
curl -X DELETE "http://localhost:8080/api/companies/1" \
-H "Accept: application/json"
```

---

### **Customers CRUD**

#### 1. **Create a Customer** (POST)
```bash
curl -X POST "http://localhost:8080/api/customers" \
-H "Content-Type: application/json" \
-d '{
  "name": "John Doe",
  "address": "789 Elm Street, Seattle",
  "email": "johndoe@gmail.com"
}'
```

#### 2. **Retrieve All Customers** (GET)
```bash
curl -X GET "http://localhost:8080/api/customers?limit=10&offset=0" \
-H "Accept: application/json"
```

#### 3. **Retrieve a Specific Customer** (GET)
```bash
curl -X GET "http://localhost:8080/api/customers/1" \
-H "Accept: application/json"
```

#### 4. **Update a Customer** (PATCH)
```bash
curl -X PATCH "http://localhost:8080/api/customers/1" \
-H "Content-Type: application/json" \
-d '{
  "name": "Jane Doe",
  "address": "123 Pine Street, LA",
  "email": "janedoe@gmail.com"
}'
```

#### 5. **Delete a Customer** (DELETE)
```bash
curl -X DELETE "http://localhost:8080/api/customers/1" \
-H "Accept: application/json"
```

---

### **Items CRUD**

#### 1. **Create an Item** (POST)
```bash
curl -X POST "http://localhost:8080/api/items" \
-H "Content-Type: application/json" \
-d '{
  "name": "Laptop",
  "type": "hardware"
}'
```

#### 2. **Retrieve All Items** (GET)
```bash
curl -X GET "http://localhost:8080/api/items?limit=10&offset=0" \
-H "Accept: application/json"
```

#### 3. **Retrieve a Specific Item** (GET)
```bash
curl -X GET "http://localhost:8080/api/items/1" \
-H "Accept: application/json"
```

#### 4. **Update an Item** (PATCH)
```bash
curl -X PATCH "http://localhost:8080/api/items/1" \
-H "Content-Type: application/json" \
-d '{
  "name": "Gaming Laptop",
  "description": "Updated high-end gaming laptop",
  "price": 1600
}'
```

#### 5. **Delete an Item** (DELETE)
```bash
curl -X DELETE "http://localhost:8080/api/items/1" \
-H "Accept: application/json"
```

---

### **Invoices CRUD**

#### 1. **Create an Invoice** (POST)
```bash
curl -X POST "http://localhost:8080/api/invoices" \
-H "Content-Type: application/json" \
-d '{
  "company_id": 2,
  "customer_id": 1,
  "subject": "Pembelian Barang",
  "items": [
          {
            "item_id": 1,
            "quantity": 2,
            "unit_price": 10000,
            "amount": 20000
          }, 
          {
            "item_id": 2,
            "quantity": 3,
            "unit_price": 5000,
            "amount": 15000
          }
  ],
  "issued_date": "2025-01-01T10:00:00",
  "due_date": "2025-01-15T10:00:00",
  "sub_total": 35000,
  "tax": 3500,
  "grand_total": 38500
}'
```

#### 2. **Retrieve All Invoices** (GET)
```bash
curl -X GET "http://localhost:8080/api/invoices?limit=10&offset=0&invoiceid={invoiceid}&issueddate={issueddate}&subject={subject}&totalitems={totalitems}&customer={customer}&duedate={duedate}&status={status}" \
-H "Accept: application/json"
```

#### 3. **Retrieve a Specific Invoice** (GET)
```bash
curl -X GET "http://localhost:8080/api/invoices/1" \
-H "Accept: application/json"
```

#### 4. **Update an Invoice** (PATCH)
```bash
curl -X PATCH "http://localhost:8080/api/invoices/1" \
-H "Content-Type: application/json" \
-d '{
  "company_id": 2,
  "customer_id": 1,
  "subject": "Pembelian Barang",
  "items": [
          {
            "item_id": 1,
            "quantity": 2,
            "unit_price": 10000,
            "amount": 20000
          }, 
          {
            "item_id": 2,
            "quantity": 3,
            "unit_price": 5000,
            "amount": 15000
          }
  ],
  "issued_date": "2025-01-01T10:00:00",
  "due_date": "2025-01-15T10:00:00",
  "sub_total": 35000,
  "tax": 3500,
  "grand_total": 38500
}'
```

#### 5. **Delete an Invoice** (DELETE)
```bash
curl -X DELETE "http://localhost:8080/api/invoices/1" \
-H "Accept: application/json"
```

---

### Notes:
- Adjust query parameters (e.g., `limit`, `offset`, `name`) as needed for testing.
