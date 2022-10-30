<?php

declare (strict_types = 1);

namespace App;

class SqliteDb extends \SQLite3
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var mixed
     */
    protected $bind;

    /**
     * @param $file
     */
    public function __construct($file)
    {
        $this->open($this->file = $file);
    }

    public static function make()
    {
        return new static(...func_get_args());
    }

    /**
     * @param $statement
     * @param array $bindings
     * @return mixed
     */
    public function fetchAll($statement, array $bindings = [])
    {
        $items = [];

        $statement = $this->prepare($statement);

        foreach ($bindings as $key => $val) {
            $statement->bindValue(':' . $key, $val);
        }

        $rows = $statement->execute();

        while ($row = $rows->fetchArray(SQLITE3_ASSOC)) {
            $items[] = new Data($row);
        }

        return $items;
    }

    /**
     * @return self
     */
    public function forget()
    {
        if (is_file($this->getFile())) {
            unlink($this->getFile());
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }
}
