<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <p  class="text-center">Пожалуйста, заполните следующие поля:</p>
    <div class="d-flex justify-content-center align-items-center mt-4">
        <div class="col-md-4 col-md-offset-4">
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'ml-3 form-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'errorOptions' => ['class' => 'invalid-feedback'],
                ],
            ]); ?>

                <?= $form->field($model, 'email')
                    ->input('email', ['autofocus' => true])
                    ->label('Электронная почта')?>

                <?= $form->field($model, 'password')->passwordInput()
                    ->label('Пароль')?>

                <?= $form->field($model, 'rememberMe')->checkbox([
                    'template' => "<div class=\"ml-3 custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
                ])->label('Запомнить') ?>

                <div class="form-group">
                    <div class="text-center">
                        <?= Html::submitButton('Login', ['class' => 'btn btn-primary w-25', 'name' => 'login-button']) ?>
                    </div>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
