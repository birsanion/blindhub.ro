     <div id="content" class="container my-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Caută universități</h1>
            </div>

            <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10">
                <form id="frm_cauta" method="post" enctype="multipart/form-data">
                    <div data-index="0" class="section selected">
                        <div class="form-group mb-4">
                            <label class="form-label"><strong>Orașul dorit?</strong></label>
                            <select class="selectpicker form-control shadow" data-size="10" id="hCombo_Oras" data-style="btn-white" required name="idx_oras">
                                <option value="" data-hidden="true"></option>
                                <?php foreach ($this->DATA['orase'] as $oras): ?>
                                <option value="<?= $oras['idx'] ?>"><?= $oras['nume'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div data-index="1" class="section invisible">
                        <div class="form-group mb-4">
                            <label class="form-label"><strong>Universitate</strong></label>
                            <select class="selectpicker form-control shadow" data-size="10"  id="hCombo_Universitate" data-style="btn-white" required name="idxauthuniversitate">
                            </select>
                        </div>
                    </div>


                    <div data-index="2" class="section invisible">
                        <div class="form-group mb-4">
                            <label class="form-label"><strong>Domeniu de interes:</strong></label>
                            <select class="selectpicker form-control shadow" data-size="10" id="hCombo_Domeniu" data-style="btn-white" required name="idx_domeniu_universitate">
                                <option value="" data-hidden="true"></option>
                                <?php foreach ($this->DATA['domenii_universitate'] as $domeniu): ?>
                                <option value="<?= $domeniu['idx'] ?>"><?= $domeniu['nume'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </form>

                <div class="row mt-5">
                    <div class="col-6">
                        <button type="button" name="hButtonPrev" id="hButtonPrev" class="btn btn-link btn-lg" style="display:none" >
                            Înapoi
                        </button>
                    </div>
                    <div class="col-6" style="text-align:right">
                        <button type="button" name="hButtonNext" id="hButtonNext" class="btn btn-primary btn-lg rounded-pill ox-4" >
                            Următorul pas
                        </button>
                    </div>
                </div>
            </div>

            <div class="offset-lg-1 col-lg-10">
                <div id="results-container" data-index="3" class="section invisible" >
                    <button type="button" id="hButtonStart" class="btn btn-link btn-lg mb-4">
                        Înapoi
                    </button>
                    <div id="tbl-rezultate-locmunca"></div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        const STEP_CITY = 0
        const STEP_UNIVERSITY = 1
        const STEP_DOMAIN = 2
        const STEP_RESULTS = 3

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
                    message: 'Ai aplicat cu success la această facultate!',
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

        function getUniversities(cityId, onSuccess, onError) {
            $.ajax({
                url: "<?= qurl_s('api/web-getlistauniversitati') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    idx_oras: cityId
                }
            }).done(function (data) {
                onSuccess(data)
            }).fail(function (e) {
                onError(e)
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

        $( document ).ready(function () {
            var form = $("#frm_cauta");
            var btnNext = $('#hButtonNext')
            var btnPrev = $('#hButtonPrev')
            var btnStart = $('#hButtonStart')
            var cityInput = $('#hCombo_Oras')
            var universityInput = $('#hCombo_Universitate')
            var domainInput = $('#hCombo_Domeniu')
            var resultsContainer = $('#tbl-rezultate-locmunca')
            var currStep = STEP_CITY

            function goToStep (step) {
                $('.section.selected').addClass('invisible');
                $('.section.selected').removeClass('selected');
                console.log(step)
                if (step >= 0) {
                    $('.section[data-index="' + step + '"]').addClass('selected');
                    $('.section[data-index="' + step + '"]').removeClass('invisible');
                    currStep = step
                }

                if (!step) {
                    btnPrev.hide()
                } else {
                    btnPrev.show()
                }

                if (step >= STEP_RESULTS) {
                    btnNext.hide()
                    btnPrev.hide()
                } else {
                    btnNext.show()
                }
            }

            function showResults (container, results) {
                var html =
                    '<h4><strong>' + results.length + ' rezultate</strong></h4>' +
                    '<div class="card shadow">' +
                    '   <div class="table-vcenter table-mobile-md table-responsive">' +
                    '       <table class="table b-table m-0">' +
                    '           <thead>' +
                    '               <tr>' +
                    '                   <th>Universitate</th>' +
                    '                   <th>Facultate</th>' +
                    '                   <th>Oraș</th>' +
                    '                   <th>Număr de locuri</th>' +
                    '                   <th class="w-1 text-right"></th>' +
                    '               </tr>' +
                    '           </thead>' +
                    '           <tbody>';

                results.forEach(function (elem) {
                    html +=
                        '<tr>' +
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
                    html +=
                        '           </tbody>' +
                        '       </table>' +
                        '   </div>' +
                        '</div>'
                container.html(html)
                goToStep(STEP_RESULTS)
            }

            form.validate({
                errorClass: "text-danger",
                errorPlacement: function (error, element) {
                    var formGroup = element.closest('.form-group')
                    formGroup.append(error)
                },
                submitHandler: function (form) {
                    $.ajax({
                        url: "<?= qurl_s('api/web-cautauniversitati') ?>",
                        type: "POST",
                        dataType: "json",
                        data: $(form).serialize()
                    }).done(function (data) {
                        showResults(resultsContainer, data.rezultate)
                    }).fail(function (e) {
                        onError(e)
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


            btnStart.click(function () {
                goToStep(STEP_CITY)
            })

            btnPrev.click(function () {
                goToStep(currStep - 1)
            })

            btnNext.click(function () {
                $this = $(this)

                switch (currStep) {
                    case STEP_CITY:
                        if (!cityInput.valid()) {
                            return
                        }

                        $this.html('<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>Loading...').attr('disabled', true)
                        getUniversities(cityInput.val(), function (data) {
                            $this.html('Următorul pas').attr('disabled', false)
                            if (!data.universitati.length) {
                                showResults(resultsContainer, [])
                                return
                            }

                            universityInput.html('')
                            for (var i = 0; i < data.universitati.length; i++){
                                universityInput.append('<option value=' + data.universitati[i].idxauth + '>' + data.universitati[i].nume + '</option>')
                            }

                            universityInput.selectpicker('refresh');
                            goToStep(currStep + 1)
                        }, function () {
                            $this.html('Următorul pas').attr('disabled', false)
                        })
                        break;

                    case 1:
                        if (!universityInput.valid()) {
                            return
                        }

                        goToStep(currStep + 1)
                        break;

                    case 2:
                        if (!domainInput.valid()) {
                            return
                        }


                        form.submit()
                        break;
                }
            })
        })
    </script>

