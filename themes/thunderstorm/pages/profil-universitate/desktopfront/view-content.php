<div id="content" class="container">
    <div class="row my-5">
        <div class="offset-lg-1 col-lg-10">
            <h1 class="titlu">Profil</h1>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card shadow-lg">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center text-center">
                                <img src="<?= qurl_file('media/uploads/'. $this->DATA['details']['img']) ?>" alt="Admin" class="rounded-circle" width="150">
                                <div class="mt-3">
                                    <h3><strong><?= $this->DATA['details']['nume'] ?></strong></h3>
                                    <p class="text-secondary mb-4"><?= $this->AUTH->GetUsername() ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card shadow mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <h6 class="mb-0">Reprezentant</h6>
                                </div>
                                <div class="col-sm-8 text-secondary">
                                    <?= $this->DATA['details']['reprezentant'] ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <h6 class="mb-0">Orașe</h6>
                                </div>
                                <div class="col-sm-8 text-secondary">
                                    <?= $this->DATA['details']['orase'] ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <h6 class="mb-0">Grad de accesibilizare</h6>
                                </div>
                                <div class="col-sm-8 text-secondary">
                                    <?= $this->DATA['details']['gradaccess'] ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-4">
                                    <h6 class="mb-0">Grad de echipare</h6>
                                </div>
                                <div class="col-sm-8 text-secondary">
                                    <?= $this->DATA['details']['gradechipare'] ?>
                                </div>
                            </div>
                            <hr>
                             <div class="row">
                                <div class="col-sm-4">
                                    <h6 class="mb-0">Persoană dedicată Blindhub</h6>
                                </div>
                                <div class="col-sm-8 text-secondary">
                                    <?= $this->DATA['details']['persdedic'] ? 'Da' : 'Nu' ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                  <a class="btn btn-primary btn-lg rounded-pill" href="<?= qurl_l('editeaza-profil-universitate') ?>"><strong>Editare cont</strong></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!--<div class="center-text">
        <h1 class="bold space-4040">EDITARE PROFIL UNIVERSITATE</h1>

        <form id="frm_creeazacont" method="post" enctype="multipart/form-data">

            <div id="section-1" class="section selected">
                <h1 class="space-2020">Nume universitate</h1>
                <input type="text" name="hEditNume" id="hEditNume" value="<?php echo $this->DATA['details']['nume']; ?>" class="center-text rounded space-0040 w60lst" placeholder="nume universitate" />
            </div>

            <div id="section-2" class="section invisible">
                <h1 class="space-2020">Oraș</h1>
                <select name="hComboOras" id="hComboOras" class="w60lst rounded center-text">
                    <option value="albaiulia" <?php if ($this->DATA['details']['oras'] == 'albaiulia') echo 'selected="selected"'; ?>>Alba Iulia</option>
                    <option value="alexandria" <?php if ($this->DATA['details']['oras'] == 'alexandria') echo 'selected="selected"'; ?>>Alexandria</option>
                    <option value="arad" <?php if ($this->DATA['details']['oras'] == 'arad') echo 'selected="selected"'; ?>>Arad</option>
                    <option value="baiamare" <?php if ($this->DATA['details']['oras'] == 'baiamare') echo 'selected="selected"'; ?>>Baia Mare</option>
                    <option value="bistritanasaud" <?php if ($this->DATA['details']['oras'] == 'bistritanasaud') echo 'selected="selected"'; ?>>Bistrița Năsăud</option>
                    <option value="braila" <?php if ($this->DATA['details']['oras'] == 'braila') echo 'selected="selected"'; ?>>Brăila</option>
                    <option value="bucuresti" <?php if ($this->DATA['details']['oras'] == 'bucuresti') echo 'selected="selected"'; ?>>București</option>
                    <option value="botosani" <?php if ($this->DATA['details']['oras'] == 'botosani') echo 'selected="selected"'; ?>>Botoșani</option>
                    <option value="brasov" <?php if ($this->DATA['details']['oras'] == 'brasov') echo 'selected="selected"'; ?>>Brașov</option>
                    <option value="bacau" <?php if ($this->DATA['details']['oras'] == 'bacau') echo 'selected="selected"'; ?>>Bacău</option>
                    <option value="buzau" <?php if ($this->DATA['details']['oras'] == 'buzau') echo 'selected="selected"'; ?>>Buzău</option>
                    <option value="calarasi" <?php if ($this->DATA['details']['oras'] == 'calarasi') echo 'selected="selected"'; ?>>Călărași</option>
                    <option value="clujnapoca" <?php if ($this->DATA['details']['oras'] == 'clujnapoca') echo 'selected="selected"'; ?>>Cluj-Napoca</option>
                    <option value="constanta" <?php if ($this->DATA['details']['oras'] == 'constanta') echo 'selected="selected"'; ?>>Constanța</option>
                    <option value="craiova" <?php if ($this->DATA['details']['oras'] == 'craiova') echo 'selected="selected"'; ?>>Craiova</option>
                    <option value="deva" <?php if ($this->DATA['details']['oras'] == 'deva') echo 'selected="selected"'; ?>>Deva</option>
                    <option value="iasi" <?php if ($this->DATA['details']['oras'] == 'iasi') echo 'selected="selected"'; ?>>Iași</option>
                    <option value="focsani" <?php if ($this->DATA['details']['oras'] == 'focsani') echo 'selected="selected"'; ?>>Focșani</option>
                    <option value="galati" <?php if ($this->DATA['details']['oras'] == 'galati') echo 'selected="selected"'; ?>>Galați</option>
                    <option value="giurgiu" <?php if ($this->DATA['details']['oras'] == 'giurgiu') echo 'selected="selected"'; ?>>Giurgiu</option>
                    <option value="oradea" <?php if ($this->DATA['details']['oras'] == 'oradea') echo 'selected="selected"'; ?>>Oradea</option>
                    <option value="ploiesti" <?php if ($this->DATA['details']['oras'] == 'ploiesti') echo 'selected="selected"'; ?>>Ploiești</option>
                    <option value="pitesti" <?php if ($this->DATA['details']['oras'] == 'pitesti') echo 'selected="selected"'; ?>>Pitești</option>
                    <option value="piatraneamt" <?php if ($this->DATA['details']['oras'] == 'piatraneamt') echo 'selected="selected"'; ?>>Piatra Neamț</option>
                    <option value="resita" <?php if ($this->DATA['details']['oras'] == 'resita') echo 'selected="selected"'; ?>>Reșița</option>
                    <option value="ramnicuvalcea" <?php if ($this->DATA['details']['oras'] == 'ramnicuvalcea') echo 'selected="selected"'; ?>>Râmnicu Vâlcea</option>
                    <option value="timisoara" <?php if ($this->DATA['details']['oras'] == 'timisoara') echo 'selected="selected"'; ?>>Timișoara</option>
                    <option value="targumures" <?php if ($this->DATA['details']['oras'] == 'targumures') echo 'selected="selected"'; ?>>Târgu Mureș</option>
                    <option value="targujiu" <?php if ($this->DATA['details']['oras'] == 'targujiu') echo 'selected="selected"'; ?>>Târgu Jiu</option>
                    <option value="slatina" <?php if ($this->DATA['details']['oras'] == 'slatina') echo 'selected="selected"'; ?>>Slatina</option>
                    <option value="sibiu" <?php if ($this->DATA['details']['oras'] == 'sibiu') echo 'selected="selected"'; ?>>Sibiu</option>
                    <option value="satumare" <?php if ($this->DATA['details']['oras'] == 'satumare') echo 'selected="selected"'; ?>>Satu Mare</option>
                    <option value="suceava" <?php if ($this->DATA['details']['oras'] == 'suceava') echo 'selected="selected"'; ?>>Suceava</option>
                    <option value="vaslui" <?php if ($this->DATA['details']['oras'] == 'vaslui') echo 'selected="selected"'; ?>>Vaslui</option>
                </select>
            </div>

            <div id="section-3" class="section invisible">
                <h1 class="space-2020">Nume și prenume reprezentant</h1>
                <input type="text" name="hEditNumeReprezentant" id="hEditNumeReprezentant" value="<?php echo $this->DATA['details']['reprezentant']; ?>" class="center-text rounded space-0040 w40lst" placeholder="nume reprezentant" />
            </div>

            <div id="section-4" class="section invisible">
                <h1 class="space-2020">Creează o parolă</h1>
                <span>Parola trebuie să conțină cel puțin 8 caractere, o literă mică, o literă mare, o cifră și un simbol.</span>
                <br /><br />
                <input type="password" name="hEditParola" id="hEditParola" value="" class="center-text rounded space-0040" placeholder="parola" />
            </div>

            <div id="section-5" class="section invisible">
                <h1 class="space-2020">Menționati gradul de accesibilizare a instituției dumneavoastră</h1>

                <div style="width: 30%; text-align: left;" class="center-page">
                    <label>
                        <input type="radio" name="hRadioGradAcces" id="hRadioGradAcces_1" value="partiala"<?php
                            if ($this->DATA['details']['gradacces'] == 'partiala') echo ' checked="checked"'; ?> />
                        parțială
                    </label><br /><br />

                    <label>
                        <input type="radio" name="hRadioGradAcces" id="hRadioGradAcces_2" value="satisfacatoare"<?php
                            if ($this->DATA['details']['gradacces'] == 'satisfacatoare') echo ' checked="checked"'; ?> />
                        satisfăcătoare
                    </label><br /><br />

                    <label>
                        <input type="radio" name="hRadioGradAcces" id="hRadioGradAcces_3" value="totala"<?php
                            if ($this->DATA['details']['gradacces'] == 'totala') echo ' checked="checked"'; ?> />
                        totală
                    </label><br /><br />
                </div>
            </div>

            <div id="section-6" class="section invisible">
                <h1 class="space-2020">Menționati gradul de echipare cu tehnologie asistivă</h1>

                <div style="width: 30%; text-align: left;" class="center-page">
                    <label>
                        <input type="radio" name="hRadioGradEchipare" id="hRadioGradEchipare_1" value="partiala"<?php
                            if ($this->DATA['details']['gradechipare'] == 'partiala') echo ' checked="checked"'; ?> />
                        parțială
                    </label><br /><br />

                    <label>
                        <input type="radio" name="hRadioGradEchipare" id="hRadioGradEchipare_2" value="satisfacatoare"<?php
                            if ($this->DATA['details']['gradechipare'] == 'satisfacatoare') echo ' checked="checked"'; ?> />
                        satisfăcătoare
                    </label><br /><br />

                    <label>
                        <input type="radio" name="hRadioGradEchipare" id="hRadioGradEchipare_3" value="completa"<?php
                            if ($this->DATA['details']['gradechipare'] == 'completa') echo ' checked="checked"'; ?> />
                        completă
                    </label><br /><br />
                </div>
            </div>

            <div id="section-7" class="section invisible">
                <h1 class="space-2020">Menționati dacă universitatea are studenți cu dizabilități înmatriculați</h1>

                <div style="width: 20%; text-align: left;" class="center-page">
                    <label>
                        <input type="radio" name="hRadioAreStudenti" id="hRadioAreStudenti_1" value="da"<?php
                            if ($this->DATA['details']['studdiz'] == 'da') echo ' checked="checked"'; ?> />
                        da
                    </label><br /><br />

                    <label>
                        <input type="radio" name="hRadioAreStudenti" id="hRadioAreStudenti_2" value="nu"<?php
                            if ($this->DATA['details']['studdiz'] == 'nu') echo ' checked="checked"'; ?> />
                        nu
                    </label><br /><br />
                </div>
            </div>

            <div id="section-8" class="section invisible">
                <h1 class="space-2020">Menționati dacă universitatea are un centru de sprijin destinat studenților cu dizabilități</h1>

                <div style="width: 20%; text-align: left;" class="center-page">
                    <label>
                        <input type="radio" name="hRadioAreCentruSprijin" id="hRadioAreCentruSprijin_1" value="da"<?php
                            if ($this->DATA['details']['studcentru'] == 'da') echo ' checked="checked"'; ?> />
                        da
                    </label><br /><br />

                    <label>
                        <input type="radio" name="hRadioAreCentruSprijin" id="hRadioAreCentruSprijin_2" value="nu"<?php
                            if ($this->DATA['details']['studcentru'] == 'nu') echo ' checked="checked"'; ?> />
                        nu
                    </label><br /><br />
                </div>
            </div>

            <div id="section-9" class="section invisible">
                <h1 class="space-2020">Menționati dacă universitatea deține camere în căminele studențești adaptate la toate tipurile de dizabilități</h1>

                <div style="width: 20%; text-align: left;" class="center-page">
                    <label>
                        <input type="radio" name="hRadioAreCamere" id="hRadioAreCamere_1" value="da"<?php
                            if ($this->DATA['details']['camerecamine'] == 'da') echo ' checked="checked"'; ?> />
                        da
                    </label><br /><br />

                    <label>
                        <input type="radio" name="hRadioAreCamere" id="hRadioAreCamere_2" value="nu"<?php
                            if ($this->DATA['details']['camerecamine'] == 'nu') echo ' checked="checked"'; ?> />
                        nu
                    </label><br /><br />
                </div>
            </div>

            <div id="section-10" class="section invisible">
                <h1 class="space-2020">Menționați dacă universitatea are o persoană sau un birou dedicat aplicației BlindHUB</h1>

                <div style="width: 20%; text-align: left;" class="center-page">
                    <label>
                        <input type="radio" name="hRadioArePersDedicat" id="hRadioArePersDedicat_1" value="da"<?php
                            if ($this->DATA['details']['persdedic'] == 'da') echo ' checked="checked"'; ?> />
                        da
                    </label><br /><br />

                    <label>
                        <input type="radio" name="hRadioArePersDedicat" id="hRadioArePersDedicat_2" value="nu"<?php
                            if ($this->DATA['details']['persdedic'] == 'nu') echo ' checked="checked"'; ?> />
                        nu
                    </label><br /><br />
                </div>
            </div>

            <div id="section-11" class="section invisible">
                <h1 class="space-2020">Cazare - accesibilizări</h1>

                <div style="width: 20%; text-align: left;" class="center-page">
                    <label>
                        <input type="radio" name="hRadioCazareAcces" id="hRadioCazareAcces_1" value="mediu"<?php
                            if ($this->DATA['details']['cazare'] == 'mediu') echo ' checked="checked"'; ?> />
                        mediu
                    </label><br /><br />

                    <label>
                        <input type="radio" name="hRadioCazareAcces" id="hRadioCazareAcces_2" value="usor"<?php
                            if ($this->DATA['details']['cazare'] == 'usor') echo ' checked="checked"'; ?> />
                        usor
                    </label><br /><br />

                    <label>
                        <input type="radio" name="hRadioCazareAcces" id="hRadioCazareAcces_3" value="total"<?php
                            if ($this->DATA['details']['cazare'] == 'total') echo ' checked="checked"'; ?> />
                        total
                    </label><br /><br />
                </div>
            </div>

            <div id="section-12" class="section invisible">
                <h1 class="space-2020">Costuri adaptate</h1>

                <div style="width: 20%; text-align: left;" class="center-page">
                    <label>
                        <input type="radio" name="hRadioCosturi" id="hRadioCosturi_1" value="mediu"<?php
                            if ($this->DATA['details']['costuri'] == 'mediu') echo ' checked="checked"'; ?> />
                        mediu
                    </label><br /><br />

                    <label>
                        <input type="radio" name="hRadioCosturi" id="hRadioCosturi_2" value="usor"<?php
                            if ($this->DATA['details']['costuri'] == 'usor') echo ' checked="checked"'; ?> />
                        usor
                    </label><br /><br />

                    <label>
                        <input type="radio" name="hRadioCosturi" id="hRadioCosturi_3" value="total"<?php
                            if ($this->DATA['details']['costuri'] == 'total') echo ' checked="checked"'; ?> />
                        total
                    </label><br /><br />
                </div>
            </div>

            <input type="hidden" name="userkey" value="mod" />
        </form>

        <br /><br />
        <div id="hStaticErrorMsg"></div>
        <input type="button" name="hButtonNext" id="hButtonNext" value="URMĂTORUL PAS &rArr;" class="standard-button rounded space-2020" />
    </div>
