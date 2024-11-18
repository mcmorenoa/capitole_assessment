#!/bin/bash

# Build Docker images without cache
echo "Building Docker images..."
docker-compose build --no-cache

# Start Docker containers with --pull always, detached mode, and wait for them to be ready
echo "Starting Docker containers..."
docker-compose up --pull always -d --wait

# Show the link to localhost
echo "Docker containers are up. Visit https://localhost to view your application."

read -p "Press [Enter] to exit..."
