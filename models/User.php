<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $token
 * @property string $instagram
 * @property int $coins
 *
 * @property InstaFollow[] $instaFollows
 * @property InstaFollow[] $instaFollows0
 * @property Orders $orders
 * @property Transactions[] $transactions
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'token', 'instagram'], 'required'],
            [['coins'], 'integer'],
            ['coins', 'default', 'value' => 4],
            [['username', 'token', 'instagram'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'token' => Yii::t('app', 'Token'),
            'instagram' => Yii::t('app', 'Instagram'),
            'coins' => Yii::t('app', 'Coins'),
        ];
    }


    /**
     * @param float $coins
     * @return bool
     */
    public function reduceCoins(float $coins): bool
    {
        $this->coins -= $coins;
        return $this->save(false);
    }

    /**
     * @param int $COIN_REWARD_PER_FOLLOW
     * @return bool
     */
    public function increaseCoins(int $coins): bool
    {
        $this->coins += $coins;
        return $this->save(false);
    }


    /**
     * Gets query for [[InstaFollows]].
     *
     * @return \yii\db\ActiveQuery|InstaFollowQuery
     */
    public function getInstaFollows()
    {
        return $this->hasMany(InstaFollow::class, ['follow_by' => 'id']);
    }

    /**
     * Gets query for [[InstaFollows0]].
     *
     * @return \yii\db\ActiveQuery|InstaFollowQuery
     */
    public function getInstaFollows0()
    {
        return $this->hasMany(InstaFollow::class, ['owner_id' => 'id']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery|OrdersQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Orders::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Transactions]].
     *
     * @return \yii\db\ActiveQuery|TransactionsQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transactions::class, ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

}
