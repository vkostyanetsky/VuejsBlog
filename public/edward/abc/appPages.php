<?

class appPages {


    ///////////////////////////////////////////////////////////////////////////////
    // ПРИВАТНЫЕ ПЕРЕМЕННЫЕ

    
    private
    
        $page = array();


    ///////////////////////////////////////////////////////////////////////////////
    // ПУБЛИЧНЫЕ МЕТОДЫ


    public function __construct() {

        // Загрузим данные страницы — они понадобятся и для вывода,
        // HTTP-заголовков, и для вывода мета-тегов OpenGraph.

        $pathString = app::$pathString;
        $this->page = pages::pageOpenGraphDataByPath($pathString);

    } // __construct
    

    public function printHttpHeaders() {

        if ($this->page) {
            app::printHttpHeader200();
        }
        else {
            app::printHttpHeader404();
        }

    } // printHttpHeaders


    public function printDescriptionMetaTag() {

        if (! $this->page) return;

        $description = $this->page['description'];

        app::printDescriptionMetaTagHTML($description);

    } // printDescriptionMetaTag

    
    public function printOpenGraphMetaTags() {

        if (! $this->page) return;
        
        $ogTitle        = $this->page['title'] !== '' ? $this->page['title'] : app::text('site');
        $ogDescription  = $this->page['description'];

        if ($this->page['preview_image'] !== '') {
            $ogImage = app::$url . '/images/' . $this->page['preview_image'];
        }
        else {
            $ogImage = '';
        }
        
        $ogType = 'article';
        $ogURL  = app::$url . '/' . app::$pathString;
        
        // ...

        app::printOpenGraphMetaTagHTML('og:title',       $ogTitle);
        app::printOpenGraphMetaTagHTML('og:description', $ogDescription);
        app::printOpenGraphMetaTagHTML('og:image',       $ogImage);
        app::printOpenGraphMetaTagHTML('og:type',        $ogType);
        app::printOpenGraphMetaTagHTML('og:url',         $ogURL);
        
    } // printOpenGraphMetaTags


} // appPages