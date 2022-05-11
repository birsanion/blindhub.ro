<div class="container" id="content">
    <div class="row my-5">
        <div class="offset-lg-1 col-lg-10">
            <h1 class="titlu">Resetare parolă</h1>

            <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10 mt-5">
                <form id="frm_resetpassword">
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Care este adresa ta de email ?</strong></label>
                        <input type="email" name="hEditEmail" id="hEditEmail" class="form-control shadow" placeholder="introduceți emailul aici" required>
                    </div>


                    <div class="form-group my-5">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">
                            Resetează parola
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    <script type="text/javascript">
         $( document ).ready(function () {
            $("#frm_resetpassword").validate({
                errorClass: "text-danger",
                errorPlacement: function (error, element) {
                    var formGroup = element.closest('.form-group')
                    formGroup.append(error)
                },
                submitHandler: function (form) {
                    $submit = $(form).find('button[type="submit"]')
                    $submit.html('<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>Loading...').attr('disabled', true);
                    $.ajax({
                        url: "<?= qurl_s('api/web-resetareparola') ?>",
                        type: "POST",
                        data: $(form).serialize()
                    }).done(function (data) {
                        $submit.html('Resetează parola').attr('disabled', false);
                        bootbox.alert({
                            message: 'Resetarea parolei a fost inițiată cu succes !<br />Vă rugăm să vă verificați căsuța de email și să parcurgeți pașii de acolo.',
                            closeButton: false,
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


        $('#hEditEmail').focus();

    </script>
