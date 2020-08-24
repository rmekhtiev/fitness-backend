FROM php:7.4-apache
LABEL maintainer="dev@devolt.one"

ENV \
    COMPOSER_ALLOW_SUPERUSER="1" \
    COMPOSER_HOME="/tmp/composer" \
    PS1='\[\033[1;32m\]\[\033[1;36m\][\u@\h] \[\033[1;34m\]\w\[\033[0;35m\] \[\033[1;36m\]# \[\033[0m\]'

# Install packages
RUN apt-get update && apt-get install -y \
    bc \
    git \
    zip \
    curl \
    sudo \
    unzip \
    libpq-dev \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libxml2-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    g++

RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable \
       redis

RUN docker-php-ext-configure \
    pgsql -with-pgsql=/usr/local/pgsql

# Common PHP Extensions
RUN docker-php-ext-install \
    bz2 \
    xml \
    intl \
    iconv \
    pcntl \
    bcmath \
    opcache \
    calendar \
    tokenizer \
    pdo_pgsql \
    gd

# Apache configuration
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

ARG ARG_APACHE_LISTEN_PORT=5000
ENV APACHE_LISTEN_PORT=${ARG_APACHE_LISTEN_PORT}
RUN sed -i "s/80/${APACHE_LISTEN_PORT}/g" /etc/apache2/sites-available/*.conf /etc/apache2/ports.conf
RUN a2enmod rewrite headers

# Ensure PHP logs are captured by the container
ENV LOG_CHANNEL=stderr

# Set a volume mount point for your code
# VOLUME /var/www/html
WORKDIR /var/www/html

# Copy code and run composer
COPY --from=composer:1.10.8 /usr/bin/composer /usr/bin/composer
RUN composer global require hirak/prestissimo

# Ensure file ownership for source code files
COPY . /var/www/html
RUN composer install --optimize-autoloader --prefer-dist --no-dev --no-interaction --no-ansi --no-suggest

RUN chown -R www-data:www-data \
    /var/www/html/storage \
    /var/www/html/bootstrap/cache
RUN mkdir -p /tmp/storage/bootstrap/cache \
    && chmod 777 -R /tmp/storage/bootstrap/cache

RUN php artisan storage:link

# RUN php artisan optimize

# Application port
EXPOSE ${APACHE_LISTEN_PORT}

# USER www-data

# The default apache run command
CMD ["apache2-foreground"]
