<div id="content">
    <div class="fullwidth" style="background: #FFF;">
        <div class="container">
            <div class="row">
                <div class="offset-lg-1 col-lg-10">
                    <?php if ($this->ROUTE->GetFlagsLanguage() == 'ro'): ?>
                    <img src="<?php echo qurl_f('images/banner-nologin.jpg'); ?>" class="fullwidth" />
                    <?php elseif ($this->ROUTE->GetFlagsLanguage() == 'fr') : ?>
                    <img src="<?php echo qurl_f('images/banner-nologin-fr.png'); ?>" class="fullwidth" />
                    <?php else: ?>
                    <img src="<?php echo qurl_f('images/banner-nologin-en.png'); ?>" class="fullwidth" />
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <br />

    <div class="container my-4">
        <div class="row">
            <div class="offset-lg-1 col-lg-10 pb-5">
                <div>
                    <p>
                        <?= $this->LANG('Conform estimărilor, în Europa sunt peste 30 de milioane de persoane cu deficiențe de vedere, iar rata medie a somajului în rândul acestui grup vulnerabil este de peste 75% (sursa Euroblind.org). Mai mult, un număr limitat de persoane nevăzătoare / cu deficiențe de vedere au urmat studii superioare sau au beneficiat de îndrumare în carieră.') ?>
                    </p>
                    <p>
                        <?= $this->LANG('În acest context, proiectul BlindHub propune o soluție digitală menită să faciliteze accesul persoanelor cu deficiențe de vedere atât pe piața muncii, cât și în mediul educațional. BLINDHub vine astfel în întâmpinarea nevoilor celor peste 50.000 de persoane cu deficiențe de vedere și apte de muncă, în contextul tendinței de digitalizare, dar și a nevoii tot mai mari în rândul angajatorilor de capital uman bine pregătit.') ?>
                    </p>

                    <h5 class="subtitlu mt-5"><?= $this->LANG('Succesul BLINDHUB se bazeaza pe trei piloni importanți:') ?> </h5>
                    <ul class="lista">
                        <li class="pb-2"><?= $this->LANG('o schimbare de mentalitate în ceea ce privește comunitatea nevăzătorilor sub aspectul îmbrățișării noilor tehnologii digitale;') ?>
                        </li>
                        <li class="pb-2"><?= $this->LANG('adoptarea unei noi viziuni asupra capitalului uman reprezentat de persoanele nevăzătoare') ?></li>
                        <li><?= $this->LANG('revoluția tehnologică care transformă interacțiunea nevăzătorilor cu angajatorii și mediul educațional') ?></li>
                    </ul>

                    <?php if ($_ENV['APPLE_STORE_URL'] || $_ENV['GOOGLE_PLAY_URL']): ?>
                    <h5 class="mt-5"><strong><?= $this->LANG('Intră în comunitate și conectează-te la BlindHUB!') ?></strong></h5>

                    <?php if ($_ENV['APPLE_STORE_URL']): ?>
                    <a href="<?= $_ENV['APPLE_STORE_URL'] ?>">
                        <img src= "<?= qurl_f('images/app-store.png'); ?>" style="height: 50px"/>
                    </a>
                    <?php endif; ?>

                    <?php if ($_ENV['GOOGLE_PLAY_URL']): ?>
                    <a href="<?= $_ENV['GOOGLE_PLAY_URL'] ?>">
                        <img src= "<?= qurl_f('images/google-play.png'); ?>" style="height: 50px"/>
                    </a>
                    <?php endif; ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>