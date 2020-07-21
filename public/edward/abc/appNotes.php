<?

class appNotes {


    ///////////////////////////////////////////////////////////////////////////////
    // ПРИВАТНЫЕ ПЕРЕМЕННЫЕ


    private

        $routeName      = '',

        $tagName        = '',        
        $noteName       = '',
        $pageNumber     = 0,

        $noteData       = array();


    ///////////////////////////////////////////////////////////////////////////////
    // ПУБЛИЧНЫЕ МЕТОДЫ


    public function __construct() {
        
        // Определим активный маршрут и его параметры.

        $this->initRoute();

    } // __construct
    
    public function printHttpHeaders() {

        if ($this->routeName == 'notes') {

            $this->printHttpHeadersForNotes();
        }
        else if ($this->routeName == 'note') {

            $this->printHttpHeadersForNote();
        }
        else if ($this->routeName == 'tags') {

            $this->printHttpHeadersForTags();
        }
        else app::printHttpHeader404();

    } // printHttpHeaders

    public function printDescriptionMetaTag() {

        if ($this->routeName != 'note' or ! $this->noteData) return;

        $description = $this->noteData['description'];

        app::printDescriptionMetaTagHTML($description);

    } // printDescriptionMetaTag

    public function printOpenGraphMetaTags() {

        if ($this->routeName != 'note' or ! $this->noteData) return;

        $ogTitle        = $this->noteData['title'] !== '' ? $this->noteData['title'] : app::text('site');
        $ogDescription  = $this->noteData['description'];

        if ($this->noteData['preview_image'] !== '') {
            $ogImage = app::$url . '/images/' . $this->noteData['preview_image'];
        }
        else $ogImage = '';
        
        $ogType = 'article';
        $ogURL  = app::$url . '/' . app::$pathString;
        
        // ...

        app::printOpenGraphMetaTagHTML('og:title',       $ogTitle);
        app::printOpenGraphMetaTagHTML('og:description', $ogDescription);
        app::printOpenGraphMetaTagHTML('og:image',       $ogImage);
        app::printOpenGraphMetaTagHTML('og:type',        $ogType);
        app::printOpenGraphMetaTagHTML('og:url',         $ogURL);
        
    } // printOpenGraphMetaTags


    ///////////////////////////////////////////////////////////////////////////////
    // ПРИВАТНЫЕ МЕТОДЫ    


    private function initRoute() {

        $pathElementsCount = count(app::$pathElements);

        switch ($pathElementsCount) {

            // /notes/tags/games/page/1

            case 5:

                if (app::$pathElements[1] == 'tags' and app::$pathElements[3] == 'page') {

                    $this->routeName  = 'notes';

                    $this->tagName    = app::$pathElements[2];
                    $this->pageNumber = (int)app::$pathElements[4];

                }

                break;

            // /notes/tags/games/gris

            case 4:

                if (app::$pathElements[1] == 'tags') {

                    $this->routeName  = 'note';

                    $this->tagName    = app::$pathElements[2];
                    $this->noteName   = app::$pathElements[3];

                }

                break;

            // /notes/tags/games или /notes/page/1

            case 3:

                if (app::$pathElements[1] == 'tags') {

                    $this->routeName  = 'notes';
                    $this->tagName    = app::$pathElements[2];

                }
                else if (app::$pathElements[1] == 'page') {

                    $this->routeName  = 'notes';
                    $this->pageNumber = (int)app::$pathElements[2];

                }

                break;

            // /notes/tags или /notes/draugen

            case 2:

                if (app::$pathElements[1] == 'tags') {

                    $this->routeName  = 'tags';

                }
                else {

                    $this->routeName  = 'note';
                    $this->noteName   = app::$pathElements[1];

                }

                break;

            // /notes/

            case 1:

                $this->routeName = 'notes';

                break;

        }

        if ($this->routeName == 'note') {

            $tagId = notes::tagIdByName($this->tagName);
         
            if ($tagId != 0) {
                $this->noteData = notes::noteOpenGraphDataByNameAndTagId($this->noteName, $tagId);    
            }
            else {
                $this->noteData = notes::noteOpenGraphDataByName($this->noteName);
            }            

        }

    } // initRoute

    private function printHttpHeadersForNotes() {

        $tagId          = notes::tagIdByName($this->tagName);
        $pagesNumber    = notes::pagesNumber($tagId);

        if ($this->pageNumber == 0) {
            $this->pageNumber = $pagesNumber;
        }
            
        if ($this->pageNumber > $pagesNumber) {
            app::printHttpHeader404();
        }
        else {
            app::printHttpHeader200();
        }

    } // printHttpHeadersForNotes

    private function printHttpHeadersForNote() {

        if ($this->noteData) {
            app::printHttpHeader200();
        }
        else {
            app::printHttpHeader404();
        }

    } // printHttpHeadersForNote

    private function printHttpHeadersForTags() {

        app::printHttpHeader200();

    } // printHttpHeadersForTags


} // appNotes