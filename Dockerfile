FROM bitnami/laravel:8.6.10-debian-10-r42

#NODE INSTALL
RUN sudo apt-get update &&\
    sudo apt-get -y install curl &&\
    curl -sL https://deb.nodesource.com/setup_14.x | sudo -E bash - &&\
    sudo apt-get -y install nodejs &&\
    sudo ln -s /usr/bin/nodejs /usr/local/bin/node

RUN node -v

WORKDIR /app/backend

COPY entrypoint.sh /app/entrypoint.sh

RUN sudo chmod +x /app/entrypoint.sh

EXPOSE 80
EXPOSE 6001
EXPOSE 8000

ENTRYPOINT ["/app/entrypoint.sh"]
