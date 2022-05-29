    <div id="content" class="container my-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Aplicațiile mele</h1>
                <?php if (!count($this->DATA['locuri'])): ?>
                <h4><strong>Până acum nu ați aplicat la nici o slujbă.</strong></h4>
                <?php endif; ?>

                <?php foreach ($this->DATA['locuri'] as $loc): ?>
                    <div class="card shadow-lg mb-4">
                        <div class="card-body">
                            <h4><strong><?= $loc['titlu'] ?></strong></h4>
                            <h5><?= $loc['vechimeanunt'] ?></h5>
                            <h6>Tip Job: <?= $loc['tipslujba'] ?></h6>
                            <h6>Companie: <?= $loc['companie'] ?></h6>
                            <h6>Oraș: <?= $loc['oras'] ?></h6>
                            <h6>Domeniu: <?= $loc['domeniu_cv'] ?></h6>
                            <h6>Competențe necesare: <?= $loc['competente'] ?></h6>
                            <h6>Descriere: <?= $loc['descriere'] ?></h6>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
