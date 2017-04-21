<?php
/**
 * Created by PhpStorm.
 * User: yura
 * Date: 02.03.17
 * Time: 23:17
 */

namespace app\models;

use vendor\core\base\Model;

class Answer extends Model
{
    protected $table = 'user_answers';

    private $name, $id, $answers, $pass_date;

    private $charachters = [
        'melancholic' => ['neuroticism' => [11, 24], 'extraversion' => [0, 10], 'description' => 'Меланхолик-интраверт: нестабильная личность, тревожен, пессимистичен, очень сдержан внешне, но чувствителен и эмоционален внутри, интеллектуальный, склонен к размышлениям. В ситуации стресса — склонность к внутренней тревоге, депрессии, срыву или ухудшению результатов деятельности (стресс кролика).'],
        'sanguine' => ['neuroticism' => [0, 10], 'extraversion' => [11, 24], 'description' => 'Сангвиник-экстраверт: стабильная личность, социален, направлен к внешнему миру, общителен, порой болтлив, беззаботный, веселый, любит лидерство, много друзей, жизнерадостен'],
        'choleric' => ['neuroticism' => [11, 24], 'extraversion' => [11, 24], 'description' => 'Холерик-экстраверт: нестабильная личность, обидчив, возбужден, несдержан, агрессивен, импульсивен, оптимистичен, активен, но работоспособность и настроение нестабильны, цикличны. В ситуации стресса — склонность к истерико-психопатическим реакциям.'],
        'phlegmatic' => ['neuroticism' => [0, 10], 'extraversion' => [0, 10], 'description' => 'Флегматик -интраверт: стабильная личность, медлителен, спокоен, пассивен, невозмутим, осторожен, задумчив, мирный, сдержанный, надежный, спокойный в отношениях, способен выдержать длительные невзгоды без срывов здоровья и настроения.'],
    ];

    /**
     * @return mixed
     */
    public function getPassDate()
    {
        return $this->pass_date;
    }

    /**
     *
     */
    public function setPassDate()
    {
        $this->pass_date = date("Y-m-d H:i:s");
    }

    /**
     * @return array
     */
    public function getAnswers()
    {
        return json_decode($this->answers);
    }

    /**
     * @param array $answers
     */
    public function setAnswers($answers)
    {
        $this->answers = json_encode($answers);
    }

    public function getPositiveAnswers()
    {
        return $this->getAnswers()->yes;
    }

    public function getNegativeAnswers()
    {
        return $this->getAnswers()->no;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = trim($name);
    }

    public function validate()
    {
        $errors = [];
        if ($this->name === '') {
            $errors['name'] = 'Name is required';
        }

        return $errors;
    }

    /**
     * @return mixed
     */
    public function save()
    {
        $template = json_encode(['yes' => [], 'no' => []]);
        $sql = "INSERT INTO {$this->table}(name, answers) VALUES (:name, '$template')";

        $bindParams = [
            ':name' => $this->name,
        ];
        $stmt = $this->pdo->queryBindParams($sql, $bindParams);
        if (!$stmt) {
            return false;
        }
        $this->id = $this->pdo->lastInsertedId();
        return true;
    }

    public function update()
    {
        if (isset($this->pass_date))
            $sql = "UPDATE {$this->table} SET  answers = '$this->answers', pass_date = '$this->pass_date' WHERE id = $this->id";
        else
            $sql = "UPDATE {$this->table} SET  answers = '$this->answers' WHERE id = $this->id";
        return $this->pdo->execute($sql);
    }

    public function isSaved()
    {
        return (int)$this->id > 0;
    }

    public function findOne($id, $field = '')
    {
        $row = parent::findOne($id, $field);
        if (!$row) return false;
        $this->id = $row->id;
        $this->name = $row->name;
        $this->answers = $row->answers;
        $this->pass_date = $row->pass_date;
    }

    public function getResults()
    {
        $result = false;
        if ($this->isTestPassed()) {
            $result['extraversion'] = $this->getExtraversion();
            $result['neuroticism'] = $this->getNeuroticism();
            $result['lie'] = $this->getLie();
            $result['charachter'] = $this->getCharachterDescription($result);
        }
        return $result;
    }

    private function getExtraversion()
    {
        $extraversion = new Extraversion(['yes' => $this->getPositiveAnswers(), 'no' => $this->getNegativeAnswers()]);
        $points = $extraversion->getPoints();
        $extraversionType = $extraversion->getResult();
        return ['points' => $points, 'extraversiontype' => $extraversionType];
    }

    private function getNeuroticism()
    {
        $neuroticism = new Neuroticism(['yes' => $this->getPositiveAnswers()]);
        $points = $neuroticism->getPoints();
        $neuroticismType = $neuroticism->getResult();
        return ['points' => $points, 'neuroticismType' => $neuroticismType];
    }

    private function getLie()
    {
        $lie = new Lie(['yes' => $this->getPositiveAnswers(), 'no' => $this->getNegativeAnswers()]);
        $points = $lie->getPoints();
        $lieType = $lie->getResult();
        return ['points' => $points, 'lieType' => $lieType];
    }

    /**
     * @return bool
     */
    private function isTestPassed()
    {
        return (isset($this->pass_date) && $this->countAnswers() == Questions::TOTAL_QUESTIONS);
    }

    private function countAnswers()
    {
        return count($this->getPositiveAnswers()) + count($this->getNegativeAnswers());
    }

    public function getCharachterDescription($results)
    {
        foreach ($this->charachters as $charachter) {
            if ($this->matchCharachter($charachter, $results)) {
                return $charachter;
            }
        }
        return false;
    }

    public function matchCharachter($charachter, $userResults)
    {

        return ($this->isInRange($userResults['neuroticism']['points'], $charachter['neuroticism'][0], $charachter['neuroticism'][1]))
            &&
            ($this->isInRange($userResults['extraversion']['points'], $charachter['extraversion'][0], $charachter['extraversion'][1]));

    }

    function isInRange($val, $min, $max)
    {
        return ($val >= $min && $val <= $max);
    }

    public function search($searchString)
    {
        $sql = "SELECT `id`, `name`, `pass_date` FROM {$this->table} WHERE `name` LIKE :searchStr";
        $bindParams = [
            ':searchStr' => "%$searchString%"
        ];
        $stmt = $this->pdo->query($sql, $bindParams);
        return $stmt;
    }
}