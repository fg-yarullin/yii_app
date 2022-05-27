<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RegisterForm extends Model
{
    public $surname;
    public $name;
    public $email;
    public $password;
    public $password_confirm;
//    private $activation_key;

    /**
     * @return array the validation rules.
     */
    public function rules():array
    {
        $message = 'Адрес электронной почты уже зарегистрирован';
        return [
            // username and password are both required
            [
                ['surname', 'name', 'password', 'email', 'password_confirm'],
                'required'
            ],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['email', 'unique', 'message' => $message]
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
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

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     * @throws \yii\base\Exception
     */
    public function register():bool
    {
        if ($this->load(Yii::$app->request->post()) /*&& validate user data*/ ) {
//            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
            $user = new User();
//            echo '<pre>'; print_r($user); die;
            $user->surname = $this->surname;
            $user->name = $this->name;
            $user->email = $this->email;
            $user->password = Yii::$app->security->generatePasswordHash($this->password);
            $user->auth_key = '';
            $user->activation_key = Yii::$app->security->generateRandomString(64);

            if($user->save()){
                return 0; // send email
            }
        }
        return false;
    }

    private function validateUserData($userData) {
        $valid = true;
        $errors = [];
        if (empty($userData['surname'])) {
            $valid = false;
            $errors[] = 'Name cannot be blank';
        }
        if (empty($userData['name'])) {
            $valid = false;
            $errors[] = 'Name cannot be blank';
        }
        if (empty($userData['email'])) {
            $valid = false;
            $errors[] = 'Email cannot be blank';
        } elseif (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            $valid = false;
            $errors[] = 'Invalid email address';
        } else {
            if ($this->isUserExists($userData['email'])) {
                $valid = false;
                $errors[] = 'That email address is already registered';
            }
        }
        if (empty($userData['password'])) {
            $valid = false;
            $errors[] = 'Password cannot be blank';
        }
        if (empty($_POST['password_verify'])) {
            $valid = false;
            $errors[] = '"Retype Password" cannot be blank';
        }

        if ($_POST['password_verify'] != $userData['password']) {
            $valid = false;
            $errors[] = 'Password and "Retype Password" are not identical';
        }
        return [
            'valid' => $valid,
            'errors' => $errors
        ];
    }
}
