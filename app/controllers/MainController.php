<?php
namespace app\controllers;

use app\models\Answer;
use app\models\Questions;

/**
 * Created by PhpStorm.
 * User: yura
 * Date: 02.03.17
 * Time: 1:22
 */
class MainController extends AppController
{
    public $layout = 'main';

    /**
     *
     */
    public function indexAction()
    {
        $model = new Answer();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model->setName($_POST['name']);
            $errors = $model->validate();
            if (empty($errors)) {
                $model->save();
            }
            if ($model->isSaved()) {
                $this->view = 'test';
                $modelQuestion = new Questions();
                $modelQuestion->findOne(1);
                $this->set(['id' => $model->getId(), 'question' => $modelQuestion->getQuestion()]);
            }
        }

    }

    public function importquestionAction()
    {
        $model = new Questions();
        $file = fopen(WWW . '/questions.txt', "r");
        if ($file) {
            while (!feof($file)) {
                $question = fgets($file);
                $model->setQuestion($question);
                $model->save();
            }
            $this->set(['message' => "Questions have been imported"]);
        } else {
            $this->set(['message' => "Cant open file: $file"]);
        }
    }

    public function saveAnswerAction()
    {
        $this->layout = 'raw';
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return false;
        }
        $answerNum = (isset($_POST['answerNum'])) ? $_POST['answerNum'] : "";
        $receivedAnswer = (isset($_POST['answer'])) ? $_POST['answer'] : "";
        $userId = (isset($_POST['userId'])) ? $_POST['userId'] : "";

        $data = [];
        $error = '';
        if ($answerNum === "" || $receivedAnswer === "" || $userId === "") {
            $data['error'] = "Answer has not received";
        } else {
            //save current answer
            $this->updateAnswer($userId, $answerNum, $receivedAnswer);


            //get next question
            $modelQuestion = new Questions();
            $modelQuestion->findOne(++$answerNum);
            $data['question'] = $modelQuestion->getQuestion();
            $data['questionNum'] = $modelQuestion->getId();
        }

        $this->set(['data' => json_encode($data)]);

    }

    /**
     * @param int $userId
     * @param int $answerNum
     * @param string $receivedAnswer
     * @return boolean
     */
    private function updateAnswer($userId, $answerNum, $receivedAnswer)
    {
        $modelAnswer = new Answer();
        $modelAnswer->findOne($userId);
        $answers = $modelAnswer->getAnswers();
        if($answerNum == Questions::TOTAL_QUESTIONS)
            $modelAnswer->setPassDate();
        $answers->$receivedAnswer[] = $answerNum;
        $modelAnswer->setAnswers($answers);
        return $modelAnswer->update();
    }

    /**
     *
     */
    public function resultAction()
    {
        if (isset($this->route['params'])) {
            $id = $this->route['params'];
        }
        $error='';
        $modelAnswer = new Answer();
        $modelAnswer->findOne($id);
        $results = $modelAnswer->getResults();
//        var_dump($results);

        if($results['lie']['points'] > 3)
        {
            $this->view = "doubtresult";
            $error = 'Пожалуйста, попробуйте ответить на вопросы снова и более правдивее.';
        }
        if(!$results)
        {
            $this->view = "doubtresult";
            $error = 'Тест с ошибкой либо незаконченный...';

        }
        $this->set([
            'model'=>$modelAnswer,
            'results' => $results,
            'error' => $error
        ]);

    }

    public function searchuserAction()
    {
        $this->layout = 'raw';

        $searchString = $_POST['search'];
        $data = $this->getSearchUserTestAsJson($searchString);

        $this->set(['data' => $data]);
    }

    private function getSearchUserTestAsJson($searchString)
    {
        $modelAnswer = new Answer();
        $result = $modelAnswer->search($searchString);
        $ret = array();
        foreach($result as $row) {
            $r = array();
            $r["id"] = $row->id;
            $r["value"] = $row->name . " ($row->pass_date)";
            $ret[] = $r;
        }
        return json_encode($ret);
    }
}