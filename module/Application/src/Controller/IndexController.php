<?php

declare(strict_types=1);

namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{   
    public $owApiKey = '109a3f5cf1f69be30e6bcf2656de76f4';
    public $apiEndpoint = 'https://api.openweathermap.org';

    /* ---- This function loads view ---- */

    public function indexAction(){
        return new ViewModel();
    }

    /* ---- This function provides JSON response for geo locations based on entered location input ---- */

    public function getAutoSuggestionsAction(){
        $request = $this->getRequest();
        $keyword = $request->getPost('keyword');
        $url = $this->apiEndpoint.'/geo/1.0/direct?q='.$keyword.'&limit=5&appid='.$this->owApiKey;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response_json = curl_exec($ch);
        curl_close($ch);
        $response=json_decode($response_json, true);
        $autoSuggestionsUniqueArr = [];
        $autoSuggestionsArr = [];
        if(is_array($response) && count($response) > 0){
            foreach($response as $key => $value){
                $keyGenerated = '';
                if(isset($value['name']) && !empty($value['name'])){
                    $keyGenerated .= $value['name'].', ';
                }
                if(isset($value['state']) && !empty($value['state'])){
                    $keyGenerated .= $value['state'].', ';
                }
                if(isset($value['country']) && !empty($value['country'])){
                    $keyGenerated .= $value['country'];
                }
                $keyGenerated = trim($keyGenerated);
                if(!in_array($keyGenerated, $autoSuggestionsUniqueArr)){
                    $autoSuggestionsUniqueArr[] = $keyGenerated;
                    $autoSuggestionsArr[] = $response[$key];
                }
            }
        }
        $response = array("status" => "success", "message" => "Data fetched successfully", "data" => $autoSuggestionsArr);
        echo json_encode($response);exit();
    }

    /* ---- This function provides JSON response of weather forcast for Current Weather, Next 24 Hours and Next 7 Days data fetched from 'openweathermap' apis. ---- */

    public function getWeatherForcastAction(){
        $request = $this->getRequest();

        $weatherFor = $request->getPost('weatherFor');
        $location = $request->getPost('location');
        $lat = $request->getPost('lat');
        $lon = $request->getPost('lon');
        if($weatherFor == "Current Weather"){
            $this->getCurrentWeatherForcast($weatherFor, $location, $lat, $lon);
        } else if($weatherFor == "Next 24 Hours"){
            $this->getNext24HoursWeatherForcast($weatherFor, $location, $lat, $lon);
        } else if($weatherFor == "Next 7 Days"){
            $this->getNext7DaysWeatherForcast($weatherFor, $location, $lat, $lon);
        }
    }

    /* ---- This function provides JSON response of weather forcast for Current Weather data fetched from 'openweathermap' apis. ---- */

    private function getCurrentWeatherForcast($weatherFor, $location, $lat, $lon){
        $url = $this->apiEndpoint.'/data/2.5/weather?units=metric&lat='.$lat.'&lon='.$lon.'&appid='.$this->owApiKey;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response_json = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response_json, true);
        $response = array("status" => "success", "message" => "Data fetched successfully", "data" => $response);
        echo json_encode($response);exit();
    }

    /* ---- This function provides JSON response of weather forcast for Next 24 Hours data fetched from 'openweathermap' apis. ---- */

    private function getNext24HoursWeatherForcast($weatherFor, $location, $lat, $lon){
        $url = $this->apiEndpoint.'/data/2.5/forecast?units=metric&cnt=9&lat='.$lat.'&lon='.$lon.'&appid='.$this->owApiKey;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response_json = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response_json, true);
        $response = array("status" => "success", "message" => "Data fetched successfully", "data" => $response);
        echo json_encode($response);exit();
    }

    /* ---- This function provides JSON response of weather forcast for Next 7 Days data fetched from 'openweathermap' apis. ---- */

    private function getNext7DaysWeatherForcast($weatherFor, $location, $lat, $lon){
        $url = $this->apiEndpoint.'/data/2.5/forecast?units=metric&cnt=40&lat='.$lat.'&lon='.$lon.'&appid='.$this->owApiKey;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response_json = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response_json, true);
        $response = array("status" => "success", "message" => "Data fetched successfully", "data" => $response);
        echo json_encode($response);exit();
    }
}
