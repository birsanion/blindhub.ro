<?php
////////////////////////////////////////////////////////////////////////////////
// Part of theme Thunderstorm, of Quick Web Frame
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.

call_user_func($this->fncCallback, 'htmlheader', 'structure-javascript', MANOP_SET,
    array(
        'jquery-ui-1-10-3-custom-min.js',
        'jq-file-upload/jquery.iframe-transport.js',
        'jq-file-upload/jquery.fileupload.js'
    )
);

call_user_func($this->fncCallback, 'htmlheader', 'structure-styles', MANOP_SET,
    array(
        'jquery-ui-1-10-3-custom.css',
        'jquery-fileupload-ui.css'
    )
);

if (!$this->AUTH->IsAuthenticated()) $this->ROUTE->Redirect(qurl_l(''));

//$this->GLOBAL['infomsg'] = 'info message';
//$this->GLOBAL['errormsg'] = (string)$this->AUTH->GetLastActionResult();

function MySQLDate_to_RomanianDate($strDate)
{
    // 0123-56-89 11:14:17
    return substr($strDate, 8, 2) . '/' . substr($strDate, 5, 2) . '/' .
        substr($strDate, 0, 4) . substr($strDate, 10, 6);
}

function SortCustom($a, $b)
{
    return strcmp($a['nume'], $b['nume']);
}

$this->DATA['mesaje'] = array();

switch ((int)$this->AUTH->GetAdvancedDetail('tiputilizator'))
{
    case 0:{ // nevazatori
        $arrInterlocutori = array();
        $arrInterlocutoriIndexed = array();

        // get list of already messages
        $arrData = $this->DATABASE->RunQuickSelect('DISTINCT(`idxauthinterlocutor`)', SYSCFG_DB_PREFIX . 'mesaje',
            array('idxauthangajat', '=', $this->AUTH->GetUserId())
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
            array('idxauthangajat', '=', $this->AUTH->GetUserId())
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
            array('idxauthangajat', '=', $this->AUTH->GetUserId())
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

        $this->DATA['interlocutori'] = array();
        $this->DATA['idxinterlocutori'] = array();

        foreach ($arrInterlocutori as $nKey => $arrInterlocutor){
            // add names and indexes to master list
            $this->DATA['interlocutori'][] = $arrInterlocutor['nume'];
            $this->DATA['idxinterlocutori'][] = $arrInterlocutor['idx'];
        }

        // get mesaj pentru interlocutorul selectat
        if ((int)PARAM(1) > 0){
            $arrMesaje = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'mesaje',
                array(
                    array('idxauthangajat', '=', $this->AUTH->GetUserId(), 'AND'),
                    array('idxauthinterlocutor', '=', (int)PARAM(1))
                ),
                'tstamp'
            );

            if (is_array($arrMesaje) && !empty($arrMesaje)){
                foreach ($arrMesaje as $arrMesaj){
                    $this->DATA['mesaje'][] = array(
                        'mesaj' => $arrMesaj['mesaj'],
                        'altau' => (int)$arrMesaj['idxauthmesaj'] == $this->AUTH->GetUserId()
                    );
                }
            }
        }
    }break;

    case 1: { // angajatori
        $arrInterlocutori = [];
        $arrInterlocutoriIndexed = [];

        // get list of already messages
        $arrData = $this->DATABASE->RunQuickSelect('DISTINCT(`idxauthangajat`)', SYSCFG_DB_PREFIX . 'mesaje', [
            'idxauthinterlocutor', '=', $this->AUTH->GetUserId()
        ]);

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
            array('idxauthangajator', '=', $this->AUTH->GetUserId())
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

        foreach ($arrInterlocutori as $nKey => $arrInterlocutor){
            // add names and indexes to master list
            $this->DATA['interlocutori'][] = $arrInterlocutor['nume'];
            $this->DATA['idxinterlocutori'][] = $arrInterlocutor['idx'];
        }

        // get mesaje
        if ((int)PARAM(1) > 0){
            $arrMesaje = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'mesaje',
                array(
                    array('idxauthangajat', '=', (int)PARAM(1), 'AND'),
                    array('idxauthinterlocutor', '=', $this->AUTH->GetUserId())
                ),
                'tstamp'
            );

            if (is_array($arrMesaje) && !empty($arrMesaje)){
                foreach ($arrMesaje as $arrMesaj){
                    $this->DATA['mesaje'][] = array(
                        'mesaj' => $arrMesaj['mesaj'],
                        'altau' => (int)$arrMesaj['idxauthmesaj'] == $this->AUTH->GetUserId()
                    );
                }
            }
        }
    }break;

    case 2:{ // universitati
        $arrInterlocutori = array();
        $arrInterlocutoriIndexed = array();

        // get list of already messages
        $arrData = $this->DATABASE->RunQuickSelect('DISTINCT(`idxauthinterlocutor`)', SYSCFG_DB_PREFIX . 'mesaje',
            array('idxauthinterlocutor', '=', $this->AUTH->GetUserId())
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
            array('idxauthuniversitate', '=', $this->AUTH->GetUserId())
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

        foreach ($arrInterlocutori as $nKey => $arrInterlocutor){
            // add names and indexes to master list
            $this->DATA['interlocutori'][] = $arrInterlocutor['nume'];
            $this->DATA['idxinterlocutori'][] = $arrInterlocutor['idx'];
        }

        // get mesaje
        if ((int)PARAM(1) > 0){
            $arrMesaje = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'mesaje',
                array(
                    array('idxauthangajat', '=', (int)PARAM(1), 'AND'),
                    array('idxauthinterlocutor', '=', $this->AUTH->GetUserId())
                ),
                'tstamp'
            );

            if (is_array($arrMesaje) && !empty($arrMesaje)){
                foreach ($arrMesaje as $arrMesaj){
                    $this->DATA['mesaje'][] = array(
                        'mesaj' => $arrMesaj['mesaj'],
                        'altau' => (int)$arrMesaj['idxauthmesaj'] == $this->AUTH->GetUserId()
                    );
                }
            }
        }
    }break;
}
