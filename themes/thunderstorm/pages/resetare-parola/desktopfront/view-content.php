
    <div class="center-text">
        <h1 class="bold space-4040">RESETARE PAROLĂ</h1>
        
        <h1 class="space-2020">Care este adresa ta de email ?</h1>
        <input type="text" name="hEditEmail" id="hEditEmail" value="" class="center-text rounded space-0040 w60lst" placeholder="introduceți emailul aici" />
        
        <br /><br />
        <div id="hStaticErrorMsg"></div>
        <input type="button" name="hButtonNext" id="hButtonNext" value="RESETARE PAROLĂ" class="standard-button rounded space-2020" />
    </div>
    
    <script type="text/javascript">
    
        function ResetPass()
        {
            var jqXHR=$.post("<?php echo qurl_s('api/web-resetareparola'); ?>",
                {
                    email: $('#hEditEmail').val()
                },
                function(data){
                    if (data['result']=='success'){
                        $('#hStaticErrorMsg').html('Resetarea parolei a fost inițiată cu succes !<br />' +
                            'Vă rugăm să vă verificați căsuța de email și să parcurgeți pașii de acolo.');
                    }else $('#hStaticErrorMsg').html(data['result']);
                },
            "json");
            
            jqXHR.fail(function(a,b,c){
                alert("AJAX err: "+a+' - '+b);
            });
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
    