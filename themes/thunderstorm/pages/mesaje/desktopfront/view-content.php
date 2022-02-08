
    <div class="master-container center-page center-text">
        <h1 class="bold space-2040">MESAJE</h1>
        
        <select id="hComboInterlocutor" class="w60lst rounded center-text">
            <option value="0">-- selectați interlocutorul --</option>
            <?php
                if (count($this->DATA['interlocutori']) > 0){
                    foreach ($this->DATA['interlocutori'] as $nKey => $strInterl){
            ?>
            <option value="<?php echo $this->DATA['idxinterlocutori'][$nKey]; ?>"<?php
                if ($this->DATA['idxinterlocutori'][$nKey] == (int)PARAM(1))
                    echo ' selected="selected"';
                ?>><?php
                echo htmlspecialchars($strInterl);
                ?></option>
            <?php
                    }
                }
            ?>
        </select>
        
        <div id="hStaticMsgSurface" class="rounded center-page">
            <?php
                if (!empty($this->DATA['mesaje']))
                    foreach ($this->DATA['mesaje'] as $arrMesaj){
                        echo '<div class="'.
                            ($arrMesaj['altau'] ? 'msg-meu' : 'msg-interlocutor') .
                            ' rounded">'. htmlspecialchars($arrMesaj['mesaj']) .'</div>';
                    }
             ?>
        </div>
        
        <div>
            <input type="text" id="hEditMsg" class="rounded w40lst" value="" placeholder="scrieți mesajul aici" />
            <input type="button" id="hButtonSend" value="TRIMITE" class="standard-button rounded space-2020" />
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