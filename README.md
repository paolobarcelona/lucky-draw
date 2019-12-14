## Lucky Draw System

### Lucky Draw System is a simple lottery application built with laravel 6

### Setting up

`composer install`

`composer build-project`

### Tests

`composer run-tests`

### Seeding

#### To run a full cycle of migration, create-admin-user command, and seeding, simply run:

`composer refresh-and-seed`

#### This will run the following:

#### To refresh the db:

`php artisan migrate:reset`

#### To run migration:

`php artisan migrate`

#### To create the admin user for site access run:

`php artisan create-admin-user`

#### To perform user seeding

`php artisan db:seed --class=UserWinningNumberTableSeeder`


### Admin credentials

`username: admin@lucky.draw`

`password: password`
