#!/bin/bash

admin_app="apps/admin"
hotel_app="apps/hotel"

build_frontend() {
  local app_folder=$1
  (
    cd "$app_folder" || exit
    echo "Building frontend app in $app_folder\n"
    npm run build
    echo "Build completed for $app_folder\n"
  ) &
}

git fetch
git pull

build_frontend "$admin_app"
build_frontend "$hotel_app"

wait

echo "All frontend applications built successfully\n"
