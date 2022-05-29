    <div id="content" class="container mt-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Încarcă logo firmă</h1>
            </div>
            <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10">
                <form id="frm_uploadlogo">
                    <div class="form-group mb-4">
                        <div class="text-center">
                            <img style="max-width:250px" id="img-preview" src="<?= qurl_f('images/no-image.svg') ?>">
                        </div>
                        <input type="file" accept="image/*" class="custom-file-input shadow form-control" id="file-img" required>
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
            document.getElementById("file-img").onchange = function(event) {
                let file = event.target.files[0];
                let blobURL = URL.createObjectURL(file);
                document.querySelector("#img-preview").src = blobURL;
            }

            $("#frm_uploadlogo").validate({
                errorClass: "text-danger",
                errorPlacement: function (error, element) {
                    var formGroup = element.closest('.form-group')
                    formGroup.append(error)
                },
                submitHandler: function (form) {
                    $submit = $(form).find('button[type="submit"]')
                    $submit.html('<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>Loading...').attr('disabled', true);
                    var formData = new FormData();
                    formData.append('uploaded_file', $('input[type=file]')[0].files[0]);
                    $.ajax({
                        url: "<?= qurl_s('api/web-angajator-upload') ?>",
                        type: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                    }).done(function (data) {
                        $submit.html('Salvează').attr('disabled', false);
                        bootbox.alert({
                            message: "Logo a fost setat cu succes!",
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
    </script>