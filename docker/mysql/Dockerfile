# MacでM-1チップ搭載の場合は --platform を追記
FROM --platform=linux/x86_64 mysql:8.0

ADD my.cnf /etc/mysql/conf.d/my.cnf

RUN chmod 644 /etc/mysql/conf.d/my.cnf
