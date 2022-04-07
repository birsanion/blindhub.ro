<?php

$this->DATA = array(
    'result' => 'success',
    'tstamp' => date('YmdHis'),
    'chatrooms' => array()
);

////////////////////////////////////////////////////////////////////////////////

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
                                    'nume' => $arrExtraData[0]['companie'],
                                    'icon' => $arrExtraData[0]['img'] ? qurl_file('media/uploads/' .  $arrExtraData[0]['img']) : ''
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
                                    'nume' => $arrExtraData[0]['nume'],
                                    'icon' => $arrExtraData[0]['img'] ? qurl_file('media/uploads/' .  $arrExtraData[0]['img']) : ''
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
                                'nume' => $arrExtraData[0]['companie'],
                                'icon' => $arrExtraData[0]['img'] ? qurl_file('media/uploads/' .  $arrExtraData[0]['img']) : ''
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
                                'nume' => $arrExtraData[0]['nume'],
                                'icon' => $arrExtraData[0]['img'] ? qurl_file('media/uploads/' .  $arrExtraData[0]['img']) : ''
                            );

                            $arrInterlocutoriIndexed[(int)$arrInterviu['idxauthuniversitate']] = $arrExtraData[0]['nume'];
                        }
                    }
                }
            }

            // sort list
            usort($arrInterlocutori, 'SortCustom');

            // get messages for each
            foreach ($arrInterlocutori as $nKey => $arrInterlocutor){
                // add names and indexes to master list
                //$this->DATA['interlocutori'][] = $arrInterlocutor['nume'];
                //$this->DATA['idxinterlocutori'][] = $arrInterlocutor['idx'];
                $arrMesajeOut = array();

                // get mesaje
                $arrMesaje = $this->DATABASE->RunQuickSelect('*, UNIX_TIMESTAMP(tstamp) AS tstamp', SYSCFG_DB_PREFIX . 'mesaje',
                    array(
                        array('idxauthangajat', '=', (int)$arrUser['idx'], 'AND'),
                        array('idxauthinterlocutor', '=', $arrInterlocutor['idx'])
                    ),
                    'tstamp'
                );

                if (is_array($arrMesaje) && !empty($arrMesaje)){
                    foreach ($arrMesaje as $arrMesaj){
                        $arrMesajeOut[] = array(
                            'message' => $arrMesaj['mesaj'],
                            'sender_id' => (int)$arrMesaj['idxauthmesaj'],
                            'time' => (int)$arrMesaj['tstamp']
                        );
                    }
                }

                $this->DATA['chatrooms'][] = array(
                    'name' => $arrInterlocutor['nume'],
                    'idx' => $arrInterlocutor['idx'],
                    'icon' => $arrInterlocutor['icon'],
                    'last_message' => (!empty($arrMesajeOut) ? $arrMesajeOut[count($arrMesajeOut)-1]['message'] : ''),
                    'last_message_time' => (!empty($arrMesajeOut) ? $arrMesajeOut[count($arrMesajeOut)-1]['time'] : 0),
                    'messages' => $arrMesajeOut
                );
            }
        }break;

        case 1:{ // angajatori
            $arrInterlocutori = array();
            $arrInterlocutoriIndexed = array();

            // get list of already messages
            $arrData = $this->DATABASE->RunQuickSelect('DISTINCT(`idxauthangajat`)', SYSCFG_DB_PREFIX . 'mesaje',
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
                            'nume' => $arrExtraData[0]['nume'] . ' ' . $arrExtraData[0]['prenume'],
                            'icon' => $arrExtraData[0]['img'] ? qurl_file('media/uploads/' .  $arrExtraData[0]['img']) : ''
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
                                'nume' => $arrExtraData[0]['nume'] . ' ' . $arrExtraData[0]['prenume'],
                                'icon' => $arrExtraData[0]['img'] ? qurl_file('media/uploads/' .  $arrExtraData[0]['img']) : ''
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
                $arrMesajeOut = array();

                // get mesaje
                $arrMesaje = $this->DATABASE->RunQuickSelect('*, UNIX_TIMESTAMP(tstamp) AS tstamp', SYSCFG_DB_PREFIX . 'mesaje',
                    array(
                        array('idxauthangajat', '=', $arrInterlocutor['idx'], 'AND'),
                        array('idxauthinterlocutor', '=', (int)$arrUser['idx'])
                    ),
                    'tstamp'
                );

                if (is_array($arrMesaje) && !empty($arrMesaje)){
                    foreach ($arrMesaje as $arrMesaj){
                        $arrMesajeOut[] = array(
                            'message' => $arrMesaj['mesaj'],
                            'sender_id' => (int)$arrMesaj['idxauthmesaj'],
                            'time' => (int)$arrMesaj['tstamp']
                        );
                    }
                }

                $this->DATA['chatrooms'][] = array(
                    'name' => $arrInterlocutor['nume'],
                    'idx' => $arrInterlocutor['idx'],
                    'icon' => $arrInterlocutor['icon'],
                    'last_message' => (!empty($arrMesajeOut) ? $arrMesajeOut[count($arrMesajeOut)-1]['message'] : ''),
                    'last_message_time' => (!empty($arrMesajeOut) ? $arrMesajeOut[count($arrMesajeOut)-1]['time'] : 0),
                    'messages' => $arrMesajeOut
                );
            }
        }break;

        case 2:{ // universitati
            $arrInterlocutori = array();
            $arrInterlocutoriIndexed = array();

            // get list of already messages
            $arrData = $this->DATABASE->RunQuickSelect('DISTINCT(`idxauthangajat`)', SYSCFG_DB_PREFIX . 'mesaje',
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
                            'nume' => $arrExtraData[0]['nume'] . ' ' . $arrExtraData[0]['prenume'],
                            'icon' => $arrExtraData[0]['img'] ? qurl_file('media/uploads/' .  $arrExtraData[0]['img']) : ''
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
                                'nume' => $arrExtraData[0]['nume'] . ' ' . $arrExtraData[0]['prenume'],
                                'icon' => $arrExtraData[0]['img'] ? qurl_file('media/uploads/' .  $arrExtraData[0]['img']) : ''
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
                $arrMesajeOut = array();

                // get mesaje
                $arrMesaje = $this->DATABASE->RunQuickSelect('*, UNIX_TIMESTAMP(tstamp) AS tstamp', SYSCFG_DB_PREFIX . 'mesaje',
                    array(
                        array('idxauthangajat', '=', $arrInterlocutor['idx'], 'AND'),
                        array('idxauthinterlocutor', '=', (int)$arrUser['idx'])
                    ),
                    'tstamp'
                );

                if (is_array($arrMesaje) && !empty($arrMesaje)){
                    foreach ($arrMesaje as $arrMesaj){
                        $arrMesajeOut[] = array(
                            'message' => $arrMesaj['mesaj'],
                            'sender_id' => (int)$arrMesaj['idxauthmesaj'],
                            'time' => (int)($arrMesaj['tstamp'])
                        );
                    }
                }

                $this->DATA['chatrooms'][] = array(
                    'name' => $arrInterlocutor['nume'],
                    'idx' => $arrInterlocutor['idx'],
                    'icon' => $arrInterlocutor['icon'],
                    'last_message' => (!empty($arrMesajeOut) ? $arrMesajeOut[count($arrMesajeOut)-1]['message'] : ''),
                    'last_message_time' => (!empty($arrMesajeOut) ? $arrMesajeOut[count($arrMesajeOut)-1]['time'] : 0),
                    'messages' => $arrMesajeOut
                );
            }
        }break;
    }
}

if ($this->DATA['result'] != 'success') http_response_code(400);
