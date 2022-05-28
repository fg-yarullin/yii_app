<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use app\controllers\SiteController;

class RegisterForm extends Model
{
    public $username;
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
                [
                    'surname', 'name', 'password',
                    'email', 'password_confirm'
                ],
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
            $user = new User();
            $user->username = $this->email;
            $user->surname = $this->surname;
            $user->name = $this->name;
            $user->email = $this->email;
            $user->password = Yii::$app->security->generatePasswordHash($this->password);
            $user->auth_key = '';
            $user->activation_key = sha1(mt_rand(10000, 99999).time().$this->email);

//            $this->sendActivationKey($user);
//            echo '<pre>'; print_r('hello')); die;

            if($user->save()){
                $this->sendActivationKey($user);
                return true;
            }
        }
        return false;
    }

    private function validateUserData($userData) {
        $valid = true;
        $errors = [];
        if (empty($userData['username'])) {
            $valid = false;
            $errors[] = 'Логи не может быть пустым';
        }
        if (empty($userData['surname'])) {
            $valid = false;
            $errors[] = 'Фамилия не может быть пустым';
        }
        if (empty($userData['name'])) {
            $valid = false;
            $errors[] = 'Name не может быть пустым';
        }
        if (empty($userData['email'])) {
            $valid = false;
            $errors[] = 'Email не может быть пустым';
        } elseif (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            $valid = false;
            $errors[] = 'Проверьте адрес электронной почты';
        } else {
            if ($this->isUserExists($userData['email'])) {
                $valid = false;
                $errors[] = 'Адрес электронной почты уже используется';
            }
        }
        if (empty($userData['password'])) {
            $valid = false;
            $errors[] = 'Пароль может быть пустым';
        }
        if (empty($_POST['password_verify'])) {
            $valid = false;
            $errors[] = '«Повторите пароль» может быть пустым';
        }

        if ($_POST['password_verify'] != $userData['password']) {
            $valid = false;
            $errors[] = 'Пароль и «Повторите пароль» не идентичны';
        }
        return [
            'valid' => $valid,
            'errors' => $errors
        ];
    }

    private function sendActivationKey($user) {
//        $mail_to = $user->email;
        $actionPath = 'site/activation';
        $subject = "Добро пожаловать на наш сайт!\r\n";
        $message = "\r\nСпасибо, что за ваш интерес!\r\nДля активации вашей учетной записи пройдите по ссылке в пиьме.\r\n";
        $message .= "\r\nКод активации: ";
        $message .= Yii::$app
            ->urlManager
            ->createAbsoluteUrl([
                $actionPath,
                'activation_key' => $user->activation_key
            ]);

        Yii::$app->mailer->compose()
            ->setTo($user->email)
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setReplyTo([$this->email => $this->name])
            ->setSubject($subject)
            ->setTextBody($message)
            ->send();
    }
}
