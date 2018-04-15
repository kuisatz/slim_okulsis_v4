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
class MblSinavRaporlari extends \DAL\DalSlim {

    /**
     * @author Okan CIRAN
     * @  
     * @version v 1.0  15-02-2018
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
     * @version v 1.0  15-02-2018 
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
            FROM SNV_SinavTurleri        
            WHERE LOWER(REPLACE(name,' ','')) = LOWER(REPLACE('" . $params['name'] . "',' ','')) 
                AND resource_id = ".intval($params['resource_id'])."
                ". $addSql . " 
               AND deleted =0   
                               ";
            $statement = $pdo->prepare($sql);
            //   
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
     * @version v 1.0  15-02-2018
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
     * @version v 1.0  15-02-2018
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
     * @version v 1.0  15-02-2018
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
     * @version v 1.0  15-02-2018
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
     * @ sınav turleri  !!
     * @version v 1.0  15-02-2018
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function getSinavTurlerikullanilmiyor($params = array()) {
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
  //  
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
     * @ öğrenci seviye tespit sınavı ilkokul-ortaokul raporu   
     * @version v 1.0  20.02.2018
     * @param array | null $args
     * @return array
     * @throws \PDOException
     */
    public function StsIoRpt($params = array()) {
        try {
            $cid = -1;
            if ((isset($params['Cid']) && $params['Cid'] != "")) {
                $cid = $params['Cid'];
            } 
            $did = NULL;
            if ((isset($params['Did']) && $params['Did'] != "")) {
                $did = $params['Did'];
            }
            $dbnamex = 'dbo.';
            $dbConfigValue = 'pgConnectFactory';
            $dbConfig =  MobilSetDbConfigx::mobilDBConfig( array( 'Cid' =>$cid,'Did' =>$did,));
            if (\Utill\Dal\Helper::haveRecord($dbConfig)) {
                $dbConfigValue =$dbConfigValue.$dbConfig['resultSet'][0]['configclass']; 
                if ((isset($dbConfig['resultSet'][0]['configclass']) && $dbConfig['resultSet'][0]['configclass'] != "")) {
                   $dbnamex =$dbConfig['resultSet'][0]['dbname'].'.'.$dbnamex;
                    }   
            }     
            
            $pdo = $this->slimApp->getServiceManager()->get($dbConfigValue);  
            
            $SinifID ='CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC';
            $findOgrenciseviyeIDValue= null ; 
            $findOgrenciseviyeID = $this->findOgrenciseviyeID(
                            array( 'KisiID' =>$KisiID,  'Cid' =>$cid,'Did' =>$did, ));
            if (\Utill\Dal\Helper::haveRecord($findOgrenciseviyeID)) {
                $findOgrenciseviyeIDValue = $findOgrenciseviyeID ['resultSet'][0]['OgrenciseviyeID'];
                $SinifID = $findOgrenciseviyeID ['resultSet'][0]['SinifID'];
            }  
            
            $OgrenciSeviyeID = $findOgrenciseviyeIDValue;
           
            $SinavID = 'CCCCCCCC-CCCC-CCCC-CCCC-CCCCCCCCCCCC'; 
            if ((isset($params['SinavID']) && $params['SinavID'] != "")) {
                $SinavID = $params['SinavID'];
            } 
            
            $lid = NULL;
            $languageIdValue = 647;
            if (isset($params['LanguageID']) && $params['LanguageID'] != "") {
                $languageIdValue = $params['LanguageID'];
                if ($languageIdValue!= 647 ) {$lid = 385;}
            } 
            
              
        $sql =   
           " SET NOCOUNT ON; 
            declare 
            @SinavID UNIQUEIDENTIFIER,
            @SinavOgrenciID UNIQUEIDENTIFIER, 
            @OgrenciSeviyeID UNIQUEIDENTIFIER;
  
            /* set @SinavOgrenciID = '80ED025D-C23D-4A0C-B214-12127A291084'; */ 
            set @SinavID = '".$SinavID."'; /* 'F41A8E18-EC46-46A7-818C-A16642C877C0'; */ 
            set  @OgrenciSeviyeID ='".$OgrenciSeviyeID."'; /* '55F80B5A-2CB3-49D1-AA79-25F94AFFBE27'; */
  
            IF OBJECT_ID('tempdb..#tmpSinif') IS NOT NULL DROP TABLE #tmpSinif;
            IF OBJECT_ID('tempdb..#Siralamalar') IS NOT NULL DROP TABLE #Siralamalar; 
            IF OBJECT_ID('tempdb..#OkulOrtalama') IS NOT NULL DROP TABLE #OkulOrtalama;
            IF OBJECT_ID('tempdb..#teksatirbilgiler') IS NOT NULL DROP TABLE #teksatirbilgiler;
            
            SELECT op.SinavOgrenciID ,
                Snf.SeviyeID ,
                SNF.SinifID ,
                SNF.SinifKodu ,
                PST.PuanSiralamaTipID ,
                SUM(OP.Puan) AS TopPuan ,
                S.sinavID,
                s.sinavkodu,
                OPS.Sira ,
                OPS.SinavaGirenOgrenciSayisi,
                s.SinavAciklamasi 
            INTO #tmpSinif  
            FROM ".$dbnamex."OD_SinavPuanTipleri SPT
            INNER JOIN ".$dbnamex."SNV_Sinavlar S ON S.SinavID = SPT.SinavID
            INNER JOIN ".$dbnamex."OD_OgrenciPuanlari OP ON OP.SinavPuanTipID = SPT.SinavPuanTipID
            INNER JOIN ".$dbnamex."OD_PuanTipleri PT ON PT.PuanTipID = SPT.PuanTipID
            INNER JOIN ".$dbnamex."OD_OgrenciPuanSiralari OPS ON OPS.OgrenciPuanID = OP.OgrenciPuanID
            INNER JOIN ".$dbnamex."OD_PuanSiralamaTipleri PST ON PST.PuanSiralamaTipID = OPS.PuanSiralamaTipID
            INNER JOIN ".$dbnamex."SNV_SinavOgrencileri SO ON SO.SinavOgrenciID = OP.SinavOgrenciID
            INNER JOIN ".$dbnamex."SNV_SinavOkullari SOK ON SOK.SinavOkulID = SO.SinavOkulID
            INNER JOIN ".$dbnamex."GNL_OgrenciSeviyeleri OS ON OS.OgrenciSeviyeID = SO.OgrenciSeviyeID
            INNER JOIN ".$dbnamex."GNL_Siniflar SNF ON SNF.SinifID = OS.SinifID
            INNER JOIN ".$dbnamex."GNL_DersYillari DY ON DY.DersYiliID = SNF.DersYiliID
            WHERE SPT.SinavID = @SinavID AND
                PST.PuanSiralamaTipID IN (4,5,1)
            GROUP BY op.SinavOgrenciID ,
                Snf.SeviyeID ,
                SNF.SinifID ,
                SNF.SinifKodu ,
                s.sinavkodu,
                s.SinavAciklamasi,
                OPS.Sira ,
                OPS.SinavaGirenOgrenciSayisi ,
                PST.PuanSiralamaTipID ,
                S.sinavID
            ORDER BY Snf.SeviyeID, Snf.SinifKodu
  
            SELECT RANK() OVER (ORDER BY SUM(OOP.Puan) DESC) AS Sira,
                O.OkulID,
                UPPER(O.OkulAdi) AS OkulAdi,
                ILCE.IlceAdi,
                SUM(OOP.Puan) AS Puan,
                K.Adi,
                K.Soyadi,
                K.TCKimlikNo,
                OOP.SinavOgrenciID,
                OPT.PuanTipAdi,
                OPT.PuanTipKodu ,
                OS.OgrenciID,
                oob.Numarasi
            INTO #Siralamalar
            FROM ".$dbnamex."SNV_Sinavlar S
            INNER JOIN ".$dbnamex."SNV_SinavSiniflari SS ON SS.SinavID = S.SinavID
            INNER JOIN ".$dbnamex."SNV_SinavOgrencileri SOGR ON SOGR.SinavSinifID = SS.SinavSinifID
            INNER JOIN ".$dbnamex."GNL_OgrenciSeviyeleri OS ON OS.OgrenciSeviyeID = SOGR.OgrenciSeviyeID
            INNER JOIN ".$dbnamex."GNL_Kisiler K ON K.KisiID = OS.OgrenciID
            INNER JOIN ".$dbnamex."SNV_SinavOkullari SOKL ON SOKL.SinavOkulID = SOGR.SinavOkulID
            INNER JOIN ".$dbnamex."GNL_Okullar O ON O.OkulID = SOKL.OkulID
            LEFT JOIN ".$dbnamex."GNL_Adresler ADR ON ADR.AdresID = O.AdresID
            LEFT JOIN ".$dbnamex."GNL_Ilceler ILCE ON ILCE.IlceID = ADR.IlceID
            INNER JOIN ".$dbnamex."OD_OgrenciPuanlari OOP ON OOP.SinavOgrenciID = SOGR.SinavOgrenciID
            INNER JOIN ".$dbnamex."OD_SinavPuanTipleri SPT ON SPT.SinavPuanTipID = OOP.SinavPuanTipID
            INNER JOIN ".$dbnamex."OD_PuanTipleri OPT ON OPT.PuanTipID = SPT.PuanTipID
            INNER JOIN ".$dbnamex."GNL_OgrenciOkulBilgileri OOB ON OOB.OkulID = O.OkulID  AND OOB.OgrenciID = OS.OgrenciID
            WHERE S.SinavID = @SinavID
            GROUP BY O.OkulID ,
                O.OkulAdi ,
                ILCE.IlceAdi ,
                OS.OgrenciID,
                oob.Numarasi,
                K.Adi ,
                K.Soyadi ,
                K.TCKimlikNo ,
                OPT.PuanTipAdi ,
                OPT.PuanTipKodu ,
                OOP.SinavOgrenciID
            ORDER BY SUM(OOP.Puan) DESC, K.Adi, K.Soyadi
 
        /**/
  
            SELECT  SD.SinavDersID,
                SOKL.OkulID,
                CAST(AVG(OS.Net) AS DECIMAL(18,2)) AS Net
            INTO #OkulOrtalama
            FROM ".$dbnamex."SNV_SinavDersleri SD
            LEFT JOIN ".$dbnamex."OD_OgrenciSonuclari OS ON OS.SinavDersID = SD.SinavDersID
            LEFT JOIN ".$dbnamex."SNV_SinavOgrencileri SO ON SO.SinavOgrenciID = OS.SinavOgrenciID
            LEFT JOIN ".$dbnamex."SNV_SinavOkullari SOKL ON SOKL.SinavOkulID = SO.SinavOkulID
            INNER JOIN ".$dbnamex."SNV_SinavDersSabitleri SDS ON SDS.SinavDersSabitID = SD.SinavDersSabitID
            INNER JOIN ".$dbnamex."SNV_SinavKategorileri SK ON SK.SinavKategoriID = SD.SinavKategoriID
                                                       AND SK.SinavID = @SinavID 
            GROUP BY SOKL.OkulID,SD.SinavDersID
 
            SELECT BS.BolumAdi,
                BK.BolumKategoriAdi,
                ".$dbnamex."FNC_SNV_SinavDersSabitleri_GetDersKodu(DS.DersSabitAdi) AS DersAdi,
                CASE WHEN SD.AcikUcluSoruSayisi IS NULL
                        THEN SD.DersSoruSayisi ELSE
                        SD.DersSoruSayisi + SD.AcikUcluSoruSayisi END AS DersSoruSayisi,
                CAST(OS.DogruSayisi AS DECIMAL(18,0)) AS DogruSayisi,
                CAST(OS.YanlisSayisi AS DECIMAL(18,0)) AS YanlisSayisi,
                CAST(OS.BosSayisi AS DECIMAL(18,0)) AS BosSayisi,
                CAST(OS.Net AS DECIMAL(18,2)) AS Net,
                CAST(SD.AritmetikOrtalama AS DECIMAL(18, 2)) AS OrtalamaNet,
                CAST(#O.Net AS DECIMAL(18,2)) AS OkulOrtalamaNet,
                SD.SinavDersID,
                SDS.DersKodu,
                SDS.SinavDersSabitID,
                OS.SinavOgrenciID,
                SO.OgrenciSeviyeID 
            into #teksatirbilgiler
            FROM ".$dbnamex."SNV_SinavDersleri SD    
            LEFT JOIN ".$dbnamex."OD_OgrenciSonuclari OS ON OS.SinavDersID = SD.SinavDersID
                                          /*  AND OS.SinavOgrenciID = @SinavOgrenciID */ 
            LEFT JOIN ".$dbnamex."SNV_SinavOgrencileri SO ON SO.SinavOgrenciID = OS.SinavOgrenciID AND OS.SinavOgrenciID = SO.SinavOgrenciID AND SO.OgrenciSeviyeID = @OgrenciSeviyeID
            LEFT JOIN ".$dbnamex."SNV_SinavOkullari SOKL ON SOKL.SinavOkulID = SO.SinavOkulID
            INNER JOIN ".$dbnamex."SNV_SinavDersSabitleri SDS ON SDS.SinavDersSabitID = SD.SinavDersSabitID
            INNER JOIN ".$dbnamex."SNV_SinavKategorileri SK ON SK.SinavKategoriID = SD.SinavKategoriID
                                            AND SK.SinavID = @SinavID
            INNER JOIN ".$dbnamex."GNL_DersSabitleri DS ON DS.DersSabitID = SDS.DersSabitID
            INNER JOIN ".$dbnamex."SNV_BolumKategorileri BK ON BK.BolumKategoriID = SDS.BolumKategoriID
            INNER JOIN ".$dbnamex."SNV_BolumSabitleri BS ON BS.BolumSabitID = BK.BolumSabitID
            LEFT JOIN #OkulOrtalama #O ON #O.SinavDersID = OS.SinavDersID AND #O.OkulID = SOKL.OkulID
            ORDER BY BS.BolumSabitID,
                BK.BolumKategoriID,
                SDS.Sira;
  
            declare @raporkey varchar(50) ;
            set @raporkey = 'STSIO'+replace(newID(),'-','');
 
            INSERT INTO BILSANET_MOBILE.dbo.Mobile_tempRaporOzetBilgisi
                (raporkey,
                Sira,OkulID,
                OkulAdi,IlceAdi,
                Puan,OgrenciID,
                Adi,Soyadi,
                TCKimlikNo,SinavOgrenciID,
                PuanTipAdi,PuanTipKodu,
                SinifKodu,SinifID,
                sinavID,sinavkodu,
                SinifOrtalamasi,OkulOrtalamasi,
                SinavaGirenOgrenciSayisi,GenelSira,
                ToplamPuan,BolumAdi,
                BolumKategoriAdi,DersAdi,
                DersSoruSayisi,DogruSayisi,
                YanlisSayisi,BosSayisi,
                Net,OrtalamaNet,
                OkulOrtalamaNet,SinavDersID,
                DersKodu,SinavDersSabitID,
                OgrenciSeviyeID,
                SinavAciklamasi,Numarasi)
      
            SELECT DISTINCT
                @raporkey AS raporkey, 
                ss.Sira,
                ss.OkulID,
                ss.OkulAdi,
                ss.IlceAdi,
                ss.Puan,
                ss.OgrenciID,
                ss.Adi,
                ss.Soyadi,
                ss.TCKimlikNo,
                ss.SinavOgrenciID,
                ss.PuanTipAdi,
                ss.PuanTipKodu,
                tt.SinifKodu,
                tt.SinifID,
                tt.sinavID,
                tt.sinavkodu,
                /*-- tt.TopPuan as Puan,  */
                ( SELECT AVG(TopPuan) FROM #tmpSinif WHERE SinifID = tt.SinifID AND PuanSiralamaTipID = 5) AS SinifOrtalamasi ,
                ( SELECT AVG(TopPuan)FROM #tmpSinif WHERE PuanSiralamaTipID = 4) AS OkulOrtalamasi,
                tt.SinavaGirenOgrenciSayisi,
                /*-- tt.Sira , */  
                (SELECT Sira FROM #Siralamalar
                    WHERE #Siralamalar.SinavOgrenciID = (select xxc1.SinavOgrenciID from #teksatirbilgiler xxc1 where xxc1.OgrenciSeviyeID=bb.OgrenciSeviyeID) /* @SinavOgrenciID */
                            ) AS GenelSira,
                (SELECT Puan FROM #Siralamalar
                    WHERE #Siralamalar.SinavOgrenciID = (select xxc2.SinavOgrenciID from #teksatirbilgiler xxc2 where xxc2.OgrenciSeviyeID=bb.OgrenciSeviyeID) /* @SinavOgrenciID */
                            ) AS ToplamPuan, 
                bb.BolumAdi,
                bb.BolumKategoriAdi,
                bb.DersAdi,
                bb.DersSoruSayisi,
                bb.DogruSayisi,
                bb.YanlisSayisi,
                bb.BosSayisi,
                bb.Net,
                bb.OrtalamaNet,
                bb.OkulOrtalamaNet,
                bb.SinavDersID,
                bb.DersKodu,
                bb.SinavDersSabitID,
                bb.OgrenciSeviyeID,
                tt.SinavAciklamasi,
                ss.Numarasi 
            FROM #Siralamalar ss 
            INNER JOIN #tmpSinif tt on tt.SinavOgrenciID=ss.SinavOgrenciID
            INNER JOIN #teksatirbilgiler bb on tt.SinavOgrenciID=bb.SinavOgrenciID
  
            set @SinavOgrenciID = (SELECT zx.SinavOgrenciID FROM #Siralamalar zx ) 
  
            INSERT INTO BILSANET_MOBILE.dbo.Mobile_tempRaporDetayBilgisi
                       (raporkey
                       ,SinavOgrenciID
                       ,SinavKitapcikID
                       ,SinavKitapcikSoruID
                       ,BolumKategoriID
                       ,BolumKategoriAdi
                       ,DogruCevap
                       ,OgrenciCevap
                       ,SoruSira
                       ,Sira
                       ,DugumAciklama
                       ,DersKodu
                       ,SinavDersSirasi)
 
            SELECT @raporkey as raporkey, 
                    SO.SinavOgrenciID,
                    SKS.SinavKitapcikID,
                    SKS.SinavKitapcikSoruID,
                    BKS.BolumKategoriID,
                    BKS.BolumKategoriAdi,
                    CASE WHEN SS.SinavSoruDurumID = 1 THEN
                            CASE WHEN D_CVP.CevapSiklariSirasi = 1 THEN 'A' ELSE 
                                    CASE WHEN D_CVP.CevapSiklariSirasi = 2 THEN 'B' ELSE 
                                            CASE WHEN D_CVP.CevapSiklariSirasi = 3 THEN 'C' ELSE 
                                                    CASE WHEN D_CVP.CevapSiklariSirasi = 4 THEN 'D' ELSE 
                                                            CASE WHEN D_CVP.CevapSiklariSirasi = 5 THEN 'E' ELSE 
                                                                    ''
                                                            END
                                                    END
                                            END
                                    END
                            END
                    ELSE
                            'X'
                    END AS DogruCevap,
                    CASE WHEN O_CVP.CevapSiklariSirasi = 1 THEN 'A' ELSE 
                            CASE WHEN O_CVP.CevapSiklariSirasi = 2 THEN 'B' ELSE 
                                    CASE WHEN O_CVP.CevapSiklariSirasi = 3 THEN 'C' ELSE 
                                            CASE WHEN O_CVP.CevapSiklariSirasi = 4 THEN 'D' ELSE 
                                                    CASE WHEN O_CVP.CevapSiklariSirasi = 5 THEN 'E' ELSE 
                                                            ''
                                                    END
                                            END
                                    END
                            END
                    END AS OgrenciCevap,
                    ROW_NUMBER() OVER (PARTITION BY SO.SinavOgrenciID ORDER BY BKS.BolumKategoriID, SDS.Sira, SKS.Sira) AS SoruSira,
                    SKS.Sira,
                    DUGUM.DugumAciklama,
                    SDS.DersKodu,
                    SDS.Sira as SinavDersSirasi 
            FROM ".$dbnamex."SNV_SinavKitapcikSorulari SKS 
            INNER JOIN ".$dbnamex."SNV_SinavOgrencileri SO ON SKS.SinavKitapcikID = SO.SinavKitapcikID AND SO.SinavOgrenciID = @SinavOgrenciID 		
            INNER JOIN ".$dbnamex."SNV_SinavSorulari SS ON SS.SinavSoruID = SKS.SinavSoruID
            INNER JOIN ".$dbnamex."SB_SoruIcerikleri SI ON SI.SoruID = SS.SoruID
            INNER JOIN ".$dbnamex."SB_Sorular SORU ON SORU.SoruID = SI.soruID
            LEFT JOIN ".$dbnamex."SNV_SinavOgrenciSoruCevaplari SOSC ON SOSC.SinavSoruID = SS.SinavSoruID AND SOSC.SinavOgrenciID = SO.SinavOgrenciID
            INNER JOIN ".$dbnamex."SNV_SinavKitapcikSoruCevapSiralari D_CVP ON D_CVP.SinavKitapcikSoruID = SKS.SinavKitapcikSoruID AND D_CVP.SoruCevaplariID = SI.SoruCevaplariID 
            LEFT JOIN ".$dbnamex."SNV_SinavKitapcikSoruCevapSiralari O_CVP ON O_CVP.SinavKitapcikSoruCevapSiraID = SOSC.SinavKitapcikSoruCevapSiraID
            INNER JOIN ".$dbnamex."SNV_SinavDersleri SD ON SD.SinavDersID = SS.SinavDersID 
            INNER JOIN ".$dbnamex."SNV_SinavDersSabitleri SDS ON SDS.SinavDersSabitID = SD.SinavDersSabitID
            INNER JOIN ".$dbnamex."SNV_BolumKategorileri BKS ON BKS.BolumKategoriID = SDS.BolumKategoriID
            INNER JOIN ".$dbnamex."KA_Dugumler DUGUM ON DUGUM.DugumID = SORU.DugumID 
 
            SELECT top 1 raporkey,
                'http://mobile.okulsis.net:8000/jasperserver/rest_v2/reports/reports/bilsa/mobile/rapor/SinavGirenOgrenciListesi".$lid.".html?raporkey='+@raporkey+'&lid=".$languageIdValue."&j_username=joeuser&j_password=joeuser' as proad,
                'http://mobile.okulsis.net:8000/jasperserver/rest/login?j_username=joeuser&j_password=joeuser' as lroad
            FROM BILSANET_MOBILE.dbo.Mobile_tempRaporOzetBilgisi
            where raporkey = @raporkey;
             
            IF OBJECT_ID('tempdb..#tmpSinif') IS NOT NULL DROP TABLE #tmpSinif;
            IF OBJECT_ID('tempdb..#Siralamalar') IS NOT NULL DROP TABLE #Siralamalar; 
            IF OBJECT_ID('tempdb..#OkulOrtalama') IS NOT NULL DROP TABLE #OkulOrtalama;
            IF OBJECT_ID('tempdb..#teksatirbilgiler') IS NOT NULL DROP TABLE #teksatirbilgiler;
  
            SET NOCOUNT OFF; 

		 ";
           // $sql =  $sql +  $sql1;
       //    print_r($sql);
            $statement = $pdo->prepare($sql);   
    // 
            $statement->execute();
            
            //   http://localhost:8081/jasperserver/flow.html?_flowId=viewReportFlow&reportUnit=/reports/bilsa/mobile/oppp&output=pdf&j_username=jasperadmin&j_password=12345678oki
       /*    $c = new \Jaspersoft\Client\Client(
                "http://localhost:8000/jasperserver",
                "jasperadmin",
                "jasperadmin",
                "organization_1"
              );
         */  
        //    $info = $c->serverInfo();
        //    print_r($info);
// http://localhost:8000/jasperserver/rest_v2/reports/reports/bilsa/ddd.html
          //   $report = $c->reportService()->runReport('/reports/bilsa/mobile/rapor/ogrenciSinavDetay', 'pdf');
        //     print_r($c);
             //    $report ='http://localhost:8081/jasperserver/flow.html?_flowId=viewReportFlow&reportUnit=/reports/bilsa/mobile/oppp&output=pdf&j_username=jasperadmin&j_password=12345678oki';
          //     echo $report; 
 
          //  'http://localhost:8000/jasperserver/rest_v2/reports/reports/bilsa/mobile/rapor/ogrenciSinavDetay.pdf&dosyaID=';
            
             
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
