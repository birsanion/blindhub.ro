    <div id="content" class="container mt-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Postează o ofertă</h1>
            </div>
            <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10">
                <form id="frm_oferta">
                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>Nume Facultate:</strong>
                        </label>
                        <input type="text" name="nume" class="form-control shadow" placeholder="nume facultate" required>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Domeniu de activitate:</strong></label>
                        <select class="selectpicker form-control shadow" data-style="btn-white" data-live-search="true" data-size="12" required name="idx_domeniu_universitate">
                            <option data-hidden="true"></option>
                            <?php foreach ($this->DATA['domenii_universitate'] as $domeniu): ?>
                            <option value="<?= $domeniu['idx'] ?>"><?= $domeniu['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>Numar de locuri:</strong>
                        </label>
                        <input type="number" name="nrlocuri" class="form-control shadow" placeholder="Introdu aici numărul de locuri" required>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Oraș:</strong></label>
                        <select class="selectpicker form-control shadow" data-style="btn-white" data-live-search="true" data-size="12" required name="idx_oras">
                            <option data-hidden="true"></option>
                            <?php foreach ($this->DATA['orase'] as $oras): ?>
                            <option value="<?= $oras['idx'] ?>"><?= $oras['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group my-5">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">
                            Adaugă ofertă
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--<div class="master-container center-page">
        <div class="center-text"><h1 class="bold space-4040">OFERTE</h1></div>

        <div class="w80lst center-page">
            <form id="frm_oferta" enctype="multipart/form-data" method="post">
            <table id="tbl-posteaza-locmunca" class="fullwidth">
                <tr>
                    <td>Facultate:</td>
                    <td>
                        <input type="text" name="hEditFacultate" id="hEditFacultate" value="" class="rounded w80lst" placeholder="facultate" />
                    </td>
                </tr>
                <tr>
                    <td>Domeniu de activitate:</td>
                    <td>
                        <select name="hComboDomeniu" id="hComboDomeniu" class="rounded w80lst">
                            <option value="it">IT</option>
                            <option value="medical">Medical</option>
                            <option value="callcenter">Call center</option>
                            <option value="resurseumane">Resurse umane</option>
                            <option value="asistentasociala">Asistență socială</option>
                            <option value="jurnalism">Jurnalism și relații publice</option>
                            <option value="radio">Radio</option>
                            <option value="psihologie">Psihologie consiliere coaching</option>
                            <option value="educatie">Educație și training</option>
                            <option value="artistica">Industria creativă și artistică</option>
                            <option value="administratie">Administrație publică și instituții</option>
                            <option value="desk">Desk office</option>
                            <option value="wellness">Wellness și SPA</option>
                            <option value="traducator">Traducător / translator</option>
                            <option value="diverse">Diverse</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Număr locuri rezervate:</td>
                    <td>
                        <input type="text" name="hEditNrLocuri" id="hEditNrLocuri" value="" class="rounded w20lst" placeholder="locuri" />
                    </td>
                </tr>
            </table>
            </form>

            <div class="center-text">
                <div id="hStaticErrorMsg"></div>
                <input type="button" name="hButtonNext" id="hButtonNext" value="ADAUGĂ ANUNȚ" class="standard-button rounded space-2020" />
            </div>
        </div>
    </div>-->

    <script type="text/javascript">
        $( document ).ready(function () {
             $("#frm_oferta").validate({
                errorClass: "text-danger",
                errorPlacement: function (error, element) {
                    var formGroup = element.closest('.form-group')
                    formGroup.append(error)
                },
                submitHandler: function (form) {
                    $submit = $(form).find('button[type="submit"]')
                    $submit.html('<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>Loading...').attr('disabled', true);
                    $.ajax({
                        url: "<?= qurl_s('api/web-universitate-publicaoferta') ?>",
                        type: "POST",
                        data: $(form).serialize()
                    }).done(function (data) {
                        $submit.html('Salvează').attr('disabled', false);
                        bootbox.alert({
                            message: "Oferta postată cu success!",
                            closeButton: false,
                            callback: function () {
                                window.location = '<?= qurl_l('home-universitate'); ?>';
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

        $('#hButtonNext').click(function(){
            if ($('#hEditFacultate').val().length > 0 && $('#hEditNrLocuri').val().length > 0){
                var jqXHR=$.post("<?php echo qurl_s('api/web-universitate-publicaoferta'); ?>",
                    $('#frm_oferta').serialize(),
                    function(data){
                        if (data['result']=='success'){
                            window.location = '<?php echo qurl_l('home-universitate'); ?>';
                        }else{
                            $('#hStaticErrorMsg').html(data['result']);
                        }
                    },
                "json");

                jqXHR.fail(function(a,b,c){
                    alert("AJAX err: "+a+' - '+b);
                });
            }else{
                $('#hStaticErrorMsg').html('Trebuie să completați facultatea și numărul de locuri !');
            }
        });

    </script>


