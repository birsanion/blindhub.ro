
    <div class="center-text">
        <?php
            if (PARAMS() >= 2 && $this->DATA['valid']){
        ?>
        <h1 class="bold space-4040">SETAȚI O PAROLĂ NOUĂ</h1>
        
        <span>Parola trebuie să conțină cel puțin 8 caractere, o literă mică, o literă mare, o cifră și un simbol.</span>
        <br /><br />
        <input type="password" name="hEditParola" id="hEditParola" value="" class="center-text rounded space-0040 w60lst" placeholder="introduceți parola aici" />
        
        <br /><br />
        <div id="hStaticErrorMsg"></div>
        <input type="button" name="hButtonNext" id="hButtonNext" value="RESETARE PAROLĂ" class="standard-button rounded space-2020" />
        <?php
            }else{
        ?>
        <h1 class="bold space-4040">
            La această pagină trebuie să ajungeți dând click pe linkul de resetare a parolei primit în email !
            <br />
            De asemenea, linkul respectiv trebuie să fie încă valid.
            <br />
            Fiecare link de resetare a parolei este de unică folosință !
        </h1>
        <?php
            }
        ?>
    </div>
    
    <script type="text/javascript">
    
        function ValidatePassword(strPass)
        {
            if (strPass.length < 8) return false;
            
            var kRegEx = /[A-Z]+/;
            if (!kRegEx.test(strPass)) return false;
            
            var kRegEx = /[a-z]+/;
            if (!kRegEx.test(strPass)) return false;
            
            var kRegEx = /[0-9]+/;
            if (!kRegEx.test(strPass)) return false;
            
            var kRegEx = /[^A-Za-z0-9]+/;
            if (!kRegEx.test(strPass)) return false;
            
            return true;
        }
    
        function ResetPass()
        {
            var strPass = $('#hEditParola').val();
            
            if (ValidatePassword(strPass)){
                var jqXHR=$.post("<?php echo qurl_s('api/web-reseteazaparola'); ?>",
                    {
                        hEditPass: strPass,
                        hStaticUserIdx: <?php echo (int)PARAM(1); ?>,
                        hStaticUserKey: '<?php echo htmlspecialchars(PARAM(2)); ?>'
                    },
                    function(data){
                        if (data['result']=='success'){
                            $('#hStaticErrorMsg').html('Resetarea parolei a fost efectuată cu succes !<br />' +
                                'Acum vă puteți autentifica în cont.');
                            
                            $('#hButtonNext').remove();
                        }else $('#hStaticErrorMsg').html(data['result']);
                    },
                "json");
                
                jqXHR.fail(function(a,b,c){
                    alert("AJAX err: "+a+' - '+b);
                });
            }else $('#hStaticErrorMsg').html('Trebuie să puneți o parolă corespunzătoare !');
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
    