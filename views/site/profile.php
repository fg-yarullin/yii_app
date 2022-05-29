<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var app\models\ProfileForm $model */

use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

Html::csrfMetaTags();
$this->title = 'Профиль пользователя';
$this->params['breadcrumbs'][] = $this->title;

use app\assets\AppAsset;
AppAsset::register($this);
$this->registerJsFile('@web/js/profile.js', ['position' => Yii::$app->view::POS_END]);

/* or
$this->registerJsFile(
    '@web/js/profile.js',
    [['position' => Yii::$app->view::POS_END]]
);*/
?>
<div class="site-about">
    <div id="app" class="d-flex justify-content-center"></div>
    <div class="text-center">
        <h1><?= Html::encode($this->title) ?></h1>
        <label class="form-label" for="email-address">Адрес email:</label>
        <?= Html::encode($model->email) ?>
        <input type="hidden" id="user-id" name="id" value="<?=Html::encode($model->id)?>">
    </div>

    <div class="d-flex justify-content-center align-items-center mt-4">
        <div class="col-md-4 col-md-offset-4">

            <?php $form = ActiveForm::begin([
                'id' => 'profile-form',
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'ml-3 form-label'],
                    'inputOptions' => ['class' => 'form-control'],
                    'errorOptions' => ['class' => 'invalid-feedback'],
                ],
            ]); ?>

            <?= $form->field($model, 'surname')
                ->textInput(['autofocus' => true])
                ->label('Фамилия')?>

            <?= $form->field($model, 'name')
                ->textInput(['autofocus' => true])
                ->label('Имя')?>

            <?= $form->field($model, 'password')
                ->passwordInput()
                ->label('Пароль')?>

            <?= $form->field($model, 'password_confirm')
                ->passwordInput()
                ->label('Повторите пароль ')?>

            <div class="form-group d-flex justify-content-end">
                <div class="w-50">
                    <?= Html::submitButton(
                        'Сохранить',
                        [
                            'class' => 'btn btn-primary w-75',
                            'name' => 'profile-save-button'
                        ])
                    ?>
                </div>
                <div class="w-50">
                    <a class="btn btn-danger w-75" id="delete-button" href="deleteprofile">Удалить</a>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>

</div>
