<?php
/**
 * Created by PhpStorm.
 * User: yura
 * Date: 20.04.17
 * Time: 13:53
 */

namespace app\models;


class Extraversion
{
    private $positiveLook = [1, 3, 8, 10, 13, 17, 22, 25, 27, 39, 44, 46, 49, 53, 56];
    private $negativeLook = [5, 15, 20, 29, 32, 37, 41, 51];
    private $resultCount, $answers, $result;

    function __construct(array $answers)
    {
        $this->answers = $answers;
        $this->result='';
        $this->calcBelongingAnswers($this->answers);
        if($this->resultCount <= 10)
            $this->result = "интроверт, замкнуты внутри себя.";
        if($this->resultCount > 10 && $this->resultCount < 15)
            $this->result = "амбиверт, общительны, обращены к внешнему миру";
        if($this->resultCount > 15 )
            $this->result = "экстраверт, общаетесь, когда вам это нужно";
    }

//    public function getQuestionsBelongTo()
//    {
//        return ['positive'=>$this->positiveLook, 'negative'=>$this->negativeLook];
//    }

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