<?

// Настраиваем работу с ошибками.

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Подключаем общеиспользуемый функционал.

require_once PATH . 'common/apps.php';
require_once PATH . 'common/db.php';
require_once PATH . 'common/pages.php';
require_once PATH . 'common/notes.php';
require_once PATH . 'common/parser.php';