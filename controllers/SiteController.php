<?php

namespace app\controllers;

use app\models\InstaFollow;
use app\models\Orders;
use app\models\Transactions;
use app\models\User;
use yii\filters\VerbFilter;
use yii\rest\Controller;

class SiteController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'start-project' => ['GET'],
                    ],
                ],
            ]
        );
    }

    public function actionStartProject()
    {
        Transactions::deleteAll();
        Orders::deleteAll();
        InstaFollow::deleteAll();
        User::deleteAll();
        $status = true;
        for ($i = 1; $i < 10; $i++) {
            $model = new User(['username' => ("Alireza" . $i), 'token' => '12345' . $i, 'instagram' => 'Alireza_insta' . $i,'coins' => 4]);
            if (!$model->save(false)) {
                $status = false;
                break;
            }
        }
        return ['status' => $status, 'message' => 'project started'];
    }

}
