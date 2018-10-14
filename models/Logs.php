<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%logs}}".
 *
 * @property int $id
 * @property string $ip Айпи с которого пришел запрос
 * @property string $data_time Дата и время запроса
 * @property string $req Тело запроса
 * @property string $res Тело ответа
 */
class Logs extends \yii\db\ActiveRecord
{
    public $datetime_min;
    public $datetime_max;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'logs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip', 'data_time', 'req', 'res','code'], 'required'],
            [['data_time'], 'safe'],
            [['ip'], 'string', 'max' => 20],
            [['req', 'res'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'Айпи',
            'data_time' => 'Дата и время запроса',
            'req' => 'Тело запроса',
            'res' => 'Тело ответа',
            'type' => 'Тип запроса',
            'code' => 'Код ответа'
        ];
    }

    public static function myFields(){
        return ['ip','data_time','req','res','type','code'];
    }

    public function writeLogsInArray(string $path){
        $file = fopen($path, "r");
        if ($file) {
            $i = 0;
            while (($buffer = fgets($file, 4096)) !== false) {
                $reqResType = self::getReqResBodyAndType($buffer);
                $newLineInDb[$i]['ip'] = stristr(trim(stristr($buffer," ", false))," ", true);
                $newLineInDb[$i]['data_time'] = self::getDate($file,$buffer);
                $newLineInDb[$i]['req'] = $reqResType['req'];
                $newLineInDb[$i]['res'] = $reqResType['res'];
                $newLineInDb[$i]['type'] = $reqResType['type'];
                $newLineInDb[$i]['code'] = self::getStatusCode($buffer);
                $i++;
            }
            if (!feof($file)) {
                echo "Ошибка: fgets() неожиданно потерпел неудачу\n";
            }
            fclose($file);
        }
        return $newLineInDb;
    }

    private static function getDate($file,string $str){
        $date = stristr(stristr(ltrim(stristr($str,"[", false),'['),"]", true)," ",true);
        $p = strpos($date,':');
        $date[$p] = " ";
        $date = date("Y-m-d H:i:s",strtotime(str_replace("/","-",$date)));
        return $date;
    }

    private static function getReqResBodyAndType(string $str){
        $startReqBody = strpos($str, 'http:');
        if($startReqBody === false)
            $bodyReq = "-";
        else{
            $buffReqBody = substr($str,$startReqBody);
            $bodyReq = stristr($buffReqBody,'"',true);
        }
        $startResBody = strpos($str, '"');
        $buffResBody = substr($str,$startResBody+1);
        $bodyRes = stristr($buffResBody,'"',true);
        $type = stristr($bodyRes," ",true);
        $responseArray = [
            'req' => $bodyReq,
            'res' => $bodyRes,
            'type' => $type
        ];
        return $responseArray;
    }

    public function writeLogsInDb(array $logs){
        if($logs == null)
            return "Ошибка! Вы передали пустой массив!";
        $sql = Yii::$app->db->createCommand()->batchInsert(self::tableName(), self::myFields(), $logs)->execute();
    }

    private static function getStatusCode(string $str){
        $find = '/[ ][1-5][0-9][0-9][ ]/';
        $matches = [];
        preg_match($find,$str,$matches);
        return $matches[0];
    }
}