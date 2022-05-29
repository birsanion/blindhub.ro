    <div id="content" class="container my-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu">Mesaje</h1>
            </div>

            <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10">
                <?php if ($this->AUTH->GetAdvancedDetail('tiputilizator') == 1 && PARAM(1)): ?>
                <div style="text-align:right" class="mb-3">
                    <button class="btn btn-warning rounded-pill btn-interviu px-3 mb-2"  data-type="company" data-idxauth="<?= PARAM(1) ?>">Planifică interviu</button>
                </div>
                <?php endif; ?>
                <?php if ($this->AUTH->GetAdvancedDetail('tiputilizator') == 2 && PARAM(1)): ?>
                <div style="text-align:right" class="mb-3">
                    <button class="btn btn-warning rounded-pill btn-interviu px-3 mb-2" data-type="university" data-idxauth="<?= PARAM(1) ?>">Planifică interviu</button>
                </div>
                <?php endif; ?>
                <div class="shadow">
                    <select id="hComboInterlocutor" class="form-control">
                        <option value="0">-- selectați interlocutorul --</option>
                        <?php foreach ($this->DATA['interlocutori'] as $nKey => $strInterl): ?>
                        <option value="<?php echo $this->DATA['idxinterlocutori'][$nKey]; ?>"<?php
                            if ($this->DATA['idxinterlocutori'][$nKey] == (int)PARAM(1))
                                echo ' selected="selected"';
                            ?>>
                            <?= htmlspecialchars($strInterl) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <div id="hStaticMsgSurface" class="w-100">
                        <?php
                            if (!empty($this->DATA['mesaje']))
                                foreach ($this->DATA['mesaje'] as $arrMesaj){
                                    echo '<div class="'.
                                        ($arrMesaj['altau'] ? 'msg-meu' : 'msg-interlocutor') .
                                        ' rounded">'. htmlspecialchars($arrMesaj['mesaj']) .'</div>';
                                }
                         ?>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" id="hEditMsg" placeholder="scrieți mesajul aici">
                        <div class="input-group-append">
                            <button class="btn btn-primary" id="hButtonSend" type="button" id="button-addon2">Trimite</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        function WindowResize()
        {
            $('#hStaticMsgSurface').css('height', ($(window).height() - 530) + 'px');
        }

        $(window).resize(WindowResize);
        WindowResize();

        $('#hComboInterlocutor').change(function(){
            if (parseInt($('#hComboInterlocutor').val()) > 0)
                window.location = '<?php echo qurl_l('mesaje/'); ?>' + $('#hComboInterlocutor').val();
            else $('#hComboInterlocutor').val(<?php echo (int)PARAM(1); ?>);
        });

        $(document).ready(function(){
            $('#hStaticMsgSurface').scrollTop($('#hStaticMsgSurface').prop("scrollHeight"));
            $('#hEditMsg').focus();
        });

        function SendMessage()
        {
            var jqXHR=$.post("<?php echo qurl_s('api/web-mesaje-send'); ?>",
                {
                    idxauthinter: $('#hComboInterlocutor').val(),
                    mesaj: $('#hEditMsg').val()
                },
                function(data){
                    if (data['result']=='success'){
                        $('#hStaticMsgSurface').append('<div class="msg-meu rounded">' +
                            htmlspecialchars($('#hEditMsg').val()) +'</div>');

                        $('#hStaticMsgSurface').scrollTop($('#hStaticMsgSurface').prop("scrollHeight"));
                        $('#hEditMsg').focus();
                        $('#hEditMsg').val('')
                    }
                },
            "json");

            jqXHR.fail(function(a,b,c){
                alert("AJAX err: "+a+' - '+b);
            });
        }

        $('#hButtonSend').click(SendMessage);
        $(document).keyup(function(kEvent){
            if (kEvent.keyCode === 13){
                kEvent.preventDefault();
                SendMessage();
            }
        });

        $( document ).ready(function () {
            $('.btn-interviu').click(function () {
                var btn = $(this)
                var apiUrl
                switch (btn.data('type')) {
                    case 'company':
                        apiUrl = '<?= qurl_s('api/web-cautaangajati-initinterviu') ?>'
                        break

                    case 'university':
                        apiUrl = '<?= qurl_s('api/web-universitate-cautacandidati-initinterviu') ?>'
                }

                var message =
                    '<p>Vă rugăm să selectați data și ora la care doriți să setați interviul</p>' +
                    '<form id="frm-interviu">' +
                    '   <input name="idxauthnevazator" value="' + btn.data('idxauth') + '" hidden/>' +
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
                                    url: apiUrl,
                                    type: "POST",
                                    data: $(form).serialize()
                                }).done(function () {
                                    dialog.modal('hide')
                                    bootbox.alert({
                                        closeButton: false,
                                        message: 'Interviul a fost planificat cu succes!',
                                        callback: function() {
                                            window.location = '<?= qurl_l('mesaje') . "/" . PARAM(1) ?>';
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
                        minDate: 0
                    })
                })
            })
        });

    </script>