FROM nginx:1.20-alpine
SHELL ["/bin/ash", "-oeux", "pipefail", "-c"]

ENV TZ=UTC

RUN apk update && \
  apk add --update --no-cache --virtual=.build-dependencies g++

# nginx config file
COPY ./infra/docker/nginx-api/*.conf /etc/nginx/conf.d/

WORKDIR /work/backend
