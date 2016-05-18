<?php

namespace Brisum\Lib\Excel\Xlsx;

use SimpleXMLElement;
use XMLReader;

class Sheet
{
    const SHEET  = 'xl/worksheets/sheet%d.xml';
    const SHARED_STRING = 'xl/sharedStrings.xml';
    const TAG_ROW = 'row';
    const TAG_SI = 'si';

    protected $document;
    protected $sheet;
    protected $sharedStrings;

    public function __construct(Document $document, $sheetId)
    {
        $this->document = $document;
        $this->sheet = new XMLReader();
        $this->sharedStrings = new XMLReader();

        $this->sheet->open($this->document->getSourcePath(sprintf(self::SHEET, $sheetId)));
        $this->sharedStrings->open($this->document->getSourcePath(self::SHARED_STRING));
    }

    public function getRow($number)
    {

    }

    /**
     * @param int $from
     * @param int $amount
     * @return array
     */
    public function getRows($from = 1, $amount = 100000)
    {
        $rowNumber = 1;
        $columnNames = [];
        $xmlRows = [];
        $searchStrings = [];
        $strings = [];
        $rows = [];

        while ($this->sheet->read() && self::TAG_ROW != $this->sheet->localName);
        while ($rowNumber < $from){
            $this->sheet->next(self::TAG_ROW);
            $rowNumber += 1;
        }
        while ($amount && self::TAG_ROW == $this->sheet->localName)
        {
            $xmlRow = new SimpleXMLElement($this->sheet->readOuterXML());
            $xmlRows[$rowNumber] = [];
            $column = 0;
            while(isset($xmlRow->c[$column])) {
                $xmlCell = $xmlRow->c[$column];
                $v = (string)$xmlCell->v;
                $name = rtrim((string)$xmlCell['r'], '1234567890'); // trim row number from cell name

                $xmlRows[$rowNumber][$name] = $xmlCell;
                $columnNames[$name] = $name;

                // If it has a "t" (type?) of "s" (string?), use the value to look up string value
                if (isset($xmlCell['t']) && $xmlCell['t'] == 's') {
                    $searchStrings[$v] = true;
                }

                $column += 1;
                unset($xmlCell);
            }
            unset($xmlRow);

            $this->sheet->next(self::TAG_ROW);
            $amount -= 1;
            $rowNumber += 1;
        }

        ksort($searchStrings);
        reset($searchStrings);
        $firstKey = key($searchStrings);
        end($searchStrings);
        $lastKey = key($searchStrings);
        $count = 0;
        while ($this->sharedStrings->read() && self::TAG_SI != $this->sharedStrings->localName);
        while ($count < $firstKey){
            $this->sharedStrings->next(self::TAG_SI);
            $count += 1;
        }
        while ($count <= $lastKey && self::TAG_SI == $this->sharedStrings->localName)
        {
            if (isset($searchStrings[$count])) {
                $elem = new SimpleXMLElement($this->sharedStrings->readOuterXML());
                $strings[$count] = (string)$elem->t;

                unset($elem);
            }

            $this->sharedStrings->next(self::TAG_SI);
            $count += 1;
        }

        foreach ($xmlRows as $rowKey => $xmlRow) {
            foreach ($columnNames as $columnName) {
                if (isset($xmlRow[$columnName])) {
                    $xmlCell = $xmlRow[$columnName];
                    $val = '';
                    $v = (string)$xmlCell->v;

                    // If it has a "t" (type?) of "s" (string?), use the value to look up string value
                    if (isset($xmlCell['t']) && $xmlCell['t'] == 's') {
                        $val = $strings[$v];
                    } elseif (isset($xmlCell['t']) && $xmlCell['t'] == 'str') {
                        $val = (string)$v;
                    } elseif (!isset($xmlCell['t'])) {
                        $val = (string)$v;
                    }

                    // elseif ( !$v ) {
                    //     $r = (string) $xmlCell['r'];
                    //     if ( isset($images[$r]) ) {
                    //         $v = $images[$r];
                    //     }
                    // }
                } else {
                    $val = '';
                }

                $rows[$rowKey][$columnName] = trim($val);
            }

            uksort($rows[$rowKey], [&$this, 'sortRow']);
        }

        return $rows;
    }

    protected function sortRow($a, $b)
    {
        $lenA = strlen($a);
        $lenB = strlen($b);

        if ($lenA < $lenB) {
            return -1;
        }
        if ($lenA > $lenB) {
            return 1;
        }
        return strcmp($a, $b);
    }
//
//    public static function parseImages($dir, $url)
//    {
//        $images = array();
//
//        // parse image rId => imageTarget
//        $drawings_rel = simplexml_load_file($dir . '/xl/drawings/_rels/drawing1.xml.rels');
//        foreach ($drawings_rel->Relationship as $imgRel) {
//            $rId = (string)$imgRel['Id'];
//            $imgTarget = (string)$imgRel['Target'];
//
//            $images[$rId] = str_replace('..', $url, $imgTarget);
//        }
//        unset($drawings_rel);
//
//
//        // parse cell => rId, and replace rId to cell;
//        $drawings = simplexml_load_file($dir . '/xl/drawings/drawing1.xml', 'SimpleXMLElement', 0,
//            'http://schemas.openxmlformats.org/drawingml/2006/spreadsheetDrawing');
//        $drawings->registerXPathNamespace('a', 'http://schemas.openxmlformats.org/drawingml/2006/main');
//        foreach ($drawings->twoCellAnchor as $imgInfo) {
//            $cell = 'G' . (1 + ((string)$imgInfo->from->row));
//
//            $blipFill = $imgInfo->pic->blipFill;
//            $blipFill = $blipFill->children('http://schemas.openxmlformats.org/drawingml/2006/main');
//
//            $blip = $blipFill->blip;
//            $blip->registerXPathNamespace('r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
//
//            $rId = $blip->xpath('@r:embed');
//            $rId = (string)$rId[0]['embed'];
//
//            if (isset($images[$rId])) {
//                $images[$cell] = $images[$rId];
//                unset($images[$rId]);
//            }
//        }
//        unset($drawings);
//
//        return $images;
//    }


}