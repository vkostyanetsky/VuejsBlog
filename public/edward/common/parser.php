<?

class parser {


    static public function parse(& $content) {

        // Построчная обработка.

        $delimiter  = "\r\n";
        $lines      = explode($delimiter, $content);        

        self::parseParagraphs($lines);
        self::parseHeaders($lines);
        self::parseQuotes($lines);
        self::parseCode($lines);
        self::parseOrderedList($lines);
        self::parseUnorderedList($lines);

        $content = implode($delimiter, $lines);

        // Общая обработка регулярными выражениями.

        self::parseSeparateLineLinksToYoutube($content);        
        
        self::parseImagesWithLinks($content);
        self::parseImages($content);
        self::parseLinks($content);

        self::parseBoldTexts($content);
        self::parseItalicTexts($content);
        self::parseStrikeThroughTexts($content);

    } // parse


    ///////////////////////////////////////////////////////////////////////////////
    // КОНКРЕТНЫЕ КЕЙСЫ


    static private function parseParagraphs(& $lines) {

        $isCode = false;

        foreach ($lines as $index => $line) {
            
            if (self::isParagraph($line, $isCode)) $line = "<p>$line</p>";
            
            $lines[$index] = $line;
        } 

    } // parseParagraphs

    
    static private function parseHeaders(& $lines) {

        // # Одежда

        foreach ($lines as $index => $line) {

            $isHeader = self::isHeader($line);

            if ($isHeader) {
                
                $line = self::deleteHeaderMarker($line);
                $line = '<h1 class="f3 lh-copy">' . $line . '</h1>';

                $lines[$index] = $line;
            }
        }

    } // parseHeaders


    static private function parseQuotes(& $lines) {

        $searchMarker   = 'isQuote';
        $deleteMarker   = 'deleteQuoteMarker';

        $htmlBegin      = '<div class="pl4 pb2 pt4"><blockquote class="ml0 mt0 pl4 bl bw2 b--blue">';
        $htmlEnd        = '</blockquote></div>';
        
        $htmlItemBegin  = '<p class="f5 f4-m f4-l lh-copy measure mt0">';
        $htmlItemEnd    = '</p>';

        self::parseMarkedSequences($lines, $searchMarker, $deleteMarker, $htmlBegin, $htmlEnd, $htmlItemBegin, $htmlItemEnd);

    } // parseQuotes


    static private function parseCode(& $lines) {
        
        // `hello world`
        
        $isCode = false;

        foreach ($lines as $index => $line) {

            if (self::isCodeBegin($line))   $line = '<pre><code>' . mb_substr($line, 1);
            if (self::isCodeEnd($line))     $line = mb_substr($line, 0, mb_strlen($line) - 1) . '</code></pre>';
            
            $lines[$index] = $line;
        }

    } // parseCode        


    static private function parseOrderedList(& $lines) {

        $searchMarker   = 'isOrderedList';
        $deleteMarker   = 'deleteOrderedListMarker';

        $htmlBegin      = '<ol>';
        $htmlEnd        = '</ol>';
        
        $htmlItemBegin  = '<li>';
        $htmlItemEnd    = '</li>';

        self::parseMarkedSequences($lines, $searchMarker, $deleteMarker, $htmlBegin, $htmlEnd, $htmlItemBegin, $htmlItemEnd);

    } // parseOrderedList


    static private function parseUnorderedList(& $lines) {

        $searchMarker   = 'isUnorderedList';
        $deleteMarker   = 'deleteUnorderedListMarker';

        $htmlBegin      = '<ul>';
        $htmlEnd        = '</ul>';
        
        $htmlItemBegin  = '<li>';
        $htmlItemEnd    = '</li>';

        self::parseMarkedSequences($lines, $searchMarker, $deleteMarker, $htmlBegin, $htmlEnd, $htmlItemBegin, $htmlItemEnd);

    } // parseUnorderedList    


    static private function parseSeparateLineLinksToYoutube(& $content) {

        $replacement = '<iframe width="560" height="315" src="https://www.youtube.com/embed/$1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

        // https://youtu.be/bGvEi_ShiRo

        $pattern = '/https:\/\/youtu.be\/(.{11})(\r\n|$)/iuU';
        $content = preg_replace($pattern, $replacement, $content);

        // https://www.youtube.com/watch?v=r6RUXzb3NxY
        
        $pattern = '/https:\/\/www.youtube.com\/watch\?v=(.{11})(\r\n|$)/iuU';
        $content = preg_replace($pattern, $replacement, $content);

    } // parseSeparateLineLinksToYoutube


