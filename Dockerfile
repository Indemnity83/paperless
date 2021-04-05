ARG BUILD_ENV=production

##
# NPM Install Container
#
FROM    node:latest as build-assets
WORKDIR /app
COPY    package*.json tailwind.config.js webpack.mix.js ./
COPY    resources ./resources
RUN     npm install
RUN     if [ "$BUILD_ENV" = "production" ] ; \
            then npm run production ; \
            else npm run dev ; \
        fi

##
# Composer Install Container
#
FROM    composer:latest as build-vendor
WORKDIR /app
COPY    composer.* ./
RUN     if [ "$BUILD_ENV" = "production" ] ; \
            then composer install --no-cache --ignore-platform-reqs --no-scripts --no-dev ; \
            else composer install --no-cache --ignore-platform-reqs --no-scripts ; \
        fi


##
# Application Container
#
FROM    alpine
LABEL   maintainer="Kyle Klaus <kklaus@indemnity83.com>"

# Install packages
RUN     apk --no-cache add supervisor shadow curl redis poppler-utils inotify-tools su-exec \
        php8 php8-fpm php8-json php8-mbstring php8-iconv php8-pcntl php8-posix php8-sodium \
        php8-session php8-xml php8-curl php8-fileinfo php8-gd php8-intl php8-zip php8-redis \
        php8-simplexml php8-pdo php8-sqlite3 php8-pdo_sqlite php8-exif php8-pecl-imagick \
        php8-dom php8-xmlwriter php8-tokenizer php8-phar php8-openssl php8-pdo_mysql php8-bcmath

# Link php to php8
RUN     ln -s /usr/bin/php8 /usr/bin/php

# Copy system configurations
COPY    runtime/watch-consume /usr/bin/watch-consume
RUN     chmod +x /usr/bin/watch-consume
COPY    runtime/start-container /usr/bin/start-container
RUN     chmod +x /usr/bin/start-container
COPY    runtime/supervisord.conf /etc/supervisor.conf
COPY    runtime/redis.conf /etc/redis.conf
COPY    runtime/php.ini /etc/php8/conf.d/custom.ini

# Setup the scheduler
RUN     echo "*  *  *  *  *    /usr/bin/php /app/artisan schedule:run" | crontab -

# Configure default user account
RUN     usermod -u 99 -g users nobody

# Copy application
COPY    --chown=nobody:users . /app
COPY    --chown=nobody:users --from=build-vendor /app/vendor /app/vendor
COPY    --chown=nobody:users --from=build-assets /app/public /app/public

ENV     APP_ENV=production
ENV     APP_DEBUG=false
ENV     LOG_CHANNEL=stderr
ENV     SCOUT_DRIVER=tntsearch
ENV     SCOUT_QUEUE=true
ENV     DB_CONNECTION=sqlite
ENV     CACHE_DRIVER=redis
ENV     QUEUE_CONNECTION=redis
ENV     SESSION_DRIVER=redis

EXPOSE  8000
WORKDIR /app
VOLUME  /app/storage/app
VOLUME  /app/storage/config
VOLUME  /app/storage/consume

ENTRYPOINT ["/usr/bin/start-container"]
