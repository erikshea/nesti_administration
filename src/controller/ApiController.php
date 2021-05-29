<?php
class ApiController extends BaseController
{
    public function dispatch($actionSlug, $options= [])
    {
        header('Content-Type: application/json');
        parent::dispatch($actionSlug, $options);
    }

    public function actionTagsApi()
    {
        $tags = EntityUtil::toArray(TagDao::findAll());
        //echo $this->encodeJson($tags);
        //echo '[{"idTag":"1","name":"Facile a faire"},{"idTag":"2","name":"Traditionnelles"},{"idTag":"3","name":"C est de saison"},{"idTag":"4","name":"Sans gluten"}]';


        echo '[{"idTag":"1","name":"Dr. Edwin Orn"}]';
    }



    private function encodeJson($var){
        return $this->cleanupJson(json_encode($var));
    }

    private function cleanupJson($text){
        $bom = pack('H*','EFBBBF');
        $text = preg_replace("/^$bom/", '', $text);
        return $text;
    }
}
