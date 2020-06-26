# Getting Started


<h4>Prerequisites</h4>
<p>What things you need to install the software and how to install them.</p>

<ul>
<li>PHP 7.4.0 (or higher)</li>
<li>composer</li>
<li>mysql 5.7 (or higher)</li>
<li>Elastic 7.6.0 (or higher)</li>
</ul>

<p>Another alternative via docker:</p>

Run docker and connect to container

<pre>
  docker-compose build
  docker-compose up -d
  docker-compose exec php sh
</pre>

# Installation
Run the command below:

<pre>
git clone https://github.com/oussama-aitmi/management
cd management
composer install
(if you not using docker then run the local server) php -S localhost:8000 -t public or (symfony server:start)
</pre>

To initialize the database, set the DATABASE_URL variable in the .env file with your database server URL:
<pre>
DATABASE_URL=mysql://root:root@mysql:3306/symfony?serverVersion=5.7
</pre>

then run the following commands:

<pre>
php bin/console doctrine:database:create

php bin/console doctrine:migrations:migrate
</pre>

The project comes with a data fixture for all entitities, to execute them, run the following line command:

<pre>
php bin/console doctrine:fixtures:load
</pre>

Execute TestUnit:

<pre>
./vendor/bin/phpunit tests/
</pre>

# Example conecting to Api and Consuming Resources via JWT RESTful API

<h3>Register to Api</h3>

<pre>
{
  "email":"email", 
  "firstName":"firstName",  
  "password": "password",
  "confirm_password": "password" 
  }
</pre>

<h5>Output format with HTTP Response Code: 201</h5>

<pre>

    HTTP/1.1 201
    Content-Type: application/json

{
    "token": "token"
}
</pre>

<h3>Conecting to Api</h3>

<pre>
{
  "email":"convoi1@management.com", 
  "password": "management"
}
</pre>

<h5>Output format is an Access Token valid for 86400 seconds (24 hours). - HTTP Response Code: 200</h5>

<pre>
    HTTP/1.1 200
    Content-Type: application/json

{
  "token":"token"
}
</pre>


...

