     <div id="content" class="container my-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Caută universități</h1>
            </div>

            <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10">
                <form id="frm_cauta" method="post" enctype="multipart/form-data">
                <div id="section-1" class="section selected">
                    <!--<h1 class="space-2020">Orașul dorit:</h1>

                    <br />-->
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Orașul dorit?</strong></label>
                        <select class="selectpicker form-control shadow" data-size="10" id="hCombo_Oras" data-style="btn-white" required name="idx_oras">
                            <option value="" data-hidden="true"></option>
                            <?php foreach ($this->DATA['orase'] as $oras): ?>
                            <option value="<?= $oras['idx'] ?>"><?= $oras['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!--<select name="hCombo_Oras" id="hCombo_Oras" class="w60lst center-text">
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
                    </select>-->
                </div>

                <div id="section-2" class="section invisible">
                     <div class="form-group mb-4">
                        <label class="form-label"><strong>Universitate</strong></label>
                        <select class="selectpicker form-control shadow" data-size="10"  id="hCombo_Universitate" data-style="btn-white" required name="idxauthuniversitate">
                        </select>
                    </div>
                    <!--<h1 class="space-2020">Universitatea:</h1>
                    <br />
                    <select name="hCombo_Universitate" id="hCombo_Universitate" class="center-text"></select>-->
                </div>

                <div id="section-3" class="section invisible">
                    <div class="form-group mb-4">
                        <label class="form-label"><strong>Domeniu de interes:</strong></label>
                        <select class="selectpicker form-control shadow" data-size="10" id="hCombo_Domeniu" data-style="btn-white" required name="idx_domeniu_universitate">
                            <option value="" data-hidden="true"></option>
                            <?php foreach ($this->DATA['domenii_universitate'] as $domeniu): ?>
                            <option value="<?= $domeniu['idx'] ?>"><?= $domeniu['nume'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!--<h1 class="space-2020">Domeniu de interes:</h1>
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
                    </select>-->
                    </div>
                </form>
                <button type="button" name="hButtonNext" id="hButtonNext" class="btn btn-primary btn-lg rounded-pill ox-4" >
                    Următorul pas
                </button>
            </div>
            <div class="offset-lg-1 col-lg-10">
                <div id="section-4" class="section invisible" >
                    <div id="tbl-rezultate-locmunca"></div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).on('click', '.btn-solicita-info', function (e) {
            $this = $(this)
            $this.html('<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>Loading...').
                attr('disabled', true);
            $.ajax({
                url: "<?= qurl_s('api/web-cautauniversitati-solicitainfo') ?>",
                type: "POST",
                data: {
                    idxauthuniversitate: $this.data('idxauthuniversitate'),
                    idxloc: $this.data('idxloc'),
                }
            }).done(function (data) {
                $this.html('Solicită informații').attr('disabled', false);
                bootbox.alert({
                    closeButton: false,
                    message: 'Ai aplicat cu success',
                })
            }).fail(function (e) {
                $this.html('Solicită informații').attr('disabled', false);
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
        })


        $('#hButtonNext').click(function(){
            var nCurrId = parseInt($('div.section.selected').attr('id').substring(8));

            $('#section-' + nCurrId).removeClass('selected');
            $('#section-' + nCurrId).addClass('invisible');

            $('#section-' + (nCurrId + 1)).addClass('selected');
            $('#section-' + (nCurrId + 1)).removeClass('invisible');

            switch (nCurrId) {
                case 1:
                    var jqXHR = $.post("<?php echo qurl_s('api/web-getlistauniversitati'); ?>", {
                        idx_oras: $('#hCombo_Oras').val()
                    }, function (data) {
                        for (var i = 0; i < data['universitati'].length; i++){
                            $('#hCombo_Universitate').append('<option value=' + data['universitati'][i]['idxauth'] + '>' + data['universitati'][i]['nume'] + '</option>');
                        }
                        $('#hCombo_Universitate').selectpicker('refresh');
                    }, "json");

                    jqXHR.fail(function (a,b,c){
                        alert("AJAX err: "+a+' - '+b);
                    });
                    break;

                case 2:
                    $('#hButtonNext').val('FINALIZARE');
                    break;

                case 3:
                    $('#hButtonNext').remove();

                    var jqXHR = $.post("<?= qurl_s('api/web-cautauniversitati') ?>", $('#frm_cauta').serialize(), function (response) {
                        var html = '<div class="card shadow">' +
                            '<div class="table-vcenter table-mobile-md table-responsive">' +
                            '   <table class="table b-table m-0">' +
                            '       <thead>' +
                            '           <tr>' +
                            '               <th>Universitate</th>' +
                            '               <th>Facultate</th>' +
                            '               <th>Oraș</th>' +
                            '               <th>Număr de locuri</th>' +
                            '               <th class="w-1 text-right"></th>' +
                            '           </tr>' +
                            '       </thead>' +
                            '       <tbody>';

                        response.rezultate.forEach(function (elem) {
                            html += '<tr>' +
                                    '   <td class="py-4"><strong>' + elem.numeuniversitate + '</strong></td>' +
                                    '   <td data-label="Domeniu">' + elem.facultate + '</td>' +
                                    '   <td data-label="Oraș">' + elem.oras + '</td>' +
                                    '   <td data-label="Număr de locuri">' + elem.nrlocuri + '</td>' +
                                    '   <td align="right">' +
                                    '       <a class="btn btn-primary rounded-pill px-3 btn-solicita-info" data-idxauthuniversitate="' + elem.idxauth + '" data-idxloc="' + elem.idxloc + '">' +
                                    '           Solicită informații' +
                                    '       </a>' +
                                    '   </td>' +
                                    '</tr>'
                        })
                        html += '           </tbody>' +
                                '       </table>' +
                                '   </div>' +
                                '</div>'

                        $('#tbl-rezultate-locmunca').html(html);

                        $('a.solicitinfo').click(function(kEvent){
                            kEvent.preventDefault();

                            var nIdxUniv = $(this).data('idxuniv');
                            var nIdxLoc = $(this).data('idxloc');
                            var kElem = $(this);

                            var jqXHR2 = $.post("<?php echo qurl_s('api/web-cautauniversitati-solicitainfo'); ?>", {
                                idxauthuniversitate: nIdxUniv,
                                idxlocuniversitate: nIdxLoc
                            }, function (data) {
                                kElem.append('<div class="adhocinfo">Operațiune realizată cu succes !</div>');
                                setTimeout(function(){
                                    $('div.adhocinfo').remove();
                                }, 5000);
                            }, "json");

                            jqXHR2.fail(function(a,b,c){
                                alert("AJAX err: "+a+' - '+b);
                            });
                        });
                    }, "json");

                    jqXHR.fail(function(a,b,c){
                        alert("AJAX err: "+a+' - '+b);
                    });
                break;
            }
        });

    </script>

