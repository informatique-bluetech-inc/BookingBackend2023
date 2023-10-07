FROM php:8.2-fpm-alpine

# Run entrypoint
COPY ./entrypoint.sh /usr/bin/docker-entrypoint
RUN chmod +x /usr/bin/docker-entrypoint
CMD [ "docker-entrypoint" ]
