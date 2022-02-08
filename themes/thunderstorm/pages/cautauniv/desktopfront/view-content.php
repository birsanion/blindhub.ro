
    <div class="master-container center-page center-text">
        <h1 class="bold space-4040">CAUTĂ UNIVERSITĂȚI</h1>
        
        <form id="frm_cauta" method="post" enctype="multipart/form-data">
        <div id="section-1" class="section selected">
            <h1 class="space-2020">Orașul dorit:</h1>
            
            <br />
            <select name="hCombo_Oras" id="hCombo_Oras" class="w60lst center-text">
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
        
        <div id="section-2" class="section invisible">
            <h1 class="space-2020">Universitatea:</h1>
            <br />
            <select name="hCombo_Universitate" id="hCombo_Universitate" class="center-text"></select>
        </div>
        
        <div id="section-3" class="section invisible">
            <h1 class="space-2020">Domeniu de interes:</h1>
            <br />
            <select name="hCombo_Domeniu" id="hCombo_Domeniu" class="center-text">
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
        </div>
        </form>
        
        <div id="section-4" class="section invisible">
            <table id="tbl-rezultate-locmunca" class="fullwidth center-page"></table>
        </div>
        
        <br /><br />
        <input type="button" name="hButtonNext" id="hButtonNext" value="URMĂTORUL PAS &rArr;" class="standard-button rounded space-2020" />
    </div>
        
    <script type="text/javascript">
        
        $('#hButtonNext').click(function(){
            var nCurrId = parseInt($('div.section.selected').attr('id').substring(8));
            
            $('#section-' + nCurrId).removeClass('selected');
            $('#section-' + nCurrId).addClass('invisible');
            
            $('#section-' + (nCurrId + 1)).addClass('selected');
            $('#section-' + (nCurrId + 1)).removeClass('invisible');
            
            switch (nCurrId)
            {
                case 1:{
                    var jqXHR=$.post("<?php echo qurl_s('api/web-getlistauniversitati'); ?>",
                        {
                            oras: $('#hCombo_Oras').val()
                        },
                        function(data){
                            if (data['result']=='success'){
                                for (var i = 0; i < data['universitati'].length; i++){
                                    $('#hCombo_Universitate').append('<option>' + data['universitati'][i]['nume'] + '</option>');
                                }
                            }
                        },
                    "json");
                    
                    jqXHR.fail(function(a,b,c){
                        alert("AJAX err: "+a+' - '+b);
                    });
                }break;
                
                case 2:{
                    $('#hButtonNext').val('FINALIZARE');
                }break;
                
                case 3:{
                    $('#hButtonNext').remove();
                    
                    var jqXHR=$.post("<?php echo qurl_s('api/web-cautauniversitati'); ?>",
                        $('#frm_cauta').serialize(),
                        function(data){
                            if (data['result']=='success'){
                                $('#tbl-rezultate-locmunca').html(data['html']);
                                
                                $('a.solicitinfo').click(function(kEvent){
                                    kEvent.preventDefault();
                                    
                                    var nIdxUniv = $(this).data('idxuniv');
                                    var nIdxLoc = $(this).data('idxloc');
                                    var kElem = $(this);
                                    
                                    var jqXHR2=$.post("<?php echo qurl_s('api/web-cautauniversitati-solicitainfo'); ?>",
                                        {
                                            idxauthuniversitate: nIdxUniv,
                                            idxlocuniversitate: nIdxLoc
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
                                    
                                    jqXHR2.fail(function(a,b,c){
                                        alert("AJAX err: "+a+' - '+b);
                                    });
                                });
                            }
                        },
                    "json");
                    
                    jqXHR.fail(function(a,b,c){
                        alert("AJAX err: "+a+' - '+b);
                    });
                }break;
            }
        });
        
    </script>
    
