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
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Tip Job:</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?= $loc['tipslujba'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Companie</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?= $loc['companie'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Oraș</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?= $loc['oras'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Domeniu</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?= $loc['domeniu_cv'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Competențe necesare</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?= $loc['competente'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Descriere</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?= $loc['descriere'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
