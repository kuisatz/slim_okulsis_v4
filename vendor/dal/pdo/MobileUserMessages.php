<?php

/**
 *  Framework 
 *
 * @link       
 * @copyright Copyright (c) 2017
 * @license   
 */

namespace DAL\PDO;

/**
 * Class using Zend\ServiceManager\FactoryInterface
 * created to be used by DAL MAnager
 * @
 * @author Okan CIRAN
 */
class MobileUserMessages extends \DAL\DalSlim {

    /**
     * @author Okan CIRAN
     * @  
     * @version v 1.0  15-01-2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function delete($params = array()) {
        try {
                            
        } catch (\PDOException $e /* Exception $e */) {
                            
        }
    }

    /**
     * @author Okan CIRAN
     * @  
     * @version v 1.0  15-01-2018 
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function getAll($params = array()) {
        try {
                            
        } catch (\PDOException $e /* Exception $e */) {
                            
        }
    }

    /**
     * @author Okan CIRAN
     * @ sys_acl_action_rrp tablosunda name sutununda daha önce oluşturulmuş mu? 
     * @version v 1.0 11.08.2016
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function haveRecords($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactory');
            $addSql = "";
            if (isset($params['id'])) {
                $addSql = " AND id != " . intval($params['id']) . " ";
            }
            $sql = " 
           SELECT  
                name as name , 
                '" . $params['name'] . "' as value , 
                name ='" . $params['name'] . "' as control,
                concat(name , ' daha önce kayıt edilmiş. Lütfen Kontrol Ediniz !!!' ) as message                             
            FROM sys_acl_action_rrp        
            WHERE LOWER(REPLACE(name,' ','')) = LOWER(REPLACE('" . $params['name'] . "',' ','')) 
                AND resource_id = ".intval($params['resource_id'])."
                ". $addSql . " 
               AND deleted =0   
                               ";
            $statement = $pdo->prepare($sql);
            //   echo debugPDO($sql, $params);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);

            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }

    /**
     * @author Okan CIRAN
     * @ sys_acl_action_rrp tablosuna yeni bir kayıt oluşturur.  !!
     * @version v 1.0  15-01-2018
     * @return array
     * @throws \PDOException
     */
    public function insert($params = array()) {
        try {
                            
        } catch (\PDOException $e /* Exception $e */) {
                            
        }
    }    

    /**
     * @author Okan CIRAN
     * sys_acl_action_rrp tablosuna parametre olarak gelen id deki kaydın bilgilerini günceller   !!
     * @version v 1.0  15-01-2018
     * @param type $params
     * @return array
     * @throws \PDOException
     */
    public function update($params = array()) {
        try {
                            
                            
        } catch (\PDOException $e /* Exception $e */) {
                            
        }
    }

    /**
     * @author Okan CIRAN
     * @ Gridi doldurmak için sys_acl_action_rrp tablosundan kayıtları döndürür !!
     * @version v 1.0  15-01-2018
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillGrid($args = array()) {
                            
        try {
                            
        } catch (\PDOException $e /* Exception $e */) {
               }
    }

    /**     
     * @author Okan CIRAN
     * @ Gridi doldurmak için sys_acl_action_rrp tablosundan çekilen kayıtlarının kaç tane olduğunu döndürür   !!
     * @version v 1.0  15-01-2018
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function fillGridRowTotalCount($params = array()) {
        try {
                            
        } catch (\PDOException $e /* Exception $e */) {
             }
    }
                            
    /** 
     * @author Okan CIRAN
     * @ donem mesajları  !!
     * @version v 1.0  15-01-2018
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function getSemesters($params = array()) {
        try {
            $pdo = $this->slimApp->getServiceManager()->get('pgConnectFactoryMobil'); 
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
            } 
            $sql = "  
            SET NOCOUNT ON;  
            SELECT * FROM (           
                SELECT  
                    0 AS id,
                    COALESCE(NULLIF(ax.description,''),a.description_eng) AS aciklama, 
                    a.description_eng
                FROM BILSANET_MOBILE.dbo.sys_specific_definitions a
                LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0
                LEFT JOIN BILSANET_MOBILE.dbo.sys_specific_definitions  ax on (ax.language_parent_id = a.id or ax.id = a.id ) and  ax.language_id= lx.id  
                WHERE a.main_group = 1 and a.first_group =13 and
                    a.language_parent_id =0 
            UNION
                SELECT    
                    av.first_group as id ,
                    upper( COALESCE(NULLIF(avx.description,''),av.description_eng)) as aciklama,  
                    upper(av.description_eng) 
                FROM BILSANET_MOBILE.dbo.Mobile_User_Messages av
                LEFT JOIN BILSANET_MOBILE.dbo.sys_language lx ON lx.id =".$languageIdValue." AND lx.deleted =0 AND lx.active =0
                LEFT JOIN BILSANET_MOBILE.dbo.Mobile_User_Messages  avx on (avx.language_parent_id = av.id or avx.id = av.id ) and  avx.language_id= lx.id
                WHERE av.main_group = 7 and av.language_id = 647 
             ) as  ssss
             ORDER BY ssss.id
            SET NOCOUNT OFF; 
                 "; 
            $statement = $pdo->prepare($sql);   
  //  echo debugPDO($sql, $params);
            $statement->execute(); 
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $errorInfo = $statement->errorInfo();
            if ($errorInfo[0] != "00000" && $errorInfo[1] != NULL && $errorInfo[2] != NULL)
                throw new \PDOException($errorInfo[0]);
            return array("found" => true, "errorInfo" => $errorInfo, "resultSet" => $result);
        } catch (\PDOException $e /* Exception $e */) {    
            return array("found" => false, "errorInfo" => $e->getMessage());
        }
    }
    
    
                              
}
