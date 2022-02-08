
    <div class="master-container center-page">
        <div class="center-text"><h1 class="bold space-4040">OFERTE</h1></div>
        
        <div class="w80lst center-page">
            <form id="frm_oferta" enctype="multipart/form-data" method="post">
            <table id="tbl-posteaza-locmunca" class="fullwidth">
                <tr>
                    <td>Facultate:</td>
                    <td>
                        <input type="text" name="hEditFacultate" id="hEditFacultate" value="" class="rounded w80lst" placeholder="facultate" />
                    </td>
                </tr>
                <tr>
                    <td>Domeniu de activitate:</td>
                    <td>
                        <select name="hComboDomeniu" id="hComboDomeniu" class="rounded w80lst">
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
                    </td>
                </tr>
                <tr>
                    <td>Număr locuri rezervate:</td>
                    <td>
                        <input type="text" name="hEditNrLocuri" id="hEditNrLocuri" value="" class="rounded w20lst" placeholder="locuri" />
                    </td>
                </tr>
            </table>
            </form>
            
            <div class="center-text">
                <div id="hStaticErrorMsg"></div>
                <input type="button" name="hButtonNext" id="hButtonNext" value="ADAUGĂ ANUNȚ" class="standard-button rounded space-2020" />
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
    
        $('#hButtonNext').click(function(){
            if ($('#hEditFacultate').val().length > 0 && $('#hEditNrLocuri').val().length > 0){
                var jqXHR=$.post("<?php echo qurl_s('api/web-universitate-publicaoferta'); ?>",
                    $('#frm_oferta').serialize(),
                    function(data){
                        if (data['result']=='success'){
                            window.location = '<?php echo qurl_l('home-universitate'); ?>';
                        }else{
                            $('#hStaticErrorMsg').html(data['result']);
                        }
                    },
                "json");
                
                jqXHR.fail(function(a,b,c){
                    alert("AJAX err: "+a+' - '+b);
                });
            }else{
                $('#hStaticErrorMsg').html('Trebuie să completați facultatea și numărul de locuri !');
            }
        });
        
    </script>


