# This Dockerfile is intended only for TESTING.
#
# Usage:
#   docker build --no-cache -t poweradmin .
#   docker run -d --name poweradmin -p 80:80 poweradmin
#
#   Alternatively, you can run the program with a current folder mounted:
#   docker run -d --name poweradmin -p 80:80 -v $(pwd):/app poweradmin
#
# Open your browser and navigate to "localhost", then log in using the provided username and password
# admin / testadmin

FROM php:8.2-cli-alpine

RUN apk add --no-cache --virtual .build-deps \
    icu-data-full \
    gettext \
    gettext-dev \
    libintl \
    postgresql-dev \
    sqlite \
    openldap-dev \
    && docker-php-ext-install -j$(nproc) \
    gettext \
    intl \
    mysqli \
    pdo \
    pdo_mysql \
    pdo_pgsql \
    ldap \
    && rm -rf /var/cache/apk/*

WORKDIR /app

COPY . .

RUN rm -rf /app/sql

RUN chown -R www-data:www-data /app \
    && chmod -R 755 /app

USER www-data

EXPOSE 80

ENTRYPOINT ["php", "-S", "0.0.0.0:80", "-t", "/app"]
