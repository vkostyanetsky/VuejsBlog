<?

class appRSS {


    ///////////////////////////////////////////////////////////////////////////////
    // ПРИВАТНЫЕ ПЕРЕМЕННЫЕ


    private 

        $rss        = null,
        $rssChannel = null;


    ///////////////////////////////////////////////////////////////////////////////
    // ПУБЛИЧНЫЕ МЕТОДЫ


    public function __construct() {
             
        $this->rss          = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><rss version="2.0"></rss>');
        $this->rssChannel   = $this->rssChannel();

        $this->addLastBuildDate();
        $this->addItems();

        header('Content-type: text/xml;charset=utf-8');
    
        print $this->rss->asXML();

        exit; 

    } // __construct


    ///////////////////////////////////////////////////////////////////////////////
    // ПРИВАТНЫЕ МЕТОДЫ
 
  
    private function rssChannel() {
        
        $title          = app::text('site');
        $description    = $this->homePageDescription();
        $language       = app::$language;
        $link           = app::$url;

        $channel = $this->rss->addChild('channel');
        
        $channel->addChild('title',         $title);
        $channel->addChild('description',   $description);
        $channel->addChild('language',      $language);
        $channel->addChild('link',          $link);

        return $channel;

    } // rssChannel
    
    private function homePageDescription() {

        $queryText = "
        SELECT
            description
            
        FROM
            ??_pages
            
        WHERE 
            path = '/'";

        $page = db::select($queryText);

        return $page ? $page['description'] : '';

    } // homePageDescription

    private function addLastBuildDate() {

        $queryText = "
        SELECT 
            id,
            UNIX_TIMESTAMP(date) AS 'date'
        
        FROM
            ??_pages            
            
        WHERE
            exclude_from_rss = 0                 
    
        UNION ALL 
    
        SELECT 
            id,
            UNIX_TIMESTAMP(date) AS 'date'
        
        FROM
            ??_notes
        
        WHERE
            exclude_from_rss = 0                 

        ORDER BY
            date DESC, id DESC
                                
        LIMIT 0, 1";

        $queryResult    = db::statement($queryText);        
        $entry          = $queryResult->fetch(PDO::FETCH_LAZY);
        
        $lastBuildDate = $this->date($entry['date']);
        
        $this->rssChannel->addChild('lastBuildDate', $lastBuildDate);

    } // addLastBuildDate    

    private function addItems() {

        $queryText = "
        SELECT 
            1                       AS 'is_page',
            id                      AS 'id',
            title                   AS 'title',
            path                    AS 'path',
            UNIX_TIMESTAMP(date)    AS 'date',
            description             AS 'description',
            content                 AS 'content'
                                    
        FROM ??_pages
                 
        WHERE
            exclude_from_rss = 0
          
        UNION ALL 

        SELECT 
            0                       AS 'is_page',
            id                      AS 'id',
            title                   AS 'title',
            name                    AS 'path',
            UNIX_TIMESTAMP(date)    AS 'date',
            description             AS 'description',
            content                 AS 'content'
                                    
        FROM ??_notes
                 
        WHERE
            exclude_from_rss = 0

        ORDER BY
            date DESC, id DESC
                    
        LIMIT 0, 10";

        $queryResult = db::statement($queryText);
        
        while ($entry = $queryResult->fetch(PDO::FETCH_LAZY)) {
            $this->addItem($entry);
        }

    } // addItems

    private function addItem($entry) {

        $item = $this->rssChannel->addChild('item');

        $this->addItemTitle($item, $entry);
        $this->addItemLink($item, $entry);
        $this->addItemGUID($item, $entry);
        $this->addItemPubDate($item, $entry);
        $this->addItemDescription($item, $entry);

    } // addItem

    private function addItemTitle($item, $entry) {

        $title = $this->text($entry['title']);         
     
        $item->addChild('title', $title);

    } // addItemTitle

    private function addItemLink($item, $entry) {

        if ($entry['is_page']) {
            $link = pages::url($entry['path']);
        }
        else {
            $link = notes::url($entry['path']);
        }
        
        $item->addChild('link', $link);

    } // addItemLink

    private function addItemGUID($item, $entry) {

        if ($entry['is_page']) {
            $guid = pages::guid($entry['id']);
        }
        else {
            $guid = notes::guid($entry['id']);
        }
        
        $guidItem = $item->addChild('guid', $guid);
        $guidItem->addAttribute('isPermaLink', 'false');

    } // addItemGUID

    private function addItemPubDate($item, $entry) {

        $pubDate = $entry['date'];
        $pubDate = $this->date($pubDate);

        $item->addChild('pubDate', $pubDate);

    } // addItemPubDate

    private function addItemDescription($item, $entry) {

        $description = $entry['content'];

        parser::parse($description);

        $description = $this->text($description);
        
        $item->addChild('description', $description);

    } // addItemDescription

    private function date($timestamp) {

        return date(DATE_RFC2822, $timestamp);

    } // date
    
    private function text($text) {

        $text = htmlspecialchars($text, ENT_QUOTES);
        
        return $text;

    } // text
    

} // appRSS