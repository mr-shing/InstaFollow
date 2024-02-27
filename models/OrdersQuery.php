<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Orders]].
 *
 * @see Orders
 */
class OrdersQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Orders[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Orders|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function notFilled()
    {
        return $this->andHaving('SUM(follow_request_count - filled_count)>0');
    }

    public function notByUser($user_id)
    {
        return $this->andWhere(['<>', 'user_id', $user_id]);
    }

    public function byUser(int $user_id)
    {
        return $this->andWhere(['user_id' => $user_id]);
    }
}
