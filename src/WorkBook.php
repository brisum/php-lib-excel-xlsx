<?php

namespace Brisum\Lib\Excel\Xlsx;

use XMLReader;

class WorkBook
{
    // scheme
    const SCHEMA_OFFICEDOCUMENT  =  'http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument';
    const TAG_RELATIONSHIP = 'Relationship';
    const TAG_SHEET = 'sheet';

    protected $document;
    protected $sheets;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    protected function getSourceWorkBook()
    {
        $xmlReader = new XMLReader();
        $sourceWorkbook = null;

        $xmlReader->open($this->document->getSourcePath('_rels/.rels'));
        while ($xmlReader->read() && self::TAG_RELATIONSHIP != $xmlReader->localName);
        while (self::TAG_RELATIONSHIP == $xmlReader->localName)
        {
            if (self::SCHEMA_OFFICEDOCUMENT == $xmlReader->getAttribute('Type')) {
                $sourceWorkbook = $xmlReader->getAttribute('Target');
                break;
            }
            $xmlReader->next(self::TAG_RELATIONSHIP);
        }

        return $sourceWorkbook;
    }

    /**
     * Get sheets list.
     *
     * @return array
     */
    public function getSheetList() {
        if ($this->sheets) {
            return $this->sheets;
        }

        $xmlReader = new XMLReader();
        $sourceWorkBook = $this->getSourceWorkBook();

        $xmlReader->open($this->document->getSourcePath($sourceWorkBook));
        while ($xmlReader->read() && self::TAG_SHEET != $xmlReader->localName);
        while (self::TAG_SHEET == $xmlReader->localName)
        {
            $this->sheets[$xmlReader->getAttribute('sheetId')] = $xmlReader->getAttribute('name');
            $xmlReader->next(self::TAG_SHEET);
        }

        ksort($this->sheets);
        return $this->sheets;
    }

    /**
     * @param int $id
     * @return Sheet
     */
    public function getSheetById($id)
    {
        return new Sheet($this->document, $id);
    }
}
