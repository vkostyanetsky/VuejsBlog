<?

class db {


    ///////////////////////////////////////////////////////////////////////////////
    // ПРИВАТНЫЕ ПЕРЕМЕННЫЕ


    static private    

        $connected  = false,
        $pdo        = null,
        
        $hostname   = '',
        $database   = '',        
        $username   = '',
        $password   = '';
    

    ///////////////////////////////////////////////////////////////////////////////
    // ПУБЛИЧНЫЕ МЕТОДЫ


    static public function init($parameters) {

        self::$hostname = $parameters['hostname'];
        self::$database = $parameters['database'];
        self::$username = $parameters['username'];
        self::$password = $parameters['password'];        

    } // init
        
    static public function select($query, $parameters = array()) {    
        
        $statement = self::statement($query, $parameters);
        
        return $statement->fetch();    

    } // select

    static public function selectAll($query, $parameters = array()) {    

        $statement = self::statement($query, $parameters);
        
        return $statement->fetchAll();    

    } // selectAll

    static public function placeholders($parameters) {

        return str_repeat('?,', count($parameters) - 1) . '?';

    } // placeholders

    static public function statement($query, $parameters = array()) {

        $prefix = app::$language . '_';
        $query  = str_replace('??_', $prefix, $query);
        
        if (! self::$connected) self::connect();
        
        $statement = self::$pdo->prepare($query);
        $statement->execute($parameters);

        return $statement;

    } // statement


    ///////////////////////////////////////////////////////////////////////////////
    // ПРИВАТНЫЕ МЕТОДЫ
    
    
    static private function connect() {

        $dsn = 'mysql:host=' . self::$hostname . ';dbname=' . self::$database . ';charset=utf8';

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        self::$pdo = new PDO($dsn, self::$username, self::$password, $options);

    } // connect
  

} // db