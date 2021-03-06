<?php

namespace app\models;

use Yii;
use yii\base\Model;

class RegisterForm extends Model
{
    public $username;
    public $surname;
    public $name;
    public $email;
    public $password;
    public $password_confirm;

    public $is_registered = false;
//    private $activation_key;

    /**
     * @return array the validation rules.
     */
    public function rules():array
    {
        $message = 'Адрес электронной почты уже зарегистрирован';
        return [
            [
                [
                    'surname', 'name', 'password',
                    'email', 'password_confirm'
                ],
                'required'
            ],
            // password is validated by validatePassword()
            ['password_confirm', 'validatePassword'],
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
    private function validatePassword()
    {
        if (!$this->hasErrors()) {
            if (strcmp($this->password_confirm, $this->password) !== 0) {
                $this->addError('password', 'Пароли не совпадают.');
                return false;
            }
            return true;
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

            if (User::findByUsername($this->email)) {
                $this->addError('email', 'Адрес электронной почты уже зарегистрирован');
            }

            if($this->validatePassword($this->password) && $user->save()){
                $this->sendActivationKey($user);
                return true;
            }
        }
        return false;
    }


    private function sendActivationKey($user) {
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
