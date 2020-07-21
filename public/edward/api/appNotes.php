<?

class appNotes {


    ///////////////////////////////////////////////////////////////////////////////
    // ПРИВАТНЫЕ ПЕРЕМЕННЫЕ


    private
    
        $notesPerPage = 10;


    ///////////////////////////////////////////////////////////////////////////////
    // ПУБЛИЧНЫЕ МЕТОДЫ


    public function data() {

        // Определимся, что будем выводить.

        $entity = app::var('entity');

        switch ($entity) {

            case 'tags':
                $data = $this->tags();
                break;

            case 'note':
                $data = $this->note();
                break;

            default:
                $data = $this->notes();

        }

        return $data;

    } // data


    ///////////////////////////////////////////////////////////////////////////////
    // ПРИВАТНЫЕ МЕТОДЫ


    private function tags() {

        $tagsUsage = notes::tagsUsage();

        return array(
            'tags' => $tagsUsage
        );
        
    } // tags

    private function note() {

        // Определим имя заметки, которую нужно получить.

        $noteName = app::var('note');

        // Определим ID тэга, по которому будем отбирать заметку
        // и соседние заметки (если тэг, конечно, вообще указан).

        $tagId = $this->tagIdByQueryString();

        // Извлекаем заметку, добавляем её тэги и удаляем
        // из результата запроса идентификатор заметки.

        if ($tagId != 0) {
            $note = notes::noteByNameAndTagId($noteName, $tagId);
        }
        else {
            $note = notes::noteByName($noteName);
        }

        if (! $note) throw new Exception('Note Not Found', 404);
        
        $notes = array($note);

        $this->addTags($notes);
        $this->removeNoteIds($notes);

        // Извлекаем данные предыдущей и следующей заметки.

        $previousNote   = notes::previousNote($note['date'], $tagId);
        $nextNote       = notes::nextNote($note['date'], $tagId);

        // Возвращаем результат.

        return array(

            'note' => $notes[0],

            'previousNote'  => $previousNote,
            'nextNote'      => $nextNote,            

        );

    } // note

    private function notes() {
        
        // Определим ID тэга, по которому будем отбирать заметки (если он есть).

        $tagId = $this->tagIdByQueryString();

        // Определим номер страницы, в пределах которой будем отбирать заметки.

        $pagesNumber    = notes::pagesNumber($tagId);
        $pageNumber     = (int)app::var('page');

        if ($pageNumber == 0) {
            $pageNumber = $pagesNumber;
        }

        if ($pageNumber < 0 or $pageNumber > $pagesNumber) {
            throw new Exception('Page Not Found', 404);
        }

        // Определяем номера предыдущей страницы и следующей страницы.

        $previousPageNumber = $pageNumber > 1               ? $pageNumber - 1   : 0;
        $nextPageNumber     = $pageNumber < $pagesNumber    ? $pageNumber + 1   : 0;

        // Отберем заметки в зависимости от того, есть ли тэг.

        if ($tagId == 0) {
            $notes = notes::notesByPageNumber($pageNumber);
        }
        else {
            $notes = notes::notesByPageNumberAndTagId($pageNumber, $tagId);
        }

        $tag = notes::tagById($tagId);
        
        $this->addTags($notes);
        $this->removeNoteIds($notes);

        return array(

            'tag'   => $tag,
            'notes' => $notes,
            
            'previousPageNumber'    => $previousPageNumber,
            'nextPageNumber'        => $nextPageNumber,

        );

    } // notes

    private function addTags(& $notes)
    {
        // Получаем ID заметок и тэги для них.
        
        $noteIds = array();
        
        foreach($notes as $note) {
            $noteIds[] = $note['id'];
        }

        $tagBindings = notes::tagsByNoteIds($noteIds);

        // Проходим по заметкам и к каждой добавляем тэги,
        // полученные из базы данных на предыдущем этапе.

        foreach($notes as $index => $note) {
            
            $notes[$index]['tags'] = array();

            foreach($tagBindings as $tagBinding) {

                if ($tagBinding['note_id'] == $note['id']) {

                    $tag = array(
                        'name'  => $tagBinding['tag_name'],
                        'title' => $tagBinding['tag_title'],
                    );

                    $notes[$index]['tags'][] = $tag;
                }
            }
        }

    }  // addTags

    private function removeNoteIds(& $notes) {

        foreach($notes as $index => $note) {

            unset($notes[$index]['id']);
        }

    } // removeNoteIds

    private function tagIdByQueryString() {

        $tagName = app::var('tag');

        if ($tagName != '') {

            $tagId = notes::tagIdByName($tagName);
    
            if ($tagId == 0) {
                throw new Exception('Tag Not Found', 404);
            }
        }
        else {

            $tagId = 0;
        }

        return $tagId;

    } // tagIdByQueryString


} // appNotes