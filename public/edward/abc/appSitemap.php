<?

class appSitemap {


    ///////////////////////////////////////////////////////////////////////////////
    // ПРИВАТНЫЕ ПЕРЕМЕННЫЕ


    private 
        $sitemap = null;


    ///////////////////////////////////////////////////////////////////////////////
    // ПУБЛИЧНЫЕ МЕТОДЫ


    public function __construct() {
        
        $this->sitemap = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"></urlset>');

        $this->includePages();
        $this->includeNotes();
                
        header('Content-type: text/xml;charset=utf-8');
        
        print $this->sitemap->asXML();

        exit;

    } // __construct
    

    ///////////////////////////////////////////////////////////////////////////////
    // ПРИВАТНЫЕ МЕТОДЫ    


    private function includePages() {

        $queryText = "
        SELECT 
            path                                    AS 'path',
            DATE_FORMAT(change_date, '%Y-%m-%d')    AS 'change_date',
            IF(path = '', '1.0', '0.8')             AS 'priority'
                
        FROM
            ??_pages
             
        WHERE
            exclude_from_sitemap = 0";

        $queryResult = db::statement($queryText);
        
        while ($page = $queryResult->fetch(PDO::FETCH_LAZY)) {

            $loc        = pages::url($page['path']);
            $lastmod    = $page['change_date'];
            $changefreq = 'weekly';
            $priority   = $page['priority'];

            $urlTag = $this->sitemap->addChild('url');
                        
            $urlTag->addChild('loc',            $loc);
            $urlTag->addChild('lastmod',        $lastmod);
            $urlTag->addChild('changefreq',     $changefreq);
            $urlTag->addChild('priority',       $priority);

        }

    } // includePages

    private function includeNotes() {

        $queryText = "
        SELECT 
            name                                    AS 'name',
            DATE_FORMAT(change_date, '%Y-%m-%d')    AS 'change_date'
                
        FROM
            ??_notes
             
        WHERE
            exclude_from_sitemap = 0";

        $queryResult = db::statement($queryText);

        while ($note = $queryResult->fetch(PDO::FETCH_LAZY)) {

            $loc        = notes::url($note['name']);
            $lastmod    = $note['change_date'];
            $changefreq = 'weekly';
            $priority   = '0.6';

            $urlTag = $this->sitemap->addChild('url');
                        
            $urlTag->addChild('loc',            $loc);
            $urlTag->addChild('lastmod',        $lastmod);
            $urlTag->addChild('changefreq',     $changefreq);
            $urlTag->addChild('priority',       $priority);
            
        }

    } // includeNotes
    

} // appSitemap