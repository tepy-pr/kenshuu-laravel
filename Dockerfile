FROM php:7.3-apache-stretch

# Surpresses debconf complaints of trying to install apt packages interactively
# https://github.com/moby/moby/issues/4032#issuecomment-192327844

ARG DEBIAN_FRONTEND=noninteractive

# Update
RUN apt-get -y update --fix-missing && \
  apt-get upgrade -y && \
  apt-get --no-install-recommends install -y apt-utils && \
  rm -rf /var/lib/apt/lists/*


# Install useful tools and install important libaries
RUN apt-get -y update && \
  apt-get -y --no-install-recommends install nano wget dialog libsqlite3-dev libsqlite3-0 && \
  apt-get -y --no-install-recommends install mysql-client zlib1g-dev libzip-dev libicu-dev && \
  apt-get -y --no-install-recommends install --fix-missing apt-utils build-essential git curl && \ 
  apt-get -y --no-install-recommends install --fix-missing libcurl3 libcurl3-dev zip openssl && \
  rm -rf /var/lib/apt/lists/* && \
  curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install xdebug
RUN pecl install xdebug-2.7.2 && \
  docker-php-ext-enable xdebug
# Other PHP7 Extensions

RUN docker-php-ext-install pdo && \
  docker-php-ext-install pdo_mysql && \
  docker-php-ext-install mysqli && \
  docker-php-ext-install exif

# Enable apache modules
RUN a2enmod rewrite headers


# Cleanup
RUN rm -rf /usr/src/*