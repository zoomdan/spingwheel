# Use a lean official PHP-FPM image as the base
FROM php:8.3-fpm-alpine

# Set the working directory inside the container
WORKDIR /var/www/html

# Install git and other necessary utilities (optional, but good practice)
RUN apk add --no-cache git

# Copy the entire application source code into the image
# This includes index.php, save_entries.php, etc.
COPY . /var/www/html

# Permissions Setup:
# Change ownership of the files to the web server user (www-data)
RUN chown -R www-data:www-data /var/www/html
