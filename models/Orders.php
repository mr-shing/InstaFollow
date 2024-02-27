<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int $user_id
 * @property int $follow_request_count
 * @property int $filled_count
 *
 * @property User $user
 * @property InstaFollow[] $instaFollows
 */
class Orders extends \yii\db\ActiveRecord
{
    const COIN_COST_PER_ORDER = 4;
    public $follow_request;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    //پر کردن سفارش.
    public static function fillCount($user_id)
    {
        $model = self::find()->byUser($user_id)->limit(1)->one();
        $model->filled_count += 1;
        return $model->save(false);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'follow_request'], 'required'],
            [['user_id', 'follow_request'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function scenarios()
    {
        $scenarios[self::SCENARIO_DEFAULT] = ['!user_id', 'follow_request'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'follow_request' => Yii::t('app', 'Follow Request'),
            'follow_request_count' => Yii::t('app', 'Follow Request Count'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getInstaFollows()
    {
        return $this->hasMany(InstaFollow::class, ['owner_id' => 'user_id']);
    }


    /**
     * @return bool
     */
    public function createOrder(): bool
    {
        $this->follow_request_count += $this->follow_request;
        $flag = $this->save();
        $flag = $flag && Transactions::create(
                $this->user_id,
                Transactions::TYPE_ORDER,
                ($this->follow_request * self::COIN_COST_PER_ORDER),
                Orders::class,
                $this->id
            );
        $flag = $flag && $this->user->reduceCoins($this->follow_request * self::COIN_COST_PER_ORDER);

        return $flag;

    }

    /**
     * {@inheritdoc}
     * @return OrdersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrdersQuery(get_called_class());
    }


}
