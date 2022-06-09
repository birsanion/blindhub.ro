    <header id="header" class="header shadow bg-white border-bottom">
        <div class="container">
            <div class="offset-lg-1 col-lg-10 d-flex align-items-center justify-content-between">

                <a href="<?= qurl_l(''); ?>" class="logo d-flex align-items-center">
                    <?php if ($this->ROUTE->GetFlagsLanguage() == 'ro'): ?>
                    <img src="<?= qurl_f('images/logo_final_blindhub.png'); ?>" alt="">
                    <?php elseif ($this->ROUTE->GetFlagsLanguage() == 'de'): ?>
                    <img src="<?= qurl_f('images/logo_final_blindhub_de.png'); ?>" alt="">
                    <?php else: ?>
                    <img src="<?= qurl_f('images/logo_final_blindhub_en.png'); ?>" alt="">
                    <?php endif; ?>
                </a>

                <a href="<?= $_ENV['PAYMENT_PROCESSOR'] ? qurl_l('doneaza') : '#' ?>" class="logo d-flex align-items-center">
                    <img
                        src="<?php echo qurl_f('images/icon_doneaza_normal.png'); ?>"
                        src-normal="<?php echo qurl_f('images/icon_doneaza_normal.png'); ?>"
                        src-over="<?php echo qurl_f('images/icon_doneaza_mouseover.png'); ?>"
                        class="img-hover"
                        style="margin-right:7px" />
                    <h5><strong><?= $this->LANG('Donează') ?></strong></h5>
                </a>

                <div>
                    <?php if ($this->ROUTE->GetFlagsLanguage() == 'ro'): ?>
                    <a href="#" class="logo ">
                        <img src="<?= qurl_f('images/logo_fundatia-orange.png'); ?>" alt="">
                    </a>
                    <?php endif;?>
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
        </div>
        <div class="container">
            <div class="offset-lg-1 col-lg-10 d-flex align-items-center justify-content-end">

            </div>
        </div>
        <div class="container mt-4">
            <div class="offset-lg-1 col-lg-10 d-flex justify-content-between">
                <nav id="navbar" class="navbar" >
                    <ul>
                        <li class="dropdown"><a href="#"><span><?= $this->LANG('Despre proiect') ?></span> <i class="bi bi-chevron-down"></i></a>
                            <ul>
                                <li><a href="<?= qurl_l('despre-obiective') ?>"><?= $this->LANG('Obiective') ?></a></li>
                                <?php if ($this->ROUTE->GetFlagsLanguage() == 'ro') :?>
                                <li><a href="<?= qurl_l('evenimente') ?>">Evenimente</a></li>
                                <?php else: ?>
                                <li><a href="<?= qurl_l('avantaje') ?>"><?= $this->LANG('Avantaje') ?></a></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <li class="dropdown"><a href="#"><span><?= $this->LANG('Comunitate BlindHub') ?></span> <i class="bi bi-chevron-down"></i></a>
                            <ul>
                                <?php if ($this->ROUTE->GetFlagsLanguage() == 'ro') :?>
                                <li><a href="<?= qurl_l('comunitate-misiune') ?>">Misiune</a></li>
                                <li><a href="<?= qurl_l('comunitate-centre') ?>">Centre BlindHUB</a></li>
                                <?php endif; ?>
                                <li><a href="<?= qurl_l('comunitate-echipa') ?>"><?= $this->LANG('Echipa') ?></a></li>
                                <li><a href="<?= qurl_l('comunitate-ambasadori') ?>"><?= $this->LANG('Ambasadori') ?></a></li>
                            </ul>
                        </li>
                        <li class="dropdown"><a href="#"><span><?= $this->LANG('Dezvoltare profesională') ?></span> <i class="bi bi-chevron-down"></i></a>
                            <ul>
                                <li><a href="<?= qurl_l('dezvoltare-profesionala-cv') ?>"><?= $this->LANG('CV') ?></a></li>
                                <li><a href="<?= qurl_l('dezvoltare-profesionala-interviu') ?>"><?= $this->LANG('Interviu') ?></a></li>
                                <li><a href="<?= qurl_l('dezvoltare-profesionala-beneficii') ?>"><?= $this->LANG('Beneficii pentru companie') ?></a></li>
                            </ul>
                        </li>
                        <li class="dropdown"><a href="#"><span><?= $this->LANG('Educație și formare') ?></span> <i class="bi bi-chevron-down"></i></a>
                            <ul>
                                <li><a href="<?= qurl_l('educatie-universitatea-incluziva') ?>"><?= $this->LANG('Universitatea incluzivă') ?></a></li>
                            </ul>
                        </li>

                    </ul>
                    <i class="bi bi-list mobile-nav-toggle"></i>
                </nav>
                <?php if ($this->ROUTE->GetFlagsLanguage() == 'ro') :?>
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
                <?php endif; ?>
            </div>
        </div>
    </header>