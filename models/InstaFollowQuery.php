<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[InstaFollow]].
 *
 * @see InstaFollow
 */
class InstaFollowQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return InstaFollow[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return InstaFollow|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param int $owner_id
     * @return InstaFollowQuery
     */
    public function byOwner(int $owner_id): InstaFollowQuery
    {
        return $this->andWhere([InstaFollow::tableName().'.owner_id' => $owner_id]);
    }

    /**
     * @param int $user_id
     * @return InstaFollowQuery
     */
    public function byFollower(int $user_id): InstaFollowQuery
    {
        return $this->andWhere([InstaFollow::tableName().'.follow_by' => $user_id]);
    }

}
