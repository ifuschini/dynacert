FROM mariadb:10.5

ENV MYSQL_ROOT_PASSWORD dynacert
ENV MYSQL_USER dynaadmin
ENV MYSQL_PASSWORD dynacert
ENV MYSQL_DATABASE dynadb

COPY ./sql /docker-entrypoint-initdb.d/
