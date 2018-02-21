<?php

// test commit for branch slim2
require 'vendor/autoload.php';




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
 * @since 2.10.2015
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
 * @since 25-01-2016
 */
$app->get("/fillMainDefinitions_sysSpecificDefinitions/", function () use ($app ) {


    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');

    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillMainDefinitions(array('language_code' => $languageCode
    ));

    $menus = array();
    $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => $menu["name"],
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {        
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => $menu["name"],
                "value" => intval($menu["id"]),
                "selected" => false,
                "description" => $menu["name_eng"],
               // "imageSrc" => ""
            );
        }
    }
 
    $app->response()->header("Content-Type", "application/json");
 

    $app->response()->body(json_encode($menus));
});
/**
 *  * Okan CIRAN
 * @since 25-01-2016
 */
$app->get("/fillFullDefinitions_sysSpecificDefinitions/", function () use ($app ) {

    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');

    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }
   
    $resCombobox = $BLL->fillFullDefinitions(array('language_code' => $languageCode
    ));

    
    $menus = array();
    $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => $menu["name"],
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {        
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => $menu["name"],
                "value" => intval($menu["id"]),
                "selected" => false,
                "description" => $menu["name_eng"],
              //  "imageSrc" => ""
            );
        }
    }

    $app->response()->header("Content-Type", "application/json");
  
    $app->response()->body(json_encode($menus));
});


/**
 *  * Okan CIRAN
 * @since 25-01-2016
 */
$app->get("/fillCommunicationsTypes_sysSpecificDefinitions/", function () use ($app ) {

    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');

    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }
 
    $resCombobox = $BLL->fillCommunicationsTypes(array('language_code' => $languageCode
    ));
    $menus = array();
    $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => $menu["name"],
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => $menu["name"],
                "value" => intval($menu["id"]),
                "selected" => false,
                "description" => $menu["name_eng"],
            //    "imageSrc" => ""
            );
        }
    }

    $app->response()->header("Content-Type", "application/json");


    $app->response()->body(json_encode($menus));

    //$app->response()->body(json_encode($flows));
});

/**
 *  * Okan CIRAN
 * @since 25-01-2016
 */
$app->get("/fillBuildingType_sysSpecificDefinitions/", function () use ($app ) {

    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');

    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }

    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillBuildingType(array('language_code' => $languageCode
    ));

        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => $menu["name"],
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {     
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => $menu["name"],
                "value" => intval($menu["id"]),
                "selected" => false,
                "description" => $menu["name_eng"],
                //"imageSrc" => ""
            );
        }
    }

    $app->response()->header("Content-Type", "application/json");
 

    $app->response()->body(json_encode($menus));
});

/**
 *  * Okan CIRAN
 * @since 25-01-2016
 */
$app->get("/fillOwnershipType_sysSpecificDefinitions/", function () use ($app ) {

    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');

    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillOwnershipType(array('language_code' => $languageCode
    ));

        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => $menu["name"],
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {       
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => $menu["name"],
                "value" => intval($menu["id"]),
                "selected" => false,
                "description" => $menu["name_eng"],
               // "imageSrc" => ""
            );
        }
    }
    $app->response()->header("Content-Type", "application/json");
 
    $app->response()->body(json_encode($menus));
});


/**
 *  * Okan CIRAN
 * @since 25-01-2016
 */
$app->get("/fillPersonnelTypes_sysSpecificDefinitions/", function () use ($app ) {

    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');

    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillPersonnelTypes(array('language_code' => $languageCode
    ));

        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => $menu["name"],
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {      
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => $menu["name"],
                "value" =>  intval($menu["id"]),
                "selected" => false,
                "description" => $menu["name_eng"],
                //"imageSrc" => ""
            );
        }
    }

    $app->response()->header("Content-Type", "application/json");
 
    $app->response()->body(json_encode($menus));
});


