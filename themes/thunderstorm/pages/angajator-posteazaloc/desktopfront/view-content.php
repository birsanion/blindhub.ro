
    <div class="master-container center-page">
        <div class="center-text"><h1 class="bold space-4040">LOCURI DE MUNCĂ</h1></div>
        
        <div class="w80lst center-page">
            <form id="frm_locmunca" enctype="multipart/form-data" method="post">
            <table id="tbl-posteaza-locmunca" class="fullwidth">
                <tr>
                    <td>Oraș:</td>
                    <td>
                        <select name="hComboOras" class="w80lst rounded">
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
                    <td>Competențe necesare:</td>
                    <td>
                        <input type="text" name="hEditCompetente" id="hEditCompetente" value="" class="rounded w80lst" placeholder="competențe" />
                    </td>
                </tr>
                <tr>
                    <td>Titlu:</td>
                    <td>
                        <input type="text" name="hEditTitlu" id="hEditTitlu" value="" class="rounded w80lst" placeholder="titlu loc de muncă" />
                    </td>
                </tr>
                <tr>
                    <td>Descriere:</td>
                    <td>
                        <textarea name="hEditDescriere" id="hEditDescriere" class="rounded w80lst"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>Data expirării anunțului:</td>
                    <td>
                        <input type="text" name="hEditDataExp" id="hEditDataExp" value="" class="rounded w40lst" placeholder="data expirării" />
                    </td>
                </tr>
            </table>
            </form>
            
            <div class="center-text">
                <input type="button" name="hButtonNext" id="hButtonNext" value="ADAUGĂ ANUNȚ" class="standard-button rounded space-2020" />
                <div id="hStaticErrorMsg"></div>
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
        
        $('#hEditDataExp').datepicker({
            showWeek: true,
            firstDay: 1,
            showButtonPanel: true,
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true
        });
        
        $('#hButtonNext').click(function(){
            if ($('#hEditTitlu').val().length > 0 && $('#hEditDataExp').val().length > 0){
                var jqXHR=$.post("<?php echo qurl_s('api/web-addlocmunca'); ?>",
                    $('#frm_locmunca').serialize(),
                    function(data){
                        if (data['result']=='success'){
                            window.location = '<?php echo qurl_l('home-angajator'); ?>';
                        }else{
                            $('#hStaticErrorMsg').html(data['result']);
                        }
                    },
                "json");
                
                jqXHR.fail(function(a,b,c){
                    alert("AJAX err: "+a+' - '+b);
                });
            }else{
                $('#hStaticErrorMsg').html('Trebuie să completați titlul și data expirării !');
            }
        });
        
    </script>
