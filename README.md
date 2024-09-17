# Laravel 10 Project

## Introduction

This project is a Laravel 10 application designed to run within Docker containers. It utilizes services like **Nginx**, **PHP-FPM**, **MySQL**, and **Redis** to provide a complete development environment with SSL support, PHP extensions, and caching.

## Requirements

- **PHP**: ^8.1 (Handled by Docker)
- **Composer**
- **Docker** & **Docker Compose**
- **Nginx**

## Project Setup

### Step 1: Clone the Repository

First, clone the repository to your local machine:

```bash
git clone <repository-url>
cd <project-directory>
```

### Step 2: Configure Hosts

To map the application URLs, open the `hosts` file in your local machine (`C:\Windows\System32\drivers\etc\hosts` on Windows or `/etc/hosts` on Linux/macOS) and add the following lines:

```
127.0.0.1   client.dacsancamau.com
127.0.0.1   admin.dacsancamau.com
127.0.0.1   dacsancamau.com
```

This ensures that the domain names will resolve to your local machine.

### Step 3: Build and Start the Docker Containers

Start all services defined in the `docker-compose.yml` file by running:

```bash
docker-compose up -d
```

This will spin up the following containers:

- **PHP-FPM** (Alpine-based, with all necessary extensions)
- **MySQL**
- **Redis**
- **Nginx**
- **PhpMyAdmin**

### Step 4: Install Composer Dependencies

Once your Docker containers are running, access the **PHP** container to install the required PHP packages:

```bash
docker exec -it ct550-app bash
composer install
```

### Step 5: Clear and Optimize Cache

Inside the Docker container, run the following to ensure the application cache and configurations are up-to-date:

```bash
php artisan optimize:clear
# or
php artisan config:clear
```

### Step 6: Run Migrations

Run the following command to create the database structure:

```bash
php artisan migrate
```

### Step 7: Seed the Database

To insert initial data into your database, run the seed command:

```bash
php artisan db:seed
```

## Nginx Configuration

The Nginx server is configured to serve the application on both HTTP and HTTPS. You can find the configuration in `docker/nginx/conf.d/default.conf`.

### HTTP Server Block

- **Server Name**: `localhost` and `dacsancamau.com`
- **Root Directory**: `/var/www/public`
- **Redirect**: All HTTP requests are redirected to HTTPS.

### HTTPS Server Block

- **SSL Certificates**: Self-signed certificates are used (`nginx-selfsigned.crt` and `nginx-selfsigned.key`).
- **SSL Configuration**: The server supports TLSv1.2 and TLSv1.3 with strong ciphers, session caching, and security headers (HSTS, X-Frame-Options, X-Content-Type-Options).

## Dockerfile Configuration

The **Dockerfile** is designed for building a custom PHP-FPM container with various extensions:

- **Extensions Installed**: `pdo`, `pdo_mysql`, `mysqli`, `gd` (with JPEG and FreeType support), `redis`, `zip`, `exif`.
- **Composer**: Installed via the official Composer image.
- **Non-Root User**: The application runs under a less privileged user (`appuser`) with a configurable UID and GID.

The startup script (`docker-start.sh`) is located in the project root and executed when the container starts.

### Build the Docker Image

If any changes are made to the Dockerfile, rebuild the image:

```bash
docker-compose build
```

### Running Commands Inside the Container

You can execute any Laravel or PHP commands inside the container using:

```bash
docker exec -it ct550-app bash
```

## PhpMyAdmin Access

You can manage the MySQL database using **PhpMyAdmin** at:

```
http://localhost:91
```

- **Username**: `ct550_user`
- **Password**: `ct550`

## Environment Variables

The `.env` file contains necessary configurations such as database credentials, Redis setup, and app environment settings.

### Example `.env` Configuration

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:your-app-key
APP_DEBUG=true
APP_URL=http://dacsancamau.com

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=ct550_db
DB_USERNAME=ct550_user
DB_PASSWORD=ct550

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=sync
```

## Accessing the Application

Once everything is set up, you can access the application via:

- **Frontend**: `http://dacsancamau.com` (or `https://dacsancamau.com` for HTTPS)
- **Admin**: `http://admin.dacsancamau.com`
- **Client**: `http://client.dacsancamau.com`

## Stopping Docker Services

To stop all running containers, use:

```bash
docker-compose down
```
