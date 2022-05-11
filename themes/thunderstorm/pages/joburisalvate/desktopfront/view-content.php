    <div id="content" class="container my-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Job-uri salvate</h1>
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
                            <div class="row">
                                <div class="col-sm-12 mt-3">
                                    <button class="btn btn-primary btn-aplica rounded-pill px-4" data-idx="<?= $loc['idxlocmunca'] ?>">
                                        Aplică
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $( document ).ready(function () {
            $('.btn-aplica').click(function (Event) {
                $this = $(this)
                $this.html('<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>Loading...').attr('disabled', true);
                $.ajax({
                    url: "<?= qurl_s('api/web-cautalocmunca-initinterviu') ?>",
                    type: "POST",
                    dataType: "json",
                    data: {
                        idxlocmunca: $this.data('idx'),
                    },
                }).done(function (data) {
                    $this.html('Aplică').attr('disabled', false);
                    bootbox.alert({
                        closeButton: false,
                        message: "Ai aplicat cu success la acest loc de muncă",
                    })
                }).fail(function (e) {
                    var message = "A apărut o eroare. Va rugăm sa încercați mai târziu!"
                    if (e.responseText) {
                        var res = JSON.parse(e.responseText)
                        if (res.result) {
                            message = res.result
                        }
                    }
                    $this.html('Aplică').attr('disabled', false);
                    bootbox.alert({
                        closeButton: false,
                        message: message,
                    })
                })
            })
        })
    </script>