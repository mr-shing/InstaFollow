<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transactions".
 *
 * @property int $id
 * @property int $user_id
 * @property int $type
 * @property int $coin_count
 * @property string $model_class
 * @property int $model_id
 *
 * @property User $user
 */
class Transactions extends \yii\db\ActiveRecord
{
    const TYPE_ORDER = 1;
    const TYPE_FOLLOW = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transactions';
    }

    public static function create(int $user_id, int $type_order, int $coins, string $class, int $id)
    {
        $model = new self([
            'user_id' => $user_id,
            'type' => $type_order,
            'coin_count' => $coins,
            'model_class' => $class,
            'model_id' => $id
        ]);
        return $model->save(false);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'coin_count', 'model_class', 'model_id'], 'required'],
            [['user_id', 'type', 'coin_count', 'model_id'], 'integer'],
            [['model_class'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'type' => Yii::t('app', 'Type'),
            'coin_count' => Yii::t('app', 'Coin Count'),
            'model_class' => Yii::t('app', 'Model Class'),
            'model_id' => Yii::t('app', 'Model ID'),
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

    /**
     * {@inheritdoc}
     * @return TransactionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionsQuery(get_called_class());
    }
}
