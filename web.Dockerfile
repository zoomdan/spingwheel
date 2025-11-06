# Use a lean official Nginx image as the base
FROM nginx:stable-alpine

# Copy the entire application source code into the image
# Nginx needs the files to know what to serve.
COPY . /var/www/html

# --- NGINX CONFIGURATION MERGED ---
# Create the Nginx configuration file directly inside the image.
RUN echo 'server { \
    listen 80; \
    index index.php index.html; \
    root /var/www/html; \
 \
    client_max_body_size 10M; \
 \
    location / { \
        try_files $uri $uri/ =404; \
    } \
 \
    # Pass PHP scripts to the PHP-FPM service (named "app" in docker-compose)
    location ~ \.php$ { \
        try_files $uri =404; \
        fastcgi_split_path_info ^(.+\.php)(/.+)$; \
        fastcgi_pass app:9000; \
        fastcgi_index index.php; \
        include fastcgi_params; \
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \
        fastcgi_param PATH_INFO $fastcgi_path_info; \
    } \
}' > /etc/nginx/conf.d/default.conf

# Set permissions for the mounted volume data directory
# This directory is where PHP needs to write the entries.txt file.
RUN mkdir -p /var/www/html/data && chown -R www-data:www-data /var/www/html/data
