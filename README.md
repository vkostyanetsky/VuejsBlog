# Блог на Vue.js

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Клиент-серверное веб-приложение для моего сайта. Написано для эксперимента.

## Что умеет?

1. Вывод ленты заметок и группировка их по тегам;
2. Вывод страниц с произвольным содержимым;
3. Генерация sitemap.xml и robots.txt;
4. Генерация RSS-фида.

Движок позволяет вести два сайта сразу — на русском и английском языках. То есть, если есть два домена (один для русскоязычной аудитории, второй — для англоязычной), оба можно завернуть на один сервер, где развернуто это приложение. Оно определит, на какой домен пришел пользователь, и выведет подходящий интерфейс плюс данные из БД — опять-таки, на подходящем языке.

Админки нет, управление — через сторонний софт для работы с базой данных. Я, например, использовал слегка допиленный [Adminer Editor](https://www.adminer.org/en/editor/).

## На чем работает?

- Веб-сервер — [Apache](https://httpd.apache.org/) 2.4 (понадобятся mod_filter и mod_deflate).
- В качестве СУБД используется [MariaDB](https://mariadb.org/) 10.3 (потребуются триггеры).
- Клиентская часть приложения написана на [Vue.js](https://vuejs.org/) с помощью [Vue CLI](https://cli.vuejs.org/).
- Серверная часть крутится на [PHP](https://www.php.net/) 7.1.
- Сборка делалась на [NodeJS](https://nodejs.org/en/) 12.6.0.
