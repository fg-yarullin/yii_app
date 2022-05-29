<?php


namespace app\models;
use Yii;
use yii\base\Model;


class ProfileForm extends Model
{
    public $surname;
    public $name;
    public $email;
    public $password;
    public $password_confirm;
    public $id;

    public function rules():array
    {
        return [
            [
                [
                    'name', 'surname'
                ],
                'required'
            ],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            // create user
//            $user = $this->getUser();

//            if (!$user || !$user->validatePassword($this->password)) {
//                $this->addError($attribute, 'Incorrect username or password.');
//            }
        }
    }

    public function showProfile($user) {
        $this->id =  $user['id'];
        $this->surname = $user['surname'];
        $this->name = $user['name'];
        $this->email = $user['email'];
        $this->password = '';
        $this->password_confirm = '';
    }
}