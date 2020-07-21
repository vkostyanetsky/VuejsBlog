<?

class app extends apps {
    

    ///////////////////////////////////////////////////////////////////////////////
    // ПУБЛИЧНЫЕ ПЕРЕМЕННЫЕ


    static public
    
        $pathString     = '',
        $pathElements   = array();

        
    ///////////////////////////////////////////////////////////////////////////////
    // ПУБЛИЧНЫЕ МЕТОДЫ (ОБЩЕГО НАЗНАЧЕНИЯ)


    static public function run() {
        
        try {
    
            self::init();

        }
        catch (Exception $exception) {

            // ...

        }

    } // function run


    ///////////////////////////////////////////////////////////////////////////////
    // ПУБЛИЧНЫЕ МЕТОДЫ (HTTP-ЗАГОЛОВКИ)    


    static public function printHttpHeaders() {
        
        try {

            self::$module->printHttpHeaders();
        }
        catch (Exception $exception) {

            self::printHttpHeader500();
        }

        header('Content-type: text/html');

    } // printHttpHeaders

    static public function printHttpHeader200() {

        header('HTTP/1.1 200 OK');

    } // printHttpHeader200

    static public function printHttpHeader404() {

        header('HTTP/1.1 404 Not Found');

    } // printHttpHeader404

    static public function printHttpHeader500() {

        header('HTTP/1.1 500 Internal Server Error');

    } // printHttpHeader500

    
    ///////////////////////////////////////////////////////////////////////////////
    // ПУБЛИЧНЫЕ МЕТОДЫ (ТЕГ DESCRIPTION)        


    static public function printDescriptionMetaTag() {

        try {

            self::$module->printDescriptionMetaTag();

        }
        catch (Exception $exception) {

            // ...

        }        
        
    } // printOpenGraphMetaTags    

    static public function printDescriptionMetaTagHTML($content) {

        print '<meta name="description" content="' . $content . '"/>';

    } // printDescriptionMetaTagHTML


    ///////////////////////////////////////////////////////////////////////////////
    // ПУБЛИЧНЫЕ МЕТОДЫ (ТЕГИ OPENGRAPH)    


    static public function printOpenGraphMetaTags() {

        try {

            self::$module->printOpenGraphMetaTags();

        }
        catch (Exception $exception) {

            // ...

        }        
        
    } // printOpenGraphMetaTags

    static public function printOpenGraphMetaTagHTML($property, $content) {

        print '<meta property="' . $property . '" content="' . $content . '"/>';

    } // printOpenGraphMetaTagHTML


    ///////////////////////////////////////////////////////////////////////////////
    // ЗАЩИЩЕННЫЕ МЕТОДЫ


    static protected function init() {

        parent::init();
        
        self::initPath();

        // Создаем нужный модуль.

        $moduleClassName = self::moduleClassName();
        
        require_once PATH . "abc/{$moduleClassName}.php";        

        self::$module = new $moduleClassName;

    } // init


    ///////////////////////////////////////////////////////////////////////////////
    // ПРИВАТНЫЕ МЕТОДЫ (ОБЩЕГО НАЗНАЧЕНИЯ)
    

    static private function initPath() {
        
        self::$pathString = isset($_GET['path']) ? $_GET['path'] : '';
        self::$pathString = self::removeSlashes(self::$pathString);

        self::$pathElements = self::$pathString == '' ? array() : explode('/', self::$pathString);                
        
    } // initPath

    static private function moduleClassName() {
        
        $module     = isset(self::$pathElements[0]) ? self::$pathElements[0] : '';
        $modules    = array('notes', 'rss.xml', 'robots.txt', 'sitemap.xml');
        
        if (! in_array($module, $modules)) $module = 'pages';
        
        $classes = array(
            'notes'         => 'appNotes',
            'pages'         => 'appPages',
            'robots.txt'    => 'appRobots',
            'sitemap.xml'   => 'appSitemap',
            'rss.xml'       => 'appRSS',
        );

        return $classes[$module];
        
    } // moduleClassName

    
} // app