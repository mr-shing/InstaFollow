<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * InstaFollowSearch represents the model behind the search form of `app\models\InstaFollow`.
 */
class InstaFollowSearch extends InstaFollow
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'owner_id', 'follow_by'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = InstaFollow::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'follow_by' => $this->follow_by,
        ]);

        return $dataProvider;
    }

    public function list($params, $user_id)
    {
        $query = Orders::find()
            ->joinWith(['instaFollows' => function(InstaFollowQuery $q) use ($user_id) {
                $q->andWhere(InstaFollow::tableName() . '.follow_by IS NULL OR ' . InstaFollow::tableName() . '.follow_by != ' . $user_id);
            }], false)
            ->notFilled()->notByUser($user_id)->groupBy(Orders::tableName() . '.user_id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'follow_by' => $this->follow_by,
        ]);

        return $dataProvider;
    }
}
