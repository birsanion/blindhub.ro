    <header id="header" class="header shadow bg-white border-bottom">
        <div class="container">
            <div class="offset-lg-1 col-lg-10 d-flex align-items-center justify-content-between">

                <a href="<?= qurl_l(''); ?>" class="logo d-flex align-items-center">
                    <img src="<?= qurl_f('images/logo_final_blindhub.png'); ?>" alt="">
                </a>

                <a href="#" class="logo d-flex align-items-center float-right">
                    <img src="<?= qurl_f('images/logo_fundatia-orange.png'); ?>" alt="">
                </a>
            </div>
        </div>
        <div class="container">
            <div class="offset-lg-1 col-lg-10 d-flex align-items-center justify-content-end">
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
            </div>
        </div>
        <div class="container mt-4">
            <div class="offset-lg-1 col-lg-10 d-flex justify-content-between">
                <nav id="navbar" class="navbar" >
                    <ul>
                        <li class="dropdown"><a href="#"><span>Despre proiect</span> <i class="bi bi-chevron-down"></i></a>
                            <ul>
                                <li><a href="<?= qurl_l('despre-obiective') ?>">Obiective</a></li>
                                <li><a href="<?= qurl_l('evenimente') ?>">Evenimente</a></li>
                            </ul>
                        </li>
                        <li class="dropdown"><a href="#"><span>Comunitate BlindHub</span> <i class="bi bi-chevron-down"></i></a>
                            <ul>
                                <li><a href="<?= qurl_l('comunitate-misiune') ?>">Misiune</a></li>
                                <li><a href="<?= qurl_l('comunitate-centre') ?>">Centre BlindHUB</a></li>
                                <li><a href="<?= qurl_l('comunitate-echipa') ?>">Echipa</a></li>
                                <li><a href="<?= qurl_l('comunitate-ambasadori') ?>">Ambasadori</a></li>
                            </ul>
                        </li>
                        <li class="dropdown"><a href="#"><span>Dezvoltare profesională</span> <i class="bi bi-chevron-down"></i></a>
                            <ul>
                                <li><a href="<?= qurl_l('dezvoltare-profesionala-cv') ?>">CV</a></li>
                                <li><a href="<?= qurl_l('dezvoltare-profesionala-interviu') ?>">Interviu</a></li>
                                <li><a href="<?= qurl_l('dezvoltare-profesionala-beneficii') ?>">Beneficii pentru companie</a></li>
                            </ul>
                        </li>
                        <li class="dropdown"><a href="#"><span>Educație și formare</span> <i class="bi bi-chevron-down"></i></a>
                            <ul>
                                <li><a href="<?= qurl_l('educatie-universitatea-incluziva') ?>">Universitatea incluzivă</a></li>
                            </ul>
                        </li>

                    </ul>
                    <i class="bi bi-list mobile-nav-toggle"></i>
                </nav>
                <nav class="navbar" >
                    <ul>
                        <?php if ($this->AUTH->IsAuthenticated()): ?>

                        <?php if ($this->AUTH->GetAdvancedDetail('tiputilizator') == 0): ?>
                        <li><a class="p-0" href="<?= qurl_l('home-nevaz'); ?>"><span>Contul meu</span></a></li>
                        <?php elseif ($this->AUTH->GetAdvancedDetail('tiputilizator') == 1): ?>
                        <li><a class="p-0" href="<?= qurl_l('home-angajator'); ?>"><span>Contul meu</span></a></li>
                        <?php elseif ($this->AUTH->GetAdvancedDetail('tiputilizator') == 2): ?>
                        <li><a class="p-0" href="<?= qurl_l('home-universitate'); ?>"><span>Contul meu</span></a></li>
                        <?php endif; ?>

                        <?php else: ?>
                        <li class="dropdown"><a class="p-0" href="#"><span>Conectare</span> <i class="bi bi-chevron-down"></i></a>
                            <ul>
                                <li><a href="<?php echo qurl_l('conecteaza-candidat'); ?>">Candidat</a></li>
                                <li><a href="<?php echo qurl_l('conecteaza-angajator'); ?>">Angajator</a></li>
                                <li><a href="<?php echo qurl_l('conecteaza-universitate'); ?>">Universitate</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

