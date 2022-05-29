<div id="content">
    <div class="container mt-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10 mb-5">
                <h1 class="titlu mb-5">Creare cont universitate</h1>

                <div>
                    <p>
                        Universitățile reprezintă centre de excelență prin care ne putem desăvârși în drumul nostru vocațional și profesional. Ele sunt adevărate repere în transformarea comunităților și dezvoltare societății noastre
                    </p>

                    <p>
                        Dacă reprezentați o universitate care dorește să crească participarea persoanelor cu dizabilități de vedere la mediul educațional contribuind astfel la o societate incluzivă, aplicația BLINDHUB îți oferă posibilitatea de a crea punți de legătură cu comunitatea persoanelor cu dizabilități de vedere.
                    </p>
                    <p>
                        Poți posta mesaje, raspunde la întrebările potenșialilor studenți sau studenților înmatriculați, iniția apeluri video pentru a răspunde întrebărilor sau furniza clarificări în orice demers instuțional.
                    </p>
                </div>

                <h5 class="mt-3">
                    <strong>
                        Ai deja un cont? <a href="<?= qurl_l('auth-universitate') ?>">Intră în cont</a>
                    </strong>
                </h5>
            </div>

            <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10">
                <form id="frm_creeazacont">
                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>Nume universitate:</strong>
                        </label>
                        <input type="text" name="nume" class="form-control shadow" placeholder="nume universitate" required=>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>Adresa de email:</strong>
                        </label>
                        <input type="email" name="email" class="form-control shadow" placeholder="email" required>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>Creează o parolă:</strong>
                        </label>
                        <input type="password" name="parola" class="form-control shadow" placeholder="parolă" required>
                        <small class="form-text text-muted">Parola trebuie să conțină cel puțin 8 caractere, o literă mică, o literă mare, o cifră și un simbol.</small>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Orașe:</strong></label>
                        <select class="selectpicker form-control shadow" multiple  data-style="btn-white" data-live-search="true" data-size="12" required name="idx_orase[]">
                            <?php foreach ($this->DATA['orase'] as $oras): ?>
                            <option value="<?= $oras['idx'] ?>"><?= $oras['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>Numele reprezentantului universității:</strong>
                        </label>
                        <input type="text" name="reprezentant" class="form-control shadow" placeholder="reprezentant" required=>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Menționați gradul de accesibilitate a instituției dumneavoastră:</strong></label>
                        <?php foreach ($this->DATA['optiuni']['accesibilizare_clasa'] as $idx => $optiune): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="<?= $idx ?>" name="idx_optiune_accesibilizare_clasa" required>
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
                            <input class="form-check-input" type="radio" value="<?= $idx ?>" name="idx_optiune_gradechipare" required>
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
                            <input class="form-check-input" type="radio" value="0" name="studdiz" required>
                            <label class="form-check-label">
                                Nu
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" name="studdiz" required>
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
                            <input class="form-check-input" type="radio" value="0" name="studcentru" required>
                            <label class="form-check-label">
                                Nu
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" name="studcentru" required>
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
                            <input class="form-check-input" type="radio" value="0" name="camerecamine" required>
                            <label class="form-check-label">
                                Nu
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" name="camerecamine" required>
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
                            <input class="form-check-input" type="radio" value="0" name="persdedic" required>
                            <label class="form-check-label">
                                Nu
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" name="persdedic" required>
                            <label class="form-check-label">
                                Da
                            </label>
                        </div>
                    </div>

                     <div class="form-group mb-4">
                        <label class="form-label"><strong>Menționați gradul de accesibilizare a sălilor de curs:</strong></label>
                        <?php foreach ($this->DATA['optiuni']['gradacces'] as $idx => $optiune): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="<?= $idx ?>" name="idx_optiune_gradacces" required>
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
                            <input class="form-check-input" type="radio" value="0" name="braille" required>
                            <label class="form-check-label">
                                Nu
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" name="braille" required>
                            <label class="form-check-label">
                                Da
                            </label>
                        </div>
                    </div>


                    <div class="form-group my-5">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">
                            Crează cont
                        </button>
                    </div>
                </form>
            </div>
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
                        url: "<?= qurl_s('api/web-createacc-universitati') ?>",
                        type: "POST",
                        data: $(form).serialize()
                    }).done(function (data) {
                        bootbox.dialog({
                            message: "Contul a fost creat cu success!",
                            closeButton: false,
                            buttons: {
                                ok: {
                                    label: "Intră in cont",
                                    className: 'btn-primary',
                                    callback: function() {
                                        window.location = '<?= qurl_l('universitate-logo'); ?>';
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
