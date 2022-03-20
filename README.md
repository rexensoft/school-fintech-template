<p align="center"><kbd><a href="./public/assets/img/screenshot.png" target="_blank"><img src="./public/assets/img/screenshot.png" width="1000"></a></kbd></p>

<p align="center">
    <a href="https://laravel.com/docs/9.x"><img src="https://img.shields.io/badge/v9.2-Laravel-F9322C" alt="Laravel"></a>
    <a href="https://getbootstrap.com/docs/5.0x"><img src="https://img.shields.io/badge/v5.0-Bootstrap-7952b3" alt="Bootstrap"></a>
    <a href="https://fontawesome.com/icons"><img src="https://img.shields.io/badge/v6.0-Font%20Awesome-146EBE" alt="Bootstrap"></a>
</p>
<h1 align="center"><b>SB Admin 2 Laravel Component</b></h1>


## About

sb admin 2 component is based on the free version of sb admin 2 template, now we convert the template into laravel and make it a component!

## How To Install
- Open your terminal
- Change directory you want
- Type `git clone --branch main https://github.com/rexencorp/sb-admin-2-component` in terminal
- After that, type `cd sb-admin-2-component` to enter the `sb-admin-2-component` directory
- Type `composer install` in terminal
- After that, type `npm install` in terminal 

## How to Run
- Create a database with the name `sb_admin2_component` (you can change the database name)
- Type `cp .env.example .env` in terminal OR `copy .env.example .env` in cmd
- Adjust the `DB_DATABASE` in `.env` file according the database you created 
- Type `php artisan key:generate` in terminal
- Type `php artisan migrate --seed` in terminal
- After that serve the project with `php artisan serve` in your terminal
- Open http://127.0.0.1:8000/ in your browser.
- Voila! your project is ready to use

## Demo Account
| Email | Password |
| :---  |   :---   |
| user@example.com | password |

## License

The SB Admin 2 Laravel Component is open-sourced template licensed under the [MIT license](https://opensource.org/licenses/MIT).
