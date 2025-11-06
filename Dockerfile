# Use a lean official PHP-FPM image as the base
FROM php:8.3-fpm-alpine

# Set the working directory inside the container
WORKDIR /var/www/html

# Install useful utilities
RUN apk add --no-cache git

# Copy the entire application source code into the image
# FIX: Removed invalid trailing comment to resolve "unknown instruction" error.
COPY . /var/www/html

# Permissions Setup:
# Ensure the 'data' directory exists and is owned by the PHP-FPM process user (www-data, UID 82)
# This fixes the file saving permission issue.
RUN mkdir -p /var/www/html/data && \
    chown -R www-data:www-data /var/www/html/data

# Expose port 9000 for Nginx to connect to PHP-FPM
EXPOSE 9000

# The default command runs php-fpm
CMD ["php-fpm"]
