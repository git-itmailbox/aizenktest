<div class="container">
    <div class="col-md-12 col-sm-12 text-center">
        <div class="row ">
            <div class="form-group">
                <div class="col-md-1 col-sm-2">
                    <label for="extraversion">
                        <small>extraversion</small>
                    </label>
                    <input class="form-control" readonly id="extraversion" type="text"
                           value="<?= $results['extraversion']['points'] ?>">
                </div>
                <div class="col-md-1 col-sm-2">
                    <label for="neuroticism">
                        <small>neuroticism</small>
                    </label>
                    <input class="form-control" id="neuroticism" type="text"
                           value="<?= $results['neuroticism']['points'] ?>" readonly>
                </div>
                <div class="col-md-1 col-sm-2">
                    <label for="lie">
                        <small>lie</small>
                    </label>
                    <input class="form-control" id="lie" type="text" value="<?= $results['lie']['points'] ?>" readonly>
                </div>
            </div>
            <div class="col-md-6"></div>
        </div>
        <h4 class="text-primary">
            <?= $model->getName(); ?>, Ваш результат теста:
        </h4>
       <label class="label-success">
           <p>Вы <?= $results['extraversion']['extraversiontype'] . "-"; ?>,
            (<?= $results['extraversion']['points']; ?> баллов)
        </p>
        <p> <?= $results['neuroticism']['neuroticismType']; ?>
            (<?= $results['neuroticism']['points']; ?> баллов)
        </p>
        <p> <?= $results['charachter']['description']; ?></p>
       </label>
    </div>
    <canvas id='graph'>Обновите браузер</canvas>
</div>
