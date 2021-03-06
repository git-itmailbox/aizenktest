<?php
/**
 * Created by PhpStorm.
 * User: yura
 * Date: 02.03.17
 * Time: 23:17
 */

namespace app\models;

use vendor\core\base\Model;

class Questions extends Model
{
    const TOTAL_QUESTIONS = 57;
    protected $table = 'questions';

    private $question=null, $id=null;

    /**
     * @param mixed $question
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }


    /**
     * @return mixed
     */
    public function save()
    {
        $sql = "INSERT INTO {$this->table}(question) VALUES (:question)";

        $bindParams = [ ':question' => $this->question, ];
        return $this->pdo->queryBindParams($sql, $bindParams);
    }

    public function findOne($id, $field = '')
    {
        $row = parent::findOne($id, $field); // TODO: Change the autogenerated stub
        if(!$row) return $row;

        $this->id = $row->id;
        $this->question = $row->question;
    }

    public function isExist()
    {
        
    }
}