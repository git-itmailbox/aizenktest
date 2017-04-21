<?php
/**
 * Created by PhpStorm.
 * User: yura
 * Date: 20.04.17
 * Time: 13:53
 */

namespace app\models;


class Neuroticism
{
    private $positiveLook = [2, 4, 7, 9, 11, 14, 16, 19, 21, 23, 26, 28, 31, 33, 35, 38, 40, 43, 45, 47, 50, 52, 55, 57];
//    private $negativeLook = [];
    private $resultCount, $answers, $result;

    function __construct(array $answers)
    {
        $this->answers = $answers;
        $this->result='';
        $this->calcBelongingAnswers($this->answers);
        if($this->resultCount <= 10)
            $this->result = "эмоциональная устойчивость";
        if($this->resultCount > 10 && $this->resultCount < 17)
            $this->result = "эмоциональная впечатлительность";
        if($this->resultCount > 16 && $this->resultCount < 23)
            $this->result = "появляются отдельные признаки расшатанности нервной системы";
        if($this->resultCount > 22 )
            $this->result = "невротизм, граничащий с патологией, возможен срыв, невроз";
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

        return $this->resultCount;
    }
}