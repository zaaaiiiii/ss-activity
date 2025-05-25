FROM php:8.1-apache-bullseye

# Install Tesseract, language data, and other dependencies quickly
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        tesseract-ocr tesseract-ocr-eng \
        wget git unzip \
        ca-certificates \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Create directories with correct permissions
RUN mkdir -p uploads screenshots && \
    chown -R www-data:www-data uploads screenshots && \
    chmod -R 775 uploads screenshots

# Set environment variable for Tesseract path
ENV TESSERACT_PATH=/usr/local/bin/tesseract

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80