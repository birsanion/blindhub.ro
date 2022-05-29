     <div id="content" class="container my-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Caută candidați</h1>
                <?php if (!count($this->DATA['locuri'])): ?>
                <h4><strong>Nu am găsit aplicanți</strong></h4>
                <p>Vă recomandăm să actiulizati lista cu oprtunități cât mai des</p>
                <?php endif; ?>
                <?php
                    if (count($this->DATA['locuri']) > 0):
                        foreach ($this->DATA['locuri'] as $arrLoc) :
                ?>
                <div class="card shadow-lg mb-4">
                    <div class="card-body">
                        <h4><strong><?= $arrLoc['nume'] ?></strong></h4>
                        <h6>Facultate: <?= $arrLoc['locuniversitate']['facultate'] ?></h6>
                        <h6>Grad handicap: <?= $arrLoc['gradhandicap'] ?></h6>
                        <h6>Nevoi speciale: <?= $arrLoc['nevoispecifice'] ?></h6>
                        <h6>Domeniu: <?= $arrLoc['locuniversitate']['domeniu_universitate'] ?></h6>
                        <h6>Oraș: <?= $arrLoc['locuniversitate']['oras'] ?></h6>
                        <?php if ($arrLoc['interviu_tstamp']): ?>
                        <h6><strong>Interviu planificat: <?= $arrLoc['interviu_tstamp'] ?></strong></h6>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <a class="btn btn-primary rounded-pill px-3 mb-2" href="<?= qurl_l('mesaje/' . $arrLoc['idxauthnevazator']) ?>">Trimite mesaj</a>
                        <a class="btn btn-info rounded-pill px-3 mb-2" href="<?= qurl_l('cv-video/' . $arrLoc['idxauthnevazator']) ?>">Vizioneză CV-ul video</a>
                        <?php if (!$arrLoc['interviu_tstamp']): ?>
                        <button class="btn btn-warning rounded-pill btn-interviu  px-3 mb-2" data-idxauth="<?= $arrLoc['idxauthnevazator'] ?>" data-idxobject="<?= $arrLoc['locuniversitate']['idx'] ?>">Planifică interviu</button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
                        endforeach;
                    endif;
                ?>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $( document ).ready(function () {
            $('.btn-interviu').click(function () {
                var btn = $(this)
                var message =
                    '<p>Vă rugăm să selectați data și ora la care doriți să setați interviul</p>' +
                    '<form id="frm-interviu">' +
                    '   <input name="idxauthnevazator" value="' + btn.data('idxauth') + '" hidden/>' +
                    '   <input name="idxoferta" value="' + btn.data('idxobject') + '" hidden/>' +
                    '   <div class="form-group mb-3">' +
                    '       <label>Ora</label>' +
                    '       <select class="form-select" name="ora" required>' +
                    '           <option>09:00</option>' +
                    '           <option>09:15</option>' +
                    '           <option>09:30</option>' +
                    '           <option>09:45</option>' +
                    '           <option>10:00</option>' +
                    '           <option>10:15</option>' +
                    '           <option>10:30</option>' +
                    '           <option>10:45</option>' +
                    '           <option>11:00</option>' +
                    '           <option>11:15</option>' +
                    '           <option>11:30</option>' +
                    '           <option>11:45</option>' +
                    '           <option>12:00</option>' +
                    '           <option>12:15</option>' +
                    '           <option>12:30</option>' +
                    '           <option>12:45</option>' +
                    '           <option>13:00</option>' +
                    '           <option>13:15</option>' +
                    '           <option>13:30</option>' +
                    '           <option>13:45</option>' +
                    '           <option>14:00</option>' +
                    '           <option>14:15</option>' +
                    '           <option>14:30</option>' +
                    '           <option>14:45</option>' +
                    '           <option>15:00</option>' +
                    '           <option>15:15</option>' +
                    '           <option>15:30</option>' +
                    '           <option>15:45</option>' +
                    '           <option>16:00</option>' +
                    '           <option>16:15</option>' +
                    '           <option>16:30</option>' +
                    '           <option>16:45</option>' +
                    '           <option>17:00</option>' +
                    '           <option>17:15</option>' +
                    '           <option>17:30</option>' +
                    '           <option>17:45</option>' +
                    '       </select>' +
                    '   </div>' +
                    '   <div class="form-group">' +
                    '       <label>Data</label>' +
                    '       <input type="text" class="form-control datepicker" name="datacalend" required/>' +
                    '   </div>' +
                    '</form>'

                var dialog = bootbox.dialog({
                    title: 'Planifică interviu',
                    message: message,
                    closeButton: false,
                    buttons: {
                        cancel: {
                            label: "Anulează",
                            className: 'btn-danger rounded-pill',
                        },
                        ok: {
                            label: "Planifică interviu",
                            className: 'btn-primary btn-ok rounded-pill',
                            callback: function () {
                                var form = dialog.find('form')
                                if (!form.valid()) {
                                    return false
                                }

                                $this = dialog.find('.btn-ok')
                                $this.html('<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>Loading...').attr('disabled', true);
                                $.ajax({
                                    url: "<?= qurl_s('api/web-universitate-cautacandidati-initinterviu') ?>",
                                    type: "POST",
                                    data: $(form).serialize()
                                }).done(function () {
                                    dialog.modal('hide')
                                    bootbox.alert({
                                        closeButton: false,
                                        message: 'Interviul a fost planificat cu succes!',
                                        callback: function() {
                                            window.location = '<?= qurl_l('universitate-candidati'); ?>';
                                        }
                                    })
                                }).fail(function (e) {
                                    $this.html('Planifică interviu').attr('disabled', false);
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

                                return false
                            }
                        }
                    }
                })

                dialog.on("shown.bs.modal", function() {
                    $('.datepicker').datepicker({
                        dateFormat: 'dd/mm/yy',
                        minDate: 0,
                    })
                })
            })
        })
    </script>

    <script type="text/javascript">

        $('input.dateforinterviu').datepicker({
            showWeek: true,
            firstDay: 1,
            showButtonPanel: true,
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true
        });

        $('a.setinterviu').click(function(){
            var kElem = $(this);
            var strDate = $(this).parent().children('input.dateforinterviu').val();
            var nIdxAngajat = $(this).data('idx');
            var strOra = $(this).parent().children('select').val();

            if (strDate.length > 0){
                var jqXHR=$.post("<?php echo qurl_s('api/web-universitate-cautacandidati-initinterviu'); ?>",
                    {
                        datacalend: strDate,
                        idxauthnevazator: nIdxAngajat,
                        ora: strOra
                    },
                    function(data){
                        if (data['result']=='success'){
                            kElem.append('<div class="adhocinfo">Operațiune realizată cu succes !</div>');
                            setTimeout(function(){
                                $('div.adhocinfo').remove();
                            }, 5000);
                        }else{
                            kElem.append('<div class="adhocinfo">'+ data['result'] +'</div>');
                            setTimeout(function(){
                                $('div.adhocinfo').remove();
                            }, 5000);
                        }
                    },
                "json");

                jqXHR.fail(function(a,b,c){
                    alert("AJAX err: "+a+' - '+b);
                });
            }else{
                kElem.append('<div class="adhocinfo">Selectați o dată din calendar !</div>');
                setTimeout(function(){
                    $('div.adhocinfo').remove();
                }, 5000);
            }
        });

    </script>