#!/bin/bash
set -e  # Exit immediately if a command exits with a non-zero status

# Set RabbitMQ host and port, fallback to defaults if not set
host="${RABBITMQ_HOST:-rabbitmq}"
port="${RABBITMQ_PORT:-5671}"

echo "Waiting for RabbitMQ at $host:$port..."

# Wait until RabbitMQ service is available on the specified host and port
while ! nc -z "$host" "$port"; do
  echo "RabbitMQ is unavailable - sleeping for 3 seconds..."
  sleep 3
done

echo "RabbitMQ is up - starting Laravel Queue Worker."

# Run Laravel queue worker using RabbitMQ
/usr/local/bin/php /srv/ddd/src/artisan queue:work rabbitmq \
  --queue=default \
  --sleep=3 \
  --tries=3 \
  --timeout=60
  