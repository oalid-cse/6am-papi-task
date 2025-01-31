## 6amTech Technical Task

### Requirements

- `php:^8.2`
- `composer`

### Installation process of this task

1. `git clone git@github.com:oalid-cse/6am-papi-task.git`
2. `cd 6am-papi-task`
3. `composer install`
4. `cp .env.example .env`
5. update database connection in .env file
6. `php artisan key:generate`
7. `php artisan migrate --seed`
8. `php artisan passport:keys`
9. `php artisan passport:client --personal`
10. `php artisan optimize`
11. `php artisan serve` [If local]



