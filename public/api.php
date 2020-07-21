<?

define('PATH', $_SERVER['DOCUMENT_ROOT'] . '/edward/');

require_once PATH . 'init.php';
require_once PATH . 'api/app.php';

app::run();