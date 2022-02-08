<?php

if ($this->AUTH->GetHasHadActions() && $this->AUTH->GetLastActionResult() != 0){
    $this->GLOBAL['errormsg'] = DebugTranslateIntConstants(
        $this->AUTH->GetLastActionResult(),
        array(
            AUTH_USERNOTEXISTENT => 'AUTH_USERNOTEXISTENT',
            AUTH_MULTIPLEUSERS => 'AUTH_MULTIPLEUSERS',
            AUTH_WRONGPASS => 'AUTH_WRONGPASS',
            AUTH_CANNOTCHANGE => 'AUTH_CANNOTCHANGE',
            AUTH_USEREXISTSALREADY => 'AUTH_USEREXISTSALREADY',
            AUTH_DATABASEERR => 'AUTH_DATABASEERR',
            AUTH_INIT_DIFFDEVICE => 'AUTH_INIT_DIFFDEVICE',
            AUTH_INIT_SESSEXP => 'AUTH_INIT_SESSEXP',
            AUTH_IPNOTALLOWED => 'AUTH_IPNOTALLOWED',
            AUTH_USERNOTALLOWED => 'AUTH_USERNOTALLOWED'
        )
    );
}
