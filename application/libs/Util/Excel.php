<?php
/**
 * Excel出力機能
 * @author ou
 *
 */

require_once 'Classes/PHPExcel.php';
class Lib_Util_Excel
{
    const DOC_AUTHOR = 'NTT DOCOMO inc.';

    protected $_workBook;
    protected $_workSheet;
    protected $_sheetId;
    protected $_rowIdArr;

    /**
     * コンストラクタ
     *
     * @return  void
     */
    public function __construct($title = '', $subject = '', $description = '', $keywords = '', $category = '')
    {
        $this->_workBook = new PHPExcel();
        $this->_workBook->getProperties()->setCreator(self::DOC_AUTHOR)
            ->setLastModifiedBy(self::DOC_AUTHOR)
            ->setTitle($title)
            ->setSubject($subject)
            ->setDescription($description)
            ->setKeywords($keywords)
            ->setCategory($category);
    }

    /**
     * 
     * 列の英字名前を取得
     * @param     string  $num    列番号(0から)
     * @return    string
    */
    public static function getColumnName($num)
    {
        $numeric = $num % 26;
        $letter = chr(65 + $numeric);
        if($num >= 26) {
            $num2 = intval($num / 26);
            return self::getColumnName($num2 - 1) . $letter;
        }
        return $letter;
    }

    /**
     * シート設定
     *
     * @return  object
     */
    public function getWorkBook($sheetId)
    {
        return $this->_workBook;
    }

    /**
     * シート設定
     *
     * @param int  $sheetId シートID
     * @param string  $sheetTitle シートタイトル
     * @return  object
     */
    public function setActiveSheet($sheetId)
    {
        $this->_workSheet = $this->_workBook->setActiveSheetIndex($sheetId);
        $this->_sheetId = $sheetId;
        if(!isset($this->_rowIdArr[$sheetId])) {
            $this->_rowIdArr[$sheetId] = 1;
        }
        return $this;
    }

    /**
     * シート設定
     *
     * @return  object
     */
    public function getActiveSheet()
    {
        return $this->_workSheet->getActiveSheetIndex();
    }

    /**
     * シートタイトル設定
     *
     * @param string  $title タイトル
     * @return  object
     */
    public function setSheetTitle($title)
    {
        $this->_workSheet->setTitle($title);
        return $this;
    }

    /**
     * 行ID設定
     *
     * @param int  $rowId 行ID
     * @return  object
     */
    public function setRowId($rowId)
    {
        if($rowId < 1) {
            throw new Exception('row id must greater than 1');
        } else {
            $this->_rowIdArr[$this->_sheetId] = $rowId;
        }
        return $this;
    }

    /**
     * 次の行へ
     *
     * @return  object
     */
    public function nextRow()
    {
        $this->_rowIdArr[$this->_sheetId] += 1;
        return $this;
    }

    /**
     * 行データ設定
     *
     * @param array  $data シート
     * @return  object
     */
    public function setRowData($data = array())
    {
        foreach($data as $i => $value) {
            $cellId = self::getColumnName($i) . $this->_rowIdArr[$this->_sheetId];
            $this->_workSheet->setCellValue($cellId, $value);
        }
        return $this;
    }

    /**
     * xls形式のファイル保存
     *
     * @param string  $filePath ファイルパス
     * @return  object
     */
    public function saveXls($filePath)
    {
        $writer = PHPExcel_IOFactory::createWriter($this->_workBook, 'Excel5');
        $writer->save($filePath);
        return $this;
    }

    /**
     * xlsx形式のファイル保存
     *
     * @param string  $filePath ファイルパス
     * @return  object
     */
    public function saveXlsx($filePath)
    {
        $writer = PHPExcel_IOFactory::createWriter($this->_workBook, 'Excel2007');
        $writer->save($filePath);
        return $this;
    }
}