<div class="container">
    <input id="userId" name="userId" type="hidden" value="<?= $id ?>">
    <input id="answerNum" name="answerNum" type="hidden" value="1">
    <label>Ваш номер: <?php echo (isset($id)) ? $id : ''; ?></label>
    <div>
        <H3>Вопрос № <label class="text-info" for="" id="questionNum"> 1</label></H3>
        <div class="alert-info" id="question">
            <?php echo (isset($question)) ? $question : ''; ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-md-offset-1">
            <div class="row clearfix" id="answer">
                <div class="col-md-4">
                    <button class="btn btn-success yes answer btn-block">Да</button>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-danger no answer btn-block">Нет</button>
                </div>
            </div>
        </div>
    </div>


</div>