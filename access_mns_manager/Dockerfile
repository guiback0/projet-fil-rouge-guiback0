#syntax=docker/dockerfile:1

# Versions
FROM dunglas/frankenphp:1-php8.4 AS frankenphp_upstream

# The different stages of this Dockerfile are meant to be built into separate images
# https://docs.docker.com/develop/develop-images/multistage-build/#stop-at-a-specific-build-stage
# https://docs.docker.com/compose/compose-file/#target


# Base FrankenPHP image
FROM frankenphp_upstream AS frankenphp_base

WORKDIR /app

# VOLUME /app/var/
# persistent / runtime deps
# hadolint ignore=DL3008
RUN apt-get update && apt-get install -y --no-install-recommends \
	acl \
	file \
	gettext \
	git \
	&& rm -rf /var/lib/apt/lists/*

RUN set -eux; \
	install-php-extensions \
		@composer \
		apcu \
		intl \
		opcache \
		zip \
	;

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

# Transport to use by Mercure (default to Bolt)
ENV MERCURE_TRANSPORT_URL=bolt:///data/mercure.db

ENV PHP_INI_SCAN_DIR=":$PHP_INI_DIR/app.conf.d"

###> recipes ###
###> doctrine/doctrine-bundle ###
# Ajout de pdo_sqlite pour les tests avec SQLite
RUN install-php-extensions pdo_pgsql pdo_sqlite
###< doctrine/doctrine-bundle ###
###< recipes ###

COPY --link frankenphp/conf.d/10-app.ini $PHP_INI_DIR/app.conf.d/
COPY --link --chmod=755 frankenphp/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
COPY --link frankenphp/Caddyfile /etc/frankenphp/Caddyfile

ENTRYPOINT ["docker-entrypoint"]

HEALTHCHECK --start-period=60s CMD curl -f http://localhost:2019/metrics || exit 1
CMD [ "frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile" ]

# Dev FrankenPHP image
FROM frankenphp_base AS frankenphp_dev

ENV APP_ENV=dev
ENV XDEBUG_MODE=off
ENV FRANKENPHP_WORKER_CONFIG=watch

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN set -eux; \
	install-php-extensions \
		xdebug \
	;

COPY --link frankenphp/conf.d/20-app.dev.ini $PHP_INI_DIR/app.conf.d/

CMD [ "frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile", "--watch" ]

# Prod FrankenPHP image
FROM frankenphp_base AS frankenphp_prod

ENV APP_ENV=prod

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY --link frankenphp/conf.d/20-app.prod.ini $PHP_INI_DIR/app.conf.d/

# prevent the reinstallation of vendors at every changes in the source code
COPY --link composer.* symfony.* ./
RUN set -eux; \
	composer install --no-cache --prefer-dist --no-autoloader --no-scripts --no-progress

# copy sources
COPY --link . ./
RUN rm -Rf frankenphp/

# Declare Railway build-time variables
ARG RAILWAY_ENVIRONMENT
ARG APP_ENV=qa
ARG APP_SECRET=temp-build-secret
ARG DATABASE_URL=sqlite:///%kernel.project_dir%/var/build.db
ARG SERVER_NAME=0.0.0.0:8080
ARG SYMFONY_VERSION
ARG STRIPE_API_KEY
ARG STRIPE_PUBLIC_KEY
ARG JWT_SECRET_KEY
ARG JWT_PUBLIC_KEY
ARG JWT_PASSPHRASE
ARG MERCURE_PUBLISHER_JWT_KEY
ARG MERCURE_SUBSCRIBER_JWT_KEY
ARG MERCURE_JWT_SECRET
ARG MERCURE_PUBLISHER_JWT_ALG
ARG MERCURE_SUBSCRIBER_JWT_ALG
ARG MERCURE_URL
ARG MERCURE_PUBLIC_URL
ARG STABILITY
ARG FRANKENPHP_WORKER_CONFIG
ARG XDEBUG_MODE
ARG MAILER_DSN
ARG MESSENGER_TRANSPORT_DSN
ARG TRUSTED_PROXIES
ARG LOCK_DSN


RUN set -eux; \
	cat > .env << 'ENVFILE'
APP_ENV=qa
APP_SECRET=temp-build-secret
SERVER_NAME=0.0.0.0:8080
SYMFONY_VERSION=7.0.*
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=
MERCURE_PUBLISHER_JWT_KEY=!ChangeThisMercureHubJWTSecretKey!
MERCURE_SUBSCRIBER_JWT_KEY=!ChangeThisMercureHubJWTSecretKey!
MERCURE_JWT_SECRET=!ChangeThisMercureHubJWTSecretKey!
MERCURE_PUBLISHER_JWT_ALG=HS256
MERCURE_SUBSCRIBER_JWT_ALG=HS256
MERCURE_URL=http://localhost/.well-known/mercure
MERCURE_PUBLIC_URL=https://localhost/.well-known/mercure
STABILITY=stable
FRANKENPHP_WORKER_CONFIG=
XDEBUG_MODE=off
MAILER_DSN=null://null
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0
TRUSTED_PROXIES=127.0.0.1,172.16.0.0/12,10.0.0.0/8,192.168.0.0/16
LOCK_DSN=flock
ENVFILE
	mkdir -p var/cache var/log; \
	composer dump-autoload --classmap-authoritative; \
	chmod +x bin/console; sync;
