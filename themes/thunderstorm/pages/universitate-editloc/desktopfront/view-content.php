    <div class="container mt-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Editează ofertă</h1>
                <?php if ($this->DATA['errormsg']): ?>
                <div class="alert alert-danger mb-5" role="alert">
                    <?= $this->DATA['errormsg'] ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10">
                <form id="frm_oferta">
                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>Nume Facultate:</strong>
                        </label>
                        <input type="text" name="nume" class="form-control shadow" placeholder="nume facultate" required value="<?= $this->DATA['loc']['facultate'] ?>">
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Domeniu de activitate:</strong></label>
                        <select class="selectpicker form-control shadow" data-style="btn-white" data-live-search="true" data-size="12" required name="idx_domeniu_universitate">
                            <option data-hidden="true"></option>
                            <?php foreach ($this->DATA['domenii_universitate'] as $domeniu): ?>
                            <option value="<?= $domeniu['idx'] ?>" <?php if ($this->DATA['loc']['idx_domeniu_universitate'] == $domeniu['idx']) echo 'selected'; ?>><?= $domeniu['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>Numar de locuri:</strong>
                        </label>
                        <input type="number" name="nrlocuri" class="form-control shadow" placeholder="Introdu aici numărul de locuri" required value="<?= $this->DATA['loc']['numarlocuri'] ?>">
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Oraș:</strong></label>
                        <select class="selectpicker form-control shadow" data-style="btn-white" data-live-search="true" data-size="12" required name="idx_oras">
                            <option data-hidden="true"></option>
                            <?php foreach ($this->DATA['orase'] as $oras): ?>
                            <option value="<?= $oras['idx'] ?>" <?php if ($this->DATA['loc']['idx_oras'] == $oras['idx']) echo 'selected'; ?>><?= $oras['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <input  name="idxloc" hidden value="<?= $this->DATA['loc']['idx'] ?>">

                    <div class="form-group my-5">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">
                            Salvează
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $( document ).ready(function () {
             $("#frm_oferta").validate({
                errorClass: "text-danger",
                errorPlacement: function (error, element) {
                    var formGroup = element.closest('.form-group')
                    formGroup.append(error)
                },
                submitHandler: function (form) {
                    $submit = $(form).find('button[type="submit"]')
                    $submit.html('<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>Loading...').attr('disabled', true);
                    $.ajax({
                        url: "<?= qurl_s('api/web-universitate-editeazaoferta') ?>",
                        type: "POST",
                        data: $(form).serialize()
                    }).done(function (data) {
                        $submit.html('Salvează').attr('disabled', false);
                        bootbox.alert({
                            message: "Oferta a fost modificat cu success!",
                            closeButton: false,
                            callback: function () {
                                window.location = '<?= qurl_l('universitate-oferte'); ?>';
                            }
                        })
                    }).fail(function (e) {
                        $submit.html('Salvează').attr('disabled', false);
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
            })
        })
    </script>


