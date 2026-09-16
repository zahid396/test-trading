#!/usr/bin/env bash
set -e

echo "==> Waiting for the database to be reachable..."
max=120
i=0
until php artisan db:show --no-interaction >/dev/null 2>&1; do
    i=$((i + 1))
    if [ "$i" -ge "$max" ]; then
        echo "==> ERROR: Database not reachable after ${max} attempts. Aborting."
        exit 1
    fi
    echo "    . database not ready (attempt ${i}/${max}), retrying in 2s..."
    sleep 2
done
echo "==> Database connection OK."

echo "==> Linking storage..."
php artisan storage:link --no-interaction || true

echo "==> Running migrations..."
php artisan migrate --force --no-interaction

echo "==> Checking if database is empty (first deploy only)..."
if [ "$(php artisan tinker --execute='echo \App\Models\Admin::query()->count() > 0 ? "seeded" : "empty";' 2>/dev/null)" != "seeded" ]; then
    echo "==> Empty database detected - seeding demo data + admin account..."
    php artisan db:seed --force --no-interaction
else
    echo "==> Database already seeded - skipping."
fi

echo "==> Caching config/routes/views..."
php artisan optimize

echo "==> Starting Apache..."
exec apache2-foreground