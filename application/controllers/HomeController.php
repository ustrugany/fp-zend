<?php

class HomeController extends My_Controller_Action
{
    public function init()
    {
        parent::init();
    }
    
    /*
     * Glowna akcja
     */
    public function indexAction()
    {
        $this->fetchAndPassCountries();
    }


    /**
     * Pobiera miasta z bazy danych w danym porzadku i przekazuje do widoku
     */
    public function fetchAndPassCountries()
    {
        $Country = new Application_Model_DbTable_Country;
        $this->view->countries = $Country->fetchAllOrderedByName();
    }
    
    /**
     * Pobiera miasta dla danego kraju przekazanego
     * w zadaniu 
     */
    public function getCitiesAction()
    {
        $countryName = $this->getRequest()->get('country');
        $cities = null;
        
        if(is_string($countryName)){
            $cities = $this->getCitiesByCountryName($countryName);
        }
        
        if(is_null($cities)){
            $this->view->success = false;
        }
        
        $this->_logger->info("getCitiesAction[$countryName]");
        $this->_logger->info($cities);
        
        $this->view->cities = $cities;
    }
    
    /**
     * Akcja pobierania pogody dla danego miasta 
     */
    public function getCityWeatherAction()
    {
        $countryName = $this->getRequest()->get('country');
        $cityName = $this->getRequest()->get('city');
        
        try{
            $cityWeather = $this->getCityWeatherWithAPI($cityName, $countryName);
            $cityWeather = $this->view->partial('weather/info.phtml', array('weather' => $cityWeather));
            $CityWeather = new Application_Model_DbTable_CityWeather;
            $cityWeather = $CityWeather->createCityWeather($cityName, $cityWeather);
            $this->view->sourceInformation = "Źródło: webservice";
        } catch(Exception $e){
            $this->_API_unavailable = true;
            $this->_logger->err($e->getMessage());
            $cityWeather = $this->getCityWeatherFromDb($cityName);
            $this->view->sourceInformation = "Źródło: Baza danych ({$cityWeather->insert_time})";
        }
        
        $weatherToPassToView = $cityWeather->value;;
        
        if(is_null($cityWeather)){
            $weatherToPassToView = "Przepraszamy. API niedostępne/brak informacji pogodowych dla \"$cityName\" w bazie danych.";
            $this->view->success = false;
        } else {
            $weatherToPassToView = $cityWeather->value;
        }
        
        $this->_logger->info("getCityWeatherAction[$cityName, $countryName]: {$weatherToPassToView}");
        $this->view->weather = $weatherToPassToView;
    }
}

