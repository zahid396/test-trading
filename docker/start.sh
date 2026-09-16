#!/usr/bin/env bash
set -e

# ---------------------------------------------------------------------------
# DB setup runs in the BACKGROUND so Apache can bind port 80 immediately.
# Render scans for open ports during startup - if Apache waits for the DB,
# Render reports "No open ports detected" and may kill the service.
# ---------------------------------------------------------------------------
run_db_tasks() {
    max=120
    i=0

    echo "==> [db] Waiting for the database to be reachable..."
    until php artisan db:show --no-interaction >/dev/null 2>&1; do
        i=$((i + 1))
        if [ "$i" -ge "$max" ]; then
            echo "==> [db] ERROR: Database unreachable after ${max} attempts."
            echo "==> [db] Check: Aiven allow-list, TLS (DB_URL sslmode=require), DB_PASSWORD."
            exit 1
        fi
        echo "    . [db] not ready (attempt ${i}/${max})"
        sleep 2
    done
    echo "==> [db] Database connection OK."

    echo "==> [db] Linking storage..."
    php artisan storage:link --no-interaction || true

    echo "==> [db] Running migrations..."
    php artisan migrate --force --no-interaction

    if [ "$(php artisan tinker --execute='echo \App\Models\Admin::query()->count() > 0 ? "seeded" : "empty";' 2>/dev/null)" != "seeded" ]; then
        echo "==> [db] Empty database detected - seeding demo data + admin account..."
        php artisan db:seed --force --no-interaction
    else
        echo "==> [db] Database already seeded - skipping."
    fi

    echo "==> [db] Caching config/routes/views..."
    php artisan optimize

    echo "==> [db] Setup complete."
}

run_db_tasks &

echo "==> Starting Apache on port 80..."
exec apache2-foreground