<?php


namespace app\controllers;


use yii\db\Query;
use yii\web\Controller;

class TestController extends Controller
{
    public function actionIndex(){
        $list = (new Query())->from("test")->all();
        var_dump($list);
        exit();
        return "111";
    }
}