![Dynacert](https://raw.githubusercontent.com/ifuschini/dynacert/master/dynacert-builder/public/images/logo.png)

## Proposal

The purpose of this project is to allow, starting from a pre-existing paper form or better pdf, to create an online form and digitally sign it, and to create a pdf with the compiled data.

# ![Dynacert](https://raw.githubusercontent.com/ifuschini/dynacert/master/docimage/process.jpg)

## Demo

https://dyna-cert.org/



## Arhitecture

# ![Dynacert](https://raw.githubusercontent.com/ifuschini/dynacert/master/docimage/architecture.jpg)

The architecture of Dynacert consisit in three layer:

1. Dynacert Front-End, where are publish the two SPA (Single Page Application) components: 

   a - builder 
   b - viewer

2. Dynacert Back-End, RestfullApi Interface server based on Symfony framework

3. Dynacert DB, MariaDB 

### Prerequisite

For this project, the following is required as a prerequisite:

1. node 12 or higher (https://nodejs.org)

2. docker 20.10.x or higher (https://www.docker.com)
3. php 7.4.x or higher (https://php.net)
4. Symfony 4.25.x or higher (https://www.symfony.com)
5. Composer 2.1.x or higher (https://getcomposer.org/)

or

1. node 12 or higher (https://nodejs.org)

2. apache 2.x or higher (https://httpd.apache.org)

3. php 7.4.x or higher (https://php.net)
4. Symfony 4.25.x or higher (https://www.symfony.com)
5. Composer 2.1.x or higher (https://getcomposer.org/)
6. MariaDB 10.5.x or higher (https://mariadb.com)

## Installation

First of all clone the Dynacert project:

```
git clone https://github.com/ifuschini/dynacert.git
```

and follow this step:

1. build and deploy dynacert-builder
2. build and deploy dynacert-viewer
3. build dynacert-server
4. start docker 

### Dyna-cert builder

Dynacert builder is the administration component that allows:

1) build the form

[![Watch the video](https://img.youtube.com/vi/b3fANQYCEQk/0.jpg)](https://youtu.be/b3fANQYCEQk)

2) Place the fields on the page

[![Watch the video](https://img.youtube.com/vi/lp_BxYJYdWQ/0.jpg)](https://youtu.be/lp_BxYJYdWQ)

3) have a preview of the online form

#### Build the project 

Run this commands below if you use docker instance:

```
cd dynacert-builder
npm install
npm run builddocker
```

The build of Dyna-cert viewer will be locat in <project-path>/dynacert-server/html/viewer

#### URL 

http://localhost/builder

#### DEMO URL

https://dyna-cert.org/builder/

### Dyna-cert viewer

is the part reserved for the user to fill in the form, his signature and download the pdf with the compiled data.

[![Watch the video](https://img.youtube.com/vi/xRRvHbS7ZG4/0.jpg)](https://youtu.be/xRRvHbS7ZG4)


#### Build the project

Run this commands below:

```
cd dynacert-viewer
npm install
npm run builddocker
```

The build of Dyna-cert viewer will be locat in <project-path>/dynacert-server/html/viewer

### dynacert-server

is the server component that provides rest services for Dynacert-viewer and Dynacert-server

```
cd dynacert-server
composer install
symphony serve
```

### dynacert-docker

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

#### URL

http://localhost/viewer

#### Demo URL

https://dyna-cert.org/viewer/

## License

Dynacert	 is available under the MIT license.

###### Logo

<div class="subTitle">Logo created by <a href="https://www.designevo.com/" style="text-decoration: none;" title="Free Online Logo Maker">DesignEvo logo maker</a></div>



