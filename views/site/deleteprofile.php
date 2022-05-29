<?php
/** @var yii\web\View $this */
?>

<h5 class="text-center mt-5">Ваша учетная запись будет безвозвратно удалена! Продолжить?</h5>

<div class="form-group d-flex justify-content-center mt-4">
    <div class="mr-4">
        <a class="btn btn-primary" href="profile" style="width: 240px">Отменить</a>
    </div>
    <div>
        <form action="deleteprofile" method="post">
            <input type="hidden" name="id" value="<?=$user->id?>">
            <input
                    type="hidden"
                    name="<?=Yii :: $app->getRequest()->csrfParam?>"
                    value="<?=Yii :: $app->getRequest()->getCsrfToken()?>">
            <button type="submit" class="btn btn-danger" style="width: 240px">Удалить</button>
        </form>
    </div>
</div>