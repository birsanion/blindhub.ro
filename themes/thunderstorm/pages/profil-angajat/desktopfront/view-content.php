<div class="container">
    <div class="row my-5">
        <div class="offset-lg-1 col-lg-10">
            <h1 class="titlu">Profil</h1>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="<?= qurl_file('media/uploads/'. $this->DATA['details']['img']) ?>" alt="Admin" class="rounded-circle" width="150">
                                <div class="mt-3">
                                    <h3><strong><?= $this->DATA['details']['nume'] . " " . $this->DATA['details']['prenume']  ?></strong></h3>
                                    <p class="text-secondary"><?= $this->AUTH->GetUsername() ?></p>
                                </div>
                                <!--<div class="row mt-4">
                                    <div class="col-sm-12">
                                        <a class="btn btn-warning rounded-pill px-4" href="<?= qurl_l('resetare-parola') ?>">
                                            <strong>Schimbare parolă</strong>
                                        </a>
                                    </div>
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card shadow-lg mb-3">
                        <div class="card-body">
                            <h4><strong>Cont</strong></h4>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Grad de handicap</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?= $this->DATA['details']['gradhandicap'] ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Nevoi specifice de adaptare</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?= $this->DATA['details']['nevoispecifice'] ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                  <a class="btn btn-primary rounded-pill px-4" href="<?= qurl_l('editeaza-profil-angajat') ?>"><strong>Editare cont</strong></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow-lg mb-3">
                        <div class="card-body">
                            <h4><strong>CV</strong></h4>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Orase</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?= $this->DATA['details']['orase'] ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Sectoare de activitate</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?= $this->DATA['details']['domenii_cv'] ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                  <a class="btn btn-primary rounded-pill px-4" href="<?= qurl_l('cvitae') ?>"><strong>Actualizează CV</strong></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>