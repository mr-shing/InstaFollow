<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\base\Model;

class TokenAuth extends Model
{
    public $token;

    public function authenticate()
    {
        $user = User::find()->where(['token' => $this->token])->one();
        if ($user) {
            return $user;
        }
        return false;
    }
}