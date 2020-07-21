<?

class appRobots {


    ///////////////////////////////////////////////////////////////////////////////
    // ПУБЛИЧНЫЕ МЕТОДЫ


    public function __construct() {

        header('Content-type: text/plain;charset=utf-8');
        
        print "User-agent: *\n";
        print "Disallow: /notes/tags*\n";
        
        print 'Sitemap: ' . app::$url . '/sitemap.xml';

        exit;
        
    } // __construct


} // appRobots