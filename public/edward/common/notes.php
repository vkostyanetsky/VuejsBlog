<?

class notes {


    ///////////////////////////////////////////////////////////////////////////////
    // ПУБЛИЧНЫЕ ПЕРЕМЕННЫЕ


    static public
    
        $notesPerPage = 10;


    ///////////////////////////////////////////////////////////////////////////////
    // ПУБЛИЧНЫЕ МЕТОДЫ


    static public function url($name) {

        return app::$url . '/notes/' . $name;

    } // url


    static public function guid($id) {

        return "note-$id";

    } // guid


    static public function tagIdByName($tagName) {

        $queryText = '
        SELECT
            tags.id AS id
    
        FROM 
            ??_notes_tags AS tags
    
        WHERE
            tags.name = ?';
    
        $queryParameters = array($tagName);
    
        $tag = db::select($queryText, $queryParameters);
    
        return $tag ? $tag['id'] : 0;    
    
    } // tagIdByName


    static public function tagsByNoteIds($noteIds) {

        $placeholders   = db::placeholders($noteIds);            
        $queryText      = "        
        SELECT
            notes_tags_bindings.note_id     AS note_id,
            tags.name                       AS tag_name,
            tags.title                      AS tag_title

        FROM 
            ??_notes_tags_bindings AS notes_tags_bindings
            
        INNER JOIN ??_notes_tags AS tags
        ON 
            tags.id = notes_tags_bindings.tag_id
            
        WHERE 
            notes_tags_bindings.note_id IN ($placeholders)
                
        ORDER BY 
            tags.name ASC";

        return db::selectAll($queryText, $noteIds);

    } // tagsByNoteIds


    static public function tagsUsage() {

        $queryText = '
        SELECT
            notes_tags.name                     AS name,
            notes_tags.title                    AS title,
            COUNT(notes_tags_bindings.tag_id)   AS counter

        FROM ??_notes_tags_bindings AS notes_tags_bindings

        INNER JOIN ??_notes_tags AS notes_tags
        ON 
            notes_tags.id = notes_tags_bindings.tag_id

        GROUP BY
            notes_tags_bindings.tag_id
        
        ORDER BY
            counter DESC';

        return db::selectAll($queryText);
        
    } // tagsUsage


    static public function noteByName($name) {

        $queryText = "
        SELECT
            id, DATE_FORMAT(date, '%Y/%m/%d %k:%i:%s') AS date, name, title, content, description

        FROM
            ??_notes

        WHERE
            name = ?";

        $queryParameters = array($name);

        $note = db::select($queryText, $queryParameters);

        if ($note) parser::parse($note['content']);

        return $note;

    } // noteByName


    static public function noteOpenGraphDataByName($name) {

        $queryText = '
        SELECT
            title, description, preview_image

        FROM
            ??_notes

        WHERE
            name = ?';

        $queryParameters = array($name);

        return db::select($queryText, $queryParameters);

    } // noteOpenGraphDataByName


    static public function noteByNameAndTagId($name, $tagId) {

        $queryText = "
        SELECT
            notes.id, DATE_FORMAT(notes.date, '%Y/%m/%d %k:%i:%s') AS date, notes.name, notes.title, notes.content, notes.description

        FROM
            ??_notes AS notes

        INNER JOIN ??_notes_tags_bindings AS notes_tags_bindings
        ON
            notes_tags_bindings.note_id     = notes.id
            AND notes_tags_bindings.tag_id  = ?

        WHERE
            notes.name = ?";

        $queryParameters = array($tagId, $name);

        $note = db::select($queryText, $queryParameters);        

        if ($note) parser::parse($note['content']);

        return $note;

    } // noteByNameAndTagId


    static public function noteOpenGraphDataByNameAndTagId($name, $tagId) {

        $queryText = '
        SELECT
            notes.title             AS title,
            notes.description       AS description,
            notes.preview_image     AS preview_image

        FROM
            ??_notes AS notes

        INNER JOIN ??_notes_tags_bindings AS notes_tags_bindings
        ON
            notes_tags_bindings.note_id     = notes.id
            AND notes_tags_bindings.tag_id  = ?

        WHERE
            notes.name = ?';

        $queryParameters = array($tagId, $name);

        return db::select($queryText, $queryParameters);        

    } // noteOpenGraphDataByNameAndTagId


    static public function tagById($tagId) {

        if ($tagId > 0) {

            $queryText          = "SELECT `title`, `description` FROM ??_notes_tags WHERE id = ? LIMIT 1";
            $queryParameters    = array($tagId);

            $tag = db::select($queryText, $queryParameters);

            if ($tag) {
                parser::parse($tag['description']);
            }
            else $tag = array();

        }
        else $tag = array();

        return $tag;

    } // tagById


