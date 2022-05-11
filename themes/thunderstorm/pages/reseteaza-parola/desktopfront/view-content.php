<div class="container" id="content">
    <div class="row my-5">
        <div class="offset-lg-1 col-lg-10">
            <?php if (PARAMS() >= 2 && $this->DATA['valid']): ?>
            <h1 class="titlu">Setați o parola nouă</h1>

            <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10 mt-5">
                <h5>
                    <strong>
                        Parola trebuie să conțină cel puțin 8 caractere, o literă mică, o literă mare, o cifră și un simbol.
                    </strong>
                </h5>
                <form id="frm_resetpassword">
                    <div class="form-group mb-4">
                        <input type="password" name="hEditParola" id="hEditParola" class="form-control shadow" placeholder="introduceți parola aici" required>
                    </div>


                    <div class="form-group my-5">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">
                            Resetează parola
                        </button>
                    </div>
                </form>
            </div>
            <?php else: ?>
            <h5><strong>La această pagină trebuie să ajungeți dând click pe linkul de resetare a parolei primit în email!</strong></h5>
            <h5><strong>De asemenea, linkul respectiv trebuie să fie încă valid.</strong></h5>
            <h5><strong>Fiecare link de resetare a parolei este de unică folosință !</strong></h5>
            <?php endif; ?>
        </div>
    </div>
</div>
    <!--<div class="center-text">
        <?php
            if (PARAMS() >= 2 && $this->DATA['valid']){
        ?>
        <h1 class="bold space-4040">SETAȚI O PAROLĂ NOUĂ</h1>

        <span>Parola trebuie să conțină cel puțin 8 caractere, o literă mică, o literă mare, o cifră și un simbol.</span>
        <br /><br />
        <input type="password" name="hEditParola" id="hEditParola" value="" class="center-text rounded space-0040 w60lst" placeholder="introduceți parola aici" />

        <br /><br />
        <div id="hStaticErrorMsg"></div>
        <input type="button" name="hButtonNext" id="hButtonNext" value="RESETARE PAROLĂ" class="standard-button rounded space-2020" />
        <?php
            }else{
        ?>
        <h1 class="bold space-4040">
            La această pagină trebuie să ajungeți dând click pe linkul de resetare a parolei primit în email !
            <br />
            De asemenea, linkul respectiv trebuie să fie încă valid.
            <br />
            Fiecare link de resetare a parolei este de unică folosință !
        </h1>
        <?php
            }
        ?>
    </div>-->

    <script type="text/javascript">

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

        jQuery.validator.addMethod("parola", function (value, element) {
            return ValidatePassword(value)
        }, "Trebuie să puneți o parolă corespunzătoare ")

        $( document ).ready(function () {
            $("#frm_resetpassword").validate({
                errorClass: "text-danger",
                rules: {
                    'hEditParola': 'parola'
                },
                errorPlacement: function (error, element) {
                    var formGroup = element.closest('.form-group')
                    formGroup.append(error)
                },
                submitHandler: function (form) {
                    $submit = $(form).find('button[type="submit"]')
                    $.ajax({
                        url: "<?= qurl_s('api/web-reseteazaparola') ?>",
                        type: "POST",
                        data: {
                            hEditPass: $('#hEditParola').val(),
                            hStaticUserIdx: <?php echo (int)PARAM(1); ?>,
                            hStaticUserKey: '<?php echo htmlspecialchars(PARAM(2)); ?>'
                        }
                    }).done(function (data) {
                        $submit.html('Resetează parola').attr('disabled', false);
                        bootbox.dialog({
                            message: "Resetarea parolei a fost efectuată cu succes !<br />Acum vă puteți autentifica în cont.",
                            closeButton: false,
                            buttons: {
                                ok: {
                                    label: "Ok",
                                    className: 'btn-primary',
                                }
                            }
                        })
                    }).fail(function (e) {
                        $submit.html('Resetează parola').attr('disabled', false);
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


        function ResetPass()
        {
            var strPass = $('#hEditParola').val();

            if (ValidatePassword(strPass)){
                var jqXHR=$.post("<?php echo qurl_s('api/web-reseteazaparola'); ?>",
                    {
                        hEditPass: strPass,
                        hStaticUserIdx: <?php echo (int)PARAM(1); ?>,
                        hStaticUserKey: '<?php echo htmlspecialchars(PARAM(2)); ?>'
                    },
                    function(data){
                        if (data['result']=='success'){
                            $('#hStaticErrorMsg').html('Resetarea parolei a fost efectuată cu succes !<br />' +
                                'Acum vă puteți autentifica în cont.');

                            $('#hButtonNext').remove();
                        }else $('#hStaticErrorMsg').html(data['result']);
                    },
                "json");

                jqXHR.fail(function(a,b,c){
                    alert("AJAX err: "+a+' - '+b);
                });
            }else $('#hStaticErrorMsg').html('Trebuie să puneți o parolă corespunzătoare !');
        }

        $('#hButtonNext').click(ResetPass);
        $(document).keyup(function(kEvent){
            if (kEvent.keyCode === 13){
                kEvent.preventDefault();
                ResetPass();
            }
        });

        $('#hEditEmail').focus();

    </script>
