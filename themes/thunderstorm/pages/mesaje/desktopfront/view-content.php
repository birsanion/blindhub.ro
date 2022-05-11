    <div id="content" class="container my-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu">Mesaje</h1>
            </div>

            <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10">
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

    </script>