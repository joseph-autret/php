FROM php:8.2-apache

# Enable SSL module
RUN a2enmod rewrite ssl

# Create directory for SSL certificates
RUN mkdir /etc/apache2/ssl

# Copy SSL certificates from the certs directory
COPY certs/server.crt /etc/apache2/ssl/server.crt
COPY certs/server.key /etc/apache2/ssl/server.key

# Copy custom Apache configuration
COPY apache-ssl.conf /etc/apache2/sites-available/default-ssl.conf
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Create log file for Database connection and set permissions
RUN touch /var/log/php_db_errors.log
RUN chown www-data:www-data /var/log/php_db_errors.log
RUN chmod 755 /var/log/php_db_errors.log

# Enable the SSL site
RUN a2ensite default-ssl

# Install system dependencies for Composer
RUN apt-get update && apt-get install -y git unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set correct permissions for the document root
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html

# Copy application files
COPY php-app /var/www/html

# Run Composer to install dependencies
WORKDIR /var/www/html
RUN composer install --no-dev

# Expose ports for HTTP and HTTPS
EXPOSE 80
EXPOSE 443
