<?php

namespace app\controllers;

use app\models\Orders;
use app\models\TokenAuth;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\Controller;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller
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
                        'create' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionCreate()
    {
        $token = Yii::$app->request->headers->get('token');
        $auth = new TokenAuth(['token' => $token]);
        if ($user = $auth->authenticate()) {
            if (!$order = $user->getOrder()->one()) {
                $order = new Orders(['user_id' => $user->id]);
            }
            if ($order->load(Yii::$app->request->post()) && $order->validate()) {
                if ($order->user->coins < ($order->follow_request * Orders::COIN_COST_PER_ORDER)) {
                    return ['status' => false, 'message' => 'تعداد سکه ها کافی نست.'];
                } else {
                    $transaction = Yii::$app->db->beginTransaction();
                    try {
                        $flag = $order->createOrder();
                        if ($flag) {
                            $transaction->commit();
                            return ['status' => true, 'message' => 'سفارش با موفقیت ثبت شد.'];

                        } else {
                            $transaction->rollBack();
                            return ['status' => false, 'message' => 'خطا در ثبت سفارش.'];
                        }
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        throw $e;
                    }
                }

            }
            $order->validate();
            return $order;
        }
        return $this->asJson(['status' => false, 'message' => 'Unauthorized']);
    }
}
