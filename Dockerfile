# Use an official PHP image with Apache
FROM php:8.1-apache

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . /var/www/html/

# Expose port 80
EXPOSE 80

# Start Apache server
CMD ["apache2-foreground"]
