# Use a lean official PHP-FPM image as the base
FROM php:8.3-fpm-alpine

# Set the working directory inside the container
WORKDIR /var/www/html

# Install git (often useful for deployment, though not strictly required for this simple app)
RUN apk add --no-cache git

# Permissions Setup:
# 1. PHP-FPM often runs as a user called 'www-data' (UID 82). We need to ensure that the entries.txt file can be written by this user.
# 2. Alpine base images use a different user/group (nobody:nobody). We'll switch to the standard 'www-data' approach which is safer.

# Create a temporary directory that the PHP process can write to.
# This ensures that entries.txt can be created and updated successfully.
RUN mkdir -p /var/www/html/data && \
    chown -R www-data:www-data /var/www/html

# Expose port 9000 for Nginx to connect to PHP-FPM
EXPOSE 9000

# The default command runs php-fpm
CMD ["php-fpm"]
