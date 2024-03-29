<?php

/**
 * export a csv file (string) from given CSqlDataProvider
 * usage in your controller:
 * Yii::import('ext.CSVExport');
 * $provider = YourClass::model()->createCSqlProvider()
 * or
 * $provider = Yii::app()->db->creatCommand(...)->queryAll();
 * $csv = new ECSVExport($provider);
 * $content = $csv->toCSV();					
 * Yii::app()->getRequest()->sendFile($filename, $content, "text/csv", false);
 * exit();
 * 
 * You can also do this now:
 * $cmd = Yii::app()->db->createCommand("SELECT * FROM track_test LIMIT 10");
 * $csv = new ECSVExport($cmd);        
 * $csv->setOutputFile($outputFile);
 * $csv->toCSV();
 *
 * @author Kenrick Buchanan
 * @version 0.6
 */
class ESCVExport {

    /**
     * show column headers in csv file
     * @var boolean $includeColumnHeaders
     */
    public $includeColumnHeaders = true;

    /**
     * sometimes excel chokes on newlines in string, this will fix that
     * @var boolean $replaceNewLines strip newlines from each column
     */
    public $stripNewLines = true;

    /**
     * run through whole resultset, appending to output stream, using paging (if on)
     * @var boolean $exportFull
     */
    public $exportFull = true;

    /**
     * convert activedata provider to a cdbcommand for speed
     * @var boolean $convertActiveDataProvider
     */
    public $convertActiveDataProvider = true;

    /**
     *
     * string filename
     * @var string $_outputFile 
     */
    protected $_outputFile;

    /**
     * stream pointer
     * @var resource $_filePointer
     */
    protected $_filePointer;

    /**
     * data provider that will generate or contain resultset
     * @var mixed CSqlDataProvider|Array|CActiveDataProvider|CDbCommand $_dataProvider
     */
    protected $_dataProvider;

    /**
     * per row callable function
     * @var callable $_callback

     */
    protected $_callback;

    /**
     * csv headers
     * @var array $_headers 
     */
    protected $_headers = array();

    /**
     *
     * columns to exclude from final file
     * @var array $_exclude 
     */
    protected $_exclude = array();

    /**
     * column delimiter
     * @var string $_delimiter 
     */
    protected $_delimiter = ",";

    /**
     * string to enclose fields when delimiter is found in field
     * @var string $_enclosure 
     */
    protected $_enclosure = '"';

    /**
     * determine if overwriting output file or just append to existing
     * $this->setToAppend()
     * @var boolean
     */
    protected $_appendCsv = false;

    /**
     *
     * @param mixed $dataProvider array|CSqlDataProvider|CActiveDataProvider|CDbCommand
     * @param boolean $exportFull
     * @param boolean $includeColumnHeaders
     * @param string $delimiter
     * @param string $enclosure 

     */
    public function __construct($dataProvider, $exportFull = true, $includeColumnHeaders = true, $delimiter = null, $enclosure = null) {

        $this->_dataProvider = $dataProvider;
        $this->exportFull = (bool) $exportFull;
        $this->includeColumnHeaders = (bool) $includeColumnHeaders;
        if ($delimiter)
            $this->_delimiter = $delimiter;
        if ($enclosure)
            $this->_enclosure = $enclosure;
    }

    /**
     * get data provider
     * @return mixed $this->_dataProvider
     */
    public function getDataProvider() {

        return $this->_dataProvider;
    }

    /**
     * call this function to not force CActiveDataProvider to be converted to command
     * for speed and memory at the expense of losing the with() functionality
     *
     * @return \ECSVExport 
     */
    public function dontConvertProvider() {

        $this->convertActiveDataProvider = false;
        return $this;
    }

    /**
     * sets flag to have it append to file instead of just overwriting it
     * @return void 
     */
    public function setToAppend() {

        $this->_appendCsv = true;
        return $this;
    }

    /**
     *
     * set csv delimiter, defaults to ,
     * @param type $delimiter
     * @return \ECSVExport 
     */
    public function setDelimiter($delimiter) {

        $this->_delimiter = $delimiter;
        return $this;
    }

    /**
     * get current delimiter
     * @return type 
     */
    public function getDelimiter() {

        return $this->_delimiter;
    }

