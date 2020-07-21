<?

class pages {


    ///////////////////////////////////////////////////////////////////////////////
    // ПУБЛИЧНЫЕ МЕТОДЫ


    static public function url($path) {

        return app::$url . $path;

    } // url


    static public function guid($id) {

        return "page-$id";

    } // guid


    static public function pageOpenGraphDataByPath($path) {

        $queryText = '
        SELECT 
            `title`, `description`, `preview_image`
            
        FROM 
            `??_pages`
            
        WHERE
            `path` = ?
        
        LIMIT 1';

        $queryParameters = array($path);

        return db::select($queryText, $queryParameters);

    } // pageOpenGraphDataByPath


    static public function pageByPath($path) {

        $queryText = '
        SELECT
            `path`, `title`, `content` 

        FROM 
            `??_pages`
        
        WHERE
            `path` = ?
        
        LIMIT 1';

        $queryParameters = array($path);

        $page = db::select($queryText, $queryParameters);

        if ($page) parser::parse($page['content']);

        return $page;

    } // pageByPath


} // pages