<!--    <header class="center-page">
        <div style="position: relative; height: 110px;">
            <a href="<?php echo qurl_l(''); ?>" id="header-logo" class="block reference imglink">
                <img src="<?php echo qurl_f('images/logo_final_blindhub.png'); ?>" alt="sigla blindhub" />
            </a>
            <a id="header-logo-fundatia" class="block reference">
                <img src="<?php echo qurl_f('images/logo_fundatia-orange.png'); ?>" alt="sigla fundatia orange" />
            </a>
        </div>

        <div id="header-precontrol">
            <ul class="menu-root" style="position: absolute; right: 120px;">
                <li class="menu-level-0">
                    <a>Conectează-te la BlindHUB</a>

                    <ul class="submenu-level-1">
                        <li class="menu-level-1"><a href="<?php echo qurl_l('conecteaza-candidat'); ?>">Candidat</a></li>
                        <li class="menu-level-1"><a href="<?php echo qurl_l('conecteaza-angajator'); ?>">Angajator</a></li>
                        <li class="menu-level-1"><a href="<?php echo qurl_l('conecteaza-universitate'); ?>">Universitate</a></li>
                    </ul>
                </li>
            </ul>
        </div>

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

            <ul class="menu-root">
                <li class="menu-level-0">
                    <a>Despre proiect</a>

                    <ul class="submenu-level-1">
                        <li class="menu-level-1"><a href="<?php echo qurl_l('despre-obiective'); ?>">Obiective</a></li>
                        <li class="menu-level-1"><a href="<?php echo qurl_l('evenimente'); ?>">Evenimente</a></li>
                    </ul>
                </li>
                <li class="menu-level-0">
                    <a>Comunitate BlindHub</a>

                    <ul class="submenu-level-1">
                        <li class="menu-level-1"><a href="<?php echo qurl_l('comunitate-misiune'); ?>">Misiune</a></li>
                        <li class="menu-level-1"><a href="<?php echo qurl_l('comunitate-centre'); ?>">Centre BlindHUB</a></li>
                        <li class="menu-level-1"><a href="<?php echo qurl_l('comunitate-echipa'); ?>">Echipa</a></li>
                        <li class="menu-level-1"><a href="<?php echo qurl_l('comunitate-ambasadori'); ?>">Ambasadori</a></li>
                    </ul>
                </li>
                <li class="menu-level-0">
                    <a>Dezvoltare profesională</a>

                    <ul class="submenu-level-1">
                        <li class="menu-level-1"><a href="<?php echo qurl_l('dezvoltare-profesionala-cv'); ?>">CV</a></li>
                        <li class="menu-level-1"><a href="<?php echo qurl_l('dezvoltare-profesionala-interviu'); ?>">Interviu</a></li>
                        <li class="menu-level-1"><a href="<?php echo qurl_l('dezvoltare-profesionala-beneficii'); ?>">Beneficii pentru companie</a></li>
                    </ul>
                </li>
                <li class="menu-level-0">
                    <a>Educație și formare</a>

                    <ul class="submenu-level-1">
                        <li class="menu-level-1"><a href="<?php echo qurl_l('educatie-universitatea-incluziva'); ?>">Universitatea incluzivă</a></li>
                    </ul>
                </li>
            </ul>

            <?php
            /*
            <a href="<?php echo qurl_l('nologin-comunitatea'); ?>" class="bold">COMUNITATEA BLINDHUB</a><span> | </span>
            <a href="<?php echo qurl_l('auth-angajat'); ?>" class="bold">ANGAJAT</a><span> | </span>
            <a href="<?php echo qurl_l('auth-angajator'); ?>" class="bold">ANGAJATORI</a><span> | </span>
            <a href="<?php echo qurl_l('auth-universitate'); ?>" class="bold">UNIVERSITĂȚI</a><span> | </span>
            <a href="<?php echo qurl_l('nologin-dezvoltareprofesionala'); ?>" class="bold">DEZVOLTARE PROFESIONALĂ</a><span> | </span>
            <a href="<?php echo qurl_l('nologin-educatiesiformare'); ?>" class="bold">EDUCAȚIE ȘI FORMARE</a>
            */
            ?>
        </div>
        <br />
    </header>-->