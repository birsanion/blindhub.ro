<?php

function RomanianDate_to_MySQLDate($pcDate)
{
    return substr($pcDate,6,4).'-'.substr($pcDate,3,2).'-'.substr($pcDate,0,2).
        (strlen($pcDate)>10 ? ' '.substr($pcDate, 11) : '');
}


$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey'          => 'required',
        'idxauthnevazator' => 'required|numeric',
        'dataora'          => 'required|date:d/m/Y H:i',
    ]);

    $validation->validate();
    if ($validation->fails()) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users', [
        'apploginid', '=', $validation->getValue('userkey')
    ]);
    if ($arrUser === false) {
        throw new Exception("Eroare internă", 500);
    }
    if (empty($arrUser)) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrUser = $arrUser[0];
    switch ($arrUser['tiputilizator']) {
        case 1:
            $res = $this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'interviuri', [
                ['idxauthangajat', '=', (int)$validation->getValue('idxauthnevazator'), 'AND'],
                ['idxauthangajator', '=', (int)$arrUser['idx'], 'AND'],
                ['tstamp', '=', RomanianDate_to_MySQLDate($validation->getValue('dataora')) . ':00']
            ]);
            if ($res <= 0) {
                throw new Exception("Cerere invalidă", 400);
            }

            $res = $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'interviuri', 'initvideo',
                [
                    'initvideo' => 1
                ],
                [
                    ['idxauthangajat', '=', (int)$validation->getValue('idxauthnevazator'), 'AND'],
                    ['idxauthangajator', '=', (int)$arrUser['idx'], 'AND'],
                    ['tstamp', '=', RomanianDate_to_MySQLDate($validation->getValue('dataora')) . ':00']
                ]
            );
            if (!$res) {
                throw new Exception("Eroare internă", 500);
            }

            $arrData = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'interviuri', [
                ['idxauthangajat', '=', (int)$validation->getValue('idxauthnevazator'), 'AND'],
                ['idxauthangajator', '=', (int)$arrUser['idx'], 'AND'],
                ['tstamp', '=', RomanianDate_to_MySQLDate($validation->getValue('dataora')) . ':00']
            ]);
            if ($arrData === false) {
                throw new Exception("Eroare internă", 500);
            }

            $this->DATA['idxentry'] = (int)$arrData[0]['idx'];
            break;

        case 2:
            $res = $this->DATABASE->RunQuickCount(SYSCFG_DB_PREFIX . 'interviuri', [
                ['idxauthangajat', '=', (int)$validation->getValue('idxauthnevazator'), 'AND'],
                ['idxauthuniversitate', '=', (int)$arrUser['idx'], 'AND'],
                ['tstamp', '=', RomanianDate_to_MySQLDate($validation->getValue('dataora')) . ':00']
            ]);
            if ($res <= 0) {
                throw new Exception("Cerere invalidă", 400);
            }

            $res = $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'interviuri', 'initvideo',
                [
                    'initvideo' => 1
                ],
                [
                    ['idxauthangajat', '=', (int)$validation->getValue('idxauthnevazator'), 'AND'],
                    ['idxauthuniversitate', '=', (int)$arrUser['idx'], 'AND'],
                    ['tstamp', '=', RomanianDate_to_MySQLDate($validation->getValue('dataora')) . ':00']
                ]
            );
            if (!$res) {
                throw new Exception("Eroare internă", 500);
            }

            $arrData = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'interviuri', array(
                array('idxauthangajat', '=', (int)POST('idxauthnevazator'), 'AND'),
                array('idxauthuniversitate', '=', (int)$arrUser['idx'], 'AND'),
                array('tstamp', '=', RomanianDate_to_MySQLDate(POST('dataora')) . ':00')
            ));
            if ($arrData === false) {
                throw new Exception("Eroare internă", 500);
            }

            $this->DATA['idxentry'] = (int)$arrData[0]['idx'];
            break;


        default:
            throw new Exception("Cerere invalidă", 400);
    }
});
