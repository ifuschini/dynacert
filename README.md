# Dynacert

## Proposal
The purpose of this project is to allow, starting from a pre-existing paper form or better pdf, to create an online form and digitally sign it, and to create a pdf with the compiled data.

## Prerequisite

For this project, the following is required as a prerequisite:

1) node 12 or higher (https://nodejs.org)
2) docker 20.10.x or higher (https://www.docker.com/)

## Arhitecture

The project consists of three components:

1) dynacert-server
2) builder
3) viewer



### dynacert-server

is a docker project for creating the development environment.
It is made up of two containers:

1) php-apache
2) maria db

To launch the server environment and configure it correctly is very simple, just run these two commands:

```
cd dynacert-server
docker-compose -p dynacert up --build 
```

or

```
cd dynacert-server
./start-server
```

once the docker containers are up, the pointing to the db needs to be configured correctly:

```
cd dynacert-server
./start-db-ip.sh
```

### dynacert builder

Dynacert builder is the administration component that allows:

1) build the form
2) Place the fields on the page
3) have a preview of the online form

#### Installation

Run this commands below:

```
cd builder
npm run builddocker
```

#### URL 

http://localhost/builder

### dynacert viewer

is the part reserved for the user to fill in the form, his signature and download the pdf with the compiled data.

#### Installation

Run this commands below:

```
cd viewer
npm run builddocker
```

#### URL

http://localhost/viewer

## License

Dynacert is available under the MIT license.

