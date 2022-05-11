    <div class="container mt-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Editează cont nevăzător</h1>
            </div>
            <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10">
                <form id="frm_editcont">
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Care este numele tău ?</strong></label>
                        <input type="text" name="nume" class="form-control shadow" placeholder="Nume" required value="<?= $this->DATA['details']['nume'] ?>">
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Care este prenumele tău ?</strong></label>
                        <input type="text" name="prenume" class="form-control shadow" placeholder="Prenume" required value="<?= $this->DATA['details']['prenume'] ?>">
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Sunteți o persoană cu handicap vizual ...</strong></label>
                        <?php foreach ($this->DATA['optiuni']['gradhandicap'] as $idx => $optiune): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="idx_optiune_gradhandicap" value="<?= $idx ?>" required <?php if ($this->DATA['details']['idx_optiune_gradhandicap'] == $idx) echo 'checked'; ?>>
                            <label class="form-check-label">
                                <?= $optiune ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>Nevoi specifice de adaptare</strong>
                        </label>
                        <br>
                        <small class="form-text text-muted">Menționați orice adaptare a locului de muncă de care aveți nevoie pentru a vă desfășura activitatea optim, precum: birou ajustabil, marcaje tactile, tastaturi, softuri accesibilizare, etc.</small>
                        <textarea name="nevoispecifice" class="form-control shadow" rows="3" placeholder="introduceți nevoi specifice aici"><?= $this->DATA['details']['nevoispecifice'] ?></textarea>
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

    <!--

     <div class="form-group mb-4">
                        <label class="form-label"><strong>Ce sectoare de activitate vă interesează?</strong></label>
                        <select class="selectpicker form-control" data-style="btn-white" multiple required name="idx_domenii_cv[]">
                            <option value="" data-hidden="true"></option>
                            <?php foreach ($this->DATA['domenii_cv'] as $domeniu): ?>
                            <option value="<?= $domeniu['idx'] ?>" <?php if (in_array($domeniu['idx'], $this->DATA['details']['idx_domenii_cv'])) echo 'selected'; ?>><?= $domeniu['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>În ce oraș vrei să muncești?</strong></label>
                        <select class="selectpicker form-control" data-style="btn-white" multiple data-live-search="true" data-size="12" required name="idx_oras">
                            <option value="" data-hidden="true"></option>
                            <?php foreach ($this->DATA['orase'] as $oras): ?>
                            <option value="<?= $oras['idx'] ?>" <?php if (in_array($oras['idx'], $this->DATA['details']['idx_orase'])) echo 'selected'; ?>><?= $oras['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
    -->

    <!--<div class="master-container center-page center-text">
        <h1 class="bold space-4040">EDITARE PROFIL NEVĂZĂTOR</h1>

        <form id="frm_creeazacont" method="post" enctype="multipart/form-data">

            <div id="section-1" class="section selected">
                <h1 class="space-2020">Salutare !<br />Care este numele tău ?</h1>
                <input type="text" name="nume" id="hEditNume" value="<?php
                    echo htmlspecialchars($this->DATA['details']['nume']); ?>" class="center-text rounded space-0040 w60lst" placeholder="introduceți numele aici" tabindex="0" />
            </div>

            <div id="section-2" class="section invisible">
                <h1 class="space-2020">Care este prenumele tău ?</h1>
                <input type="text" name="prenume" id="hEditPrenume" value="<?php
                    echo htmlspecialchars($this->DATA['details']['prenume']); ?>" class="center-text rounded space-0040 w60lst" placeholder="introduceți prenumele aici" />
            </div>

            <div id="section-3" class="section invisible">
                <h1 class="space-2020">Sunteți o persoană cu handicap vizual ...</h1>

                <?php if (!empty($this->DATA['optiuni']['gradhandicap']) > 0): ?>
                <div style="width: 20%; text-align: left;" class="center-page">
                    <?php foreach ($this->DATA['optiuni']['gradhandicap'] as $idx => $optiune): ?>
                    <label>
                        <input type="radio" name="idx_optiune_gradhandicap" value="<?= $idx ?>" <?php
                            if ($this->DATA['details']['idx_optiune_gradhandicap'] == $idx) echo ' checked="checked"'; ?>/>
                        <?= $optiune ?>
                    </label><br /><br />
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <div id="section-4" class="section invisible">
                <h1 class="space-2020">Nevoi specifice de adaptare</h1>
                <input type="text" name="hEditNevoiSpecifice" id="hEditNevoiSpecifice" value="<?php
                    echo htmlspecialchars($this->DATA['details']['nevoispecifice']); ?>" class="center-text rounded space-0040 w60lst" placeholder="introduceți nevoi specifice aici" />
            </div>

            <input type="hidden" name="userkey" value="mod" />
        </form>

        <br /><br />
        <div id="hStaticErrorMsg"></div>
        <input type="button" name="hButtonNext" id="hButtonNext" value="URMĂTORUL PAS &rArr;" class="standard-button rounded space-2020" />
    </div>-->

    <script type="text/javascript">
        $( document ).ready(function () {
            $("#frm_editcont").validate({
                errorClass: "text-danger",
                errorPlacement: function (error, element) {
                    var formGroup = element.closest('.form-group')
                    formGroup.append(error)
                },
                submitHandler: function (form) {
                    $submit = $(form).find('button[type="submit"]')
                    $submit.html('<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>Loading...').attr('disabled', true);
                    $.ajax({
                        url: "<?= qurl_s('api/web-editacc-nevaz') ?>",
                        type: "POST",
                        data: $(form).serialize()
                    }).done(function (data) {
                        $submit.html('Salvează').attr('disabled', false);
                        bootbox.alert({
                            message: "Modificările profilului au avut loc cu success",
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
