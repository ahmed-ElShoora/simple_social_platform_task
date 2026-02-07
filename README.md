Steps to run :

composer install
npm install

cp .env.example .env

php artisan key:generate

php artisan migrate

php artisan db:seed

php artisan optimize:clear

php artisan storage:link

in terminal 1 : php artisan serve
in terminal 2 : npm run dev
in terminal 3 : php artisan queue:work
