<div id="content">
    <div class="container mt-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10 mb-5">
                <h1 class="titlu mb-5">Creare cont universitate</h1>

                <div>
                    <p>
                        Universitățile reprezintă centre de excelență prin care ne putem desăvârși în drumul nostru vocațional și profesional. Ele sunt adevărate repere în transformarea comunităților și dezvoltare societății noastre
                    </p>

                    <p>
                        Dacă reprezentați o universitate care dorește să crească participarea persoanelor cu dizabilități de vedere la mediul educațional contribuind astfel la o societate incluzivă, aplicația BLINDHUB îți oferă posibilitatea de a crea punți de legătură cu comunitatea persoanelor cu dizabilități de vedere.
                    </p>
                    <p>
                        Poți posta mesaje, raspunde la întrebările potenșialilor studenți sau studenților înmatriculați, iniția apeluri video pentru a răspunde întrebărilor sau furniza clarificări în orice demers instuțional.
                    </p>
                </div>

                <h5 class="mt-3">
                    <strong>
                        Ai deja un cont? <a href="<?= qurl_l('auth-universitate') ?>">Intră în cont</a>
                    </strong>
                </h5>
            </div>

            <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10">
                <form id="frm_creeazacont">
                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>Nume universitate:</strong>
                        </label>
                        <input type="text" name="nume" class="form-control shadow" placeholder="nume universitate" required=>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>Adresa de email:</strong>
                        </label>
                        <input type="email" name="email" class="form-control shadow" placeholder="email" required>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>Creează o parolă:</strong>
                        </label>
                        <input type="password" name="parola" class="form-control shadow" placeholder="parolă" required>
                        <small class="form-text text-muted">Parola trebuie să conțină cel puțin 8 caractere, o literă mică, o literă mare, o cifră și un simbol.</small>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Orașe:</strong></label>
                        <select class="selectpicker form-control shadow" multiple  data-style="btn-white" data-live-search="true" data-size="12" required name="idx_orase[]">
                            <?php foreach ($this->DATA['orase'] as $oras): ?>
                            <option value="<?= $oras['idx'] ?>"><?= $oras['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>Numele reprezentantului universității:</strong>
                        </label>
                        <input type="text" name="reprezentant" class="form-control shadow" placeholder="reprezentant" required=>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Menționați gradul de accesibilitate a instituției dumneavoastră:</strong></label>
                        <?php foreach ($this->DATA['optiuni']['accesibilizare_clasa'] as $idx => $optiune): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="<?= $idx ?>" name="idx_optiune_accesibilizare_clasa" required>
                            <label class="form-check-label" >
                                <?= $optiune ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Menționați gradul de echipare cu tehnologie asistivă:</strong></label>
                        <?php foreach ($this->DATA['optiuni']['gradechipare'] as $idx => $optiune): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="<?= $idx ?>" name="idx_optiune_gradechipare" required>
                            <label class="form-check-label" >
                                <?= $optiune ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>


                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>
                                Menționați dacă universitatea are studenți cu dizabilități înmatriculați:
                            </strong>
                        </label>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0" name="studdiz" required>
                            <label class="form-check-label">
                                Nu
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" name="studdiz" required>
                            <label class="form-check-label">
                                Da
                            </label>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>
                                Menționați dacă universitatea are un centru de sprijin destinat studenților cu dizabilități:
                            </strong>
                        </label>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0" name="studcentru" required>
                            <label class="form-check-label">
                                Nu
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" name="studcentru" required>
                            <label class="form-check-label">
                                Da
                            </label>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>
                                Menționați dacă universitatea deține camere în căminele studențești adaptate la toate tipurile de dizabilități:
                            </strong>
                        </label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0" name="camerecamine" required>
                            <label class="form-check-label">
                                Nu
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" name="camerecamine" required>
                            <label class="form-check-label">
                                Da
                            </label>
                        </div>
                    </div>


                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>
                                Menționați dacă universitatea are o persoană sau un birou dedicat aplicației BlindHub:
                            </strong>
                        </label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0" name="persdedic" required>
                            <label class="form-check-label">
                                Nu
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" name="persdedic" required>
                            <label class="form-check-label">
                                Da
                            </label>
                        </div>
                    </div>

                     <div class="form-group mb-4">
                        <label class="form-label"><strong>Menționați gradul de accesibilizare a sălilor de curs:</strong></label>
                        <?php foreach ($this->DATA['optiuni']['gradacces'] as $idx => $optiune): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="<?= $idx ?>" name="idx_optiune_gradacces" required>
                            <label class="form-check-label" >
                                <?= $optiune ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>
                                Menționați dacă universitatea are în dotare cursuri în format Braille:
                            </strong>
                        </label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="0" name="braille" required>
                            <label class="form-check-label">
                                Nu
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" value="1" name="braille" required>
                            <label class="form-check-label">
                                Da
                            </label>
                        </div>
                    </div>


                    <div class="form-group my-5">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">
                            Crează cont
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <!--<div class="center-text">
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
    </div>-->

    <script type="text/javascript">
        jQuery.validator.addMethod("parola", function (value, element) {
            return ValidatePassword(value)
        }, "Trebuie să puneți o parolă corespunzătoare ")

        $( document ).ready(function () {
             $("#frm_creeazacont").validate({
                errorClass: "text-danger",
                rules: {
                    'parola': 'parola'
                },
                errorPlacement: function (error, element) {
                    var formGroup = element.closest('.form-group')
                    formGroup.append(error)
                },
                submitHandler: function (form) {
                    $submit = $(form).find('button[type="submit"]')
                    $submit.html('<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>Loading...').attr('disabled', true);
                    $.ajax({
                        url: "<?= qurl_s('api/web-createacc-universitati') ?>",
                        type: "POST",
                        data: $(form).serialize()
                    }).done(function (data) {
                        bootbox.dialog({
                            message: "Contul a fost creat cu success!",
                            closeButton: false,
                            buttons: {
                                ok: {
                                    label: "Intră in cont",
                                    className: 'btn-primary',
                                    callback: function() {
                                        window.location = '<?= qurl_l('home-universitate'); ?>';
                                    }
                                }
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