    static public function previousNote($noteDate, $tagId) {

        $note = self::noteNextDoor($noteDate, $tagId, '<', 'DESC');

        return $note ? $note : array();

    } // previousNoteName


    static public function nextNote($noteDate, $tagId) {

        $note = self::noteNextDoor($noteDate, $tagId, '>', 'ASC');

        return $note ? $note : array();

    } // nextNoteName


    static public function notesByPageNumber($pageNumber) {

        $queryText = "
        SELECT 
            *
        FROM 
        (
            SELECT
                notes.id, DATE_FORMAT(notes.date, '%Y/%m/%d %k:%i:%s') AS date, notes.name, notes.title, notes.content

            FROM
                ??_notes AS notes
            
            ORDER BY
                notes.date ASC
                
            LIMIT ?, ?
        )
        AS notes
        
        ORDER BY
            notes.date DESC";
        
        $notesToSkip        = ($pageNumber - 1) * self::$notesPerPage;
        $queryParameters    = array($notesToSkip, self::$notesPerPage);

        $notes = db::selectAll($queryText, $queryParameters);

        foreach ($notes as $index => $note) parser::parse($notes[$index]['content']);
                        
        return $notes;

    } // notesByPageNumber


    static public function notesByPageNumberAndTagId($pageNumber, $tagId) {

        $queryText = "
        SELECT 
            *
        FROM 
        (
            SELECT 
                notes.id, DATE_FORMAT(notes.date, '%Y/%m/%d %k:%i:%s') AS date, notes.name, notes.title, notes.content		
                
            FROM
                ??_notes AS notes
                
            INNER JOIN ??_notes_tags_bindings AS notes_tags_bindings
            ON
                notes_tags_bindings.note_id		= notes.id
                AND notes_tags_bindings.tag_id	= ?
                
            ORDER BY
                notes.date ASC
                
            LIMIT ?, ?
        )
        AS notes
        
        ORDER BY
            notes.date DESC";

        $notesToSkip        = ($pageNumber - 1) * self::$notesPerPage;
        $queryParameters    = array($tagId, $notesToSkip, self::$notesPerPage);
        
        $notes = db::selectAll($queryText, $queryParameters);

        foreach ($notes as $index => $note) parser::parse($notes[$index]['content']);

        return $notes;

    } // notesByPageNumberAndTagId    

    
    static public function pagesNumber($tagId)
    {
        if ($tagId == 0) {

            $notes = db::select(
            
                'SELECT 
                    COUNT(notes.id) AS notes_number 
                    
                FROM ??_notes AS notes'
                
            );

        }
        else {

            $notes = db::select(
            
                'SELECT 
                    COUNT(notes.id) AS notes_number
                    
                FROM ??_notes AS notes
                
                INNER JOIN ??_notes_tags_bindings notes_tags_bindings
                ON
                    notes_tags_bindings.note_id      = notes.id 
                    AND notes_tags_bindings.tag_id   = ?',
                    
                array($tagId)
                
            );

        }

        $pagesNumber = intdiv($notes['notes_number'], self::$notesPerPage);
        
        if ($notes['notes_number'] % self::$notesPerPage != 0) {        

            $pagesNumber++;
        }
    
        return $pagesNumber;

    } // pagesNumber


    ///////////////////////////////////////////////////////////////////////////////
    // ПРИВАТНЫЕ МЕТОДЫ


    static private function noteNextDoor($noteDate, $tagId, $comparisonSymbol, $sortingDirection)
    {
        if ($tagId == 0) {

            $queryText = "
            SELECT 
                notes.name AS name, notes.title AS title

            FROM
                ??_notes AS notes

            WHERE
                notes.date $comparisonSymbol ?

            ORDER BY
                notes.date $sortingDirection

            LIMIT 1";

            $queryParameters = array($noteDate);
            
        }
        else
        {

            $queryText = "
            SELECT 
                notes.name AS name, notes.title AS title
                            
            FROM
                ??_notes AS notes
                
            INNER JOIN ??_notes_tags_bindings AS notes_tags_bindings
            ON
                notes_tags_bindings.note_id     = notes.id
                AND notes_tags_bindings.tag_id  = ?
                        
            WHERE
                notes.date $comparisonSymbol ?
                
            ORDER BY
                notes.date $sortingDirection
                    
            LIMIT 1";
                
            $queryParameters = array($tagId, $noteDate);

        }

        return db::select($queryText, $queryParameters);

    } // noteNextDoor


} // notes