# EcoIT
***
![HTML5](https://img.shields.io/badge/html5-%23E34F26.svg?logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/css3-%231572B6.svg?logo=css3&logoColor=white)
![Bootstrap](https://img.shields.io/badge/bootstrap-%23563D7C.svg?logo=bootstrap&logoColor=white)
![PHP](https://img.shields.io/badge/php-%23777BB4.svg?logo=php&logoColor=white)
![Symfony](https://img.shields.io/badge/symfony-%23000000.svg?logo=symfony&logoColor=white)
![MariaDB](https://img.shields.io/badge/MariaDB-003545?logo=mariadb&logoColor=white)
[![Heroku App Status](http://heroku-shields.herokuapp.com/eco-it)](https://eco-it.herokuapp.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

<div align="center">
    <a href="https://eco-it.herokuapp.com/"><img src="public/img/logo.svg" alt="Logo" height="200"></a>
</div>

## About the project
***
This project is an online course platform. The objective is to allow developers to train in eco-responsible development. Teachers can create an account in order to offer courses. Students can create an account to take these courses.  

_Project carried out as part of an evaluation._


## Getting Started
***
### Local Deployment
1. Clone the repository
```
git clone https://github.com/konradcr/EcoIT
```
2. Navigate to the repository
```
cd EcoIT
```
3. Install dependencies
```
composer install
```
#### Create the database
1. Into the .env file modify the database url with your database :
```
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"
```
2. Then, create the database and do the migrations :
```
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```
3. Restore the backup of the database located in the _annexes_ folder :
```
mysql -h DB_HOST -u DB_USER -pDB_PASSWORD DB_NAME < EcoIT/annexes/backup_eco_it.sql
```
#### Launch the server
```
symfony server:start
```

### Heroku Deployment
_Make sure that you have installed Heroku CLI and that you committed the project on git._
1. Connect your Heroku account
```
heroko login
```
2. Create the Heroku project
```
heroko create
```
3. Then create the Procfile :
```
echo 'web: heroku-php-apache2 public/' > Procfile
```
4. URL Rewrites
```
composer require symfony/apache-pack
```
#### Environment variables
1. Set APP_ENV to prod :
```
heroku config:set APP_ENV=prod
```
2. Change the APP_SECRET in production :
```
heroku config:set APP_SECRET=$(php -r 'echo bin2hex(random_bytes(16));')
```
#### Heroku Add-ons - Database with JawsDB
1. Add the JawsDB Maria Add-ons from Heroku to take advantage of a real database :
```
heroku addons:create jawsdb-maria:kitefin
```
2. Copy your database url from :
```
heroku config:get JAWSDB_MARIA_URL
```
3. Set your new DATABASE_URL :
```
heroku config:set DATABASE_URL=your_db_url
```
4. Migrating the backup of the database located in the _annexes_ folder to JawsDB :
```
mysql -h JAWS_DB_HOST -u JAWS_DB_USER -pJAWS_DB_PASSWORD JAWS_DB_DATABASE < EcoIT/annexes/backup_eco_it.sql
```
#### Deploy to Heroku
```
git add .
git commit -m "Heroku configuration"
git push heroku main
heroku open
```
## Learn more
***

[Symfony](https://symfony.com)  
[Bootstrap](https://getbootstrap.com)   
[Heroku](https://heroku.com)  
[JawDB Maria](https://www.jawsdb.com/docs/)  

## License
***

Released under [MIT](https://opensource.org/licenses/MIT) by [@konradcr](https://github.com/konradcr).