    <div id="content" class="container mt-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Editează cont universitate</h1>
            </div>
            <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10">
                <form id="frm_editcont">
                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>Nume universitate:</strong>
                        </label>
                        <input type="text" name="nume" class="form-control shadow" placeholder="nume universitate" value="<?= $this->DATA['details']['nume'] ?>" required>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Orașe:</strong></label>
                        <select class="selectpicker form-control shadow" multiple  data-style="btn-white" data-live-search="true" data-size="12" required name="idx_orase[]">
                            <?php foreach ($this->DATA['orase'] as $oras): ?>
                            <option value="<?= $oras['idx'] ?>" <?php if (in_array($oras['idx'], $this->DATA['details']['idx_orase'])) echo 'selected'; ?>><?= $oras['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>Numele reprezentantului universității:</strong>
                        </label>
                        <input type="text" name="reprezentant" class="form-control shadow" placeholder="reprezentant" value="<?= $this->DATA['details']['reprezentant'] ?>" required>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Menționați gradul de accesibilitate a instituției dumneavoastră:</strong></label>
                        <?php foreach ($this->DATA['optiuni']['accesibilizare_clasa'] as $idx => $optiune): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="<?= $idx ?>" name="idx_optiune_accesibilizare_clasa" required <?php if ($this->DATA['details']['idx_optiune_accesibilizare_clasa'] == $idx) echo 'checked'; ?>>
                            <label class="form-check-label" >
                                <?= $optiune ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Menționați gradul de echipare cu tehnologie asistivă:</strong></label>
                        <?php foreach ($this->DATA['optiuni']['gradechipare'] as $idx => $optiune): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="<?= $idx ?>" name="idx_optiune_gradechipare" required <?php if ($this->DATA['details']['idx_optiune_gradechipare'] == $idx) echo 'checked'; ?>>
                            <label class="form-check-label" >
                                <?= $optiune ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>


                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>
                                Menționați dacă universitatea are studenți cu dizabilități înmatriculați:
                            </strong>
                        </label>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0" name="studdiz" required <?php if ($this->DATA['details']['studdiz'] == 0) echo 'checked'; ?>>
                            <label class="form-check-label">
                                Nu
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" name="studdiz" required <?php if ($this->DATA['details']['studdiz'] == 1) echo 'checked'; ?>>
                            <label class="form-check-label">
                                Da
                            </label>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>
                                Menționați dacă universitatea are un centru de sprijin destinat studenților cu dizabilități:
                            </strong>
                        </label>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0" name="studcentru" required <?php if ($this->DATA['details']['studcentru'] == 0) echo 'checked'; ?>>
                            <label class="form-check-label">
                                Nu
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" name="studcentru" required <?php if ($this->DATA['details']['studcentru'] == 1) echo 'checked'; ?>>
                            <label class="form-check-label">
                                Da
                            </label>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>
                                Menționați dacă universitatea deține camere în căminele studențești adaptate la toate tipurile de dizabilități:
                            </strong>
                        </label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0" name="camerecamine" required <?php if ($this->DATA['details']['camerecamine'] == 0) echo 'checked'; ?>>
                            <label class="form-check-label">
                                Nu
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" name="camerecamine" required <?php if ($this->DATA['details']['camerecamine'] == 1) echo 'checked'; ?>>
                            <label class="form-check-label">
                                Da
                            </label>
                        </div>
                    </div>


                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>
                                Menționați dacă universitatea are o persoană sau un birou dedicat aplicației BlindHub:
                            </strong>
                        </label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0" name="persdedic" required <?php if ($this->DATA['details']['persdedic'] == 0) echo 'checked'; ?>>
                            <label class="form-check-label">
                                Nu
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" name="persdedic" required <?php if ($this->DATA['details']['persdedic'] == 1) echo 'checked'; ?>>
                            <label class="form-check-label">
                                Da
                            </label>
                        </div>
                    </div>

                     <div class="form-group mb-4">
                        <label class="form-label"><strong>Menționați gradul de accesibilizare a sălilor de curs:</strong></label>
                        <?php foreach ($this->DATA['optiuni']['gradacces'] as $idx => $optiune): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="<?= $idx ?>" name="idx_optiune_gradacces" required <?php if ($this->DATA['details']['idx_optiune_gradacces'] == $idx) echo 'checked'; ?>>
                            <label class="form-check-label" >
                                <?= $optiune ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>
                                Menționați dacă universitatea are în dotare cursuri în format Braille:
                            </strong>
                        </label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0" name="braille" required <?php if ($this->DATA['details']['braille'] == 0) echo 'checked'; ?>>
                            <label class="form-check-label">
                                Nu
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" name="braille" required <?php if ($this->DATA['details']['braille'] == 1) echo 'checked'; ?>>
                            <label class="form-check-label">
                                Da
                            </label>
                        </div>
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
                        url: "<?= qurl_s('api/web-editacc-universitate') ?>",
                        type: "POST",
                        data: $(form).serialize()
                    }).done(function (data) {
                        $submit.html('Salvează').attr('disabled', false);
                        bootbox.alert({
                            message: "Modificările profilului au avut loc cu success",
                            closeButton: false,
                            callback: function () {
                                window.location = '<?= qurl_l('profil-universitate'); ?>';
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
