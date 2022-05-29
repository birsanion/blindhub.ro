    <div id="content" class="container mt-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Actualizează CV</h1>
            </div>
            <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10">
                <form id="frm_editcv">
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Ce sectoare de activitate vă interesează?</strong></label>
                        <select class="selectpicker shadow form-control" data-style="btn-white" multiple data-live-search="true" required name="idx_domenii_cv[]" data-size="10">
                            <option value="" data-hidden="true"></option>
                            <?php foreach ($this->DATA['domenii_cv'] as $domeniu): ?>
                            <option value="<?= $domeniu['idx'] ?>" <?php if (in_array($domeniu['idx'], $this->DATA['details']['idx_domenii_cv'])) echo 'selected'; ?>><?= $domeniu['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>În ce oraș vrei să muncești?</strong></label>
                        <select class="selectpicker shadow form-control" data-style="btn-white" multiple data-live-search="true" data-size="10" required name="idx_orase[]">
                            <option value="" data-hidden="true"></option>
                            <?php foreach ($this->DATA['orase'] as $oras): ?>
                            <option value="<?= $oras['idx'] ?>" <?php if (in_array($oras['idx'], $this->DATA['details']['idx_orase'])) echo 'selected'; ?>><?= $oras['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <div>
                            <label class="form-label"><strong>Încarcă CV video</strong></label>
                        </div>
                        <div class="text-center" id="video-container">
                            <?php if ($this->DATA['details']['cv_fisier_video']): ?>
                            <video width="320" height="240" controls src="<?= qurl_file('media/uploads/'. $this->DATA['details']['cv_fisier_video']) ?>" >
                                Your browser does not support the video tag.
                            </video>
                            <?php else: ?>
                            <img class="img-fluid" src="<?= qurl_f('images/novideo.png') ?>" />
                            <?php endif; ?>
                        </div>
                        <input type="file" accept="video/*" class="custom-file-input shadow form-control" id="file-video">
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
            var file = null
            document.getElementById("file-video").onchange = function(event) {
                file = event.target.files[0];
                let blobURL = URL.createObjectURL(file);
                document.querySelector("#video-container").innerHTML = '<video width="320" height="240" controls src="' + blobURL + '" >Your browser does not support the video tag</video>';
            }

            var uploadFile = function(file, onSuccess, onError) {
                var formData = new FormData();
                formData.append('uploaded_file', file);
                $.ajax({
                    url: "<?= qurl_s('api/web-angajat-upload') ?>",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                }).done(function (data) {
                    onSuccess(data)
                }).fail(function (e) {
                    onError(e)
                })
            }

            $("#frm_editcv").validate({
                errorClass: "text-danger",
                errorPlacement: function (error, element) {
                    var formGroup = element.closest('.form-group')
                    formGroup.append(error)
                },
                submitHandler: function (form) {
                    var $submit = $(form).find('button[type="submit"]')
                    $submit.html('<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>Loading...').attr('disabled', true);

                    var onSuccess = function () {
                        $submit.html('Salvează').attr('disabled', false);
                        bootbox.alert({
                            message: "Actualizarea CV-ului a avut loc cu success",
                            closeButton: false,
                            callback: function () {
                                window.location = '<?= qurl_l('profil-angajat'); ?>';
                            }
                        })
                    }

                    var onError = function (e) {
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
                    }

                    $.ajax({
                        url: "<?= qurl_s('api/web-setcv') ?>",
                        type: "POST",
                        data: $(form).serialize()
                    }).done(function (data) {
                        if (file) {
                            uploadFile(file, onSuccess, onError)
                            return
                        }

                        onSuccess()
                    }).fail(function (e) {
                        onError(e)
                    })
                }
            })
        })

        </script>