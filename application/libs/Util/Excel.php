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
    protected $_currentRowId = array();
    protected $_columnName = array();
    /**
     * コンストラクタ
     *
     * @return  void
     */
    public function __construct($title = '', $subject = '', $description = '', $keywords = '', $category = '')
    {
        //PHPExcel_Settings::setCacheStorageMethod(PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp, array('memoryCacheSize' => '8MB'));
        //PHPExcel_Settings::setCacheStorageMethod(PHPExcel_CachedObjectStorageFactory::cache_in_memory_gzip);
        PHPExcel_Settings::setCacheStorageMethod(PHPExcel_CachedObjectStorageFactory::cache_in_memory_serialized);
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
     * シート作成（新規）
     *
     * @return  object
     */
    public function createSheet()
    {
        $this->_workBook->createSheet();
        return $this;
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
        if(!isset($this->_currentRowId[$sheetId])) {
            $this->_currentRowId[$sheetId] = 1;
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
        return $this->_workSheet;
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
            $this->_currentRowId[$this->_sheetId] = $rowId;
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
        $this->_currentRowId[$this->_sheetId] += 1;
        return $this;
    }

    /**
     * 現在の行ID設定
     *
     * @return  int
     */
    public function getCurrentRowId()
    {
        return $this->_currentRowId[$this->_sheetId];
    }

    /**
     * 行データ設定
     *
     * @param array  $data シート
     * @param array  $style スタイル
     * @return  object
     */
    public function setRowData($data = array(), $style = array())
    {
        if(!is_array($data)) {
            $data = array($data);
        }
        $rowId = $this->_currentRowId[$this->_sheetId];
        foreach($data as $i => $value) {
            if(!isset($this->_columnName[$i])) {
                //重複計算を避けるため
                $this->_columnName[$i] = self::getColumnName($i);
            }
            $cellId = $this->_columnName[$i] . $rowId;
            if(is_numeric($value) && $value != '0' && $value{0} == '0') {
                //$this->_workSheet->getStyle($cellId)->getNumberFormat()->setFormatCode('@');
                $this->_workSheet->setCellValueExplicit($cellId, $value, PHPExcel_Cell_DataType::TYPE_STRING);
            } else {
                $this->_workSheet->setCellValue($cellId, $value);
            }
            if(!empty($style)) {
                $this->_workSheet->getStyle($cellId)->applyFromArray($style);
            }
        }
        return $this;
    }

    /**
     * 現在行の指定カラムをマージする
     *
     * @param int  $startId 開始ID
     * @param int  $endId 終了ID
     * @return  object
     */
    public function mergeColumns($startId, $endId)
    {
        $rowId = $this->_currentRowId[$this->_sheetId];
        $range = self::getColumnName($startId). $rowId. ':' . self::getColumnName($endId). $rowId;
        $this->_workSheet->mergeCells($range);
        return $this;
    }

    /**
     * 現在行の指定カラムのスタイルを指定する
     *
     * @param int  $startId 開始ID
     * @param int  $endId 終了ID
     * @param array  $style スタイル
     * @return  object
     */
    public function setColumnsStyle($startId, $endId, $style)
    {
        $rowId = $this->_currentRowId[$this->_sheetId];
        $range = self::getColumnName($startId). $rowId. ':' . self::getColumnName($endId). $rowId;
        $this->_workSheet->getStyle($range)->applyFromArray($style);
        return $this;
    }

    /**
     * カラムの横幅指定
     *
     * @param int  $colWidths 開始ID
     * @return  object
     */
    public function setColumnWidths($colWidths = array())
    {
        foreach($colWidths as $colName => $width) {
            $this->_workSheet->getColumnDimension($colName)->setWidth($width);
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

    /**
     * メモリ解放
     *
     * @return  void
     */
    public function disconnect()
    {
        $this->_workBook->disconnectWorksheets();  
        unset($this->_workBook); 
    }
}