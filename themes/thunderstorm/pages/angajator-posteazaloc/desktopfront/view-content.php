<div id="content">
     <div class="container my-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Adaugă un loc de muncă</h1>
                <?php if ($this->DATA['errormsg']): ?>
                <div class="alert alert-danger mb-5" role="alert">
                    <?= $this->DATA['errormsg'] ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10">
                <form id="frm_locmunca">
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Titlu</strong></label>
                        <input type="text" name="titlu" class="form-control shadow" placeholder="Introdu titlul locului de muncă aici" required=>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Oraș:</strong></label>
                        <select class="selectpicker form-control shadow" data-style="btn-white" data-live-search="true" data-size="12" required name="idx_oras">
                            <option value="" data-hidden="true">Alege oraș</option>
                            <?php foreach ($this->DATA['orase'] as $oras): ?>
                            <option value="<?= $oras['idx'] ?>"><?= $oras['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Domeniu de activitate:</strong></label>
                        <select class="selectpicker form-control shadow" data-style="btn-white" required name="idx_domeniu_cv">
                            <option value="" data-hidden="true">Alege domeniu activitate</option>
                            <?php foreach ($this->DATA['domenii_cv'] as $domeniu): ?>
                            <option value="<?= $domeniu['idx'] ?>"><?= $domeniu['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Tip job:</strong></label>
                        <select class="selectpicker form-control shadow" data-style="btn-white" data-size="12" required name="idx_optiune_tipslujba">
                            <option value="" data-hidden="true">Alege tip job</option>
                            <?php foreach ($this->DATA['optiuni']['tipslujba'] as $idx => $optiune): ?>
                            <option value="<?= $idx ?>"><?= $optiune ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Competențe necesare:</strong></label>
                        <input type="text" name="competente" class="form-control shadow" placeholder="Ce competențe necesită acest job" required=>
                    </div>
                    <div class="form-group mb-4">
                        <label cclass="form-label"><strong>Descriere:</strong></label>
                        <textarea type="text" name="descriere" rows="3" class="form-control shadow" placeholder="Adaugați o descriere pentru această poziție" required></textarea>
                    </div>
                    <div class="form-group  mb-4">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-3">
                            Adaugă anunț
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <script type="text/javascript">
        $( document ).ready(function () {
             $("#frm_locmunca").validate({
                errorClass: "text-danger",
                errorPlacement: function (error, element) {
                    var inputContainer = element.closest('.form-group')
                    inputContainer.append(error)
                },
                submitHandler: function (form) {
                    $submit = $(form).find('button[type="submit"]')
                    $submit.html('<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>Loading...').attr('disabled', true);
                    $.ajax({
                        url: "<?= qurl_s('api/web-addlocmunca') ?>",
                        type: "POST",
                        data: $(form).serialize()
                    }).done(function (data) {
                        $submit.html('Adaugă anunț').attr('disabled', false);
                        bootbox.alert({
                            message: "Locul de muncă a fost adăugat cu succes",
                            closeButton: false,
                            callback: function () {
                                window.location = '<?= qurl_l('angajator-listeazalocuri'); ?>';
                            }
                        })
                    }).fail(function (e) {
                        $submit.html('Adaugă anunț').attr('disabled', false);
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
