<?php

declare (strict_types = 1);

namespace App;

require_once __DIR__ . '/Data.php';

class Downloader extends Data
{
    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var string
     */
    protected $fileType;

    /**
     * @param array $items
     */
    public function __construct(array $items)
    {
        foreach ($items as $key => $item) {
            !($item instanceof Data) ?: $items[$key] = $item->all();
        }

        parent::__construct($items);
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->all();
    }

    /**
     * @param array $data
     *
     * @return self
     */
    public function setData(array $data)
    {
        $this->__construct($data);

        return $this;
    }

    /**
     * @param $str
     */
    public function filterData(&$str)
    {
        $str = preg_replace("/\t/", "\\t", $str);

        $str = preg_replace("/\r?\n/", "\\n", $str);

        !strstr($str, '"') ?: $str = '"' . str_replace('"', '""', $str) . '"';
    }

    /**
     * @param string $fileName
     * @return mixed
     */
    public function download($fileName = null)
    {
        $this->setFileName($fileName);

        $extension = $this->getFileType();

        if ($extension == 'csv') {
            return $this->csvDownload();
        }

        return $this->xlsDownload();
    }

    /**
     * @param string $fileName
     * @return mixed
     */
    public function xlsxDownload(string $fileName = null)
    {
        return $this->xlsDownload($fileName);
    }

    /**
     * @param string $fileName
     */
    public function xlsDownload(string $fileName = null)
    {
        $fileName = $fileName ?: $this->getFileName();

        header("Content-Disposition: attachment; filename=" . $fileName);

        if ('.xls' === strtolower(substr($fileName, -4, 1))) {
            header("Content-Type: application/vnd.ms-excel");
        } else {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        }

        $flag = false;
        foreach ($this->getData() as $row) {
            if (!$flag && ($flag = true)) {
                // display column names as first row
                echo implode("\t", array_keys($row)) . "\n";
            }
            // filter row
            array_walk($row, [$this, 'filterData']);
            echo implode("\t", array_values($row)) . "\n";
        }
        exit;
    }

    /**
     * @param string $fileName
     */
    public function csvDownload(string $fileName = null)
    {
        $fileName = $fileName ?: $this->getFileName();

        $temp = fopen('php://memory', 'w');
        // loop over the input array
        $flag = false;
        foreach ($this->getData() as $row) {
            if (!$flag && ($flag = true)) {
                // display column names as first row
                fputcsv($temp, array_keys($row));
            }
            // filter row
            array_walk($row, [$this, 'filterData']);
            // generate csv lines from the inner arrays
            fputcsv($temp, $row);
        }
        // reset the file pointer to the start of the file
        fseek($temp, 0);
        // tell the browser it's going to be a csv file
        header('Content-Type: text/csv');
        // tell the browser we want to save it instead of displaying it
        header('Content-Disposition: attachment; filename="' . $fileName . '";');
        // make php send the generated csv lines to the browser
        fpassthru($temp);
        //
        exit;
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        $fileName = (string) $this->fileName;

        if ($extension = $this->getFileType()) {
            return $fileName . '.' . $extension;
        }

        return $fileName;
    }

    /**
     * @param string $fileName
     *
     * @return self
     */
    public function setFileName($fileName)
    {
        if ($fileName) {
            $fileName = (string) $fileName;

            $this->fileName = $fileName;

            $pos = strrpos($fileName, '.');

            $pos === false ?: $this->setFileType(substr($fileName, $pos));

        }

        return $this;
    }

    /**
     * @return string
     */
    public function getFileType()
    {
        return $this->fileType;
    }

    /**
     * @param string $fileType
     *
     * @return self
     */
    public function setFileType($fileType)
    {
        $this->fileType = strtolower(ltrim($fileType, '.'));

        return $this;
    }
}
