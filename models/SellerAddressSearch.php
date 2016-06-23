<?php

namespace inblank\showroom\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use inblank\showroom\models\SellerAddress;

/**
 * SellerAddressSearch represents the model behind the search form about `inblank\showroom\models\SellerAddress`.
 */
class SellerAddressSearch extends SellerAddress
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'seller_id', 'sort'], 'integer'],
            [['title', 'address', 'emails', 'phones', 'persons', 'schedule', 'description'], 'safe'],
            [['lat', 'lng'], 'number'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = SellerAddress::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>[
                'defaultOrder' => ['sort' => SORT_ASC],
            ],
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
            'seller_id' => $this->seller_id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'emails', $this->emails])
            ->andFilterWhere(['like', 'phones', $this->phones])
            ->andFilterWhere(['like', 'persons', $this->persons])
            ->andFilterWhere(['like', 'schedule', $this->schedule])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
