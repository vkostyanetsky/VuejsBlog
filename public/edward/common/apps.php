<?

class apps {
    

    ///////////////////////////////////////////////////////////////////////////////
    // СВОЙСТВА КЛАССА


    static public
    
        $language   = '',
        $messages   = array(),
        $domains    = array(),
        $module     = null,
        $url        = '';

        
    ///////////////////////////////////////////////////////////////////////////////
    // ПУБЛИЧНЫЕ МЕТОДЫ


    static public function printVars() {

        header('HTTP/1.1 200 OK');
        header('Content-type: text/plain;charset=utf-8');
       
        $arguments = func_get_args();
       
        if (is_array($arguments) and count($arguments)) {

            foreach ($arguments as $something) {

                print_r($something);
                
            } 

        }
        
        exit;

    } // printVars

    static public function printText($messageCode) {
        
        print self::text($messageCode);

    } // printText

    static public function text($messageCode) {

        return self::$messages[$messageCode];

    } // text

    static public function removeSlashes(& $text) {
        
        $text = preg_replace("#^/#", '', $text);
        $text = preg_replace("#/$#", '', $text);
        
        return $text;

    } // removeSlashes

    
    ///////////////////////////////////////////////////////////////////////////////
    // ЗАЩИЩЕННЫЕ МЕТОДЫ


    static protected function init() {
        
	    require_once PATH . '/settings.php';
        
        // Определим домены сайта, его активный язык.

        self::$domains  = $settings['domains'];
        self::$language = ($_SERVER['HTTP_HOST'] == self::$domains['ru'] ? 'ru' : 'en');

        // Определим URL сайта.

        $protocol   = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
        $domain     = self::$domains[self::$language];

        self::$url  = "$protocol://$domain";

        // Инициализуем подключение к базе данных.
        
        db::init($settings['database']);

        // Определим набор сообщений для активного языка.

        require_once PATH . 'language.php';

        self::$messages = $messages[self::$language];

    } // init


} // apps