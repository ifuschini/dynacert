![Dynacert](https://raw.githubusercontent.com/ifuschini/dynacert/master/dynacert-builder/public/images/logo.png)

## dynacert-docker

is a docker project for creating the development environment.
It is made up of two containers:

1) php-apache
2) maria db

To launch the server environment and configure it correctly is very simple, just run these two commands:

```
cd dynacert-docker
docker-compose -p dynacert up --build 
```

or

```
cd dynacert-docker
./start-server.sh
```

### 