FROM nginx:latest

COPY gen_htpasswd /etc/nginx

RUN apt-get update
RUN apt-get install -y openssl
RUN chmod 755 /etc/nginx/gen_htpasswd
RUN /etc/nginx/gen_htpasswd
RUN chmod 444 /etc/nginx/.htpasswd
