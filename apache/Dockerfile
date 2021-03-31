FROM php:7.4.15-apache

RUN rm /etc/apache2/sites-available/default-ssl.conf

#自簽憑證檔案
COPY ./change/nfu_ssl.conf /usr/local/etc/nfu_ssl.conf

#加入開機執行linux語法
COPY ./change/apache2-foreground /usr/local/bin/apache2-foreground

#自簽憑證所需檔案
COPY ./change/ssl-params.conf /etc/apache2/conf-available/ssl-params.conf
COPY ./change/default-ssl.conf /etc/apache2/sites-available/default-ssl.conf

#安裝php額外擴充元件
RUN docker-php-ext-install pdo_mysql; \
    docker-php-ext-install bcmath

RUN apt-get update; \
    apt-get upgrade -y

#安裝編輯程式
RUN apt-get install vim -y

#安裝git
RUN apt-get install git -y

#安裝解壓縮軟體
RUN apt-get install zip unzip -y

#安裝ssh
RUN  apt-get install openssh-server -y; \
    #更改root ssh登入密碼
    echo 'root:admin' | chpasswd ; \
    rm /etc/ssh/sshd_config

#安裝composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"; \
    php -r "if (hash_file('sha384', 'composer-setup.php') === '756890a4488ce9024fc62c56153228907f1545c228516cbf63f885e036d37e9a59d27d63f46af1d4d07ee0f76181c7d3') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"; \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer; \
    php -r "unlink('composer-setup.php');"

#安裝執行"add-apt-repository"此指令所需程式
RUN apt-get install software-properties-common -y; \
    apt-get install -y gnupg2

#安裝nodejs 14.x版
RUN add-apt-repository -y -r ppa:chris-lea/node.js; \
    rm -f /etc/apt/sources.list.d/chris-lea-node_js-*.list; \
    rm -f /etc/apt/sources.list.d/chris-lea-node_js-*.list.save; \
    curl -fsSL https://deb.nodesource.com/gpgkey/nodesource.gpg.key | apt-key add -; \
    VERSION=node_14.x; \
    DISTRO="$(lsb_release -s -c)"; \
    echo "deb https://deb.nodesource.com/$VERSION $DISTRO main" | tee /etc/apt/sources.list.d/nodesource.list; \
    echo "deb-src https://deb.nodesource.com/$VERSION $DISTRO main" | tee -a /etc/apt/sources.list.d/nodesource.list; \
    apt-get update; \
    apt-get install nodejs -y

#實作自簽憑證
RUN cd /usr/local/etc/ && \ 
    openssl req -x509 -new -nodes -sha256 -utf8 -days 365 -newkey rsa:2048 -keyout server.key -out server.crt -config nfu_ssl.conf && \
    cp server.crt /etc/ssl/certs/ && \
    cp server.key /etc/ssl/private/ && \
    update-ca-certificates; \
    a2enmod ssl; \
    a2enmod headers; \
    a2ensite default-ssl; \
    a2enconf ssl-params; \
    apache2ctl configtest

RUN apt-get update; \
    apt-get upgrade -y

#更改ssh檔案
COPY ./change/sshd_config /etc/ssh/sshd_config

RUN /etc/init.d/ssh restart
RUN /etc/init.d/apache2 restart