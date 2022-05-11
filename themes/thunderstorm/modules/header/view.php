    <header id="header" class="header shadow bg-white">
        <div class="container">
            <div class="offset-lg-1 col-lg-10 d-flex align-items-center justify-content-between">

                <a href="<?= qurl_l(''); ?>" class="logo d-flex align-items-center">
                    <img src="<?= qurl_f('images/logo_final_blindhub.png'); ?>" alt="">
                </a>

                <div id="homepage-blindcontrol">
                    <a href="#" id="hButtonSitewideHighContrast" class="block reference imglink">
                        <img src="<?= qurl_f('images/icon_highcontrast_normal.png'); ?>" class="normal" />
                        <img src="<?= qurl_f('images/icon_highcontrast_mouseover.png'); ?>" class="over" />
                    </a>

                    <a href="#" id="hButtonSitewideTextBigger" class="block reference imglink">
                        <img src="<?= qurl_f('images/icon_plussizetext_normal.png'); ?>" class="normal" />
                        <img src="<?= qurl_f('images/icon_plussizetext_mouseover.png'); ?>" class="over" />
                    </a>

                    <a href="#" id="hButtonSitewideTextSmaller" class="block reference imglink">
                        <img src="<?= qurl_f('images/icon_minussizetext_normal.png'); ?>" class="normal" />
                        <img src="<?= qurl_f('images/icon_minussizetext_mouseover.png'); ?>" class="over" />
                    </a>
                </div>
            </div>
        </div>

        <div class="container mt-4">
            <div class="offset-lg-1 col-lg-10 d-flex justify-content-between">
                <nav id="navbar" class="navbar">
                    <?php if ($this->AUTH->GetAdvancedDetail('tiputilizator') == 0): ?>
                    <ul>
                        <li>
                            <a href="<?= qurl_l('home-nevaz') ?>"><span>Acasă</span></a>
                        </li>
                        <li><a href="<?= qurl_l('cautaoportunitati') ?>"><span>Oportunități</span></a></li>
                        <li><a href="<?= qurl_l('mesaje') ?>"><span>Mesaje</span></a></li>
                        <li><a href="<?= qurl_l('interviu-nevaz') ?>"><span>Agenda</span></a></li>
                        <li><a href="<?= qurl_l('profil-angajat') ?>"><span>Profil</span></a></li>
                    </ul>
                    <?php elseif ($this->AUTH->GetAdvancedDetail('tiputilizator') == 1): ?>
                    <ul>
                        <li>
                            <a href="<?= qurl_l('home-angajator') ?>"><span>Acasă</span></a>
                        </li>
                        <li><a href="<?= qurl_l('mesaje') ?>"><span>Mesaje</span></a></li>
                        <li><a href="<?= qurl_l('interviu-angajator') ?>"><span>Agenda</span></a></li>
                        <li><a href="<?= qurl_l('profil-angajator') ?>"><span>Profil</span></a></li>
                    </ul>
                    <?php elseif ($this->AUTH->GetAdvancedDetail('tiputilizator') == 2): ?>
                    <ul>
                        <li>
                            <a href="<?= qurl_l('home-universitate') ?>"><span>Acasă</span></a>
                        </li>
                        <li><a href="<?= qurl_l('universitate-candidati') ?>"><span>Candidați</span></a></li>
                        <li><a href="<?= qurl_l('mesaje') ?>"><span>Mesaje</span></a></li>
                        <li><a href="<?= qurl_l('universitate-interviu') ?>"><span>Agenda</span></a></li>
                        <li><a href="<?= qurl_l('profil-universitate') ?>"><span>Profil</span></a></li>
                    </ul>
                    <?php endif; ?>
                    <i class="bi bi-list mobile-nav-toggle"></i>
                </nav>
                <form method="post" enctype="multipart/form-data" name="frm_login" action="<?= qurl_l('') ?>">
                    <button type="submit" name="hButtonLogout" class="btn btn-link"><strong>Deloghează-te</strong></button>
                    <input type="hidden" name="hSpecial_AUTH_Action" id="hSpecial_AUTH_Action" value="logout" />
                </form>
            </div>
        </div>
    </header>


    <!--
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
    -->