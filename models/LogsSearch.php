<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Logs;
use kartik\daterange\DateRangeBehavior;

/**
 * LogsSearch represents the model behind the search form of `app\models\Logs`.
 */
class LogsSearch extends Logs
{
    public $minDate;
    public $maxDate;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'code'], 'integer'],
            ['data_time','string'],
            [['ip', 'req', 'res', 'type'], 'safe'],
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
        $query = Logs::find();

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

        $minMaxArray = explode(" - ",$this->data_time);
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'code' => $this->code,
        ]);

        $query->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'req', $this->req])
            ->andFilterWhere(['like', 'res', $this->res])
            ->andFilterWhere(['like', 'type', $this->type]);
        if($minMaxArray[0] != "" && $minMaxArray[1] == ""){
            if(strlen($minMaxArray[0]) < 19)
                $query->andFilterWhere(['like', 'data_time', $minMaxArray[0]]);
            else
                $query->andFilterWhere(['=', 'data_time', $minMaxArray[0]]);
        }
        if($minMaxArray[0] != "")
            $query->andFilterWhere(['>=', 'data_time', $minMaxArray[0]]);
        if($minMaxArray[1] != "")
            $query->andFilterWhere(['<=', 'data_time', $minMaxArray[1]]);
        
        return $dataProvider;
    }
}
