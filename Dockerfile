FROM node:latest as build-assets
WORKDIR /app
COPY package*.json tailwind.config.js webpack.mix.js ./
COPY resources ./resources
RUN npm install
RUN npm run production

FROM composer:latest as build-vendor
WORKDIR /app
COPY composer.* ./
RUN composer install --prefer-dist --no-scripts --no-dev --no-autoloader --no-cache --ignore-platform-reqs

FROM ubuntu:20.04

LABEL maintainer="Kyle Klaus <kklaus@indemnity83.com>"

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && apt-get install -y gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 libcap2-bin libpng-dev python2 \
    && mkdir -p ~/.gnupg \
    && chmod 600 ~/.gnupg \
    && echo "disable-ipv6" >> ~/.gnupg/dirmngr.conf \
    && apt-key adv --homedir ~/.gnupg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys E5267A6C \
    && apt-key adv --homedir ~/.gnupg --keyserver hkp://keyserver.ubuntu.com:80 --recv-keys C300EE8C \
    && echo "deb http://ppa.launchpad.net/ondrej/php/ubuntu focal main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && apt-get update \
    && apt-get install -y php8.0-cli php8.0-dev \
       php8.0-pgsql php8.0-sqlite3 php8.0-gd \
       php8.0-curl php8.0-memcached \
       php8.0-imap php8.0-mysql php8.0-mbstring \
       php8.0-xml php8.0-zip php8.0-bcmath php8.0-soap \
       php8.0-intl php8.0-readline \
       php8.0-msgpack php8.0-igbinary php8.0-ldap \
       php8.0-redis \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && apt-get install -y mysql-client \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN setcap "cap_net_bind_service=+ep" /usr/bin/php8.0

RUN curl -L https://install.meilisearch.com | sh \
    && chmod +x ./meilisearch \
    && mv ./meilisearch /usr/bin/meilisearch

# Setup Imagick to make thumbnails
# TODO: might be better to just embed the PDF using js than all this work
RUN apt-get update \
    && apt-get install -y imagemagick libmagickwand-dev \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
RUN git clone https://github.com/Imagick/imagick \
    && cd imagick \
    && phpize \
    && ./configure \
    && make \
    && make install
RUN sed -i '/disable ghostscript format types/,+6d' /etc/ImageMagick-6/policy.xml

RUN groupadd --force -g 100 sail
RUN useradd -ms /bin/bash --no-user-group -g 100 -u 99 sail

COPY runtime/start-container /usr/local/bin/start-container
COPY runtime/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY runtime/php.ini /etc/php/8.0/cli/conf.d/99-sail.ini
RUN chmod +x /usr/local/bin/start-container

WORKDIR /var/www/html

COPY . ./
COPY --from=build-vendor /app/vendor ./vendor
COPY --from=build-assets /app/public ./public

RUN touch storage/config/database.sqlite
RUN composer dump-autoload
RUN php artisan storage:link

RUN chown sail:sail -R ./*

EXPOSE 80
VOLUME /var/www/html/storage/config
VOLUME /var/www/html/storage/import
VOLUME /var/www/html/storage/media

ENTRYPOINT ["start-container"]
