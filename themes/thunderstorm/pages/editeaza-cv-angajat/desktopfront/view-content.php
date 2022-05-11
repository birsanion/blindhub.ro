    <div id="content" class="container mt-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Actualizează CV</h1>
            </div>
            <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10">
                <form id="frm_editcv">
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Ce sectoare de activitate vă interesează?</strong></label>
                        <select class="selectpicker shadow form-control" data-style="btn-white" multiple data-live-search="true" required name="idx_domenii_cv[]" data-size="10">
                            <option value="" data-hidden="true"></option>
                            <?php foreach ($this->DATA['domenii_cv'] as $domeniu): ?>
                            <option value="<?= $domeniu['idx'] ?>" <?php if (in_array($domeniu['idx'], $this->DATA['details']['idx_domenii_cv'])) echo 'selected'; ?>><?= $domeniu['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>În ce oraș vrei să muncești?</strong></label>
                        <select class="selectpicker shadow form-control" data-style="btn-white" multiple data-live-search="true" data-size="10" required name="idx_orase[]">
                            <option value="" data-hidden="true"></option>
                            <?php foreach ($this->DATA['orase'] as $oras): ?>
                            <option value="<?= $oras['idx'] ?>" <?php if (in_array($oras['idx'], $this->DATA['details']['idx_orase'])) echo 'selected'; ?>><?= $oras['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

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
            $("#frm_editcv").validate({
                errorClass: "text-danger",
                errorPlacement: function (error, element) {
                    var formGroup = element.closest('.form-group')
                    formGroup.append(error)
                },
                submitHandler: function (form) {
                    $submit = $(form).find('button[type="submit"]')
                    $submit.html('<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>Loading...').attr('disabled', true);
                    $.ajax({
                        url: "<?= qurl_s('api/web-setcv') ?>",
                        type: "POST",
                        data: $(form).serialize()
                    }).done(function (data) {
                        $submit.html('Salvează').attr('disabled', false);
                        bootbox.alert({
                            message: "Actualizarea CV-ului a avut loc cu success",
                            closeButton: false,
                            callback: function () {
                                window.location = '<?= qurl_l('profil-angajat'); ?>';
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
