##
# NPM Install Container
#
FROM    node:latest as build-assets
WORKDIR /app
COPY    package*.json tailwind.config.js webpack.mix.js ./
COPY    resources ./resources
RUN     npm install
RUN     npm run production

##
# Composer Install Container
#
FROM    composer:latest as build-vendor
WORKDIR /app
COPY    composer.* ./
RUN     composer install --prefer-dist --no-scripts --no-dev --no-cache --ignore-platform-reqs

##
# Compile Meilisearch
#
FROM    alpine AS build-meilisearch
RUN     apk --no-cache add curl build-base git
RUN     curl --proto '=https' --tlsv1.2 -sSf https://sh.rustup.rs | sh -s -- -y
WORKDIR /meilisearch
RUN     git clone https://github.com/meilisearch/MeiliSearch.git . && \
        git checkout $(git describe --tags $(git rev-list --tags --max-count=1))
ENV     RUSTFLAGS="-C target-feature=-crt-static"
RUN     $HOME/.cargo/bin/cargo build --release


##
# Application Container
#
FROM    alpine
LABEL   maintainer="Kyle Klaus <kklaus@indemnity83.com>"

# Install packages
RUN     apk --no-cache add supervisor shadow curl redis poppler-utils inotify-tools \
        php8 php8-fpm php8-json php8-mbstring php8-iconv php8-pcntl php8-posix php8-sodium \
        php8-session php8-xml php8-curl php8-fileinfo php8-gd php8-intl php8-zip php8-redis \
        php8-simplexml php8-pdo php8-sqlite3 php8-pdo_sqlite php8-exif php8-pecl-imagick \
        php8-dom php8-xmlwriter php8-tokenizer php8-phar php8-openssl php8-pdo_mysql

# Link php to php8
RUN     ln -s /usr/bin/php8 /usr/bin/php

# Install meilisearch
COPY    --from=build-meilisearch /meilisearch/target/release/meilisearch /usr/bin/meilisearch

# Copy system configurations
COPY    runtime/watch-consume /usr/bin/watch-consume
RUN     chmod +x /usr/bin/watch-consume
COPY    runtime/start-container /usr/bin/start-container
RUN     chmod +x /usr/bin/start-container
COPY    runtime/supervisord.conf /etc/supervisor.conf
COPY    runtime/redis.conf /etc/redis.conf
COPY    runtime/php.ini /etc/php8/conf.d/custom.ini

# Copy application
COPY    --chown=nobody:nobody . /app
COPY    --chown=nobody:nobody --from=build-vendor /app/vendor /app/vendor
COPY    --chown=nobody:nobody --from=build-assets /app/public /app/public

ENV     APP_ENV=local
ENV     APP_DEBUG=true
ENV     LOG_CHANNEL=stderr
ENV     SCOUT_DRIVER=meilisearch
ENV     SCOUT_QUEUE=true
ENV     DB_CONNECTION=sqlite
ENV     CACHE_DRIVER=redis
ENV     QUEUE_CONNECTION=redis
ENV     SESSION_DRIVER=redis

WORKDIR /app

#EXPOSE 80
#VOLUME /app/storage/app
#VOLUME /app/storage/config
#VOLUME /app/storage/consume

ENTRYPOINT ["/usr/bin/start-container"]
