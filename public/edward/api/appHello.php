<?

class appHello {


    ///////////////////////////////////////////////////////////////////////////////
    // ОСНОВНАЯ ЛОГИКА


    public function data() {

        db::select('SELECT 1');

        return array(
            'languageCode' => app::$language,
        );

    } // data


} // appHello