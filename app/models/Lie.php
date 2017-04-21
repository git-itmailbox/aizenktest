<?php
/**
 * Created by PhpStorm.
 * User: yura
 * Date: 20.04.17
 * Time: 13:53
 */

namespace app\models;


class Lie
{
    private $positiveLook = [6, 24, 36];
    private $negativeLook = [12, 18, 30, 42, 48, 54];
    private $resultCount, $answers, $result;

    function __construct(array $answers)
    {
        $this->answers = $answers;
        $this->result='';
        $this->calcBelongingAnswers($this->answers);
        if($this->resultCount <= 3)
            $this->result = "Норма";
        if($this->resultCount > 3 && $this->resultCount < 6)
            $this->result = "Сомнительно";
        if($this->resultCount > 5 )
            $this->result = "Ответы не достоверны";
    }

    public function getResult()
    {
        return $this->result;
    }
    public function getPoints()
    {
        return $this->resultCount;
    }

    /**
     * @param array $answers
     * @return int
     */
    private function calcBelongingAnswers(array $answers)
    {
        $this->resultCount = 0;
        foreach ($this->positiveLook as $number) {
            if (in_array($number, $answers['yes']))
                $this->resultCount++;
        }
        foreach ($this->negativeLook as $number) {
            if (in_array($number, $answers['no']))
                $this->resultCount++;
        }
        return $this->resultCount;
    }
}