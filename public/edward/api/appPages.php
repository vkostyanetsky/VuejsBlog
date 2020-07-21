<?

class appPages {


    ///////////////////////////////////////////////////////////////////////////////
    // ПУБЛИЧНЫЕ МЕТОДЫ


    public function data() {

        $path = app::var('path');
        $page = pages::pageByPath($path);

        if (! $page) throw new Exception('Page Not Found', 404);

        return array(
            'page' => $page
        );        

    } // data


} // appPages