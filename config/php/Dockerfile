FROM php:7.4-fpm

# Copy composer.lock and composer.json
# COPY composer.lock composer.json /var/www/
# Set working directory
WORKDIR /var/www

# Update
RUN apt-get -y update --fix-missing && \
  apt-get upgrade -y && \
  apt-get --no-install-recommends install -y apt-utils && \
  rm -rf /var/lib/apt/lists/*


# # Install useful tools and install important libaries
# RUN apt-get -y update && \
#   apt-get -y --no-install-recommends install nano wget dialog libsqlite3-dev libsqlite3-0 && \
#   apt-get -y --no-install-recommends install default-mysql-client zlib1g-dev libzip-dev libicu-dev && \
#   apt-get -y --no-install-recommends install --fix-missing apt-utils build-essential git curl && \ 
#   apt-get -y --no-install-recommends install --fix-missing libcurl3 libcurl3-dev zip openssl && \
#   rm -rf /var/lib/apt/lists/*

RUN apt-get update && apt-get install -y \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libpng-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install xdebug
RUN pecl install xdebug \
  && docker-php-ext-enable xdebug
# Other PHP7 Extensions

RUN docker-php-ext-install pdo && \
  docker-php-ext-install pdo_mysql && \
  docker-php-ext-install mysqli && \
  docker-php-ext-install exif

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www
# Copy existing application directory contents
COPY . /var/www
# Copy existing application directory permissions
COPY --chown=www:www . /var/www
# Change current user to www
USER www
# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]


# # Other PHP7 Extensions
# RUN docker-php-ext-install pdo && \
#   docker-php-ext-install pdo_mysql && \
#   docker-php-ext-install mysqli && \
#   docker-php-ext-install exif

# # Cleanup
# RUN rm -rf /usr/src/*