<div id="content">
    <div class="container my-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu "><?= $this->LANG('Obiective') ?></h1>
                <article>
                    <p><?= $this->LANG('<strong>Proiectul BlindHUB</strong> este implementat de către <strong>Asociația Nevăzătorilor din România</strong> în parteneriat cu <strong>Universitatea din București</strong> și este finanțat de <strong>Fundația Orange</strong> prin <strong>Programul Lumea prin Culoare și Sunet</strong> - cel mai mare fond dedicat persoanelor cu deficiențe de vedere / auz din România.') ?></p>
                    <p><strong><?= $this->LANG('Scopul proiectului') ?></strong>: <?= $this->LANG('Facilitarea accesului persoanelor nevăzătoare și cu deficient de vedere pe piața muncii și în mediul educațional universitar.') ?></p>
                    <h5 class="mt-5 subtitlu"><?= $this->LANG('Obiective') ?>:</h5>
                    <ul class="lista">
                        <li><?= $this->LANG('Dezvoltarea unei aplicații informatice (BlindHUB) care să asigure informare, orientare și consiliere pentru cariera și mediere digitală pe piața muncii între persoanele nevăzătoare și cu deficiente de vedere și angajatori') ?></li>
                        <li><?= $this->LANG('Formarea abilităților de prezentare a propriei persoane și de susținere a unui interviu de angajare folosind aplicația BlindHUB pentru un număr de cel puțin 200 de persoane nevăzătoare și cu deficient de vedere prin organizarea a 10 seminarii la nivel național cu participarea angajatorilor') ?></li>
                        <li><?= $this->LANG('Înființarea și dotarea cu tehnică informatică și de accesibilizare a 2 centre BlindHUB – comunitați de dezvoltare profesională pentru persoanele nevăzătoare și cu deficient de vedere') ?></li>
                    </ul>
                    <br />
                    <?php if ($this->ROUTE->GetFlagsLanguage() == 'ro'): ?>
                    <p>Proiectul se deruleaza în perioada <strong>octombrie 2020 - octombrie 2022</strong>, finanțarea totala este <strong>532.000</strong> lei, contribuția <strong>Fundației Orange</strong> fiind de <strong>482.000</strong> lei.</p>
                    <?php endif; ?>
                </article>
            </div>
        </div>
    </div>
</div>