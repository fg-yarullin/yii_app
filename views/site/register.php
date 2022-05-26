<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-register">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <p class="text-center">Для регистрации, пожалуйста, заполните следующие поля:</p>
    <div class="d-flex justify-content-center align-items-center mt-4">
        <div class="col-md-4 col-md-offset-4">
            <?php $form = ActiveForm::begin([
                'id' => 'register-form',
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'ml-3 form-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'errorOptions' => ['class' => 'invalid-feedback'],
                ],
            ]); ?>

            <?= $form->field($model, 'surname')
                ->textInput(['autofocus' => true, 'placeholder' => 'Фамилия'])
                ->label('Фамилия')?>

            <?= $form->field($model, 'name')
                ->textInput(['autofocus' => true, 'placeholder' => 'Имя'])
                ->label('Имя')?>

            <?= $form->field($model, 'email')
                ->textInput(['autofocus' => true, 'placeholder' => 'Email адрес'])
                ->label('Email адрес')?>

            <?= $form->field($model, 'password')
                ->passwordInput(['placeholder' => 'Пароль'])
                ->label('Пароль')?>

            <?= $form->field($model, 'password_confirm')
                ->passwordInput(['placeholder' => 'Подтверждение пароля'])
                ->label('Подтверждение пароля ')?>

            <div class="form-group">
                <div class="text-center">
                    <?= Html::submitButton('Зарегистрировать', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
