<?php

require_once('system/thirdparty/vendor/autoload.php');

use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\ArchiveMode;
use OpenTok\Session;
use OpenTok\Role;

function RomanianDate_to_MySQLDate($pcDate)
{
    return substr($pcDate,6,4).'-'.substr($pcDate,3,2).'-'.substr($pcDate,0,2).
        (strlen($pcDate)>10 ? ' '.substr($pcDate, 11) : '');
}


$this->handleAPIRequest(function() {
    $validation = $this->validator->make($_POST, [
        'userkey'          => 'nullable',
        'idxauthnevazator' => 'required|numeric',
        'idxoferta'        => 'nullable|numeric',
        'datacalend'       => 'required|date:d/m/Y',
        'ora'              => 'required'
    ]);

    $validation->validate();
    if ($validation->fails()) {
        // $errors = $validation->errors();
        // $error = array_values($errors->firstOfAll())[0];
        throw new Exception("Cerere invalidă", 400);
    }

    $conds = [];
    if ($validation->getValue('userkey')) {
        $conds = [ 'apploginid', '=', $validation->getValue('userkey') ];
    } else if ($this->AUTH->IsAuthenticated()) {
        $conds = [ 'idx', '=', $this->AUTH->GetUserId() ];
    } else {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrUser = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'auth_users', $conds);
    if ($arrUser === false) {
        throw new Exception("Eroare internă", 500);
    }
    if (empty($arrUser)) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrUser = $arrUser[0];
    if ($arrUser['tiputilizator'] != 2) {
        throw new Exception("Cerere invalidă", 400);
    }

    $arrInterviu = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'interviuri', [
        ['idxauthangajat', '=', (int)$validation->getValue('idxauthnevazator'), 'AND'],
        ['idxauthuniversitate', '=', (int)$arrUser['idx'], 'AND'],
        ['idxobject', '=', (int)$validation->getValue('idxoferta')]
    ]);
    if ($arrInterviu === false) {
        throw new Exception("Eroare internă", 500);
    }
    if (empty($arrInterviu)) {
        try {
            $kOpentok = new OpenTok($_ENV['VONAGE_API_KEY'], $_ENV['VONAGE_API_SECRET']);
            $kSession = $kOpentok->createSession();
            $strSessionId = $kSession->getSessionId();
            $strNevazatorKey = $kOpentok->generateToken($strSessionId);
            $strInterlocutorKey = $kOpentok->generateToken($strSessionId);
        } catch (Exception $kEx) {
            throw new Exception('EROARE: nu se pot genera datele pentru videochat!', 500);
        }

        $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'interviuri', [
            'idxauthangajat',
            'idxauthuniversitate',
            'tstamp',
            'idxobject',
            'vonagesessid',
            'vonagenevaztoken',
            'vonageinterlocutortoken',
        ],[[
            'idxauthangajat'          => (int)$validation->getValue('idxauthnevazator'),
            'idxauthuniversitate'     => (int)$arrUser['idx'],
            'tstamp'                  => RomanianDate_to_MySQLDate($validation->getValue('datacalend')) . ' ' . $validation->getValue('ora') . ':00',
            'idxobject'               => (int)$validation->getValue('idxoferta'),
            'vonagesessid'            => $strSessionId,
            'vonagenevaztoken'        => $strNevazatorKey,
            'vonageinterlocutortoken' => $strInterlocutorKey
        ]]);
        if ($res === false) {
            throw new Exception("Eroare internă", 500);
        }

        $idxInterviu = $this->DATABASE->GetLastInsertID();
    } else {
        $res =$this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'interviuri', 'tstamp', [
            'tstamp' => RomanianDate_to_MySQLDate($validation->getValue('datacalend')) . ' ' . $validation->getValue('ora') . ':00'
        ], [
            ['idxauthangajat', '=', (int)$validation->getValue('idxauthnevazator'), 'AND'],
            ['idxauthuniversitate', '=', (int)$arrUser['idx'], 'AND'],
            ['idxobject', '=', (int)$validation->getValue('idxoferta')]
        ]);

        if ($res === false) {
            throw new Exception("Eroare internă", 500);
        }

        $idxInterviu = $arrInterviu[0]['idx'];
    }

    $titlu = $this->LANG("interview_invitation_title");
    $mesaj = sprintf($this->LANG("interview_invitation_message"), $validation->getValue('datacalend'), $validation->getValue('ora'));
    $arrNotificare = $this->DATABASE->RunQuickSelect('*', SYSCFG_DB_PREFIX . 'notificari', [
        ['idxauth', '=', $validation->getValue('idxauthnevazator'), 'AND'],
        ['idxinterviu', '=', $idxInterviu]
    ]);
    if ($arrNotificare === false) {
        throw new Exception("Eroare internă", 500);
    }
    if (empty($arrNotificare)) {
        $res = $this->DATABASE->RunQuickInsert(SYSCFG_DB_PREFIX . 'notificari', [
            'idxauth',
            'idxinterviu',
            'titlu',
            'mesaj',
        ], [[
            'idxauth'     => $validation->getValue('idxauthnevazator'),
            'idxinterviu' => $idxInterviu,
            'titlu'       => $titlu,
            'mesaj'       => $mesaj,
        ]]);

        if (!$res) {
            throw new Exception("Eroare internă", 500);
        }
    } else {
        $res = $this->DATABASE->RunQuickUpdate(SYSCFG_DB_PREFIX . 'notificari', 'titlu,mesaj', [
            'titlu' => $titlu,
            'mesaj' => $mesaj,
        ], [
            ['idxauth', '=', $validation->getValue('idxauthnevazator'), 'AND'],
            ['idxinterviu', '=', $idxInterviu]
        ]);
        if ($res === false) {
            throw new Exception("Eroare internă", 500);
        }
    }
});
