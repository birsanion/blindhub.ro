<div id="content" class="container">
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
                                    <h3><strong><?= $this->DATA['details']['companie'] ?></strong></h3>
                                    <p class="text-secondary mb-4"><?= $this->AUTH->GetUsername() ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card shadow mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Adresă</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?= $this->DATA['details']['adresa'] ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Orașe</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?= $this->DATA['details']['orase'] ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Domenii</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?= $this->DATA['details']['domenii_cv'] ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Certificat de înregistrare fiscală</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?= $this->DATA['details']['cui'] ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                  <a class="btn btn-primary btn-lg rounded-pill px-4" href="<?= qurl_l('editeaza-profil-angajator') ?>"><strong>Editează cont</strong></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>