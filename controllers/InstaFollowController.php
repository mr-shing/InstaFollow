<?php

namespace app\controllers;

use app\models\InstaFollow;
use app\models\InstaFollowSearch;
use app\models\Orders;
use app\models\TokenAuth;
use app\models\Transactions;
use Yii;
use yii\filters\VerbFilter;
use yii\rest\Controller;

/**
 * InstaFollowController implements the CRUD actions for InstaFollow model.
 */
class InstaFollowController extends Controller
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
                        'list' => ['GET'],
                        'follow' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionList()
    {
        $token = Yii::$app->request->headers->get('token');
        $auth = new TokenAuth(['token' => $token]);
        if ($user = $auth->authenticate()) {
            return (new InstaFollowSearch())->list(Yii::$app->request->queryParams, $user->id);
        }
        return ['status' => false, 'message' => 'Unauthorized'];
    }

    public function actionFollow()
    {

        $token = Yii::$app->request->headers->get('token');
        $auth = new TokenAuth(['token' => $token]);
        if ($user = $auth->authenticate()) {
            $model = new InstaFollow(['follow_by' => $user->id]);
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $flag = $model->save(false);
                    $flag = $flag && Orders::fillCount($model->owner_id);
                    $flag = $flag && Transactions::create(
                            $user->id,
                            Transactions::TYPE_FOLLOW,
                            InstaFollow::COIN_REWARD_PER_FOLLOW,
                            InstaFollow::class, $model->id);
                    $flag && $user->increaseCoins(InstaFollow::COIN_REWARD_PER_FOLLOW);
                    if ($flag) {
                        $transaction->commit();
                        return ['status' => true, 'message' => 'با موفقیت فالو شد.'];
                    }
                    $transaction->rollBack();
                    return ['status' => false, 'message' => 'خطا در ثبت اطلاعات.'];
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                }
            }

            $model->validate();
            return $model;
        }
        return ['status' => false, 'message' => 'Unauthorized'];
    }
}
