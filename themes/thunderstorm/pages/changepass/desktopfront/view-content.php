<div class="container" id="content">
    <div class="row my-5">
        <div class="offset-lg-1 col-lg-10">
            <h1 class="titlu">Schimbare parolă</h1>

            <h5>
                Parola trebuie să conțină cel puțin 8 caractere, o literă mică, o literă mare, o cifră și un simbol.
            </h5>

            <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10 mt-5">
                <form id="frm_changepassword">
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Parolă existentă</strong></label>
                        <input type="password"class="form-control shadow" name="parolaveche" placeholder="Scrie parola existentă aici." required>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Parolă nouă</strong></label>
                        <input type="password" class="form-control shadow" id="parolanoua" name="parolanoua" placeholder="Scrie parola nouă aici." required>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Repetați parola nouă aici</strong></label>
                        <input type="password"class="form-control shadow" name="parolanoua_again" placeholder="Scrie parola nouă din nou aici." required>
                    </div>

                    <div class="form-group my-5">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">
                            Aplică
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

        function ValidatePassword (strPass) {
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

        $( document ).ready(function () {
            $("#frm_changepassword").validate({
                errorClass: "text-danger",
                rules: {
                    parolanoua: 'parola',
                    parolanoua_again: {
                        equalTo: "#parolanoua"
                    },
                },
                messages: {
                    parolanoua_again: "Parola noua nu se potrivește cu repetarea acesteia.",
                },
                errorPlacement: function (error, element) {
                    var formGroup = element.closest('.form-group')
                    formGroup.append(error)
                },
                submitHandler: function (form) {
                    $submit = $(form).find('button[type="submit"]')
                    $submit.html('<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>Loading...').attr('disabled', true);
                    $.ajax({
                        url: "<?= qurl_s('api/changepass') ?>",
                        type: "POST",
                        data: $(form).serialize()
                    }).done(function (data) {
                        $submit.html('Aplică').attr('disabled', false);
                        bootbox.alert({
                            message: 'Schimbarea parolei a avut loc cu success.',
                            closeButton: false,
                            callback: function () {
                                $(form)[0].reset()
                            }
                        })
                    }).fail(function (e) {
                        $submit.html('Aplică').attr('disabled', false);
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
