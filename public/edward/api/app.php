<?

class app extends apps {
    
        
    ///////////////////////////////////////////////////////////////////////////////
    // ОСНОВНАЯ ЛОГИКА


    static public function run() {
        
        try {
    
            self::init();

            $data = self::moduleData();

        } 
        catch (Exception $exception) {

            $errorCode = $exception->getCode();
            $errorText = $exception->getMessage();

            $data = self::errorData($errorCode);

        }

        $data['isError'] = isset($data['errorCode']);

        self::printData($data);

    } // run

    static public function var($name) {

        $variable = (isset($_GET[$name]) ? $_GET[$name] : '');

        return self::removeSlashes($variable);

    } // var


    ///////////////////////////////////////////////////////////////////////////////
    // ВСПОМОГАТЕЛЬНЫЙ ФУНКЦИОНАЛ


    static protected function init() {

        parent::init();

    } // init


    static private function moduleData() {

        $moduleClassName = self::moduleClassName();
                
        require_once PATH . "api/{$moduleClassName}.php";

        self::$module = new $moduleClassName;

        return self::$module->data();

    } // moduleData


    static private function moduleClassName() {
        
        $modules    = array('pages', 'notes');
        $module     = self::var('module');
                
        if (! in_array($module, $modules)) $module = 'hello'; 

        $classNames = array(
            'hello' => 'appHello',
            'pages' => 'appPages',
            'notes' => 'appNotes',
        );        
     
        return $classNames[$module];
        
    } // moduleClassName


    static private function errorData($errorCode, $errorText = '') {

        return array(
            'errorCode' => $errorCode,
            'errorText' => $errorText,
        );

    } // errorData


    static private function printData($data) {

        header('HTTP/1.1 200 OK');
        header('Content-type: application/json');
        
        $json = json_encode($data);

        exit($json);

    } // printData


} // app