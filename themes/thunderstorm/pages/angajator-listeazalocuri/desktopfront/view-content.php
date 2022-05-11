    <div id="content" class="container my-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Posturile mele</h1>
                <?php if ($this->DATA['errormsg']): ?>
                <div class="alert alert-danger mb-5" role="alert">
                    <?= $this->DATA['errormsg'] ?>
                </div>
                <?php endif; ?>
                <?php if (!count($this->DATA['locuri'])): ?>
                <h4><strong>Nu aveți joburi postate.</strong></h4>
                <p>Pentru a posta un anunț selectați opțiunea adaugă loc de muncă din meniul principal.</p>
                <?php endif; ?>

                <?php foreach ($this->DATA['locuri'] as $arrLoc):?>
                <div class="card profile-header shadow-lg mb-4">
                    <div class="body">
                        <div class="row">
                            <div class="col-12">
                                <h3 class="m-t-0 m-b-0"><strong><?= $arrLoc['titlu'] ?></strong></h3>
                                <h6>Tip Job: <?= $arrLoc['tipslujba'] ?></h6>
                                <h6>Domeniu: <?= $arrLoc['domeniu_cv'] ?></h6>
                                <h6>Oraș: <?= $arrLoc['oras'] ?></h6>
                                <h6>Competențe necesare: <?= $arrLoc['competente'] ?></h6>
                                <h6>Descriere: <?= $arrLoc['descriere'] ?></h6>
                                <h6>Postat pe: <?= $arrLoc['datapostare'] ?></h6>
                                <div class="mt-3">
                                    <a class="btn btn-primary rounded-pill px-4" href="<?= qurl_l('angajator-editeazaloc/' . $arrLoc['idx']); ?>">Editează</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

