<?php

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $config = new Phalcon\Config\Adapter\Yaml("../app/config/adapter/yaml/config.yaml");
        $url =  $config->api->url;

        $prices = $this->getPrices($url);

        $this->view->prices = $prices;
    }

    private function getPrices(string $url): array
    {
        $prices = [];
        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $res=json_decode(curl_exec($ch),true);

        // close curl resource to free up system resources
        curl_close($ch);   

        $prices['USD'] = $res["bpi"]['USD']['rate'];
        $prices['EUR'] = $res["bpi"]['EUR']['rate'];

        return $prices;
    }
}