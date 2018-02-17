<?php

// test commit for branch slim2
require 'vendor/autoload.php';


use \Services\Filter\Helper\FilterFactoryNames as stripChainers;

/* $app = new \Slim\Slim(array(
  'mode' => 'development',
  'debug' => true,
  'log.enabled' => true,
  )); */

$app = new \Slim\SlimExtended(array(
    'mode' => 'development',
    'debug' => true,
    'log.enabled' => true,
    'log.level' => \Slim\Log::INFO,
    'exceptions.rabbitMQ' => true,
    'exceptions.rabbitMQ.logging' => \Slim\SlimExtended::LOG_RABBITMQ_FILE,
    'exceptions.rabbitMQ.queue.name' => \Slim\SlimExtended::EXCEPTIONS_RABBITMQ_QUEUE_NAME
        ));

/**
 * "Cross-origion resource sharing" kontrolüne izin verilmesi için eklenmiştir
 * @author Okan CIRAN
 * @since 25.10.2017
 */
$res = $app->response();
$res->header('Access-Control-Allow-Origin', '*');
$res->header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
$app->add(new \Slim\Middleware\MiddlewareInsertUpdateDeleteLog());
$app->add(new \Slim\Middleware\MiddlewareHMAC());
$app->add(new \Slim\Middleware\MiddlewareSecurity());
$app->add(new \Slim\Middleware\MiddlewareMQManager());
$app->add(new \Slim\Middleware\MiddlewareBLLManager());
$app->add(new \Slim\Middleware\MiddlewareDalManager());
$app->add(new \Slim\Middleware\MiddlewareServiceManager());
$app->add(new \Slim\Middleware\MiddlewareMQManager());

 

/**
 *  * Okan CIRAN
 * @since 15.01.2018
 */
$app->get("/GetSinavTurleri_mblsinavlar/", function () use ($app ) {
    $stripper = $app->getServiceManager()->get('filterChainerCustom');
    $stripChainerFactory = new \Services\Filter\Helper\FilterChainerFactory();
    $BLL = $app->getBLLManager()->get('mblSinavlarBLL'); 
     
    $vLanguageID = NULL;
    if (isset($_GET['languageID'])) {
        $stripper->offsetSet('languageID', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['languageID']));
    } 
    
    $vSinavTuruId = NULL;
    if (isset($_GET['sinavTuruId'])) {
        $stripper->offsetSet('sinavTuruId', $stripChainerFactory->get(stripChainers::FILTER_ONLY_NUMBER_ALLOWED, 
                                                                $app, 
                                                                $_GET['sinavTuruId']));
    } 
    $stripper->strip(); 
    if ($stripper->offsetExists('languageID')) 
        {$vLanguageID = $stripper->offsetGet('languageID')->getFilterValue(); }  
    if ($stripper->offsetExists('sinavTuruId')) 
        {$vSinavTuruId = $stripper->offsetGet('sinavTuruId')->getFilterValue(); }   
   
    $resDataInsert = $BLL->getSinavTurleri(array(    
                                        'LanguageID' => $vLanguageID, 
                                        'KurumGrupID' => $vSinavTuruId, 
                                           ) ); 
  
    $menus = array();
    foreach ($resDataInsert as $menu){
        $menus[]  = array( 
            "id" => $menu["id"], 
            "aciklama" => html_entity_decode($menu["aciklama"]), 
            "description_eng" => html_entity_decode($menu["description_eng"]),  
        );
    }
    
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
     
}
);
  


 
  
$app->run();
