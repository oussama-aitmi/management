# Installation
To install the application dependencies, run the command below:

<pre>composer install</pre>

To initialize the database, set the DATABASE_URL variable in the .env file with your database server URL, then run the following commands:

<pre>
php bin/console doctrine:database:create

php bin/console doctrine:migrations:migrate
</pre>

The project comes with a data fixture for all entitities, to execute them, run the following line command:

<pre>
php bin/console doctrine:fixtures:load
</pre>

# Example conecting to Api and Consuming Resources via RESTful API

Sending a POST request on /category with the following body:

<pre>
{
  "title": "name category",
  "parent": "12" (parent id if exist)
}
</pre>

