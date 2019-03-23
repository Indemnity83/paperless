FROM python:alpine

LABEL maintainer="kklaus@indemnity83.com"
ARG PAPERLESS_VERSION=master

ENV PAPERLESS_DISABLE_LOGIN=true
ENV PAPERLESS_CONSUMPTION_DIR=/paperless/consume

RUN apk add --no-cache supervisor ghostscript gnupg imagemagick libmagic libpq optipng poppler shadow tesseract-ocr unpaper

RUN apk add --no-cache git && \
    git -c advice.detachedHead=false clone --depth=1 -b $PAPERLESS_VERSION https://github.com/the-paperless-project/paperless.git && \
    cp /paperless/paperless.conf.example /etc/paperless.conf && \
    apk del git

WORKDIR /paperless

RUN apk add --no-cache --virtual build g++ gcc jpeg-dev musl-dev poppler-dev postgresql-dev python3-dev zlib-dev file-dev && \
    pip install -r requirements.txt

VOLUME /paperless/data /paperless/media /paperless/consume
EXPOSE 8000

COPY docker-entrypoint.sh /
COPY supervisord.conf /paperless

RUN chmod 755 /docker-entrypoint.sh

ENTRYPOINT ["/docker-entrypoint.sh"]

CMD ["supervisord"]
