#!/usr/bin/env bash
set -euo pipefail

# Server-side deploy script for alwaysdata
# Place this in ~/final_amikomhub on the server and run when needed.

REPO_DIR="$HOME/final_amikomhub"
BRANCH="FP_Amikom_Hub"

if [ -d "$REPO_DIR" ]; then
  cd "$REPO_DIR"
  git fetch origin "$BRANCH"
  git reset --hard "origin/$BRANCH"
else
  git clone -b "$BRANCH" git@github.com:TegarAgungPambudi/final_amikomhub.git "$REPO_DIR"
  cd "$REPO_DIR"
fi

# Install composer deps
composer install --no-dev --optimize-autoloader

# Environment
if [ ! -f .env ]; then
  if [ -f .env.example ]; then
    cp .env.example .env
  fi
fi

php artisan key:generate --force
php artisan migrate --force
php artisan storage:link || true
chmod -R 775 storage bootstrap/cache || true
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Deployment finished."