    static private function parseImagesWithLinks(& $content) {
        
        // ![Альтернативный текст](/путь/к/изображению.jpg)(/путь/куда/то)

        $content = preg_replace(
            '/!\[(.+)\]\((.+)\)\((.+)\)/iuU',
            '<a href="$3"><img src="$2" alt="$1" title="$1"></a>',
            $content
        );

    } // parseImagesWithLinks    


    static private function parseImages(& $content) {
        
        // ![Альтернативный текст](/путь/к/изображению.jpg)

        $content = preg_replace(
            '/\!\[(.+)\]\((.+)\)/iuU',
            '<img src="$2" alt="$1" title="$1">',
            $content
        );

    } // parseImages    


    static private function parseLinks(& $content) {
        
        // [компактный вариант](https://yandex.ru/alice/station-mini)
  
        $content = preg_replace(
            '/\[(.+)\]\((.+)\)/iuU',
            '<a href="$2" class="link blue dim bb">$1</a>',
            $content
        );

    } // parseLinks


    static private function parseBoldTexts(& $content) {

        // **мне**

        $content = preg_replace(
            '/\*\*(.+)\*\*/iuU',
            '<strong>$1</strong>',
            $content
        );

    } // parseBoldTexts


    static private function parseItalicTexts(& $content) {

        // *мне*
        
        $content = preg_replace(
            '/\*(.+)\*/iuU',
            '<em>$1</em>',
            $content
        );

    } // parseItalicTexts


    static private function parseStrikeThroughTexts(& $content) {

        // ~~мне~~
        
        $content = preg_replace(
            '/~~(.+)~~/iuU',
            '<s>$1</s>',
            $content
        );        

    } // parseStrikeThroughTexts    


    static private function parseMarkedSequences(& $lines, $searchMarker, $deleteMarker, $htmlBegin, $htmlEnd, $htmlItemBegin, $htmlItemEnd) {
        
        $isOpen = false;
        
        foreach ($lines as $index => $line) {

            $isItem = call_user_func(array('parser', $searchMarker), $line);

            if (! $isItem) continue;
            
            $line = call_user_func(array('parser', $deleteMarker), $line);
            $line = $htmlItemBegin . $line . $htmlItemEnd;
            
            if (! $isOpen ) {
                $line   = $htmlBegin . $line;
                $isOpen = true;
            }
            
            if (isset($lines[$index + 1])) {
                $isCloses = ! call_user_func(array('parser', $searchMarker), $lines[$index + 1]);
            }
            else $isCloses = true;

            if ($isCloses) {
                $line       = $line . $htmlEnd;
                $isOpen     = false;
                $isCloses   = false;
            }

            $lines[$index] = $line;            
        }
        
    } // parseMarkedSequences    


    static private function isParagraph($line, & $isCode) {

        if (mb_strlen($line) == 0) return false;

        if (self::isCodeBegin($line)) $isCode = true;
            
        $isHeader           = self::isHeader($line);
        $isTag              = self::isTag($line);
        $isQuote            = self::isQuote($line);
        $isOrderedList      = self::isOrderedList($line);
        $isUnorderedList    = self::isUnorderedList($line);
        $isLink             = self::isLink($line);

        $isParagraph =
            ! $isHeader 
            && ! $isTag
            && ! $isQuote
            && ! $isOrderedList
            && ! $isUnorderedList
            && ! $isLink
            && ! $isCode;

        if (self::isCodeEnd($line)) $isCode = false;

        return $isParagraph;

    } // isParagraph


    static private function isHeader($line) {
        return mb_substr($line, 0, 1) == '#';
    }


    static private function deleteHeaderMarker($line) {
        return trim(mb_substr($line, 1));
    }    


    static private function isTag($line) {
        return mb_substr($line, 0, 1) == '<';
    }


    static private function isCodeBegin($line) {
        return mb_substr($line, 0, 1) == '`';
    }


    static private function isCodeEnd($line) {
        return mb_substr($line, -1, 1) == '`';
    }    


    static private function isLink($line) {
        return mb_substr($line, 0, 4) == 'http';
    }


    static private function isQuote($line) {
        return mb_substr($line, 0, 1) == '>';
    }
    

    static private function isOrderedList($line) {
        return preg_match('/^\d+\./iuU', $line);
    }


    static private function isUnorderedList($line) {
        return mb_substr($line, 0, 1) == '-';
    }


    static private function deleteQuoteMarker($line) {
        return trim(mb_substr($line, 1));
    }


    static private function deleteOrderedListMarker($line) {
        return preg_replace('/^\d+\.(.*)$/iuU', '$1', $line);
    }


    static private function deleteUnorderedListMarker($line) {
        return trim(mb_substr($line, 1));
    }


}; // parser