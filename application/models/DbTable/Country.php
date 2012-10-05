<?php

class Application_Model_DbTable_Country extends Zend_Db_Table_Abstract
{
    protected $_rowClass = 'Application_Model_DbTable_Country_Row';
    protected $_dependantTables = array('Application_Model_DbTable_City');
    protected $_name = 'countries';

    
    public function fetchAllOrderedByName()
    {
        return $this->fetchAll(null, 'name', 'ASC');
    }
    
    /**
     * UWAGA: LEPIEJ PRZENIESC FUNKCJONALNOSC
     * TEJ METODY DO OBIEKTU REKORDU,
     * ABY ZACHOWAC ZASADE JEDNA METODA - JEDNO ZADANIE
     * NIE TRZEBA BY SIE UPEWNIAC CZY REKORD KRAJU ISTNIEJE
     * Pobiera miasta na podstawie nzwy kraju
     * @param type Application_Model_DbTable_Country_Row
     * @return type 
     * @todo usunac
     */
    public function getCitiesByCountryName($country)
    {
        $cities = null;
        $citiesResult = $country->findDependentRowset('Application_Model_DbTable_City');
        if($citiesResult->count())
        {
            $cities = $citiesResult;
        }
        return $cities;
    }
    
    /**
     * Pobiera miasta na podstawie nzwy kraju, zwraca tablice
     * @param type $country
     * @return type 
     */
    public function getCitiesByCountryNameAsArray($country)
    {
        $cities = $this->getCitiesByCountryName($country);
        if($cities instanceof Zend_Db_Table_Rowset)
        {
            $cities = $cities->toArray();
        }
        return $cities;
    }
    
    /**
     * Wyszukuje kraj na podstawie nazwy
     * @param type $countryName
     * @return type 
     */
    public function findCountryByName($countryName)
    {
        $select = $this->select()->where('LOWER(name) = :name')->limit(1);
        $select->bind(array('name' => $countryName));
        return $this->fetchAll($select)->current();
    }
    
    /**
     * Usuwa istniejace w bazie kraje o danej nazwie i wstawia nowe
     * @param type $countryName
     * @return Application_Model_DbTable_Country_Row
     * @throws Zend_Db_Statement_Exception 
     */
    public function replaceCountry($countryName)
    {
        $countryRow = null;
        $this->getAdapter()->beginTransaction();
        try{
            $tmpCountryRow = $this->findCountryByName($countryName);
            if($tmpCountryRow){
                $this->deleteCountryCities($tmpCountryRow->id);
                $this->deleteCountryById($tmpCountryRow->id);
            }

            $country = array('name' => $countryName);
            $countryRow = $this->createRow($country);
            $countryRow->save();
            $this->getAdapter()->commit();
            
        } catch(Zend_Db_Statement_Exception $exception){
            $this->getAdapter()->rollBack();
            throw $exception;
        }
        
        return $countryRow;
    }
    
    /**
     * Usuwa kraje o danej nazwie
     * @param type $countryName
     * @return int
     */
    public function deleteCountryByName($countryName)
    {
        $deleted = 0;
        $lowercasedCountryName = strtolower($countryName);
        $deleted = $this->delete(array(
            'LOWER(name) = ?' => $lowercasedCountryName
        ));
        return $deleted;
    }
    
    /**
     * Usuwa kraj o danym id
     * @param type $countryId
     * @return int
     */
    public function deleteCountryById($countryId)
    {
        $deleted = 0;
        $deleted = $this->delete(array(
            'id = ?' => $countryId
        ));
        return $deleted;
    }
    
    /**
     * Usuwa miasta nalezacego do danego kraju
     * @param type $countryId
     * @return type 
     */
    public function deleteCountryCities($countryId)
    {
        $City = new Application_Model_DbTable_City;
        $deleted = $City->delete(array(
            'country_id' => $countryId
        ));
        return $deleted;
    }
    
    /**
     * Tworzy miasta dla danego kraju na podstawie tablicy
     * @param Application_Model_DbTable_Country_Row $country
     * @param type $cities
     * @return array(Application_Model_DbTable_City_Row)
     * @throws Zend_Db_Statement_Exception 
     */
    public function createCountryCitiesFromArray($country, $cities)
    {
        $citiesRows = array();   
        $this->getAdapter()->beginTransaction();
        try{
            $City = new Application_Model_DbTable_City;
            
            foreach($cities as $city){
                $data = array(
                        'name' => $city,
                        'country_id' => $country->id
                );
                $city = $City->createRow($data);
                $city->save();
                $citiesRows[] = $city;
            }
            $this->getAdapter()->commit();
        } catch(Zend_Db_Statement_Exception $exception) {
            $this->getAdapter()->rollBack();
            throw $exception;
        }
        return $citiesRows;
    }
}