/**
 *  * Okan CIRAN
 * @since 25-01-2016
 */
$app->get("/fillAddressTypes_sysSpecificDefinitions/", function () use ($app ) {

    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');

    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillAddressTypes(array('language_code' => $languageCode
    ));

        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => $menu["name"],
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {       
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => $menu["name"],
                "value" =>  intval($menu["id"]),
                "selected" => false,
                "description" => $menu["name_eng"],
               // "imageSrc" => ""
            );
        }
    }

    $app->response()->header("Content-Type", "application/json");

    $app->response()->body(json_encode($menus));
});

/**
 *  * Okan CIRAN
 * @since 15-07-2016
 */
$app->get("/fillSexTypes_sysSpecificDefinitions/", function () use ($app ) {
    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');
    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillSexTypes(array('language_code' => $languageCode
    ));

        $menus = array();
        $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); 
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => $menu["name"],
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {       
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => $menu["name"],
                "value" =>  intval($menu["id"]),
                "selected" => false,
                "description" => $menu["name_eng"],
               // "imageSrc" => ""
            );
        }
    }

    $app->response()->header("Content-Type", "application/json");

    $app->response()->body(json_encode($menus));
});
 
/**
 *  * Okan CIRAN
 * @since 15-07-2016
 */
$app->get("/fillVarYokGecTypes_sysSpecificDefinitions/", function () use ($app ) {
    $BLL = $app->getBLLManager()->get('sysSpecificDefinitionsBLL');
    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    }
    $componentType = 'ddslick';
    if (isset($_GET['component_type'])) {
        $componentType = strtolower(trim($_GET['component_type']));
    }

    $resCombobox = $BLL->fillVarYokGecTypes(array('language_code' => $languageCode
    ));

        $menus = array();
  //      $menus[] = array("text" => "Lütfen Seçiniz", "value" => 0, "selected" => true, "imageSrc" => "", "description" => "Lütfen Seçiniz",); //
    if ($componentType == 'bootstrap') {
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "id" => $menu["id"],
                "text" => $menu["name"],
                "state" => $menu["state_type"], //   'closed',
                "checked" => false,
                "attributes" => array("notroot" => true, "active" => $menu["active"]),
            );
        }
    } else if ($componentType == 'ddslick') {       
        foreach ($resCombobox as $menu) {
            $menus[] = array(
                "text" => $menu["name"],
                "value" =>  intval($menu["id"]),
                "selected" => false,
                "description" => $menu["name_eng"],
               // "imageSrc" => ""
                "alan1" => html_entity_decode($menu["alan1"]),
                "alan2" => html_entity_decode($menu["alan2"]),
                "alan3" => html_entity_decode($menu["alan3"]),
                "alan4" => html_entity_decode($menu["alan4"]),
                 //    "alan5" => html_entity_decode($menu["alan5"]),
                 //    "alan6" => html_entity_decode($menu["alan6"]),
                 //    "alan7" => html_entity_decode($menu["alan7"]),
                 //    "alan8" => html_entity_decode($menu["alan8"]),
                 //    "alan9" => html_entity_decode($menu["alan9"]),
                //     "alan10" => html_entity_decode($menu["alan10"]),
            );
        }
    }

    $app->response()->header("Content-Type", "application/json");

    $app->response()->body(json_encode($menus));
});


/**
 *  * Okan CIRAN
 * @since 15-07-2016
 */
$app->get("/getserverkontrol_sysSpecificDefinitions/", function () use ($app ) {
    
    $languageCode = 'tr';
    if (isset($_GET['language_code'])) {
        $languageCode = strtolower(trim($_GET['language_code']));
    } 
    
    $menus = array();  
    $menus[] = array(
        "id" => '647',
        "value" =>  '200', 
        "description" => 'Ok', 
    );
      
    $app->response()->header("Content-Type", "application/json"); 
    $app->response()->body(json_encode($menus));
});



$app->run();
