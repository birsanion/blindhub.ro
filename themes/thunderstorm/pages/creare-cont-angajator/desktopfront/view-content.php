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
                                        window.location = '<?= qurl_l('angajator-logo'); ?>';
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
    </script>

