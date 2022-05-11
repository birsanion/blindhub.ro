<div id="content">
     <div class="container mt-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Creare cont nevăzător</h1>
            </div>
            <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10">
                <form id="frm_creeazacont">
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Care este numele tău ?</strong></label>
                        <input type="text" name="nume" class="form-control shadow" placeholder="Nume" required=>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Care este prenumele tău ?</strong></label>
                        <input type="text" name="prenume" class="form-control shadow" placeholder="Prenume" required=>
                    </div>
                     <div class="form-group mb-4">
                        <label class="form-label"><strong>Care este adresa ta de email ?</strong></label>
                        <input type="email" name="email" class="form-control shadow" placeholder="Email" required=>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>Creează o parolă</strong>
                        </label>
                        <br>
                        <small class="form-text text-muted">Parola trebuie să conțină cel puțin 8 caractere, o literă mică, o literă mare, o cifră și un simbol.</small>
                        <br>
                        <input type="password" name="parola" class="form-control shadow" placeholder="parolă" required>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Sunteți o persoană cu handicap vizual ...</strong></label>
                        <?php if (!empty($this->DATA['optiuni']['gradhandicap'])): ?>
                        <?php foreach ($this->DATA['optiuni']['gradhandicap'] as $idx => $optiune): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="idx_optiune_gradhandicap" value="<?= $idx ?>" required>
                            <label class="form-check-label">
                                <?= $optiune ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                        <?php endif;?>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>Nevoi specifice de adaptare</strong>
                        </label>
                        <br>
                        <small class="form-text text-muted">Menționați orice adaptare a locului de muncă de care aveți nevoie pentru a vă desfășura activitatea optim, precum: birou ajustabil, marcaje tactile, tastaturi, softuri accesibilizare, etc.</small>
                        <textarea name="nevoispecifice" class="form-control shadow" placeholder="introduceți nevoi specifice aici" required></textarea>
                    </div>
                    <!--
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Ce sectoare de activitate vă interesează?</strong></label>
                        <select class="selectpicker form-control shadow" data-style="btn-white" multiple required name="idx_domenii_cv[]">
                            <option value="" data-hidden="true"></option>
                            <?php foreach ($this->DATA['domenii_cv'] as $domeniu): ?>
                            <option value="<?= $domeniu['idx'] ?>"><?= $domeniu['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                     <div class="form-group mb-4">
                        <label class="form-label"><strong>În ce oraș vrei să muncești?</strong></label>
                        <select class="selectpicker form-control shadow" data-style="btn-white" multiple data-live-search="true" data-size="12" required name="idx_oras">
                            <option value="" data-hidden="true"></option>
                            <?php foreach ($this->DATA['orase'] as $oras): ?>
                            <option value="<?= $oras['idx'] ?>"><?= $oras['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>-->


                    <div class="form-group my-5">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">
                            Continuă
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



    <!--<div class="master-container center-page center-text">
        <h1 class="bold space-4040 center-text">CREARE CONT NEVĂZĂTOR</h1>

        <form id="frm_creeazacont" method="post" enctype="multipart/form-data">

            <div id="section-1" class="section selected">
                <h1 class="space-2020 center-text">Salutare !<br />Care este numele tău ?</h1>
                <input type="text" name="nume" id="hEditNume" value="" class="center-text rounded space-0040 w60lst" placeholder="introduceți numele aici" tabindex="0" />
            </div>

            <div id="section-2" class="section invisible">
                <h1 class="space-2020 center-text">Care este prenumele tău ?</h1>
                <input type="text" name="prenume" id="hEditPrenume" value="" class="center-text rounded space-0040 w60lst" placeholder="introduceți prenumele aici" />
            </div>

            <div id="section-3" class="section invisible">
                <h1 class="space-2020 center-text">Care este adresa ta de email ?</h1>
                <input type="text" name="email" id="hEditEmail" value="" class="center-text rounded space-0040 w60lst" placeholder="introduceți emailul aici" />
            </div>

            <div id="section-4" class="section invisible">
                <h1 class="space-2020 center-text">Creează o parolă</h1>
                <span>Parola trebuie să conțină cel puțin 8 caractere, o literă mică, o literă mare, o cifră și un simbol.</span>
                <br /><br />
                <input type="password" name="parola" id="hEditParola" value="" class="center-text rounded space-0040 w60lst" placeholder="introduceți parola aici" />
            </div>

            <div id="section-5" class="section invisible">
                <h1 class="space-2020 center-text">Sunteți o persoană cu handicap vizual ...</h1>

                <?php if (!empty($this->DATA['optiuni']['gradhandicap']) > 0): ?>
                <div style="width: 20%; text-align: left;" class="center-page">
                    <?php foreach ($this->DATA['optiuni']['gradhandicap'] as $idx => $optiune): ?>
                    <label>
                        <input type="radio" name="idx_optiune_gradhandicap" value="<?= $idx ?>" />
                        <?= $optiune ?>
                    </label><br /><br />
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <div id="section-6" class="section invisible">
                <h1 class="space-2020 center-text">Nevoi specifice de adaptare</h1>
                <span>Menționați orice adaptare a locului de muncă de care aveți nevoie pentru a vă desfășura activitatea optim, precum:
                    birou ajustabil, marcaje tactile, tastaturi, softuri accesibilizare, etc.</span>
                <br />
                <br />
                <input type="text" name="nevoispecifice" id="hEditNevoiSpecifice" value="" class="center-text rounded space-0040 w60lst" placeholder="introduceți nevoi specifice aici" />
            </div>

            <div id="section-7" class="section invisible">
                <br /><br />
                <h1 id="hStaticMesajFinal" class="space-2020 center-text">Bine ai venit !</h1>
                <br /><br />
            </div>

        </form>

        <br /><br />
        <div id="hStaticErrorMsg"></div>
        <input type="button" name="hButtonNext" id="hButtonNext" value="URMĂTORUL PAS &rArr;" class="standard-button rounded space-2020" />
    </div>-->

    <script type="text/javascript">
        jQuery.validator.addMethod("parola", function (value, element) {
            return ValidatePassword(value)
        }, "Trebuie să puneți o parolă corespunzătoare ")

        $( document ).ready(function () {
            $("#frm_creeazacont").validate({
                errorClass: "text-danger",
                rules: {
                    'parola': 'parola'
                },
                errorPlacement: function (error, element) {
                    var formGroup = element.closest('.form-group')
                    formGroup.append(error)
                },
                submitHandler: function (form) {
                    $submit = $(form).find('button[type="submit"]')
                    $.ajax({
                        url: "<?= qurl_s('api/web-createacc-nevaz') ?>",
                        type: "POST",
                        data: $(form).serialize()
                    }).done(function (data) {
                        $submit.html('Continuă').attr('disabled', false);
                        bootbox.dialog({
                            message: "Contul a fost creat cu success!",
                            closeButton: false,
                            buttons: {
                                ok: {
                                    label: "Intră in cont",
                                    className: 'btn-primary',
                                    callback: function() {
                                        window.location = '<?= qurl_l('home-nevaz'); ?>';
                                    }
                                }
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

        function ValidateEmail(strEmail)
        {
            const kRegEx = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return kRegEx.test(String(strEmail).toLowerCase());
        }

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
                case 1:{
                    if ($('#hEditNume').val().trim().length <= 0){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să completați numele !');
                    }
                }break;

                case 3:{
                    if ($('#hEditEmail').val().trim().length <= 0 || !ValidateEmail($('#hEditEmail').val())){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să completați corect adresa de email !');
                    }
                }break;

                case 2:{
                    if ($('#hEditPrenume').val().trim().length <= 0){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să completați prenumele !');
                    }
                }break;

                case 5:{
                    if (!$('input[name=idx_optiune_gradhandicap]:checked').length ) {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;

                case 4:{
                    if (!ValidatePassword($('#hEditParola').val())){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să puneți o parolă corespunzătoare !');
                    }
                }break;
            }

            if (bMoveNext){
                $('#section-' + nCurrId).removeClass('selected');
                $('#section-' + nCurrId).addClass('invisible');

                $('#section-' + (nCurrId + 1)).addClass('selected');
                $('#section-' + (nCurrId + 1)).removeClass('invisible');

                $('#hStaticErrorMsg').html('');

                if (nCurrId == 6){
                    $('#hStaticErrorMsg').html('Vă rugăm să așteptați !');
                    $('#hButtonNext').addClass('invisible');

                    var jqXHR=$.post("<?php echo qurl_s('api/web-createacc-nevaz'); ?>",
                        $('#frm_creeazacont').serialize(),
                        function(data){
                            if (data['result']=='success'){
                                $('#hStaticErrorMsg').html('');
                                $('#hButtonNext').removeClass('invisible');
                                $('#hButtonNext').val('FINALIZARE');
                            }else{
                                $('div.section').addClass('invisible');
                                $('div.section').removeClass('selected');

                                $('#section-2').removeClass('invisible');
                                $('#section-2').addClass('selected');

                                $('#hButtonNext').removeClass('invisible');
                                $('#hStaticErrorMsg').html(data['result']);
                            }
                        },
                    "json");

                    jqXHR.fail(function(a,b,c){
                        alert("AJAX err: "+a+' - '+b);
                    });
                }

                if (nCurrId == 7)
                    window.location = '<?php echo qurl_l('auth-angajat'); ?>';
            }
        }

        $('#hButtonNext').click(Next);
        $(document).keyup(function(kEvent){
            if (kEvent.keyCode === 13){
                kEvent.preventDefault();
                Next();
            }
        });

        $('#hEditNume').focus();

    </script>
