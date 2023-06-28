# Prerequisites

1. Docker
2. Node.js & NPM

# Setup

## 1. Prepare environment variables

```sh
$ cp .env.example .env
```

Then, customize them.

If you are on macOS, you can change `PHP_REMOTE_HOST` to `docker.for.mac.localhost` for PHP debugging.

If you have something launched on ports `80`, `9000` or `3306`, change `HOST_APP_PORT`, `HOST_PHP_PORT` or `HOST_DB_PORT` accordingly.

## 2. Build and run containers

```sh
$ make up
```

## 3. Install PHP dependencies

```sh
$ make composer-install
```

## 4. Generate application encryption key

```sh
$ make key-generate
```

After this command, `APP_KEY` in `.env` should be changed to unique string

## 5. Initialize database

```sh
$ make migrate
```

## 6. Add hosts to your OS

Add these lines to your `hosts` configuration:

```
127.0.0.1	www.gts.local
127.0.0.1	admin.gts.local
127.0.0.1	api.gts.local
127.0.0.1	hotel.gts.local
```

### macOS

```sh
sudo vim /private/etc/hosts
```

### Linux

```sh
sudo vim /etc/hosts
```

### Windows

Edit `C:\Windows\system32\drivers\etc\hosts` file

Simplest way is to copy this file to desktop, then edit, and copy back to `windows\system32\drivers\etc`

## 7. Build frontend assets

Prepare frontend environment:

```sh
$ cd apps/admin
$ cp .env.example .env
```

Build frontend assets:

```sh
$ make frontend-admin
```

## 8. Open project in browser

Open http://admin.gts.local in any browser

Login with developer credentials:

- User: `developer`
- Password: `123456`

They can be found in `database/migrations/install/2023_03_12_000003_fill_default_data.php` file

## 9. Additional

If you use MacOS, we recommended install docker-sync and apply `GTS_local_docker-sync_patch.patch`. For starting project use `docker-sync start` then `docker-compose up -d`.

# Utilities

1. `docker/bin/attach {container_name}` runs command line inside a container
2. `docker/bin/artisan` runs `artisan` in `php` container
3. There are some aliases in `.bash_aliases` file, you can use them inside of `php` container

Existing containers:

1. `php`
2. `nginx`
3. `mariadb`

