# Use a lean official PHP-FPM image as the base
FROM php:8.3-fpm-alpine

# Set the working directory inside the container
WORKDIR /var/www/html

# Install git (often useful for deployment, though not strictly required for this simple app)
RUN apk add --no-cache git

# Copy the entire application source code into the image
COPY . /var/www/html  <-- This line ensures all your code (index.php, save_entries.php, etc.) is in the container.

# Permissions Setup:
...
