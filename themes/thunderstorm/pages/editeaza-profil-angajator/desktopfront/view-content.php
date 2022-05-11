     <div id="content" class="container mt-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Editare cont</h1>
                <?php if ($this->DATA['errormsg']): ?>
                <div class="alert alert-danger mb-5" role="alert">
                    <?= $this->DATA['errormsg'] ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="offset-md-3 col-md-6">
                <form id="frm_editangajator">
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Nume companie</strong></label>
                        <input type="text" name="companie" class="form-control" placeholder="Nume companie" required value="<?= $this->DATA['details']['companie'] ?>">
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Adresa punctului de lucru (oraș și adresa completă)</strong></label>
                        <input type="text" name="adresa" class="form-control" placeholder="Adresa" required value="<?= $this->DATA['details']['adresa'] ?>">
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Orașe în care faceți recrutare:</strong></label>
                        <select class="selectpicker form-control" data-style="btn-white" multiple data-live-search="true" data-size="12" required name="idx_orase[]">
                            <option value="" data-hidden="true"></option>
                            <?php foreach ($this->DATA['orase'] as $oras): ?>
                            <option value="<?= $oras['idx'] ?>" <?php if (in_array($oras['idx'], $this->DATA['details']['idx_orase'])) echo 'selected'; ?>><?= $oras['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Domenii de activitate în care recrutați:</strong></label>
                        <select class="selectpicker form-control" data-style="btn-white" multiple required name="idx_domenii_cv[]">
                            <option value="" data-hidden="true">Alege domeniu activitate</option>
                            <?php foreach ($this->DATA['domenii_cv'] as $domeniu): ?>
                            <option value="<?= $domeniu['idx'] ?>"  <?php if (in_array($domeniu['idx'], $this->DATA['details']['idx_domenii_cv'])) echo 'selected'; ?>><?= $domeniu['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Certificatul de înregistrare fiscală:</strong></label>
                        <input type="text" name="cui" class="form-control" placeholder="Nume companie" required value="<?= $this->DATA['details']['cui'] ?>">
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Precizați daca sunteți unitate protejată:</strong></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="firmaprotejata" value="1" <?php if ($this->DATA['details']['firmaprotejata']) echo 'checked'; ?>>
                            <label class="form-check-label">
                                Da
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="firmaprotejata" value="0" <?php if (!$this->DATA['details']['firmaprotejata']) echo 'checked'; ?>>
                            <label class="form-check-label">
                                Nu
                            </label>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Sunteți o companie cu peste 50 de angajați:</strong></label>
                        <?php if (!empty($this->DATA['optiuni']['dimensiuneslujba'])): ?>
                        <?php foreach ($this->DATA['optiuni']['dimensiuneslujba'] as $idx => $optiune): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="idx_optiune_dimensiunefirma" value="<?= $idx ?>" <?php if ($this->DATA['details']['idx_optiune_dimensiunefirma'] == $idx) echo 'checked'; ?>>
                            <label class="form-check-label">
                                <?= $optiune ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                        <?php endif;?>
                    </div>
                    <div class="form-group my-5">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">
                            Salvează
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!--<div class="center-text">
        <h1 class="bold space-4040">EDITARE PROFIL ANGAJATOR</h1>

        <form id="frm_creeazacont" method="post" enctype="multipart/form-data">
            <div id="section-2" class="section selected">
                <h1 class="space-2020">Nume companie:</h1>
                <br />
                <input type="text" name="hEditNumeFirma" id="hEditNumeFirma" value="<?php
                    echo htmlspecialchars($this->DATA['details']['companie']); ?>" class="center-text rounded space-0040 w40lst" placeholder="nume firmă" />
            </div>

            <div id="section-3" class="section invisible">
                <h1 class="space-2020">Adresa punctului de lucru<br />(oraș și adresa completă)</h1>
                <br />
                <input type="text" name="hEditAdresa" id="hEditAdresa" value="<?php
                    echo htmlspecialchars($this->DATA['details']['adresa']); ?>" class="center-text rounded space-0040 w80lst" placeholder="adresa" />
            </div>

            <div id="section-4" class="section invisible">
                <h1 class="space-2020">Certificatul de inregistrare fiscala (C.U.I.):</h1>
                <br />
                <input type="text" name="hEditCUI" id="hEditCUI" value="<?php
                    echo htmlspecialchars($this->DATA['details']['cui']); ?>" class="center-text rounded space-0040" placeholder="cui" />
            </div>

            <div id="section-5" class="section invisible">
                <h1 class="space-2020">Creează o parolă</h1>
                <br />
                <span>Parola trebuie să conțină cel puțin 8 caractere, o literă mică, o literă mare, o cifră și un simbol.</span>
                <br /><br />
                <input type="password" name="hEditParola" id="hEditParola" value="" class="center-text rounded space-0040 w40lst" placeholder="parolă" />
            </div>

            <div id="section-6" class="section invisible">
                <h1 class="space-2020">Vă rugăm să precizați dacă sunteți firmă protejată:</h1>
                <br />
                <div style="width: 20%; text-align: left;" class="center-page">
                    <label>
                        <input type="radio" name="hRadioFirmaProtej" id="hRadioFirmaProtej_DA" value="da"<?php
                            if ($this->DATA['details']['firmaprotejata'] == 'da') echo ' checked="checked"'; ?> />
                        DA
                    </label><br /><br />

                    <label>
                        <input type="radio" name="hRadioFirmaProtej" id="hRadioFirmaProtej_NU" value="nu"<?php
                            if ($this->DATA['details']['firmaprotejata'] == 'nu') echo ' checked="checked"'; ?> />
                        NU
                    </label><br /><br />
                </div>
            </div>

            <div id="section-7" class="section invisible">
                <h1 class="space-2020">Sunteți companie ...</h1>
                <br />
                <div style="width: 20%; text-align: left;" class="center-page">
                    <label>
                        <input type="radio" name="hRadioFirmaAngajati" id="hRadioFirmaAngajati_sub" value="sub50"<?php
                            if ($this->DATA['details']['dimensiunefirma'] == 'sub50') echo ' checked="checked"'; ?> />
                        sub 50 de angajați
                    </label><br /><br />

                    <label>
                        <input type="radio" name="hRadioFirmaAngajati" id="hRadioFirmaAngajati_peste" value="peste50"<?php
                            if ($this->DATA['details']['dimensiunefirma'] == 'peste50') echo ' checked="checked"'; ?> />
                        peste 50 de angajați
                    </label><br /><br />
                </div>
            </div>

            <div id="section-8" class="section invisible">
                <h1 class="space-2020">Perioada contractuală:</h1>
                <br />
                <div style="width: 20%; text-align: left;" class="center-page">
                    <label>
                        <input type="radio" name="hRadioFirmaPerContr" id="hRadioFirmaPerContr_part" value="parttime"<?php
                            if ($this->DATA['details']['tipslujba'] == 'parttime') echo ' checked="checked"'; ?> />
                        part-time
                    </label><br /><br />

                    <label>
                        <input type="radio" name="hRadioFirmaPerContr" id="hRadioFirmaPerContr_full" value="fulltime"<?php
                            if ($this->DATA['details']['tipslujba'] == 'fulltime') echo ' checked="checked"'; ?> />
                        full-time
                    </label><br /><br />
                </div>
            </div>

            <div id="section-9" class="section invisible">
                <h1 class="space-2020">Domenii de activitate în care faceți recrutare:</h1>
                <br />
                <table class="w80lst center-page">
                    <tr>
                        <td style="text-align: left;">
                            <label><input type="checkbox" name="hCheck_Domenii_IT" value="it"<?php
                            if (strpos($this->DATA['details']['domenii'], 'it') !== false) echo ' checked="checked"'; ?> /> IT </label>
                        </td>
                        <td style="text-align: left;">
                            <label><input type="checkbox" name="hCheck_Domenii_Medical" value="medical"<?php
                            if (strpos($this->DATA['details']['domenii'], 'medical') !== false) echo ' checked="checked"'; ?> /> Medical </label>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: left;">
                            <label><input type="checkbox" name="hCheck_Domenii_CallCenter" value="callcenter"<?php
                            if (strpos($this->DATA['details']['domenii'], 'callcenter') !== false) echo ' checked="checked"'; ?> /> Call center </label>
                        </td>
                        <td style="text-align: left;">
                            <label><input type="checkbox" name="hCheck_Domenii_ResurseUmane" value="resurseumane"<?php
                            if (strpos($this->DATA['details']['domenii'], 'resurseumane') !== false) echo ' checked="checked"'; ?> /> Resurse umane </label>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: left;">
                            <label><input type="checkbox" name="hCheck_Domenii_AsistentaSociala" value="asistentasociala"<?php
                            if (strpos($this->DATA['details']['domenii'], 'asistentasociala') !== false) echo ' checked="checked"'; ?> /> Asistență socială </label>
                        </td>
                        <td style="text-align: left;">
                            <label><input type="checkbox" name="hCheck_Domenii_CustomerService" value="customerservice"<?php
                            if (strpos($this->DATA['details']['domenii'], 'customerservice') !== false) echo ' checked="checked"'; ?> /> Customer service (serviciu clienți) </label>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: left;">
                            <label><input type="checkbox" name="hCheck_Domenii_Jurnalism" value="jurnalism"<?php
                            if (strpos($this->DATA['details']['domenii'], 'jurnalism') !== false) echo ' checked="checked"'; ?> /> Jurnalism &amp; relații publice </label>
                        </td>
                        <td style="text-align: left;">
                            <label><input type="checkbox" name="hCheck_Domenii_Radio" value="radio"<?php
                            if (strpos($this->DATA['details']['domenii'], 'radio') !== false) echo ' checked="checked"'; ?> /> Radio </label>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: left;">
                            <label><input type="checkbox" name="hCheck_Domenii_Psihologie" value="psihologie"<?php
                            if (strpos($this->DATA['details']['domenii'], 'psihologie') !== false) echo ' checked="checked"'; ?> /> Psihologie / consiliere / coaching </label>
                        </td>
                        <td style="text-align: left;">
                            <label><input type="checkbox" name="hCheck_Domenii_Educatie" value="educatie"<?php
                            if (strpos($this->DATA['details']['domenii'], 'educatie') !== false) echo ' checked="checked"'; ?> /> Educație și training </label>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: left;">
                            <label><input type="checkbox" name="hCheck_Domenii_Artistica" value="artistica"<?php
                            if (strpos($this->DATA['details']['domenii'], 'artistica') !== false) echo ' checked="checked"'; ?> /> Industria creativă și artistică </label>
                        </td>
                        <td style="text-align: left;">
                            <label><input type="checkbox" name="hCheck_Domenii_AdminPub" value="administratie"<?php
                            if (strpos($this->DATA['details']['domenii'], 'administratie') !== false) echo ' checked="checked"'; ?> /> Administrație publică și instituții </label>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: left;">
                            <label><input type="checkbox" name="hCheck_Domenii_DeskOffice" value="desk"<?php
                            if (strpos($this->DATA['details']['domenii'], 'desk') !== false) echo ' checked="checked"'; ?> /> Desk office </label>
                        </td>
                        <td style="text-align: left;">
                            <label><input type="checkbox" name="hCheck_Domenii_Wellness" value="wellness"<?php
                            if (strpos($this->DATA['details']['domenii'], 'wellness') !== false) echo ' checked="checked"'; ?> /> Wellness &amp; Spa </label>
                        </td>
                    </tr>

                    <tr>
                        <td style="text-align: left;">
                            <label><input type="checkbox" name="hCheck_Domenii_Traducator" value="traducator"<?php
                            if (strpos($this->DATA['details']['domenii'], 'traducator') !== false) echo ' checked="checked"'; ?> /> Traducător / translator </label>
                        </td>
                        <td style="text-align: left;">
                            <label><input type="checkbox" name="hCheck_Domenii_Diverse" value="diverse"<?php
                            if (strpos($this->DATA['details']['domenii'], 'diverse') !== false) echo ' checked="checked"'; ?> /> Diverse </label>
                        </td>
                    </tr>
                </table>
            </div>

            <div id="section-10" class="section invisible">
                <h1 class="space-2020">Orașe în care faceți recrutare:</h1>
                <br />
                <table class="w60lst center-page">
                    <tr>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_albaiulia" value="albaiulia"<?php
                            if (strpos($this->DATA['details']['orase'], 'albaiulia') !== false) echo ' checked="checked"'; ?> />Alba Iulia</label></td>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_alexandria" value="alexandria"<?php
                            if (strpos($this->DATA['details']['orase'], 'alexandria') !== false) echo ' checked="checked"'; ?> />Alexandria</label></td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_arad" value="arad"<?php
                            if (strpos($this->DATA['details']['orase'], 'arad') !== false) echo ' checked="checked"'; ?> />Arad</label></td>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_baiamare" value="baiamare"<?php
                            if (strpos($this->DATA['details']['orase'], 'baiamare') !== false) echo ' checked="checked"'; ?> />Baia Mare</label></td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_bistritanasaud" value="bistritanasaud"<?php
                            if (strpos($this->DATA['details']['orase'], 'bistritanasaud') !== false) echo ' checked="checked"'; ?> />Bistrița Năsăud</label></td>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_braila" value="braila"<?php
                            if (strpos($this->DATA['details']['orase'], 'braila') !== false) echo ' checked="checked"'; ?> />Brăila</label></td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_bucuresti" value="bucuresti"<?php
                            if (strpos($this->DATA['details']['orase'], 'bucuresti') !== false) echo ' checked="checked"'; ?> />București</label></td>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_botosani" value="botosani"<?php
                            if (strpos($this->DATA['details']['orase'], 'botosani') !== false) echo ' checked="checked"'; ?> />Botoșani</label></td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_brasov" value="brasov"<?php
                            if (strpos($this->DATA['details']['orase'], 'brasov') !== false) echo ' checked="checked"'; ?> />Brașov</label></td>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_bacau" value="bacau"<?php
                            if (strpos($this->DATA['details']['orase'], 'bacau') !== false) echo ' checked="checked"'; ?> />Bacău</label></td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_buzau" value="buzau"<?php
                            if (strpos($this->DATA['details']['orase'], 'buzau') !== false) echo ' checked="checked"'; ?> />Buzău</label></td>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_calarasi" value="calarasi"<?php
                            if (strpos($this->DATA['details']['orase'], 'calarasi') !== false) echo ' checked="checked"'; ?> />Călărași</label></td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_clujnapoca" value="clujnapoca"<?php
                            if (strpos($this->DATA['details']['orase'], 'clujnapoca') !== false) echo ' checked="checked"'; ?> />Cluj-Napoca</label></td>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_constanta" value="constanta"<?php
                            if (strpos($this->DATA['details']['orase'], 'constanta') !== false) echo ' checked="checked"'; ?> />Constanța</label></td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_craiova" value="craiova"<?php
                            if (strpos($this->DATA['details']['orase'], 'craiova') !== false) echo ' checked="checked"'; ?> />Craiova</label></td>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_deva" value="deva"<?php
                            if (strpos($this->DATA['details']['orase'], 'deva') !== false) echo ' checked="checked"'; ?> />Deva</label></td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_iasi" value="iasi"<?php
                            if (strpos($this->DATA['details']['orase'], 'iasi') !== false) echo ' checked="checked"'; ?> />Iași</label></td>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_focsani" value="focsani"<?php
                            if (strpos($this->DATA['details']['orase'], 'focsani') !== false) echo ' checked="checked"'; ?> />Focșani</label></td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_galati" value="galati"<?php
                            if (strpos($this->DATA['details']['orase'], 'galati') !== false) echo ' checked="checked"'; ?> />Galați</label></td>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_giurgiu" value="giurgiu"<?php
                            if (strpos($this->DATA['details']['orase'], 'giurgiu') !== false) echo ' checked="checked"'; ?> />Giurgiu</label></td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_oradea" value="oradea"<?php
                            if (strpos($this->DATA['details']['orase'], 'oradea') !== false) echo ' checked="checked"'; ?> />Oradea</label></td>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_ploiesti" value="ploiesti"<?php
                            if (strpos($this->DATA['details']['orase'], 'ploiesti') !== false) echo ' checked="checked"'; ?> />Ploiești</label></td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_pitesti" value="pitesti"<?php
                            if (strpos($this->DATA['details']['orase'], 'pitesti') !== false) echo ' checked="checked"'; ?> />Pitești</label></td>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_piatraneamt" value="piatraneamt"<?php
                            if (strpos($this->DATA['details']['orase'], 'piatraneamt') !== false) echo ' checked="checked"'; ?> />Piatra Neamț</label></td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_resita" value="resita"<?php
                            if (strpos($this->DATA['details']['orase'], 'resita') !== false) echo ' checked="checked"'; ?> />Reșița</label></td>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_ramnicuvalcea" value="ramnicuvalcea"<?php
                            if (strpos($this->DATA['details']['orase'], 'ramnicuvalcea') !== false) echo ' checked="checked"'; ?> />Râmnicu Vâlcea</label></td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_timisoara" value="timisoara"<?php
                            if (strpos($this->DATA['details']['orase'], 'timisoara') !== false) echo ' checked="checked"'; ?> />Timișoara</label></td>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_targumures" value="targumures"<?php
                            if (strpos($this->DATA['details']['orase'], 'targumures') !== false) echo ' checked="checked"'; ?> />Târgu Mureș</label></td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_targujiu" value="targujiu"<?php
                            if (strpos($this->DATA['details']['orase'], 'targujiu') !== false) echo ' checked="checked"'; ?> />Târgu Jiu</label></td>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_slatina" value="slatina"<?php
                            if (strpos($this->DATA['details']['orase'], 'slatina') !== false) echo ' checked="checked"'; ?> />Slatina</label></td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_sibiu" value="sibiu"<?php
                            if (strpos($this->DATA['details']['orase'], 'sibiu') !== false) echo ' checked="checked"'; ?> />Sibiu</label></td>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_satumare" value="satumare"<?php
                            if (strpos($this->DATA['details']['orase'], 'satumare') !== false) echo ' checked="checked"'; ?> />Satu Mare</label></td>
                    </tr>
                    <tr>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_suceava" value="suceava"<?php
                            if (strpos($this->DATA['details']['orase'], 'suceava') !== false) echo ' checked="checked"'; ?> />Suceava</label></td>
                        <td style="text-align: left;"><label><input type="checkbox" name="hCheck_Orase_vaslui" value="vaslui"<?php
                            if (strpos($this->DATA['details']['orase'], 'vaslui') !== false) echo ' checked="checked"'; ?> />Vaslui</label></td>
                    </tr>
                </table>
            </div>


            <input type="hidden" name="userkey" value="mod" />
        </form>

        <br /><br />
        <div id="hStaticErrorMsg"></div>
        <input type="button" name="hButtonNext" id="hButtonNext" value="URMĂTORUL PAS &rArr;" class="standard-button rounded space-2020" />
    </div>-->

    <script type="text/javascript">
        $( document ).ready(function () {
             $("#frm_editangajator").validate({
                errorClass: "text-danger",
                errorPlacement: function (error, element) {
                    var inputContainer = element.closest('.form-group')
                    inputContainer.append(error)
                },
                submitHandler: function (form) {
                    $submit = $(form).find('button[type="submit"]')
                    $submit.html('<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>Loading...').attr('disabled', true);
                    $.ajax({
                        url: "<?= qurl_s('api/web-editacc-angajatori') ?>",
                        type: "POST",
                        data: $(form).serialize()
                    }).done(function (data) {
                        $submit.html('Salvează').attr('disabled', false);
                        bootbox.alert({
                            message: "Modificările profilului au fost facute cu succes!",
                            closeButton: false,
                            callback: function () {
                                window.location = '<?= qurl_l('profil-angajator'); ?>';
                            }
                        })
                    }).fail(function (e) {
                        $submit.html('Salvează').attr('disabled', false);
                        var message = "A apărut o eroare. Va rugăm sa încercați mai târziu!"
                        if (e.responseText) {
                            var res = JSON.parse(e.responseText)
                            if (res.result) {
                                message = res.result
                            }
                        }
                        bootbox.alert({
                            closeButton: false,
                            message: message,
                        })
                    })

                }
            })
        })



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
                case 2:{
                    if ($('#hEditNumeFirma').val().trim().length <= 0){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să completați numele firmei !');
                    }
                }break;

                case 3:{
                    if ($('#hEditAdresa').val().trim().length <= 0){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să completați adresa !');
                    }
                }break;

                case 4:{
                    if ($('#hEditCUI').val().trim().length <= 0){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să completați codul unic de înregistrare !');
                    }
                }break;

                case 5:{
                    if (!ValidatePassword($('#hEditParola').val())){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să puneți o parolă corespunzătoare !');
                    }
                }break;

                case 6:{
                    if (!$('#hRadioFirmaProtej_DA').prop('checked') && !$('#hRadioFirmaProtej_NU').prop('checked'))
                    {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;

                case 7:{
                    if (!$('#hRadioFirmaAngajati_sub').prop('checked') && !$('#hRadioFirmaAngajati_peste').prop('checked'))
                    {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;

                case 8:{
                    if (!$('#hRadioFirmaPerContr_part').prop('checked') && !$('#hRadioFirmaPerContr_full').prop('checked'))
                    {
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați o opțiune !');
                    }
                }break;

                case 9:{
                    var bChecked = false;

                    $('#section-9').find('input[type="checkbox"]').each(function(nIndex, kElement){
                        if ($(this).prop('checked')) bChecked = true;
                    });

                    if (!bChecked){
                        bMoveNext = false;
                        $('#hStaticErrorMsg').html('Trebuie să bifați cel puțin o opțiune !');
                    }
                }break;

                case 10:{
                    var bChecked = false;

                    $('#section-10').find('input[type="checkbox"]').each(function(nIndex, kElement){
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

                if (nCurrId == 10){
                    var jqXHR=$.post("<?php echo qurl_s('api/web-createacc-angajatori'); ?>",
                        $('#frm_creeazacont').serialize(),
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
                }

                if (nCurrId == 11)
                    window.location = '<?php echo qurl_l('auth-angajator'); ?>';
            }
        }

        $('#hButtonNext').click(Next);
        $(document).keyup(function(kEvent){
            if (kEvent.keyCode === 13){
                kEvent.preventDefault();
                Next();
            }
        });

        $('#hEditEmail').focus();

    </script>

