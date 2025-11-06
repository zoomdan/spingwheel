# Use the official Nginx image as the base
FROM nginx:stable-alpine

# Set the working directory
WORKDIR /var/www/html

# --- NGINX CONFIGURATION (Content of former default.conf is now inline) ---
RUN echo 'server {' > /etc/nginx/conf.d/default.conf && \
    echo '    listen 80;' >> /etc/nginx/conf.d/default.conf && \
    echo '    index index.php;' >> /etc/nginx/conf.d/default.conf && \
    echo '    root /var/www/html;' >> /etc/nginx/conf.d/default.conf && \
    echo '    location / {' >> /etc/nginx/conf.d/default.conf && \
    echo '        try_files $uri $uri/ =404;' >> /etc/nginx/conf.d/default.conf && \
    echo '    }' >> /etc/nginx/conf.d/default.conf && \
    echo '    location ~ \.php$ {' >> /etc/nginx/conf.d/default.conf && \
    echo '        fastcgi_pass app:9000;' >> /etc/nginx/conf.d/default.conf && \
    echo '        fastcgi_index index.php;' >> /etc/nginx/conf.d/default.conf && \
    echo '        include fastcgi_params;' >> /etc/nginx/conf.d/default.conf && \
    echo '        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;' >> /etc/nginx/conf.d/default.conf && \
    echo '        fastcgi_param PATH_INFO $fastcgi_path_info;' >> /etc/nginx/conf.d/default.conf && \
    echo '    }' >> /etc/nginx/conf.d/default.conf && \
    echo '}' >> /etc/nginx/conf.d/default.conf
# --------------------------------------------------------------------------

# Copy the application code from the local context into the Nginx container.
COPY index.php /var/www/html/
COPY save_entries.php /var/www/html/

# Create the data directory and ensure it's available for the volume mount.
RUN mkdir -p /var/www/html/data

# The default command runs Nginx
CMD ["nginx", "-g", "daemon off;"]
