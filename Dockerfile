# Imagen de produccion de registro/: nginx + PHP-FPM en un solo contenedor
# (sin Apache), administrados con supervisord. Consistente con el resto de
# apps de esta VM (un contenedor = una app, puerto 80 interno).

FROM php:8.3-fpm

RUN apt-get update && apt-get install -y --no-install-recommends \
        nginx \
        supervisor \
        libldap2-dev \
        libonig-dev \
    && docker-php-ext-configure ldap \
    && docker-php-ext-install ldap mysqli mbstring \
    && apt-get purge -y --auto-remove libldap2-dev libonig-dev \
    && rm -rf /var/lib/apt/lists/*

COPY docker/php.ini /usr/local/etc/php/conf.d/zz-registro.ini
COPY docker/nginx.conf /etc/nginx/sites-enabled/default
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/html

# Copia explicita del codigo de la aplicacion: deja fuera de la imagen todo
# lo que no es la app en si (.git, docker/, compose.yaml, .env, notes.txt,
# README, doc/ con el PDF) para que nunca quede servible ni horneado en una
# capa de la imagen.
COPY estudiante/ ./estudiante/
COPY docente/ ./docente/
COPY administrativo/ ./administrativo/
COPY shared/ ./shared/
COPY index.php inicio.php favicon.ico ./

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
