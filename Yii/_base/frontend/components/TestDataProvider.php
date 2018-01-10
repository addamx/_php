<?php
/**
 * Created by PhpStorm.
 * User: addams
 * Date: 2017/1/25
 * Time: 11:37
 */

namespace frontend\components;

use yii\data\BaseDataProvider;

class TestDataProvider extends BaseDataProvider
{
    public $filename;
    public $key;
    protected $fileObject;

    public function init()
    {
        parent::init();
        // open file
        $this->fileObject = new \SplFileObject($this->filename);
    }

    protected function prepareModels()
    {
        $models = [];
        $pagination = $this->getPagination();
        var_dump($pagination);

        if ($pagination === false) {
            // in case there's no pagination, read all lines
            while (!$this->fileObject->eof()) {
                $models[] = $this->fileObject->fgetcsv();
                $this->fileObject->next();
            }
        } else {
            // in case there's pagination, read only a single page
            $pagination->totalCount = $this->getTotalCount();
            //var_dump($pagination->getOffset());
            //$this->fileObject->seek($pagination->getOffset());
            $this->fileObject->seek(1);
            $limit = $pagination->getLimit();       //pagination limit默认是20
            //var_dump($this->fileObject->Key());
            //var_dump($this->fileObject->current());

            for ($count = 0; $count < $limit; ++$count) {
                if (!$this->fileObject->eof()) {
                    $models[] = $this->fileObject->fgetcsv();
                }
            }


        }
        return $models;
    }

    protected function prepareKeys($models)
    {
        if ($this->key !== null) {
            $keys = [];
            foreach ($models as $model) {
                if (is_string($this->key)) {
                    $keys[] = $model[$this->key];
                } else {
                    $keys[] = call_user_func($this->key, $model);
                }
            }
            return $keys;
        } else {
            return array_keys($models);
        }
    }

    protected function prepareTotalCount()
    {
        $count = 0;

        while (!$this->fileObject->eof()) {
            $this->fileObject->current();
            //or
            //$this->fileObject->setFlags(\SplFileObject::READ_AHEAD);
            $this->fileObject->next();
            ++$count;
        }
        return $count;
    }
}