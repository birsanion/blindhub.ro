     <div id="content" class="container mt-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Editare cont</h1>
                <?php if ($this->DATA['errormsg']): ?>
                <div class="alert alert-danger mb-5" role="alert">
                    <?= $this->DATA['errormsg'] ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="offset-md-3 col-md-6">
                <form id="frm_editangajator">
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Nume companie</strong></label>
                        <input type="text" name="companie" class="form-control shadow" placeholder="Nume companie" required value="<?= $this->DATA['details']['companie'] ?>">
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Adresa punctului de lucru (oraș și adresa completă)</strong></label>
                        <input type="text" name="adresa" class="form-control shadow" placeholder="Adresa" required value="<?= $this->DATA['details']['adresa'] ?>">
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Orașe în care faceți recrutare:</strong></label>
                        <select class="selectpicker form-control shadow" data-style="btn-white" multiple data-live-search="true" data-size="12" required name="idx_orase[]">
                            <option value="" data-hidden="true"></option>
                            <?php foreach ($this->DATA['orase'] as $oras): ?>
                            <option value="<?= $oras['idx'] ?>" <?php if (in_array($oras['idx'], $this->DATA['details']['idx_orase'])) echo 'selected'; ?>><?= $oras['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Domenii de activitate în care recrutați:</strong></label>
                        <select class="selectpicker form-control shadow" data-style="btn-white" multiple required name="idx_domenii_cv[]">
                            <option value="" data-hidden="true">Alege domeniu activitate</option>
                            <?php foreach ($this->DATA['domenii_cv'] as $domeniu): ?>
                            <option value="<?= $domeniu['idx'] ?>"  <?php if (in_array($domeniu['idx'], $this->DATA['details']['idx_domenii_cv'])) echo 'selected'; ?>><?= $domeniu['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Certificatul de înregistrare fiscală:</strong></label>
                        <input type="text" name="cui" class="form-control shadow" placeholder="Nume companie" required value="<?= $this->DATA['details']['cui'] ?>">
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Precizați daca sunteți unitate protejată:</strong></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="firmaprotejata" value="1" <?php if ($this->DATA['details']['firmaprotejata']) echo 'checked'; ?>>
                            <label class="form-check-label">
                                Da
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="firmaprotejata" value="0" <?php if (!$this->DATA['details']['firmaprotejata']) echo 'checked'; ?>>
                            <label class="form-check-label">
                                Nu
                            </label>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Sunteți o companie cu peste 50 de angajați:</strong></label>
                        <?php if (!empty($this->DATA['optiuni']['dimensiuneslujba'])): ?>
                        <?php foreach ($this->DATA['optiuni']['dimensiuneslujba'] as $idx => $optiune): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="idx_optiune_dimensiunefirma" value="<?= $idx ?>" <?php if ($this->DATA['details']['idx_optiune_dimensiunefirma'] == $idx) echo 'checked'; ?>>
                            <label class="form-check-label">
                                <?= $optiune ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                        <?php endif;?>
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
             $("#frm_editangajator").validate({
                errorClass: "text-danger",
                errorPlacement: function (error, element) {
                    var inputContainer = element.closest('.form-group')
                    inputContainer.append(error)
                },
                submitHandler: function (form) {
                    $submit = $(form).find('button[type="submit"]')
                    $submit.html('<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>Loading...').attr('disabled', true);
                    $.ajax({
                        url: "<?= qurl_s('api/web-editacc-angajatori') ?>",
                        type: "POST",
                        data: $(form).serialize()
                    }).done(function (data) {
                        $submit.html('Salvează').attr('disabled', false);
                        bootbox.alert({
                            message: "Modificările profilului au fost facute cu succes!",
                            closeButton: false,
                            callback: function () {
                                window.location = '<?= qurl_l('profil-angajator'); ?>';
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



        function ValidatePassword(strPass)
        {
            if (strPass.length < 8) return false;

            var kRegEx = /[A-Z]+/;
            if (!kRegEx.test(strPass)) return false;

            var kRegEx = /[a-z]+/;
            if (!kRegEx.test(strPass)) return false;

            var kRegEx = /[0-9]+/;
            if (!kRegEx.test(strPass)) return false;

            var kRegEx = /[^A-Za-z0-9]+/;
            if (!kRegEx.test(strPass)) return false;

            return true;
        }

        function Next()
        {
            var nCurrId = parseInt($('div.section.selected').attr('id').substring(8));

            var bMoveNext = true;

            switch (nCurrId)
            {
                case 2:{
                    if ($('#hEditNumeFirma').val().trim().length <= 0){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să completați numele firmei !');
                    }
                }break;

                case 3:{
                    if ($('#hEditAdresa').val().trim().length <= 0){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să completați adresa !');
                    }
                }break;

                case 4:{
                    if ($('#hEditCUI').val().trim().length <= 0){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să completați codul unic de înregistrare !');
                    }
                }break;

                case 5:{
                    if (!ValidatePassword($('#hEditParola').val())){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să puneți o parolă corespunzătoare !');
                    }
                }break;

                case 6:{
                    if (!$('#hRadioFirmaProtej_DA').prop('checked') && !$('#hRadioFirmaProtej_NU').prop('checked'))
                    {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;

                case 7:{
                    if (!$('#hRadioFirmaAngajati_sub').prop('checked') && !$('#hRadioFirmaAngajati_peste').prop('checked'))
                    {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;

                case 8:{
                    if (!$('#hRadioFirmaPerContr_part').prop('checked') && !$('#hRadioFirmaPerContr_full').prop('checked'))
                    {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;

                case 9:{
                    var bChecked = false;

                    $('#section-9').find('input[type="checkbox"]').each(function(nIndex, kElement){
                        if ($(this).prop('checked')) bChecked = true;
                    });

                    if (!bChecked){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați cel puțin o opțiune !');
                    }
                }break;

                case 10:{
                    var bChecked = false;

                    $('#section-10').find('input[type="checkbox"]').each(function(nIndex, kElement){
                        if ($(this).prop('checked')) bChecked = true;
                    });

                    if (!bChecked){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați cel puțin o opțiune !');
                    }
                }break;
            }

            if (bMoveNext){
                $('#section-' + nCurrId).removeClass('selected');
                $('#section-' + nCurrId).addClass('invisible');

                $('#section-' + (nCurrId + 1)).addClass('selected');
                $('#section-' + (nCurrId + 1)).removeClass('invisible');

                $('#hStaticErrorMsg').html('');

                if (nCurrId == 10){
                    var jqXHR=$.post("<?php echo qurl_s('api/web-createacc-angajatori'); ?>",
                        $('#frm_creeazacont').serialize(),
                        function(data){
                            if (data['result']=='success'){
                                window.location = '<?php echo qurl_l('home-angajator'); ?>';
                            }else{
                                $('#hStaticErrorMsg').html(data['result']);
                            }
                        },
                    "json");

                    jqXHR.fail(function(a,b,c){
                        alert("AJAX err: "+a+' - '+b);
                    });
                }

                if (nCurrId == 11)
                    window.location = '<?php echo qurl_l('auth-angajator'); ?>';
            }
        }

        $('#hButtonNext').click(Next);
        $(document).keyup(function(kEvent){
            if (kEvent.keyCode === 13){
                kEvent.preventDefault();
                Next();
            }
        });

        $('#hEditEmail').focus();

    </script>

