<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "insta_follow".
 *
 * @property int $id
 * @property int $owner_id
 * @property int $follow_by
 *
 * @property User $followBy
 * @property User $owner
 */
class InstaFollow extends \yii\db\ActiveRecord
{
    const COIN_REWARD_PER_FOLLOW = 2;
    public $status = true;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'insta_follow';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['owner_id', 'follow_by'], 'required'],
            [['owner_id', 'follow_by'], 'integer'],
            [['follow_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['follow_by' => 'id']],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['owner_id' => 'id']],
            [['owner_id', 'follow_by'], 'checkForValidation'],
        ];
    }

    public function checkForValidation($attribute, $params)
    {
        if ($this->follow_by == $this->owner_id) {
            $this->status = false;
            $this->addError($attribute, 'کاربر یکسان');
        }
        if (!Orders::find()->byUser($this->owner_id)->notFilled()->limit(1)->one()) {
            $this->status = false;
            $this->addError($attribute, 'کاربری که قصد فالو دارید سفارشی ثبت نکرده است.');
        }
        if (InstaFollow::find()->byOwner($this->owner_id)->byFollower($this->follow_by)->limit(1)->one()) {
            $this->status = false;
            $this->addError($attribute, 'شما قبلا این کاربر را فالو کردید');
        }
        return true;
    }

    public function scenarios()
    {
        $scenarios[self::SCENARIO_DEFAULT] = ['owner_id', '!followed_by'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'owner_id' => Yii::t('app', 'Owner ID'),
            'follow_by' => Yii::t('app', 'Follow By'),
        ];
    }

    /**
     * Gets query for [[FollowBy]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getFollowBy()
    {
        return $this->hasOne(User::class, ['id' => 'follow_by']);
    }

    /**
     * Gets query for [[Owner]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::class, ['id' => 'owner_id']);
    }

    /**
     * {@inheritdoc}
     * @return InstaFollowQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new InstaFollowQuery(get_called_class());
    }
}
