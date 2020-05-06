# Getting Started


<h4>Prerequisites</h4>
<p>What things you need to install the software and how to install them.</p>

<ul>
<li>PHP 7.2.0</li>
<li>composer</li>
<li>symfony</li>
</ul>


# Installation
Run the command below:

<pre>
git clone https://github.com/oussama-aitmi/management
cd management
cp .env.dist .env
## edit .env if needed
composer install
symfony server:start
</pre>

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

