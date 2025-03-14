# Build stage
FROM php:8.2-cli AS builder

# Install system dependencies
RUN apt-get update && \
    apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev && \
    docker-php-ext-install \
    zip \
    gd \
    mbstring \
    bcmath \
    xml \
    pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY ./imagine /var/www/html/imagine
WORKDIR /var/www/html/imagine

# Install dependencies
WORKDIR /var/www/html/imagine
RUN composer install --optimize-autoloader --no-dev

# Production stage
FROM php:8.2-apache

# Install runtime dependencies
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev && \
    docker-php-ext-install \
    zip \
    gd \
    mbstring \
    bcmath \
    xml \
    pdo_mysql

# Copy built application from builder
COPY --from=builder /var/www/html /var/www/html

# Create and migrate database
RUN mkdir -p /var/www/html/database && \
    touch /var/www/html/database/database.sqlite && \
    (cd /var/www/html/imagine && php artisan migrate --force) && \
    chown www-data:www-data /var/www/html/database/database.sqlite

# Install curl for health checks
RUN apt-get install -y curl

# Configure Apache
COPY imagine/docker/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite headers expires
RUN chown -R www-data:www-data /var/www/html/imagine/storage
RUN chown -R www-data:www-data /var/www/html/imagine/bootstrap/cache

# Health check
HEALTHCHECK --interval=30s --timeout=3s \
  CMD curl -f http://localhost/health || exit 1

# Expose port 80
EXPOSE 80

# Set entry point
CMD ["apache2-foreground"]
