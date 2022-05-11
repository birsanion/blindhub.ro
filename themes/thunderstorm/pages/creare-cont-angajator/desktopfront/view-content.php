     <div class="container mt-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10 mb-5">
                <h1 class="titlu mb-5">Creare cont angajator</h1>

                <div>
                    <p>
                        Persoanele cu dizabilități reprezintă un capital uman deosebit de important pentru orice
                        economie. Organizațiile pot valorifica această resursă umană în activitatea specifică aducând
                            plus de valoare în toate procesele.
                    </p>

                    <p>
                        Dacă dorești să îți diversifici forța de muncă sau dacă ești în căutarea unor persoane
                            talentate cu dizabilități, aici este locul în care poți începe.
                    </p>
                    <p>
                        BlindHub îți permite să identifici profile de candidați cu dizabilități de vedere pentru
                        organizația ta, să vizualizezi video-cv-uri, să stabilești interviuri și chiar să
                        desfășori aceste interviuri prin intermediul aplicației.
                    </p>
                    <p>
                        Aplicația noastră este un mod inovativ și incluziv prin care îți poți transforma afacerea
                            ta într-un hub de creativitate și excelență.
                    </p>
                </div>

                <h5 class="mt-3 mb-5">
                    <strong>
                        Ai deje un cont? <a href="<?= qurl_l('auth-angajator') ?>">Intră în cont</a>
                    </strong>
                </h5>
            </div>
            <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10">
                <form id="frm_creeazacont">
                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>Nume companie:</strong>
                        </label>
                        <input type="text" name="companie" class="form-control shadow" placeholder="nume firmă" required=>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>Adresa de email:</strong>
                        </label>
                        <input type="email" name="email" class="form-control shadow" placeholder="email" required>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>Parola:</strong>
                        </label>
                        <input type="password" name="parola" class="form-control shadow" placeholder="parolă" required>
                        <small class="form-text text-muted">Parola trebuie să conțină cel puțin 8 caractere, o literă mică, o literă mare, o cifră și un simbol.</small>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>
                                Certificatul de inregistrare fiscala (C.U.I.):
                            </strong>
                        </label>
                        <input type="text" name="cui" value="" class="form-control shadow" placeholder="cui" required>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>
                                Vă rugăm să precizați dacă sunteți unitate protejată:
                            </strong>
                        </label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" name="firmaprotejata" required>
                            <label class="form-check-label">
                                Da
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0" name="firmaprotejata" required>
                            <label class="form-check-label">
                                Nu
                            </label>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Adresa punctului de lucru (oraș și adresa completă)</strong></label>
                        <input type="text" name="adresa"  class="form-control shadow" placeholder="adresa" required>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Orașe în care faceți recrutare:</strong></label>
                        <select class="selectpicker form-control shadow" multiple  data-style="btn-white" data-live-search="true" data-size="12" required name="idx_orase[]">
                            <?php foreach ($this->DATA['orase'] as $oras): ?>
                            <option value="<?= $oras['idx'] ?>"><?= $oras['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Domenii de activitate în care faceți recrutare:</strong></label>
                        <select class="selectpicker form-control shadow" multiple  data-style="btn-white" data-live-search="true" data-size="12" required name="idx_domenii_cv[]">
                            <?php foreach ($this->DATA['domenii_cv'] as $domeniu): ?>
                            <option value="<?= $domeniu['idx'] ?>"><?= $domeniu['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Sunteți companie cu peste 50 de angajați ?</strong></label>
                        <?php foreach ($this->DATA['optiuni']['dimensiuneslujba'] as $idx => $optiune): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="<?= $idx ?>" name="idx_optiune_dimensiunefirma" required>
                            <label class="form-check-label" >
                                <?= $optiune ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                    </div>

                    <div class="form-group my-5">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">
                            Continuă
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!--<div class="container mt-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <form id="frm_creeazacont" method="post" enctype="multipart/form-data">
                    <div id="section-0" class="section selected">
                        <p>Persoanele cu dizabilități reprezintă un capital uman deosebit de important pentru orice
                            economie. Organizațiile pot valorifica această resursă umană în activitatea specifică aducând
                            plus de valoare în toate procesele.</p>

                        <p>Dacă dorești să îți diversifici forța de muncă sau dacă ești în căutarea unor persoane
                            talentate cu dizabilități, aici este locul în care poți începe.</p>

                        <p>BlindHub îți permite să identifici profile de candidați cu dizabilități de vedere pentru
                            organizația ta, să vizualizezi video-cv-uri, să stabilești interviuri și chiar să
                            desfășori aceste interviuri prin intermediul aplicației.</p>

                        <p>Aplicația noastră este un mod inovativ și incluziv prin care îți poți transforma afacerea
                            ta într-un hub de creativitate și excelență.</p>
                    </div>
                </form>
            </div>
        </div>

        <form id="frm_creeazacont" method="post" enctype="multipart/form-data">

            <div class="row ">
                <div class="offset-md-3 col-md-6">
                    <div id="section-1" class="section invisible">
                         <div class="mb-3">
                            <label class="form-label">Nume companie:</label>
                            <input type="text" name="companie" id="hEditNumeFirma" value="" class="form-control" placeholder="nume firmă" >
                        </div>
                    </div>

                    <div id="section-2" class="section invisible">
                        <div class="mb-3">
                            <label class="form-label">Adresa de email:</label>
                            <input type="text" name="email" id="hEditEmail" value="" class="form-control" placeholder="email" >
                        </div>
                    </div>

                    <div id="section-3" class="section invisible">
                        <div class="mb-3">
                            <label class="form-label">Parola:</label>
                            <input type="password" name="parola" id="hEditParola" value="" class="form-control" placeholder="parolă" >
                            <small class="form-text text-muted">Parola trebuie să conțină cel puțin 8 caractere, o literă mică, o literă mare, o cifră și un simbol.</small>
                        </div>
                    </div>

                    <div id="section-4" class="section invisible">
                        <div class="mb-3">
                            <label class="form-label">Adresa punctului de lucru (oraș și adresa completă)</label>
                            <input type="text" name="adresa" id="hEditAdresa" value="" class="form-control" placeholder="adresa" >
                        </div>
                    </div>

                    <div id="section-5" class="section invisible">
                        <div class="mb-3">
                            <label class="form-label">Orașe în care faceți recrutare:</label>
                        </div>
                        <div class="row">
                            <?php foreach ($this->DATA['orase'] as $oras): ?>
                            <div class="col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="idx_orase[]" value="<?= $oras['idx'] ?>">
                                    <label class="form-check-label">
                                        <?= $oras['nume'] ?>
                                    </label>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div id="section-6" class="section invisible">
                        <div class="mb-3">
                            <label class="form-label">Certificatul de inregistrare fiscala (C.U.I.):</label>
                            <input type="text" name="cui" id="hEditCUI" value="" class="form-control" placeholder="cui" >
                        </div>
                    </div>

                    <div id="section-7" class="section invisible">
                        <div class="mb-3">
                            <label class="form-label">Domenii de activitate în care faceți recrutare:</label>
                        </div>
                        <?php foreach ($this->DATA['domenii_cv'] as $domeniu): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="idx_domenii_cv[]" value="<?= $domeniu['idx'] ?>">
                            <label class="form-check-label">
                                <?= $domeniu['nume'] ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div id="section-8" class="section invisible">
                        <label class="form-label">Vă rugăm să precizați dacă sunteți unitate protejată:</label>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="firmaprotejata" id="hRadioFirmaProtej_DA" value="1">
                            <label class="form-check-label">
                                DA
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="firmaprotejata" id="hRadioFirmaProtej_NU" value="0">
                            <label class="form-check-label">
                                NU
                            </label>
                        </div>
                    </div>

                    <div id="section-9" class="section invisible">
                        <label class="form-label">Sunteți companie cu peste 50 de angajați ?</label>
                        <?php if (!empty($this->DATA['optiuni']['dimensiuneslujba'])): ?>
                        <?php foreach ($this->DATA['optiuni']['dimensiuneslujba'] as $idx => $optiune): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="idx_optiune_dimensiunefirma" value="<?= $idx ?>" >
                            <label class="form-check-label">
                                <?= $optiune ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </form>

        <form id="frm_uploadsigla" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="offset-md-3 col-md-6">
                    <div id="section-10" class="section invisible">
                        <label class="form-label">Încărcați sigla brandului:</label>
                        <span id="hFileUploadContainer" class="btn btn-success fileinput-button" style="background: #2E295C;">
                            <i class="icon-plus icon-white"></i>
                            <span>ÎNCARCĂ FIȘIERUL</span>
                            <input id="hFileUpload" type="file" name="files[]">
                        </span>
                    </div>

                    <input type="hidden" name="hStaticIdxAngajator" id="hStaticIdxAngajator" value="-1" />
                </div>
            </div>
        </form>

        <br /><br />
        <div id="hStaticErrorMsg" class="text-center"></div>
        <div class="text-center">
            <input type="button" name="hButtonNext" id="hButtonNext" value="URMĂTORUL PAS &rArr;" class="standard-button rounded space-2020" />
        </div>
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
                    $submit.html('<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>Loading...').attr('disabled', true);
                    $.ajax({
                        url: "<?= qurl_s('api/web-createacc-angajatori') ?>",
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
                                        window.location = '<?= qurl_l('home-angajator'); ?>';
                                    }
                                }
                            }
                        })
                    }).fail(function (e) {
                        $submit.html('Continuă').attr('disabled', false);
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
                    if ($('#hEditNumeFirma').val().trim().length <= 0){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să completați numele firmei !');
                    }
                }break;

                case 2:{
                    if ($('#hEditEmail').val().trim().length <= 0 || !ValidateEmail($('#hEditEmail').val())){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să completați corect adresa de email !');
                    }
                }break;

                case 3:{
                    if (!ValidatePassword($('#hEditParola').val())){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să puneți o parolă corespunzătoare !');
                    }
                }break;

                case 4:{
                    if ($('#hEditAdresa').val().trim().length <= 0){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să completați adresa !');
                    }
                }break;

                case 5:{
                    if (!$('#section-5 input[type=checkbox]:checked').length ) {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați cel puțin o opțiune !');
                    }
                }break;

                case 6:{
                    if ($('#hEditCUI').val().trim().length <= 0){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să completați codul unic de înregistrare !');
                    }
                }break;

                case 7:{
                    if (!$('#section-7 input[type=checkbox]:checked').length ) {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați cel puțin o opțiune !');
                    }
                }break;

                case 8:{
                    if (!$('input[name=firmaprotejata]:checked').length ) {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;

                case 9:{
                    if (!$('input[name=idx_optiune_dimensiunefirma]:checked').length ) {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;

            }

            if (bMoveNext){
                $('#section-' + nCurrId).removeClass('selected');
                $('#section-' + nCurrId).addClass('invisible');

                $('#section-' + (nCurrId + 1)).addClass('selected');
                $('#section-' + (nCurrId + 1)).removeClass('invisible');

                $('#hStaticErrorMsg').html('');

                if (nCurrId == 9){
                    var jqXHR=$.post("<?php echo qurl_s('api/web-createacc-angajatori'); ?>",
                        $('#frm_creeazacont').serialize(),
                        function(data){
                            if (data['result'] == 'success'){
                                $('#hStaticErrorMsg').html('');
                                $('#hButtonNext').removeClass('invisible');
                                $('#hButtonNext').val('FINALIZARE');

                                $('#hStaticIdxAngajator').val(data['idxuser']);
                            }else{
                                $('div.section').addClass('invisible');
                                $('div.section').removeClass('selected');

                                $('#section-1').removeClass('invisible');
                                $('#section-1').addClass('selected');

                                $('#hButtonNext').removeClass('invisible');
                                $('#hStaticErrorMsg').html(data['result']);
                            }
                        },
                    "json");

                    jqXHR.fail(function(a,b,c){
                        alert("AJAX err: "+a+' - '+b);
                    });
                }

                if (nCurrId == 10)
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

        function Upload(obj)
        {
            'use strict';

            $('#hStaticUploadTitle').html('Vă rugăm să așteptați !');
            var kStaticHTML = $('#hStaticUploadTitle');

            $('#'+obj['target']['id']).fileupload({
                url: '<?php echo qurl_s('creare-cont-angajator/upload'); ?>',
                dataType: 'json',
                done: function (e, data){
                    // if error show it
                    if (data['result']['result'] == 'success'){
                        kStaticHTML.html('SUCCES');
                    }else if (data['result']['result'] == 'EROARE: Filetype not allowed'){
                        kStaticHTML.html('EROARE: Fișierul încărcat trebuie să fie JPG !');
                    }else{
                        kStaticHTML.html(data['result']['result']);
                    }
                }
            });
        }

        $('#hFileUploadContainer').click(Upload);

    </script>

