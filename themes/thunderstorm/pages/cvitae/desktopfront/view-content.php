
    <div class="center-text">
        <h1 class="bold space-4040">CURRICULUM VITAE</h1>
        
        <form id="frm_cv" method="post" enctype="multipart/form-data">
        <div id="section-1" class="section selected">
            <h1 class="space-2020">Ce sectoare de activitate vă interesează ?</h1>
            
            <br />
            <table class="w80lst center-page">
                <tr>
                    <td style="text-align: left;">
                        <label><input type="checkbox" name="hCheck_IT" value="it" /> IT </label>
                    </td>
                    <td style="text-align: left;">
                        <label><input type="checkbox" name="hCheck_Medical" value="medical" /> Medical </label>
                    </td>
                </tr>
                
                <tr>
                    <td style="text-align: left;">
                        <label><input type="checkbox" name="hCheck_CallCenter" value="callcenter" /> Call center </label>
                    </td>
                    <td style="text-align: left;">
                        <label><input type="checkbox" name="hCheck_ResurseUmane" value="resurseumane" /> Resurse umane </label>
                    </td>
                </tr>
                
                <tr>
                    <td style="text-align: left;">
                        <label><input type="checkbox" name="hCheck_AsistentaSociala" value="asistentasociala" /> Asistență socială </label>
                    </td>
                    <td style="text-align: left;">
                        <label><input type="checkbox" name="hCheck_CustomerService" value="serviciuclienti" /> Customer service (serviciu clienți) </label>
                    </td>
                </tr>
                
                <tr>
                    <td style="text-align: left;">
                        <label><input type="checkbox" name="hCheck_Jurnalism" value="jurnalism" /> Jurnalism &amp; relații publice </label>
                    </td>
                    <td style="text-align: left;">
                        <label><input type="checkbox" name="hCheck_Radio" value="radio" /> Radio </label>
                    </td>
                </tr>
                
                <tr>
                    <td style="text-align: left;">
                        <label><input type="checkbox" name="hCheck_Psihologie" value="psihologie" /> Psihologie / consiliere / coaching </label>
                    </td>
                    <td style="text-align: left;">
                        <label><input type="checkbox" name="hCheck_Educatie" value="educatie" /> Educație și training </label>
                    </td>
                </tr>
                
                <tr>
                    <td style="text-align: left;">
                        <label><input type="checkbox" name="hCheck_Artistica" value="artistica" /> Industria creativă și artistică </label>
                    </td>
                    <td style="text-align: left;">
                        <label><input type="checkbox" name="hCheck_AdminPub" value="administratie" /> Administrație publică și instituții </label>
                    </td>
                </tr>
                
                <tr>
                    <td style="text-align: left;">
                        <label><input type="checkbox" name="hCheck_DeskOffice" value="desk" /> Desk office </label>
                    </td>
                    <td style="text-align: left;">
                        <label><input type="checkbox" name="hCheck_Wellness" value="wellness" /> Wellness &amp; Spa </label>
                    </td>
                </tr>
                
                <tr>
                    <td style="text-align: left;">
                        <label><input type="checkbox" name="hCheck_Traducator" value="traducator" /> Traducător / translator </label>
                    </td>
                    <td style="text-align: left;">
                        <label><input type="checkbox" name="hCheck_Diverse" value="diverse" /> Diverse </label>
                    </td>
                </tr>
            </table>
        </div>
        
        <div id="section-2" class="section invisible">
            <h1 class="space-2020">În ce oraș vrei să muncești ?</h1>
            
            <select name="hCombo_Oras" class="w60lst center-text">
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
        </form>
        
        <div id="section-3" class="section invisible">
            <h1 class="space-2020" id="hStaticUploadTitle">Upload CV Video</h1>
            
            <form>
            <span id="hFileUploadContainer" class="btn btn-success fileinput-button" style="background: #2E295C;">
                <i class="icon-plus icon-white"></i>
                <span>ÎNCARCĂ VIDEO</span>
                <input id="hFileUpload" type="file" name="files[]">
            </span>
            </form>
        </div>
        
        <br /><br />
        <div id="hStaticErrorMsg"></div>
        <input type="button" name="hButtonNext" id="hButtonNext" value="URMĂTORUL PAS &rArr;" class="standard-button rounded space-2020" />
    </div>
    
        <script type="text/javascript">
            
            function Next()
            {
                var nCurrId = parseInt($('div.section.selected').attr('id').substring(8));
                
                var bMoveNext = true;
        
                switch (nCurrId)
                {
                    case 1:{
                        var bChecked = false;
                        
                        $('#section-1').find('input[type="checkbox"]').each(function(nIndex, kElement){
                            if ($(this).prop('checked')) bChecked = true;
                        });
                        
                        if (!bChecked){
                            bMoveNext = false;
                            $('#hStaticErrorMsg').html('Trebuie să bifați cel puțin o opțiune !');
                        }
                    }break;
                }
                
                if (bMoveNext){
                    $('#section-' + nCurrId).removeClass('selected');
                    $('#section-' + nCurrId).addClass('invisible');
                    
                    $('#section-' + (nCurrId + 1)).addClass('selected');
                    $('#section-' + (nCurrId + 1)).removeClass('invisible');
                    
                    $('#hStaticErrorMsg').html('');
                    
                    if (nCurrId == 2){
                        var jqXHR=$.post("<?php echo qurl_s('api/web-setcv'); ?>",
                            $('#frm_cv').serialize(),
                            function(data){
                                if (data['result']=='success'){
                                    $('#hStaticErrorMsg').html('');
                                    $('#hButtonNext').removeClass('invisible');
                                    $('#hButtonNext').val('FINALIZARE');
                                }else{
                                    $('div.section').addClass('invisible');
                                    $('div.section').removeClass('selected');
                                    
                                    $('#section-1').removeClass('invisible');
                                    $('#section-1').addClass('selected');
                                    
                                    $('#hButtonNext').removeClass('invisible');
                                    $('#hStaticErrorMsg').html(data['result']);
                                }
                            },
                        "json");
                        
                        jqXHR.fail(function(a,b,c){
                            alert("AJAX err: "+a+' - '+b);
                        });
                    }
                    
                    if (nCurrId == 3)
                        window.location = '<?php echo qurl_l('home-nevaz'); ?>';
                }
            }
            
            $('#hButtonNext').click(Next);
            $(document).keyup(function(kEvent){
                if (kEvent.keyCode === 13){
                    kEvent.preventDefault();
                    Next();
                }
            });
            
            function Upload(obj)
            {
                'use strict';
                
                $('#hStaticUploadTitle').html('Vă rugăm să așteptați !');
                
                $('#'+obj['target']['id']).fileupload({
                    url: '<?php echo qurl_s('cvitae/upload'); ?>',
                    dataType: 'json',
                    done: function (e, data) {
                        // if error show it
                        if (data['result'] == 'success'){
                            console.log(data);
                            $('#hStaticUploadTitle').html('SUCCES');
                        }else{
                            $('#hStaticUploadTitle').html(data['result']);
                        }
                    }
                });
            }
            
            $('#hFileUploadContainer').click(Upload);
            
        </script>