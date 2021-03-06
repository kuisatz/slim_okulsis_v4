<?php
/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */
namespace DAL\Factory\PDO;


/**
 * Class using Zend\ServiceManager\FactoryInterface
 * created to be used by DAL MAnager
 * @author Okan CIRAN
 * created date : 25.10.2016
 */
class MblSinavlarFactory  implements \Zend\ServiceManager\FactoryInterface{
    
    public function createService(\Zend\ServiceManager\ServiceLocatorInterface $serviceLocator) {
        $MblSinavlar  = new \DAL\PDO\MblSinavlar();  
        $slimapp = $serviceLocator->get('slimapp');            
        $MblSinavlar -> setSlimApp($slimapp);
        return $MblSinavlar;
      
    } 
    
}