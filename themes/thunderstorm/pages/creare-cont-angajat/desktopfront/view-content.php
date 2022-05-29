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
                                        window.location = '<?= qurl_l('cvitae'); ?>';
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
