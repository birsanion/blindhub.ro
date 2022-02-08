
    <div class="center-text">
        <h1 class="bold space-4040">CREARE CONT NEVĂZĂTOR</h1>
        
        <form id="frm_creeazacont" method="post" enctype="multipart/form-data">
        
            <div id="section-1" class="section selected">
                <h1 class="space-2020">Salutare !<br />Care este numele tău ?</h1>
                <input type="text" name="hEditNume" id="hEditNume" value="" class="center-text rounded space-0040 w60lst" placeholder="introduceți numele aici" tabindex="0" />
            </div>
            
            <div id="section-2" class="section invisible">
                <h1 class="space-2020">Care este adresa ta de email ?</h1>
                <input type="text" name="hEditEmail" id="hEditEmail" value="" class="center-text rounded space-0040 w60lst" placeholder="introduceți emailul aici" />
            </div>
            
            <div id="section-3" class="section invisible">
                <h1 class="space-2020">Care este prenumele tău ?</h1>
                <input type="text" name="hEditPrenume" id="hEditPrenume" value="" class="center-text rounded space-0040 w60lst" placeholder="introduceți prenumele aici" />
            </div>
            
            <div id="section-4" class="section invisible">
                <h1 class="space-2020">Sunteți o persoană cu handicap vizual ...</h1>
                
                <div style="width: 20%; text-align: left;" class="center-page">
                    <label>
                        <input type="radio" name="hRadioHandicapVizual" id="hRadioHandicapVizual_1" value="grav" />
                        grav
                    </label><br /><br />
                    
                    <label>
                        <input type="radio" name="hRadioHandicapVizual" id="hRadioHandicapVizual_2" value="accentuat" />
                        accentuat
                    </label><br /><br />
                    
                    <label>
                        <input type="radio" name="hRadioHandicapVizual" id="hRadioHandicapVizual_3" value="mediu" />
                        mediu
                    </label><br /><br />
                    
                    <label>
                        <input type="radio" name="hRadioHandicapVizual" id="hRadioHandicapVizual_4" value="usor" />
                        ușor
                    </label><br /><br />
                </div>
            </div>
            
            <div id="section-5" class="section invisible">
                <h1 class="space-2020">Creează o parolă</h1>
                <span>Parola trebuie să conțină cel puțin 8 caractere, o literă mică, o literă mare, o cifră și un simbol.</span>
                <br /><br />
                <input type="password" name="hEditParola" id="hEditParola" value="" class="center-text rounded space-0040 w60lst" placeholder="introduceți parola aici" />
            </div>
            
            <div id="section-6" class="section invisible">
                <h1 class="space-2020">Nevoi specifice de adaptare</h1>
                <input type="text" name="hEditNevoiSpecifice" id="hEditNevoiSpecifice" value="" class="center-text rounded space-0040 w60lst" placeholder="introduceți nevoi specifice aici" />
            </div>
            
            <div id="section-7" class="section invisible">
                <br /><br />
                <h1 id="hStaticMesajFinal" class="space-2020">Bine ai venit !</h1>
                <br /><br />
            </div>
        
        </form>
        
        <br /><br />
        <div id="hStaticErrorMsg"></div>
        <input type="button" name="hButtonNext" id="hButtonNext" value="URMĂTORUL PAS &rArr;" class="standard-button rounded space-2020" />
    </div>
    
    <script type="text/javascript">
    
        function ValidateEmail(strEmail)
        {
            const kRegEx = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return kRegEx.test(String(strEmail).toLowerCase());
        }
        
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
        
        function Next()
        {
            var nCurrId = parseInt($('div.section.selected').attr('id').substring(8));
            
            var bMoveNext = true;
            
            switch (nCurrId)
            {
                case 1:{
                    if ($('#hEditNume').val().trim().length <= 0){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să completați numele !');
                    }
                }break;
                
                case 2:{
                    if ($('#hEditEmail').val().trim().length <= 0 || !ValidateEmail($('#hEditEmail').val())){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să completați corect adresa de email !');
                    }
                }break;
                
                case 3:{
                    if ($('#hEditPrenume').val().trim().length <= 0){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să completați prenumele !');
                    }
                }break;
                
                case 4:{
                    if (!$('#hRadioHandicapVizual_1').prop('checked') && !$('#hRadioHandicapVizual_2').prop('checked') &&
                        !$('#hRadioHandicapVizual_3').prop('checked') && !$('#hRadioHandicapVizual_4').prop('checked'))
                    {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;
                
                case 5:{
                    if (!ValidatePassword($('#hEditParola').val())){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să puneți o parolă corespunzătoare !');
                    }
                }break;
            }
            
            if (bMoveNext){
                $('#section-' + nCurrId).removeClass('selected');
                $('#section-' + nCurrId).addClass('invisible');
                
                $('#section-' + (nCurrId + 1)).addClass('selected');
                $('#section-' + (nCurrId + 1)).removeClass('invisible');
                
                $('#hStaticErrorMsg').html('');
                
                if (nCurrId == 6){
                    $('#hStaticErrorMsg').html('Vă rugăm să așteptați !');
                    $('#hButtonNext').addClass('invisible');
                    
                    var jqXHR=$.post("<?php echo qurl_s('api/web-createacc-nevaz'); ?>",
                        $('#frm_creeazacont').serialize(),
                        function(data){
                            if (data['result']=='success'){
                                $('#hStaticErrorMsg').html('');
                                $('#hButtonNext').removeClass('invisible');
                                $('#hButtonNext').val('FINALIZARE');
                            }else{
                                $('div.section').addClass('invisible');
                                $('div.section').removeClass('selected');
                                
                                $('#section-2').removeClass('invisible');
                                $('#section-2').addClass('selected');
                                
                                $('#hButtonNext').removeClass('invisible');
                                $('#hStaticErrorMsg').html(data['result']);
                            }
                        },
                    "json");
                    
                    jqXHR.fail(function(a,b,c){
                        alert("AJAX err: "+a+' - '+b);
                    });
                }
                
                if (nCurrId == 7)
                    window.location = '<?php echo qurl_l('auth-angajat'); ?>';
            }
        }
        
        $('#hButtonNext').click(Next);
        $(document).keyup(function(kEvent){
            if (kEvent.keyCode === 13){
                kEvent.preventDefault();
                Next();
            }
        });
        
        $('#hEditNume').focus();
        
    </script>
    