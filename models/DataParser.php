<?php
/**
 * Created by PhpStorm.
 * User: olimpiadalysenko
 * Date: 17.02.17
 * Time: 18:30
 */

namespace app\models;


use yii\base\Model;
use GuzzleHttp\Client;
use phpQuery;

class DataParser extends Model
{
    private $countryCode;
    private $pageHtml;
    private $callings;
    private $sms;
    private $rates = [
        'country' => [],
        'favourite' => [],
        'mobile' => [],
        'sms' => [],
    ];

    public function __construct($countryCode = 'in')
    {
        $this->countryCode = $countryCode;
        $this->pageHtml = $this->getPageHtml();
        $this->callings = $this->getCallings();
        $this->sms = $this->getSms();
        $this->setRates();
    }

    private function getPageHtml(){
        $client = new Client();
        $res = $client->request('GET', $this->getRequestUrl());
        $body = $res->getBody();

        return phpQuery::newDocumentHTML($body);
    }

    private function getCallings(){
        return $this->pageHtml->find('.pstn-rates > .row');
    }

    private function getSms(){
        return $this->pageHtml->find('.sms-rates > .row');
    }

    private function getRequestUrl() {
        return 'https://apps.skypeassets.com/offers/credit/rates?_accept=1.0&currency=USD&destination='
            . strtoupper($this->countryCode) . '&language=en&origin=UA&seq=18';
    }

    private function setRates()
    {
        for ($i=1; $i < $this->callings->length; $i++) {

            $key = trim($this->callings->find(".unit9Mobile:eq( $i ) > p")->text());
            $value = trim($this->callings->find(".unit3Mobile:eq( $i ) > p")->text());

            if($i == 1) {
                $this->rates['country'][$key] = $value;
            }elseif (strstr($key, 'Mobile')) {
                $this->rates['mobile'][$key] = $value;
            }else {
                $this->rates['favourite'][$key] = $value;
            }
        }

        for ($i=1; $i < $this->sms->length; $i++) {
            $key = trim($this->sms->find(".unit9Mobile:eq( $i ) > p")->text());
            $this->rates['sms'][$key] = trim($this->sms->find(".unit3Mobile:eq( $i ) > p")->text());
        }
    }
    public function getRates(){
        return $this->rates;
    }
}