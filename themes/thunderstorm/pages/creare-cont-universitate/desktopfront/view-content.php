
    <div class="center-text">
        <h1 class="bold space-4040">CREARE CONT UNIVERSITATE</h1>
        
        <form id="frm_creeazacont" method="post" enctype="multipart/form-data">
            
            <div id="section-1" class="section selected">
                <h1 class="space-2020">Nume universitate</h1>
                <input type="text" name="hEditNume" id="hEditNume" value="" class="center-text rounded space-0040 w60lst" placeholder="nume universitate" />
            </div>
            
            <div id="section-2" class="section invisible">
                <h1 class="space-2020">Oraș</h1>
                <select name="hComboOras" id="hComboOras" class="w60lst rounded center-text">
                    <option value="albaiulia">Alba Iulia</option>
                    <option value="alexandria">Alexandria</option>
                    <option value="arad">Arad</option>
                    <option value="baiamare">Baia Mare</option>
                    <option value="bistritanasaud">Bistrița Năsăud</option>
                    <option value="braila">Brăila</option>
                    <option value="bucuresti">București</option>
                    <option value="botosani">Botoșani</option>
                    <option value="brasov">Brașov</option>
                    <option value="bacau">Bacău</option>
                    <option value="buzau">Buzău</option>
                    <option value="calarasi">Călărași</option>
                    <option value="clujnapoca">Cluj-Napoca</option>
                    <option value="constanta">Constanța</option>
                    <option value="craiova">Craiova</option>
                    <option value="deva">Deva</option>
                    <option value="iasi">Iași</option>
                    <option value="focsani">Focșani</option>
                    <option value="galati">Galați</option>
                    <option value="giurgiu">Giurgiu</option>
                    <option value="oradea">Oradea</option>
                    <option value="ploiesti">Ploiești</option>
                    <option value="pitesti">Pitești</option>
                    <option value="piatraneamt">Piatra Neamț</option>
                    <option value="resita">Reșița</option>
                    <option value="ramnicuvalcea">Râmnicu Vâlcea</option>
                    <option value="timisoara">Timișoara</option>
                    <option value="targumures">Târgu Mureș</option>
                    <option value="targujiu">Târgu Jiu</option>
                    <option value="slatina">Slatina</option>
                    <option value="sibiu">Sibiu</option>
                    <option value="satumare">Satu Mare</option>
                    <option value="suceava">Suceava</option>
                    <option value="vaslui">Vaslui</option>
                </select>
            </div>
            
            <div id="section-3" class="section invisible">
                <h1 class="space-2020">Nume și prenume reprezentant</h1>
                <input type="text" name="hEditNumeReprezentant" id="hEditNumeReprezentant" value="" class="center-text rounded space-0040 w40lst" placeholder="nume reprezentant" />
            </div>
            
            <div id="section-4" class="section invisible">
                <h1 class="space-2020">Adresa de email</h1>
                <input type="text" name="hEditEmail" id="hEditEmail" value="" class="center-text rounded space-0040 w40lst" placeholder="email" />
            </div>
            
            <div id="section-5" class="section invisible">
                <h1 class="space-2020">Creează o parolă</h1>
                <span>Parola trebuie să conțină cel puțin 8 caractere, o literă mică, o literă mare, o cifră și un simbol.</span>
                <br /><br />
                <input type="password" name="hEditParola" id="hEditParola" value="" class="center-text rounded space-0040" placeholder="parola" />
            </div>
            
            <div id="section-6" class="section invisible">
                <h1 class="space-2020">Menționati gradul de accesibilizare a instituției dumneavoastră</h1>
                
                <div style="width: 30%; text-align: left;" class="center-page">
                    <label>
                        <input type="radio" name="hRadioGradAcces" id="hRadioGradAcces_1" value="partiala" />
                        parțială
                    </label><br /><br />
                    
                    <label>
                        <input type="radio" name="hRadioGradAcces" id="hRadioGradAcces_2" value="satisfacatoare" />
                        satisfăcătoare
                    </label><br /><br />
                    
                    <label>
                        <input type="radio" name="hRadioGradAcces" id="hRadioGradAcces_3" value="totala" />
                        totală
                    </label><br /><br />
                </div>
            </div>
            
            <div id="section-7" class="section invisible">
                <h1 class="space-2020">Menționati gradul de echipare cu tehnologie asistivă</h1>
                
                <div style="width: 30%; text-align: left;" class="center-page">
                    <label>
                        <input type="radio" name="hRadioGradEchipare" id="hRadioGradEchipare_1" value="partiala" />
                        parțială
                    </label><br /><br />
                    
                    <label>
                        <input type="radio" name="hRadioGradEchipare" id="hRadioGradEchipare_2" value="satisfacatoare" />
                        satisfăcătoare
                    </label><br /><br />
                    
                    <label>
                        <input type="radio" name="hRadioGradEchipare" id="hRadioGradEchipare_3" value="totala" />
                        totală
                    </label><br /><br />
                </div>
            </div>
            
            <div id="section-8" class="section invisible">
                <h1 class="space-2020">Menționati dacă universitatea are studenți cu dizabilități înmatriculați</h1>
                
                <div style="width: 20%; text-align: left;" class="center-page">
                    <label>
                        <input type="radio" name="hRadioAreStudenti" id="hRadioAreStudenti_1" value="da" />
                        da
                    </label><br /><br />
                    
                    <label>
                        <input type="radio" name="hRadioAreStudenti" id="hRadioAreStudenti_2" value="nu" />
                        nu
                    </label><br /><br />
                </div>
            </div>
            
            <div id="section-9" class="section invisible">
                <h1 class="space-2020">Menționati dacă universitatea are un centru de sprijin destinat studenților cu dizabilități</h1>
                
                <div style="width: 20%; text-align: left;" class="center-page">
                    <label>
                        <input type="radio" name="hRadioAreCentruSprijin" id="hRadioAreCentruSprijin_1" value="da" />
                        da
                    </label><br /><br />
                    
                    <label>
                        <input type="radio" name="hRadioAreCentruSprijin" id="hRadioAreCentruSprijin_2" value="nu" />
                        nu
                    </label><br /><br />
                </div>
            </div>
            
            <div id="section-10" class="section invisible">
                <h1 class="space-2020">Menționati dacă universitatea deține camere în căminele studențești adaptate la toate tipurile de dizabilități</h1>
                
                <div style="width: 20%; text-align: left;" class="center-page">
                    <label>
                        <input type="radio" name="hRadioAreCamere" id="hRadioAreCamere_1" value="da" />
                        da
                    </label><br /><br />
                    
                    <label>
                        <input type="radio" name="hRadioAreCamere" id="hRadioAreCamere_2" value="nu" />
                        nu
                    </label><br /><br />
                </div>
            </div>
            
            <div id="section-11" class="section invisible">
                <h1 class="space-2020">Menționați dacă universitatea are o persoană sau un birou dedicat aplicației BlindHUB</h1>
                
                <div style="width: 20%; text-align: left;" class="center-page">
                    <label>
                        <input type="radio" name="hRadioArePersDedicat" id="hRadioArePersDedicat_1" value="da" />
                        da
                    </label><br /><br />
                    
                    <label>
                        <input type="radio" name="hRadioArePersDedicat" id="hRadioArePersDedicat_2" value="nu" />
                        nu
                    </label><br /><br />
                </div>
            </div>
            
            <div id="section-12" class="section invisible">
                <h1 class="space-2020">Cazare - accesibilizări</h1>
                
                <div style="width: 20%; text-align: left;" class="center-page">
                    <label>
                        <input type="radio" name="hRadioCazareAcces" id="hRadioCazareAcces_1" value="mediu" />
                        mediu
                    </label><br /><br />
                    
                    <label>
                        <input type="radio" name="hRadioCazareAcces" id="hRadioCazareAcces_2" value="usor" />
                        usor
                    </label><br /><br />
                    
                    <label>
                        <input type="radio" name="hRadioCazareAcces" id="hRadioCazareAcces_3" value="total" />
                        total
                    </label><br /><br />
                </div>
            </div>
            
            <div id="section-13" class="section invisible">
                <h1 class="space-2020">Costuri adaptate</h1>
                
                <div style="width: 20%; text-align: left;" class="center-page">
                    <label>
                        <input type="radio" name="hRadioCosturi" id="hRadioCosturi_1" value="mediu" />
                        mediu
                    </label><br /><br />
                    
                    <label>
                        <input type="radio" name="hRadioCosturi" id="hRadioCosturi_2" value="usor" />
                        usor
                    </label><br /><br />
                    
                    <label>
                        <input type="radio" name="hRadioCosturi" id="hRadioCosturi_3" value="total" />
                        total
                    </label><br /><br />
                </div>
            </div>
            
            <div id="section-14" class="section invisible">
                <br /><br />
                <h1 class="space-2020">Bine ai venit !</h1>
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
                
                case 4:{
                    if ($('#hEditEmail').val().trim().length <= 0 || !ValidateEmail($('#hEditEmail').val())){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să completați corect adresa de email !');
                    }
                }break;
                
                case 5:{
                    if (!ValidatePassword($('#hEditParola').val())){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să puneți o parolă corespunzătoare !');
                    }
                }break;
                
                case 6:{
                    if (!$('#hRadioGradAcces_1').prop('checked') && !$('#hRadioGradAcces_2').prop('checked') &&
                        !$('#hRadioGradAcces_3').prop('checked'))
                    {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;
                
                case 7:{
                    if (!$('#hRadioGradEchipare_1').prop('checked') && !$('#hRadioGradEchipare_2').prop('checked') &&
                        !$('#hRadioGradEchipare_3').prop('checked'))
                    {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;
                
                case 8:{
                    if (!$('#hRadioAreStudenti_1').prop('checked') && !$('#hRadioAreStudenti_2').prop('checked'))
                    {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;
                
                case 9:{
                    if (!$('#hRadioAreCentruSprijin_1').prop('checked') && !$('#hRadioAreCentruSprijin_2').prop('checked'))
                    {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;
                
                case 10:{
                    if (!$('#hRadioAreCamere_1').prop('checked') && !$('#hRadioAreCamere_2').prop('checked'))
                    {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;
                
                case 11:{
                    if (!$('#hRadioArePersDedicat_1').prop('checked') && !$('#hRadioArePersDedicat_2').prop('checked'))
                    {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;
                
                case 12:{
                    if (!$('#hRadioCazareAcces_1').prop('checked') && !$('#hRadioCazareAcces_2').prop('checked') &&
                        !$('#hRadioCazareAcces_3').prop('checked'))
                    {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;
                
                case 13:{
                    if (!$('#hRadioCosturi_1').prop('checked') && !$('#hRadioCosturi_2').prop('checked') &&
                        !$('#hRadioCosturi_3').prop('checked'))
                    {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;
            }
            
            if (bMoveNext){
                $('#section-' + nCurrId).removeClass('selected');
                $('#section-' + nCurrId).addClass('invisible');
                
                $('#section-' + (nCurrId + 1)).addClass('selected');
                $('#section-' + (nCurrId + 1)).removeClass('invisible');
                
                $('#hStaticErrorMsg').html('');
                
                if (nCurrId == 13){
                    $('#hStaticErrorMsg').html('Vă rugăm să așteptați !');
                    $('#hButtonNext').addClass('invisible');
                    
                    var jqXHR=$.post("<?php echo qurl_s('api/web-createacc-universitati'); ?>",
                        $('#frm_creeazacont').serialize(),
                        function(data){
                            if (data['result']=='success'){
                                $('#hStaticErrorMsg').html('');
                                $('#hButtonNext').removeClass('invisible');
                                $('#hButtonNext').val('FINALIZARE');
                            }else{
                                $('div.section').addClass('invisible');
                                $('div.section').removeClass('selected');
                                
                                $('#section-4').removeClass('invisible');
                                $('#section-4').addClass('selected');
                                
                                $('#hButtonNext').removeClass('invisible');
                                $('#hStaticErrorMsg').html(data['result']);
                            }
                        },
                    "json");
                    
                    jqXHR.fail(function(a,b,c){
                        alert("AJAX err: "+a+' - '+b);
                    });
                }
                
                if (nCurrId == 14)
                    window.location = '<?php echo qurl_l('auth-universitate'); ?>';
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
    