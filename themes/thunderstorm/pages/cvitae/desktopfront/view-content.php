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

                    <div class="form-group my-5">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">
                            Salvează
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--<div class="master-container center-page center-text">
        <h1 class="bold space-4040">CURRICULUM VITAE</h1>

        <form id="frm_cv" method="post" enctype="multipart/form-data">
        <div id="section-1" class="section selected">
            <h1 class="space-2020">Ce sectoare de activitate vă interesează ?</h1>

            <br />
            <table class="w80lst center-page">
                <?php foreach ($this->DATA['domenii_cv'] as $index => $value): ?>

                <?php if ($index % 2 == 0): ?>
                <tr>
                <?php endif; ?>

                <td style="text-align: left;">
                    <label><input type="checkbox" name="idx_domenii_cv[]" value="<?= $value['idx'] ?>" /> <?= $value['nume'] ?> </label>
                </td>

                <?php if (($index + 1) % 2 == 0): ?>
                </tr>
                <?php endif; ?>

                <?php endforeach; ?>

            </table>
        </div>

        <div id="section-2" class="section invisible">
            <h1 class="space-2020">În ce oraș vrei să muncești ?</h1>

            <br />
            <table class="w80lst center-page">
                <?php foreach ($this->DATA['orase'] as $index => $value): ?>

                <?php if ($index % 2 == 0): ?>
                <tr>
                <?php endif; ?>

                <td style="text-align: left;">
                    <label><input type="checkbox" name="idx_orase[]" value="<?= $value['idx'] ?>" /> <?= $value['nume'] ?> </label>
                </td>

                <?php if (($index + 1) % 2 == 0): ?>
                </tr>
                <?php endif; ?>

                <?php endforeach; ?>
            </table>
        </div>
        </form>

        <div id="section-3" class="section invisible">
            <h1 class="space-2020" id="hStaticUploadTitle">Upload CV Video</h1>

            <form>
            <span id="hFileUploadContainer" class="btn btn-success fileinput-button" style="background: #2E295C;">
                <i class="icon-plus icon-white"></i>
                <span>ÎNCARCĂ VIDEO</span>
                <input id="hFileUpload" type="file" name="files[]">
            </span>
            </form>
        </div>

        <br /><br />
        <div id="hStaticErrorMsg"></div>
        <input type="button" name="hButtonNext" id="hButtonNext" value="URMĂTORUL PAS &rArr;" class="standard-button rounded space-2020" />
    </div>-->

    <script type="text/javascript">
        $( document ).ready(function () {
            $("#frm_editcv").validate({
                errorClass: "text-danger",
                errorPlacement: function (error, element) {
                    var formGroup = element.closest('.form-group')
                    formGroup.append(error)
                },
                submitHandler: function (form) {
                    $submit = $(form).find('button[type="submit"]')
                    $submit.html('<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>Loading...').attr('disabled', true);
                    $.ajax({
                        url: "<?= qurl_s('api/web-setcv') ?>",
                        type: "POST",
                        data: $(form).serialize()
                    }).done(function (data) {
                        $submit.html('Salvează').attr('disabled', false);
                        bootbox.alert({
                            message: "Actualizarea CV-ului a avut loc cu success",
                            closeButton: false,
                            callback: function () {
                                window.location = '<?= qurl_l('profil-angajat'); ?>';
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

            function Next()
            {
                var nCurrId = parseInt($('div.section.selected').attr('id').substring(8));

                var bMoveNext = true;

                switch (nCurrId)
                {
                    case 1:{
                        var bChecked = false;

                        $('#section-1').find('input[type="checkbox"]').each(function(nIndex, kElement){
                            if ($(this).prop('checked')) bChecked = true;
                        });

                        if (!bChecked){
                            bMoveNext = false;
                            $('#hStaticErrorMsg').html('Trebuie să bifați cel puțin o opțiune !');
                        }
                    }break;
                }

                if (bMoveNext){
                    $('#section-' + nCurrId).removeClass('selected');
                    $('#section-' + nCurrId).addClass('invisible');

                    $('#section-' + (nCurrId + 1)).addClass('selected');
                    $('#section-' + (nCurrId + 1)).removeClass('invisible');

                    $('#hStaticErrorMsg').html('');

                    if (nCurrId == 2){
                        var jqXHR=$.post("<?php echo qurl_s('api/web-setcv'); ?>",
                            $('#frm_cv').serialize(),
                            function(data){
                                if (data['result']=='success'){
                                    $('#hStaticErrorMsg').html('');
                                    $('#hButtonNext').removeClass('invisible');
                                    $('#hButtonNext').val('FINALIZARE');
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

                    if (nCurrId == 3)
                        window.location = '<?php echo qurl_l('home-nevaz'); ?>';
                }
            }

            $('#hButtonNext').click(Next);
            $(document).keyup(function(kEvent){
                if (kEvent.keyCode === 13){
                    kEvent.preventDefault();
                    Next();
                }
            });

            function Upload(obj)
            {
                'use strict';

                $('#hStaticUploadTitle').html('Vă rugăm să așteptați !');

                $('#'+obj['target']['id']).fileupload({
                    url: '<?php echo qurl_s('cvitae/upload'); ?>',
                    dataType: 'json',
                    done: function (e, data) {
                        // if error show it
                        if (data['result'] == 'success'){
                            console.log(data);
                            $('#hStaticUploadTitle').html('SUCCES');
                        }else{
                            $('#hStaticUploadTitle').html(data['result']);
                        }
                    }
                });
            }

            $('#hFileUploadContainer').click(Upload);

        </script>