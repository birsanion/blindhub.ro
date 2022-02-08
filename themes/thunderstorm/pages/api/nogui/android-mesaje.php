<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'interlocutori' => array(),
    'idxinterlocutori' => array(),
    'mesaje' => array()
);

$arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
    array('apploginid', '=', POST('userkey') /*PARAM(2)*/)
);

function SortCustom($a, $b)
{
    return strcmp($a['nume'], $b['nume']);
}

if (is_array($arrUser) && count($arrUser) > 0){
    $arrUser = $arrUser[0];
    
    switch ((int)$arrUser['tiputilizator'])
    {
        case 0:{ // nevazatori
            $arrInterlocutori = array();
            $arrInterlocutoriIndexed = array();
            
            // get list of already messages
            $arrData = $this->DATABASE->RunQuickSelect('DISTINCT(`idxauthinterlocutor`)', SYSCFG_DB_PREFIX . 'mesaje',
                array('idxauthangajat', '=', (int)$arrUser['idx'])
            );
            
            if (is_array($arrData) && !empty($arrData)){
                foreach ($arrData as $arrElem){
                    // get auth data
                    $arrInterAuth = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users',
                        array('idx', '=', (int)$arrElem['idxauthinterlocutor'])
                    );
                    
                    if (is_array($arrInterAuth) && !empty($arrInterAuth)){
                        $arrInterAuth = $arrInterAuth[0];
                        
                        if ((int)$arrInterAuth['tiputilizator'] == 1){
                            // angajator
                            $arrExtraData = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori',
                                array('idxauth', '=', (int)$arrInterAuth['idx'])
                            );
                            
                            if (is_array($arrExtraData) && !empty($arrExtraData)){
                                $arrInterlocutori[] = array(
                                    'idx' => (int)$arrElem['idxauthinterlocutor'],
                                    'nume' => $arrExtraData[0]['companie']
                                );
                                
                                $arrInterlocutoriIndexed[(int)$arrElem['idxauthinterlocutor']] = $arrExtraData[0]['companie'];
                            }
                        }else{
                            // universitate
                            $arrExtraData = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'universitati',
                                array('idxauth', '=', (int)$arrInterAuth['idx'])
                            );
                            
                            if (is_array($arrExtraData) && !empty($arrExtraData)){
                                $arrInterlocutori[] = array(
                                    'idx' => (int)$arrElem['idxauthinterlocutor'],
                                    'nume' => $arrExtraData[0]['nume']
                                );
                                
                                $arrInterlocutoriIndexed[(int)$arrElem['idxauthinterlocutor']] = $arrExtraData[0]['nume'];
                            }
                        }
                    }
                }
            }

            // get list of cereri interviuri
            $arrCereriInterv = $this->DATABASE->RunQuickSelect('DISTINCT(`idxauthangajator`)',
                SYSCFG_DB_PREFIX . 'cereriinterviu',
                array('idxauthangajat', '=', (int)$arrUser['idx'])
            );
            
            if (is_array($arrCereriInterv) && !empty($arrCereriInterv)){
                foreach ($arrCereriInterv as $arrInterviu){
                    if (!isset($arrInterlocutoriIndexed[(int)$arrInterviu['idxauthangajator']])){
                        $arrExtraData = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajatori',
                            array('idxauth', '=', (int)$arrInterviu['idxauthangajator'])
                        );
                        
                        if (is_array($arrExtraData) && !empty($arrExtraData)){
                            $arrInterlocutori[] = array(
                                'idx' => (int)$arrInterviu['idxauthangajator'],
                                'nume' => $arrExtraData[0]['companie']
                            );
                            
                            $arrInterlocutoriIndexed[(int)$arrInterviu['idxauthangajator']] = $arrExtraData[0]['companie'];
                        }
                    }
                }
            }
            
            // get list of cereri interviuri universitati
            $arrCereriInterv = $this->DATABASE->RunQuickSelect('DISTINCT(`idxauthuniversitate`)',
                SYSCFG_DB_PREFIX . 'cereriinterviuuniversitate',
                array('idxauthangajat', '=', (int)$arrUser['idx'])
            );
            
            if (is_array($arrCereriInterv) && !empty($arrCereriInterv)){
                foreach ($arrCereriInterv as $arrInterviu){
                    if (!isset($arrInterlocutoriIndexed[(int)$arrInterviu['idxauthuniversitate']])){
                        $arrExtraData = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'universitati',
                            array('idxauth', '=', (int)$arrInterviu['idxauthuniversitate'])
                        );
                        
                        if (is_array($arrExtraData) && !empty($arrExtraData)){
                            $arrInterlocutori[] = array(
                                'idx' => (int)$arrInterviu['idxauthuniversitate'],
                                'nume' => $arrExtraData[0]['nume']
                            );
                            
                            $arrInterlocutoriIndexed[(int)$arrInterviu['idxauthangajator']] = $arrExtraData[0]['companie'];
                        }
                    }
                }
            }
            
            // sort list
            usort($arrInterlocutori, 'SortCustom');
            
            // get messages for each
            foreach ($arrInterlocutori as $nKey => $arrInterlocutor){
                // add names and indexes to master list
                $this->DATA['interlocutori'][] = $arrInterlocutor['nume'];
                $this->DATA['idxinterlocutori'][] = $arrInterlocutor['idx'];
                
                // get mesaje
                $arrMesaje = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'mesaje',
                    array(
                        array('idxauthangajat', '=', (int)$arrUser['idx'], 'AND'),
                        array('idxauthinterlocutor', '=', $arrInterlocutor['idx'])
                    ),
                    'tstamp'
                );
                
                if (is_array($arrMesaje) && !empty($arrMesaje)){
                    $arrBucket = array();
                    $nLastIdx = 0;
                    $arrSmallerBucket = array(
                        'msjinterlocutor' => '',
                        'msjtau' => ''
                    );
                    
                    foreach ($arrMesaje as $arrMesaj){
                        if ((int)$arrMesaj['idxauthmesaj'] != (int)$arrUser['idx']){
                            if ($nLastIdx == (int)$arrMesaj['idxauthmesaj']){
                                $arrSmallerBucket['msjinterlocutor'] .=
                                    (strlen($arrSmallerBucket['msjinterlocutor']) > 0 ? "\n" : '') . $arrMesaj['mesaj'];
                            }else{
                                // add new message to bucket and create new smaller bucket
                                if (strlen($arrSmallerBucket['msjinterlocutor'].$arrSmallerBucket['msjtau']) > 0)
                                    $arrBucket[] = $arrSmallerBucket;
                                
                                $arrSmallerBucket = array(
                                    'msjinterlocutor' => $arrMesaj['mesaj'],
                                    'msjtau' => ''
                                );
                                
                                $nLastIdx = (int)$arrMesaj['idxauthmesaj'];
                            }
                        }else{
                            $arrSmallerBucket['msjtau'] .=
                                (strlen($arrSmallerBucket['msjtau']) > 0 ? "\n" : '') . $arrMesaj['mesaj'];
                            $nLastIdx = (int)$arrMesaj['idxauthmesaj'];
                        }
                    }

                    // add last message if necessary
                    if (strlen($arrSmallerBucket['msjinterlocutor'].$arrSmallerBucket['msjtau']) > 0)
                        $arrBucket[] = $arrSmallerBucket;
                    
                    // reverse bucket
                    $this->DATA['mesaje'][] = array_reverse($arrBucket);
                }else{
                    // add empty array
                    $this->DATA['mesaje'][] = array();
                }
            }
        }break;
        
        case 1:{ // angajatori
            $arrInterlocutori = array();
            $arrInterlocutoriIndexed = array();
            
            // get list of already messages
            $arrData = $this->DATABASE->RunQuickSelect('DISTINCT(`idxauthinterlocutor`)', SYSCFG_DB_PREFIX . 'mesaje',
                array('idxauthinterlocutor', '=', (int)$arrUser['idx'])
            );
            
            if (is_array($arrData) && !empty($arrData)){
                foreach ($arrData as $arrElem){
                    $arrExtraData = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati',
                        array('idxauth', '=', (int)$arrElem['idxauthangajat'])
                    );
                    
                    if (is_array($arrExtraData) && !empty($arrExtraData)){
                        $arrInterlocutori[] = array(
                            'idx' => (int)$arrElem['idxauthangajat'],
                            'nume' => $arrExtraData[0]['nume'] . ' ' . $arrExtraData[0]['prenume']
                        );
                        
                        $arrInterlocutoriIndexed[(int)$arrElem['idxauthangajat']] =
                            $arrExtraData[0]['nume'] . ' ' . $arrExtraData[0]['prenume'];
                    }
                }
            }

            // get list of cereri interviuri
            $arrCereriInterv = $this->DATABASE->RunQuickSelect('DISTINCT(`idxauthangajat`)',
                SYSCFG_DB_PREFIX . 'cereriinterviu',
                array('idxauthangajator', '=', (int)$arrUser['idx'])
            );
            
            if (is_array($arrCereriInterv) && !empty($arrCereriInterv)){
                foreach ($arrCereriInterv as $arrInterviu){
                    if (!isset($arrInterlocutoriIndexed[(int)$arrInterviu['idxauthangajat']])){
                        $arrExtraData = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati',
                            array('idxauth', '=', (int)$arrInterviu['idxauthangajat'])
                        );
                        
                        if (is_array($arrExtraData) && !empty($arrExtraData)){
                            $arrInterlocutori[] = array(
                                'idx' => (int)$arrInterviu['idxauthangajat'],
                                'nume' => $arrExtraData[0]['nume'] . ' ' . $arrExtraData[0]['prenume']
                            );
                            
                            $arrInterlocutoriIndexed[(int)$arrInterviu['idxauthangajat']] =
                                $arrExtraData[0]['nume'] . ' ' . $arrExtraData[0]['prenume'];
                        }
                    }
                }
            }
            
            // sort list
            usort($arrInterlocutori, 'SortCustom');
            
            // get messages for each
            foreach ($arrInterlocutori as $nKey => $arrInterlocutor){
                // add names and indexes to master list
                $this->DATA['interlocutori'][] = $arrInterlocutor['nume'];
                $this->DATA['idxinterlocutori'][] = $arrInterlocutor['idx'];
                
                // get mesaje
                $arrMesaje = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'mesaje',
                    array(
                        array('idxauthangajat', '=', $arrInterlocutor['idx'], 'AND'),
                        array('idxauthinterlocutor', '=', (int)$arrUser['idx'])
                    ),
                    'tstamp'
                );
                
                if (is_array($arrMesaje) && !empty($arrMesaje)){
                    $arrBucket = array();
                    $nLastIdx = 0;
                    $arrSmallerBucket = array(
                        'msjinterlocutor' => '',
                        'msjtau' => ''
                    );
                    
                    foreach ($arrMesaje as $arrMesaj){
                        if ((int)$arrMesaj['idxauthmesaj'] != (int)$arrUser['idx']){
                            if ($nLastIdx == (int)$arrMesaj['idxauthmesaj']){
                                $arrSmallerBucket['msjinterlocutor'] .=
                                    (strlen($arrSmallerBucket['msjinterlocutor']) > 0 ? "\n" : '') . $arrMesaj['mesaj'];
                            }else{
                                // add new message to bucket and create new smaller bucket
                                if (strlen($arrSmallerBucket['msjinterlocutor'].$arrSmallerBucket['msjtau']) > 0)
                                    $arrBucket[] = $arrSmallerBucket;
                                
                                $arrSmallerBucket = array(
                                    'msjinterlocutor' => $arrMesaj['mesaj'],
                                    'msjtau' => ''
                                );
                                
                                $nLastIdx = (int)$arrMesaj['idxauthmesaj'];
                            }
                        }else{
                            $arrSmallerBucket['msjtau'] .=
                                (strlen($arrSmallerBucket['msjtau']) > 0 ? "\n" : '') . $arrMesaj['mesaj'];
                            $nLastIdx = (int)$arrMesaj['idxauthmesaj'];
                        }
                    }

                    // add last message if necessary
                    if (strlen($arrSmallerBucket['msjinterlocutor'].$arrSmallerBucket['msjtau']) > 0)
                        $arrBucket[] = $arrSmallerBucket;
                    
                    // reverse bucket
                    $this->DATA['mesaje'][] = array_reverse($arrBucket);
                }else{
                    // add empty array
                    $this->DATA['mesaje'][] = array();
                }
            }
        }break;
        
        case 2:{ // universitati
            $arrInterlocutori = array();
            $arrInterlocutoriIndexed = array();
            
            // get list of already messages
            $arrData = $this->DATABASE->RunQuickSelect('DISTINCT(`idxauthinterlocutor`)', SYSCFG_DB_PREFIX . 'mesaje',
                array('idxauthinterlocutor', '=', (int)$arrUser['idx'])
            );
            
            if (is_array($arrData) && !empty($arrData)){
                foreach ($arrData as $arrElem){
                    $arrExtraData = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati',
                        array('idxauth', '=', (int)$arrElem['idxauthangajat'])
                    );
                    
                    if (is_array($arrExtraData) && !empty($arrExtraData)){
                        $arrInterlocutori[] = array(
                            'idx' => (int)$arrElem['idxauthangajat'],
                            'nume' => $arrExtraData[0]['nume'] . ' ' . $arrExtraData[0]['prenume']
                        );
                        
                        $arrInterlocutoriIndexed[(int)$arrElem['idxauthangajat']] =
                            $arrExtraData[0]['nume'] . ' ' . $arrExtraData[0]['prenume'];
                    }
                }
            }

            // get list of cereri interviuri
            $arrCereriInterv = $this->DATABASE->RunQuickSelect('DISTINCT(`idxauthangajat`)',
                SYSCFG_DB_PREFIX . 'cereriinterviuuniversitate',
                array('idxauthuniversitate', '=', (int)$arrUser['idx'])
            );
            
            if (is_array($arrCereriInterv) && !empty($arrCereriInterv)){
                foreach ($arrCereriInterv as $arrInterviu){
                    if (!isset($arrInterlocutoriIndexed[(int)$arrInterviu['idxauthangajat']])){
                        $arrExtraData = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'angajati',
                            array('idxauth', '=', (int)$arrInterviu['idxauthangajat'])
                        );
                        
                        if (is_array($arrExtraData) && !empty($arrExtraData)){
                            $arrInterlocutori[] = array(
                                'idx' => (int)$arrInterviu['idxauthangajat'],
                                'nume' => $arrExtraData[0]['nume'] . ' ' . $arrExtraData[0]['prenume']
                            );
                            
                            $arrInterlocutoriIndexed[(int)$arrInterviu['idxauthangajat']] =
                                $arrExtraData[0]['nume'] . ' ' . $arrExtraData[0]['prenume'];
                        }
                    }
                }
            }
            
            // sort list
            usort($arrInterlocutori, 'SortCustom');
            
            // get messages for each
            foreach ($arrInterlocutori as $nKey => $arrInterlocutor){
                // add names and indexes to master list
                $this->DATA['interlocutori'][] = $arrInterlocutor['nume'];
                $this->DATA['idxinterlocutori'][] = $arrInterlocutor['idx'];
                
                // get mesaje
                $arrMesaje = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'mesaje',
                    array(
                        array('idxauthangajat', '=', $arrInterlocutor['idx'], 'AND'),
                        array('idxauthinterlocutor', '=', (int)$arrUser['idx'])
                    ),
                    'tstamp'
                );
                
                if (is_array($arrMesaje) && !empty($arrMesaje)){
                    $arrBucket = array();
                    $nLastIdx = 0;
                    $arrSmallerBucket = array(
                        'msjinterlocutor' => '',
                        'msjtau' => ''
                    );
                    
                    foreach ($arrMesaje as $arrMesaj){
                        if ((int)$arrMesaj['idxauthmesaj'] != (int)$arrUser['idx']){
                            if ($nLastIdx == (int)$arrMesaj['idxauthmesaj']){
                                $arrSmallerBucket['msjinterlocutor'] .=
                                    (strlen($arrSmallerBucket['msjinterlocutor']) > 0 ? "\n" : '') . $arrMesaj['mesaj'];
                            }else{
                                // add new message to bucket and create new smaller bucket
                                if (strlen($arrSmallerBucket['msjinterlocutor'].$arrSmallerBucket['msjtau']) > 0)
                                    $arrBucket[] = $arrSmallerBucket;
                                
                                $arrSmallerBucket = array(
                                    'msjinterlocutor' => $arrMesaj['mesaj'],
                                    'msjtau' => ''
                                );
                                
                                $nLastIdx = (int)$arrMesaj['idxauthmesaj'];
                            }
                        }else{
                            $arrSmallerBucket['msjtau'] .=
                                (strlen($arrSmallerBucket['msjtau']) > 0 ? "\n" : '') . $arrMesaj['mesaj'];
                            $nLastIdx = (int)$arrMesaj['idxauthmesaj'];
                        }
                    }

                    // add last message if necessary
                    if (strlen($arrSmallerBucket['msjinterlocutor'].$arrSmallerBucket['msjtau']) > 0)
                        $arrBucket[] = $arrSmallerBucket;
                    
                    // reverse bucket
                    $this->DATA['mesaje'][] = array_reverse($arrBucket);
                }else{
                    // add empty array
                    $this->DATA['mesaje'][] = array();
                }
            }
        }break;
    }
}
        