#!/bin/bash

php artisan key:generate

composer install

php artisan storage:link

cd apps/admin

npm install
npx browserslist@latest --update-db
npm run prod