-->

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
                    if (!ValidatePassword($('#hEditParola').val())){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să puneți o parolă corespunzătoare !');
                    }
                }break;

                case 5:{
                    if (!$('#hRadioGradAcces_1').prop('checked') && !$('#hRadioGradAcces_2').prop('checked') &&
                        !$('#hRadioGradAcces_3').prop('checked'))
                    {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;

                case 6:{
                    if (!$('#hRadioGradEchipare_1').prop('checked') && !$('#hRadioGradEchipare_2').prop('checked') &&
                        !$('#hRadioGradEchipare_3').prop('checked'))
                    {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;

                case 7:{
                    if (!$('#hRadioAreStudenti_1').prop('checked') && !$('#hRadioAreStudenti_2').prop('checked'))
                    {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;

                case 8:{
                    if (!$('#hRadioAreCentruSprijin_1').prop('checked') && !$('#hRadioAreCentruSprijin_2').prop('checked'))
                    {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;

                case 9:{
                    if (!$('#hRadioAreCamere_1').prop('checked') && !$('#hRadioAreCamere_2').prop('checked'))
                    {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;

                case 10:{
                    if (!$('#hRadioArePersDedicat_1').prop('checked') && !$('#hRadioArePersDedicat_2').prop('checked'))
                    {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;

                case 11:{
                    if (!$('#hRadioCazareAcces_1').prop('checked') && !$('#hRadioCazareAcces_2').prop('checked') &&
                        !$('#hRadioCazareAcces_3').prop('checked'))
                    {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;

                case 12:{
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

                if (nCurrId == 12){
                    $('#hStaticErrorMsg').html('Vă rugăm să așteptați !');
                    $('#hButtonNext').addClass('invisible');

                    var jqXHR=$.post("<?php echo qurl_s('api/web-createacc-universitati'); ?>",
                        $('#frm_creeazacont').serialize(),
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
                }
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
