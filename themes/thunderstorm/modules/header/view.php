
    <header class="center-page">
        <a href="<?php echo qurl_l(''); ?>" id="header-logo" class="block reference imglink">
            <img src="<?php echo qurl_f('images/logo_final_blindhub.png'); ?>" alt="sigla blindhub" />
        </a>
        
        <div id="homepage-blindcontrol">
            <a href="#" id="hButtonSitewideHighContrast" class="block reference imglink">
                <img src="<?php echo qurl_f('images/icon_highcontrast_normal.png'); ?>" class="normal" />
                <img src="<?php echo qurl_f('images/icon_highcontrast_mouseover.png'); ?>" class="over" />
            </a>
            
            <a href="#" id="hButtonSitewideTextBigger" class="block reference imglink">
                <img src="<?php echo qurl_f('images/icon_plussizetext_normal.png'); ?>" class="normal" />
                <img src="<?php echo qurl_f('images/icon_plussizetext_mouseover.png'); ?>" class="over" />
            </a>
            
            <a href="#" id="hButtonSitewideTextSmaller" class="block reference imglink">
                <img src="<?php echo qurl_f('images/icon_minussizetext_normal.png'); ?>" class="normal" />
                <img src="<?php echo qurl_f('images/icon_minussizetext_mouseover.png'); ?>" class="over" />
            </a>
        </div>
        
        <div id="header-menu">
            <a href="<?php
                switch ((int)$this->AUTH->GetAdvancedDetail('tiputilizator'))
                {
                    case 0:{
                        echo qurl_l('home-nevaz');
                    }break;
                        
                    case 1:{
                        echo qurl_l('home-angajator');
                    }break;
                        
                    case 2:{
                        echo qurl_l('home-universitate');
                    }break;
                }
            ?>" class="bold">ACASĂ</a><span> | </span>
            <a href="<?php
                switch ((int)$this->AUTH->GetAdvancedDetail('tiputilizator'))
                {
                    case 0:{
                        echo qurl_l('cautaoportunitati');
                    }break;
                        
                    case 1:{
                        echo qurl_l('candidati');
                    }break;
                        
                    case 2:{
                        echo qurl_l('universitate-candidati');
                    }break;
                }
            ?>" class="bold"><?php
                if ((int)$this->AUTH->GetAdvancedDetail('tiputilizator') == 0)
                    echo 'OPORTUNITĂȚI';
                else echo 'CANDIDAȚI';
            ?></a><span> | </span>
            
            <a href="<?php echo qurl_l('mesaje'); ?>" class="bold">MESAJE</a><span> | </span>
            
            <a href="<?php
                switch ((int)$this->AUTH->GetAdvancedDetail('tiputilizator'))
                {
                    case 0:{
                        echo '#';
                    }break;
                        
                    case 1:{
                        echo qurl_l('interviu-angajator');
                    }break;
                        
                    case 2:{
                        echo qurl_l('universitate-interviu');
                    }break;
                }
            ?>" class="bold"><?php
                if ((int)$this->AUTH->GetAdvancedDetail('tiputilizator') == 0)
                    echo 'INSIGHT';
                else echo 'AGENDA';
            ?></a><span> | </span>
            
            <a href="<?php
                switch ((int)$this->AUTH->GetAdvancedDetail('tiputilizator'))
                {
                    case 0:{
                        echo qurl_l('profil-angajat');
                    }break;
                        
                    case 1:{
                        echo qurl_l('profil-angajator');
                    }break;
                        
                    case 2:{
                        echo qurl_l('profil-universitate');
                    }break;
                }
            ?>" class="bold">PROFIL</a>
        </div>
        <br />
    </header>