    <div id="content" class="container my-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Posturile mele</h1>
                <?php if ($this->GLOBAL['errormsg']): ?>
                <div class="alert alert-danger mb-5" role="alert">
                    <?= $this->GLOBAL['errormsg'] ?>
                </div>
                <?php endif; ?>
                <?php if (!count($this->DATA['locuri'])): ?>
                <h4><strong>Nu aveți joburi postate.</strong></h4>
                <p>Pentru a posta un anunț selectați opțiunea adaugă loc de muncă din meniul principal.</p>
                <?php endif; ?>

                <?php foreach ($this->DATA['locuri'] as $arrLoc):?>
                <div class="card shadow-lg mb-4">
                    <div class="card-body">
                        <h4><strong><?= $arrLoc['titlu'] ?></strong></h4>
                        <h6>Tip Job: <?= $arrLoc['tipslujba'] ?></h6>
                        <h6>Domeniu: <?= $arrLoc['domeniu_cv'] ?></h6>
                        <h6>Oraș: <?= $arrLoc['oras'] ?></h6>
                        <h6>Competențe necesare: <?= $arrLoc['competente'] ?></h6>
                        <h6>Descriere: <?= $arrLoc['descriere'] ?></h6>
                        <h6>Postat pe: <?= $arrLoc['datapostare'] ?></h6>
                    </div>
                    <div class="card-footer">
                        <a class="btn btn-primary rounded-pill px-4" href="<?= qurl_l('angajator-editeazaloc/' . $arrLoc['idx']); ?>">Editează</a>
                        <a class="btn btn-danger rounded-pill btn-delete px-4" data-idx="<?= $arrLoc['idx'] ?>">Șterge</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
    $( document ).ready(function () {
        function deleteAnnouncement (id, onSuccess) {
            $.ajax({
                url: "<?= qurl_s('api/web-stergelocmunca') ?>",
                data: {
                    idxloc: idx,
                },
                type: "POST",
            }).done(function (data) {
                onSuccess()
            }).fail(function (e) {
                var message = "A apărut o eroare. Va rugăm sa încercați mai târziu!"
                if (e.responseText) {
                    var res = JSON.parse(e.responseText)
                    if (res.result) {
                        message = res.result
                    }
                }
                bootbox.alert({
                    closeButton: false,
                    message: message,
                })
            })
        }

        $(".btn-delete").click(function () {
            idx = $(this).data('idx')
            bootbox.confirm({
                message: "Sunteți sigur că doriți să ștergeți acest anunț?",
                closeButton: false,
                buttons: {
                    confirm: {
                        label: 'Da',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'Nu',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if (result) {
                        deleteAnnouncement(idx, function () {
                            bootbox.alert({
                                closeButton: false,
                                message: 'Anunțul a fost șters cu succes!',
                                callback: function () {
                                    window.location = '<?= qurl_l('angajator-listeazalocuri'); ?>';
                                }
                            })
                        })
                    }
                }
            })
        })
    })
</script>
