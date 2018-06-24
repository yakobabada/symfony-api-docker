# The Task

* Read the users from the xml, csv and json files within the `data` directory
* Merge all users into a single list and sort them by their `userId` in ascending order
* Write the ordered results to new xml, csv and json files, see the `examples` directory
  * Results should use the same structure as the source files they were parsed from
  * The exception is for `lastLoginTime` where an `ISO 8601` date format is preferred for output


# Requirements

* PHP >= 7.0
* [composer](https://getcomposer.org/download/)
* mysql

# Framework

    Symfony 4.0

# Installation
    UPDATE USERNAME:PASSWORD IN `.env`, `phpunit.xml.dist` file for myql
    

    composer install
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    php bin/console doctrine:database:create --env=test
    php bin/console doctrine:migrations:migrate --env=test

# Run
    php bin/console server:start
# Test
    bin/phpunit

# Webpage
    http://127.0.0.1:8000/web-url
  
# Command
* For testing a list of given web url

    `php bin/console app:check-web-url-status`

# APIs
* Post new Web url
    * Method: POST
    * Url: http://127.0.0.1:8000/api/web-url
    * Body:
        ```
        {
          "url": "https://www.shipserv.com/info/about-us"
        }
        ```

* Get Web url
    * Method: GET
    * Url: http://127.0.0.1:8000/api/web-url/{id}

* Get list of Web url
    * Method: GET
    * Url: http://127.0.0.1:8000/api/web-url
    
* Delete Web url
    * Method: DELETE
    * Url: http://127.0.0.1:8000/api/web-url/{id}