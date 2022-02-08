<?php
////////////////////////////////////////////////////////////////////////////////
// Part of theme Thunderstorm, of Quick Web Frame
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.

?>
<div class="content">
    PAGINA INEXISTENTA
    
    <div class="section">
        <h2>CORE/ROUTE/AUTH</h2>
        <h3><?php echo $this->LANG('var app'); ?></h3>
        <div class="content">
            <table>
                <tr>
                    <td>QUICKWEBFRAME_VERSION</td>
                    <td><?php echo QUICKWEBFRAME_VERSION; ?></td>
                </tr>
                <tr>
                    <td>CORE->GetQWFVersionStage()</td>
                    <td><?php echo $this->CORE->GetQWFVersionStage(); ?></td>
                </tr>
                <tr>
                    <td>CORE->GetQWFReleaseDate()</td>
                    <td><?php echo $this->CORE->GetQWFReleaseDate(); ?></td>
                </tr>
                <tr>
                    <td>DEBUGMODE</td>
                    <td><?php echo DEBUGMODE ? 'true' : 'false'; ?></td>
                </tr>
                <tr>
                    <td>CORE->GetStartTime()</td>
                    <td><?php echo $this->CORE->GetStartTime(); ?></td>
                </tr>
                <tr>
                    <td>CORE->GetExecutionTime() (so far)</td>
                    <td><?php echo $this->CORE->GetExecutionTime(); ?></td>
                </tr>
                <tr>
                    <td>CORE->GetSessionType()</td>
                    <td><?php
                        echo DebugTranslateIntConstants(
                            $this->CORE->GetSessionType(),
                            array(
                                SESSTYPE_CLEAN => 'SESSTYPE_CLEAN',
                                SESSTYPE_URL => 'SESSTYPE_URL'
                            )
                        );
                     ?></td>
                </tr>
                <tr>
                    <td>CORE->GetAppURLType()</td>
                    <td><?php
                        echo DebugTranslateIntConstants(
                            $this->CORE->GetAppURLType(),
                            array(
                                REQTYPE_CLEAN => 'REQTYPE_CLEAN',
                                REQTYPE_PARAM => 'REQTYPE_PARAM'
                            )
                        );
                     ?></td>
                </tr>
                <tr>
                    <td>CORE->GetCallingMethod()</td>
                    <td><?php
                        echo DebugTranslateIntConstants(
                            $this->CORE->GetCallingMethod(),
                            array(
                                CALLMETH_HTTP => 'CALLMETH_HTTP',
                                CALLMETH_CLI => 'CALLMETH_CLI'
                            )
                        );
                     ?></td>
                </tr>
                <tr>
                    <td>CORE->GetScriptFilepath()</td>
                    <td><?php echo $this->CORE->GetScriptFilepath(); ?></td>
                </tr>
                <tr>
                    <td>CORE->GetAppURLAbsolute()</td>
                    <td><?php echo $this->CORE->GetAppURLAbsolute(); ?></td>
                </tr>
                <tr>
                    <td>CORE->GetAppURLRelative()</td>
                    <td><?php echo $this->CORE->GetAppURLRelative(); ?></td>
                </tr>
                <tr>
                    <td>CORE->GetRequestURLAbsolute()</td>
                    <td><?php echo $this->CORE->GetRequestURLAbsolute(); ?></td>
                </tr>
                <tr>
                    <td>CORE->GetRequestURLRelative()</td>
                    <td><?php echo $this->CORE->GetRequestURLRelative(); ?></td>
                </tr>
                
                <tr>
                    <td>AUTH->IsAuthenticated()</td>
                    <td><?php echo $this->AUTH->IsAuthenticated() ? 'true' : 'false'; ?></td>
                </tr>
                <tr>
                    <td>AUTH->GetUser()</td>
                    <td><?php echo $this->AUTH->GetUser(); ?></td>
                </tr>
                
                <tr>
                    <td>CORE->GetBrowserType()</td>
                    <td><?php
                        echo DebugTranslateIntConstants(
                            $this->CORE->GetBrowserType(),
                            array(
                                BRWSR_TYPE_UNKNOWN => 'BRWSR_TYPE_UNKNOWN',
                                BRWSR_TYPE_MOBILE => 'BRWSR_TYPE_MOBILE',
                                BRWSR_TYPE_BOT => 'BRWSR_TYPE_BOT',
                                BRWSR_TYPE_CLI => 'BRWSR_TYPE_CLI',
                                BRWSR_TYPE_DESKTOP => 'BRWSR_TYPE_DESKTOP'
                            )
                        );
                     ?></td>
                </tr>
                <tr>
                    <td>CORE->GetBrowserTypeReal()</td>
                    <td><?php
                        echo DebugTranslateIntConstants(
                            $this->CORE->GetBrowserTypeReal(),
                            array(
                                BRWSR_TYPE_UNKNOWN => 'BRWSR_TYPE_UNKNOWN',
                                BRWSR_TYPE_MOBILE => 'BRWSR_TYPE_MOBILE',
                                BRWSR_TYPE_BOT => 'BRWSR_TYPE_BOT',
                                BRWSR_TYPE_CLI => 'BRWSR_TYPE_CLI',
                                BRWSR_TYPE_DESKTOP => 'BRWSR_TYPE_DESKTOP'
                            )
                        );
                     ?></td>
                </tr>
                <tr>
                    <td>CORE->GetBrowserResolution()</td>
                    <td><?php echo $this->CORE->GetBrowserResolution(); ?></td>
                </tr>
                <tr>
                    <td>CORE->GetBrowserName(true)</td>
                    <td><?php echo $this->CORE->GetBrowserName(true); ?></td>
                </tr>
                <tr>
                    <td>CORE->GetBrowserHardware()</td>
                    <td><?php
                        echo DebugTranslateIntConstants(
                            $this->CORE->GetBrowserHardware(),
                            array(
                                PLATFORM_PC => 'PLATFORM_PC',
                                PLATFORM_ANDROID => 'PLATFORM_ANDROID',
                                PLATFORM_IOS => 'PLATFORM_IOS',
                                PLATFORM_WINDOWSPHONE => 'PLATFORM_WINDOWSPHONE',
                                PLATFORM_SONYPLAYSTATION => 'PLATFORM_SONYPLAYSTATION',
                                PLATFORM_OTHERMOBILE => 'PLATFORM_OTHERMOBILE'
                            )
                        );
                     ?></td>
                </tr>
                
                <tr>
                    <td>ROUTE->GetFlagsSection()</td>
                    <td><?php echo $this->ROUTE->GetFlagsSection(); ?></td>
                </tr>
                <tr>
                    <td>ROUTE->GetFlagsLanguage()</td>
                    <td><?php echo $this->ROUTE->GetFlagsLanguage(); ?></td>
                </tr>
                <tr>
                    <td>ROUTE->GetTargetsRequested()</td>
                    <td><?php echo $this->ROUTE->GetTargetsRequested(); ?></td>
                </tr>
                <tr>
                    <td>ROUTE->GetPage()</td>
                    <td><?php echo $this->ROUTE->GetPage(); ?></td>
                </tr>
                <tr>
                    <td>ROUTE->GetPageValue()</td>
                    <td><?php echo $this->ROUTE->GetPageValue(); ?></td>
                </tr>
                <tr>
                    <td>PARAMS()</td>
                    <td><?php echo PARAMS(); ?></td>
                </tr>
                <?php
                    for ($i=0; $i <= PARAMS(); $i++){
                ?>
                    <tr>
                        <td>PARAM(<?php echo $i; ?>)</td>
                        <td><?php echo PARAM($i); ?></td>
                    </tr>
                <?php
                    }
                ?>
                
                <?php
                    foreach($this->CONFIG->EnumerateKeys() as $strKey){
                ?>
                    <tr>
                        <td>CONFIG->Get(<?php echo $strKey; ?>)</td>
                        <td><?php echo is_bool($this->CONFIG->Get($strKey)) ?
                            ($this->CONFIG->Get($strKey) ? 'true' : 'false') :
                            $this->CONFIG->Get($strKey); ?></td>
                    </tr>
                <?php
                    }
                ?>
            </table>
        </div>
    </div>
    
    <div class="section">
        <h2>$this->LANG()</h2>
        <h3><?php echo $this->LANG('var lang'); ?></h3>
        <div class="content">
            <table>
                <?php
                    $arrDebug=array();
                    GetArrayAsDebugArray($this->LANGUAGE, $arrDebug, '$this->LANGUAGE');
                    
                    foreach ($arrDebug as $arrElem){
                ?>
                <tr>
                    <td><?php echo $arrElem['key']; ?></td>
                    <td><?php echo $arrElem['val']; ?></td>
                </tr>
                <?php
                    }
                ?>
            </table>
        </div>
    </div>
</div>
