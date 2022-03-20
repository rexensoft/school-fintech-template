<p align="center"><kbd><a href="./public/assets/img/screenshot.png" target="_blank"><img src="./public/assets/img/screenshot.png" width="1000"></a></kbd></p>

<p align="center">
    <a href="https://laravel.com/docs/9.x"><img src="https://img.shields.io/badge/v9.2-Laravel-F9322C" alt="Laravel"></a>
    <a href="https://getbootstrap.com/docs/5.0x"><img src="https://img.shields.io/badge/v5.0-Bootstrap-7952b3" alt="Bootstrap"></a>
    <a href="https://fontawesome.com/icons"><img src="https://img.shields.io/badge/v6.0-Font%20Awesome-146EBE" alt="Bootstrap"></a>
</p>
<h1 align="center"><b>School Fintech Template</b></h1>


## About

School Fintech Template is based on the [SB Admin 2 Laravel Component](https://github.com/rexencorp/sb-admin-2-component) template.

## How To Install
- Open your terminal
- Change directory you want
- Type `git clone --branch main https://github.com/rexencorp/school-fintech-template` in terminal
- After that, type `cd school-fintech-template` to enter the `school-fintech-template` directory
- Type `composer install` in terminal
- After that, type `npm install` in terminal 

## How to Run
- Create a database with the name `school-fintech-template` (you can change the database name)
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
| admin@example.com | password |
| seller@example.com | password |
| teller@example.com | password |
| student@example.com | password |

## Model Fast Methods
Fast Methods makes it easy for you to manipulate databases through models
- **[User](#user-fast-methods)**
  -  [Fast Create](#user-fast-method-create)
  -  [Fast Update](#user-fast-method-update)
  -  [Fast Delete](#user-fast-method-delete)
- **[Transaction](#fast-method-transaction)**
  -  [Fast Topup](#user-fast-method-topup)
  -  [Fast Approve](#user-fast-method-approve)
  -  [Fast Reject](#user-fast-method-reject)

## User Fast Methods

<div id="user-fast-method-create"></div>

- ### Fast Create
  Syntax:
  ```
    User::fastCreate($data [, $password]);
  ```
  
  Example:
  ```
    public function store(Request $request) {
        User::fastCreate($request);
    }
  ```

    Parameters:

    `$data` Data for create user \
    `$password` **_(optional)_** Only string type allowed

  

## License

The School Fintech Template is open-sourced template licensed under the [MIT license](https://opensource.org/licenses/MIT).
