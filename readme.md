#  posting and commenting 
Simple Application API for posting and commenting

## Prerequisites

You will need the following things properly installed on your computer.

* [Git](https://git-scm.com/)
* [php](with composer)
* mysql(database)
* postman ( to check the API)

## Installation

* `git clone <repository-url>` this repository
* `cd appPosting`
* `composer install install`
* `php artisan key:generate
* cp .env.example .env [change the environment variables like database name]
* create databases app_posting in mysql
* php artisan migrate (migrate tables) 
* php artisan db:seed ( Run all seeders)


## Running / Development

* `php artisan serve`
* Your server will be running in [http://localhost:8000](http://localhost:8000)

### Running Tests


#### Contributors: [Rojina Bajracharya](https://github.com/shrsujan)