    /**
     *
     * set csv enclosure, defaults to "
     * @param type $enclosure
     * @return \ECSVExport 
     */
    public function setEnclosure($enclosure) {

        $this->_enclosure = $enclosure;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getEnclosure() {

        return $this->_enclosure;
    }

    /**
     *
     * set filename of csv file you want to create
     * @param type $filename
     * @return \ECSVExport 
     */
    public function setOutputFile($filename) {

        $this->_outputFile = $filename;
        return $this;
    }

    /**
     * get output file
     * @return string
     */
    public function getOutputFile() {

        return $this->_outputFile;
    }

    /**
     * function to be called for each row in set
     * @param mixed callable|array $callback
     * @return \ECSVExport
     * @throws Exception on uncallable variable
     */
    public function setCallback($callback) {

        if (is_callable($callback)) {
            $this->_callback = $callback;
            return $this;
        } else {
            throw new Exception('Callback must be callable. Duh.');
        }
    }

    /**
     * get per row function    
     * @return mixed $this->_callback
     */
    public function getCallback() {

        return $this->_callback;
    }

    /**
     * existing column names remapped to other strings resultcolumn=>new name
     * @param array $headers
     * @return \ECSVExport 
     */
    public function setHeaders(array $headers) {

        $this->_headers = $headers;
        return $this;
    }

    /**
     * get current csv headers
     * @return array $this->_headers
     */
    public function getHeaders() {

        return $this->_headers;
    }

    /**
     *
     * @param string $key
     * @param string $value
     * @return \ECSVExport 
     */
    public function setHeader($key, $value) {

        $this->_headers[$key] = $value;
        return $this;
    }

    /**
     *
     * @param type $noshow
     * @return \ECSVExport 
     */
    public function setExclude($noshow) {

        if (is_array($noshow)) {
            $this->_exclude = $noshow;
            return $this;
        } else {
            $this->_exclude[] = (string) $noshow;
        }
    }

    /**
     * get excluded fields
     * @return array $this->_exclude
     */
    public function getExclude() {

        return $this->_exclude;
    }

    /**
     * turn off going through whole resultset, taking current page into account
     * @return \ECSVExport 
     */
    public function exportCurrentPageOnly() {

        $this->exportFull = false;
        return $this;
    }

    /**
     * create a csv string, or file if $outputFile is set
     * 
     * @param string $outputFile
     * @param string $delimiter
     * @param string $enclosure
     * @param boolean $includeHeaders
     * @return mixed string|boolean|integer csv string when no outputFile is specified
     * boolean if the writing failed, or integer of num bytes written to file 
     */
    public function toCSV($outputFile = null, $delimiter = null, $enclosure = null, $includeHeaders = true) {

        // check that data provider is something useful
        $isGood = false;


        if ($this->_dataProvider instanceof CActiveDataProvider) {
            $isGood = true;
        }


        if ($this->_dataProvider instanceof CSqlDataProvider) {
            $isGood = true;
        }


        if ($this->_dataProvider instanceof CDbCommand) {
            $isGood = true;
        }


        if (is_array($this->_dataProvider)) {
            $isGood = true;
        }


        if (!$isGood) {
            throw new Exception('Bad data provider given as source to ' . __CLASS__);
        }


        if ($outputFile !== null) {
            $this->setOutputFile($outputFile);
        }


        if (!$includeHeaders) {
            $this->includeColumnHeaders = false;
        }


        if ($delimiter !== null) {
            $this->_delimiter = $delimiter;
        }


        if ($enclosure !== null) {
            $this->_enclosure = $enclosure;
        }


        // create file pointer
        $this->_filePointer = fopen("php://temp", 'w');
        $this->_writeData();
        rewind($this->_filePointer);


        // make sure you can write to file!
        if ($this->_outputFile !== null) {
            // write stream to file



            return $this->_appendCsv ? file_put_contents($this->_outputFile, $this->_filePointer, FILE_APPEND | LOCK_EX) : file_put_contents($this->_outputFile, $this->_filePointer, LOCK_EX);
        } else {
            return stream_get_contents($this->_filePointer);
        }
    }

    /**
     * where the magic happens. depending on type of dataProvider, it uses
     * different methods to get the data efficiently and to write to file
     * pointer in memory. the most effecient is either a small array or
     * by just passing in a CDbCommand instance 
     * @throws Exception - no data found
     */
    protected function _writeData() {


        $firstTimeThrough = true;
        if ($this->_dataProvider instanceof CActiveDataProvider) {
            if ($this->convertActiveDataProvider) {
                $criteria = $this->_dataProvider->getCriteria();
                $model = $this->_dataProvider->model;
                $criteria = $model->getCommandBuilder()
                        ->createCriteria($criteria, array());
                $this->_dataProvider = $model->getCommandBuilder()
                        ->createFindCommand($model->getTableSchema(), $criteria);

                unset($model, $criteria);
            } else {
                // suggested implementation from marcovtwout	
                $models = $this->_dataProvider->getData();
                $dataReader = array();
                $attributes = $this->_dataProvider->model->getMetaData()->columns;
                // since we are already looping through results, don't bother
                // passing results to _loopRow, just write it here.
                foreach ($models as &$model) {
                    $row = array();


                    foreach ($attributes as $attribute => $col) {
                        $row[$attribute] = $model->{$attribute};
                    }


                    if ($firstTimeThrough) {
                        $this->_writeHeaders($row);
                        $firstTimeThrough = false;
                    }
                    $this->_writeRow($row);
                }
                unset($models, $attributes);
                return;
            }
        }


        if ($this->_dataProvider instanceof CSqlDataProvider) {
            if ($this->exportFull) {
                $this->_dataProvider->setId('csvexport');
                $this->_dataProvider->getPagination()->setItemCount($this->_dataProvider->getTotalItemCount());
                $pageVar = $this->_dataProvider->getPagination()->pageVar;

                $_GET[$pageVar] = 0;
                $totalPages = $this->_dataProvider->getPagination()->getPageCount();
                $this->setToAppend();
                for ($i = 0; $i <= $totalPages; $i++) {
                    $_GET[$pageVar] = $i;
                    $this->_dataProvider->getPagination()->setCurrentPage($i);
                    $this->_loopRows($this->_dataProvider->getData());
                    $this->includeColumnHeaders = !(bool) $i;
                }
            } else {
                $this->_loopRows($this->_dataProvider->getData());
            }


            return;
        }


        if ($this->_dataProvider instanceof CDbCommand) {
            $dataReader = $this->_dataProvider->query();
            $this->_loopRows($dataReader);
            return;
        }


        if (is_array($this->_dataProvider)) {
            $this->_loopRows($this->_dataProvider);
            return;
        }

        // if program made it this far something happened
        throw new Exception('Data source failed to retrieve data, are you sure you passed something useable?');
    }

    /**
     * loop through result set
     * @param mixed CDbDataReader|array $dp 
     */
    public function _loopRows(&$dp) {

        $firstTimeThrough = true;
        if ($dp instanceof CDbDataReader) {
            while (($row = $dp->read()) !== false) {
                if ($firstTimeThrough) {
                    $this->_writeHeaders($row);
                    $firstTimeThrough = false;
                }
                $this->_writeRow($row);
            }
        } else {
            $total = count($dp);
            for ($i = 0; $i < $total; $i++) {
                if ($firstTimeThrough) {
                    $this->_writeHeaders($dp[$i]);
                    $firstTimeThrough = false;
                }
                $this->_writeRow($dp[$i]);
            }
        }
    }

    /**
     * Write headers to csv file, taking into account string replacements and exclusions     
     * @param array $row
     * @return void 
     */
    protected function _writeHeaders($row) {
        if (!$this->includeColumnHeaders) {
            return;
        }

        // loop through the relations
        foreach ($row->relations() as $key => $related) {

            if ($row->hasRelated($key)) {
                if (is_object($row->$key)) {

                    foreach ($row->$key->getAttributes() as $attr_key => $attr_val) {
                        $related_attributes[$key . '_' . $attr_key] = $attr_val;
                    }
                } elseif (is_array($row->$key)) {
                    // array implies one to many relationship. attempt to flatten array
                    // not yet supported
                    continue;
                }
                else
                    continue;
            }
        }

        if ($row instanceof CActiveRecord) {
            $attributes = $row->getAttributes();

            if (!empty($related_attributes))
                $attributes += $related_attributes;

            $headers = array_keys($attributes);
        } else {
            $headers = array_keys($row);
        }

        // remove excluded
        if (count($this->_exclude) > 0) {
            foreach ($this->_exclude as $e) {
                $key = array_search($e, $headers);
                if ($key !== false) {
                    unset($headers[$key]);
                }
            }
        }

        if (count($this->_headers) > 0) {
            foreach ($headers as &$header) {
                if (array_key_exists($header, $this->_headers)) {
                    $header = $this->_headers[$header];
                }
            }
        }

        fputcsv($this->_filePointer, $headers, $this->_delimiter, $this->_enclosure);
    }

    /**
     * Write array row to current {$this->_filePointer}, taking into account exclusions
     * @param array $row 
     * @return void
     */
    public function _writeRow($row) {


        $related_attributes = array();

        // loop through the relations
        foreach ($row->relations() as $key => $related) {

            if ($row->hasRelated($key)) {

                if (!is_object($row->$key))
                    continue;


                foreach ($row->$key->getAttributes() as $attr_key => $attr_val) {
                    $related_attributes[$key . '_' . $attr_key] = $attr_val;
                }
            }
        }

        if ($row instanceof CActiveRecord) {
            $row = $row->getAttributes();

            $row+=$related_attributes;
        }
        // remove excluded
        if (count($this->_exclude) > 0) {
            foreach ($this->_exclude as $e) {
                if (array_key_exists($e, $row)) {
                    unset($row[$e]);
                }
            }
        }


        if ($this->stripNewLines) {
            array_walk($row, function(&$value, $key) {
                        $value = str_replace("\r\n", " ", $value);
                    });
        }


        if (isset($this->_callback) && $this->_callback) {
            fputcsv($this->_filePointer, call_user_func($this->_callback, $row), $this->_delimiter, $this->_enclosure);
        } else {
            fputcsv($this->_filePointer, $row, $this->_delimiter, $this->_enclosure);
        }
        unset($row);
    }

}
