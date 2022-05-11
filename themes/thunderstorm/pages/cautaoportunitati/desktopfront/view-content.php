    <div id="content" class="container my-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Oportunități de muncă</h1>
                <?php if (!count($this->DATA['locuri'])): ?>
                <h4><strong>Nu există nici o oportunitate</strong></h4>
                <?php endif; ?>
                <?php foreach ($this->DATA['locuri'] as $loc): ?>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h4><strong><?= $loc['titlu'] ?></strong></h4>
                            <h5><?= $loc['vechimeanunt'] ?></h5>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Tip Job:</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?= $loc['tipslujba'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Companie</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?= $loc['nume'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Oraș</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?= $loc['oras'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Domeniu</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?= $loc['domeniu_cv'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Competențe necesare</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?= $loc['competente'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Descriere</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    <?= $loc['descriere'] ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 mt-3">
                                    <button class="btn btn-primary btn-salveaza rounded-pill px-4" data-idx="<?= $loc['idxlocmunca'] ?>">
                                        Salvează job
                                    </button>
                                    <button class="btn btn-primary btn-aplica rounded-pill px-4" data-idx="<?= $loc['idxlocmunca'] ?>">
                                        Aplică
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <!--
    <div class="master-container center-page">
        <div class="center-text"><h1 class="bold space-4040">OPORTUNITĂȚI DE MUNCĂ</h1></div>

        <?php
            if (isset($this->GLOBAL['errormsg']) && !empty($this->GLOBAL['errormsg'])){
        ?>
        <div class="center-text"><?php echo $this->GLOBAL['errormsg']; ?></div>
        <?php
            }
        ?>

        <div class="w80lst center-page">
            <table id="tbl-rezultate-locmunca" class="fullwidth">
                <?php
                    if (count($this->DATA['locuri']) > 0){
                        foreach ($this->DATA['locuri'] as $arrLoc){
                ?>
                <tr>
                    <td style="width: 50%; padding: 5px;"><h1><?php echo htmlspecialchars($arrLoc['nume']); ?></h1></td>
                    <td>
                        <ol>
                            <li><?php echo htmlspecialchars($arrLoc['firmaprotejata']); ?></li>
                            <li><?php echo htmlspecialchars($arrLoc['dimensiunefirma']); ?></li>
                            <li><?php echo htmlspecialchars($arrLoc['tipslujba']); ?></li>
                        </ol>
                        <br />
                        <a href="#" class="block reference imgtextlink initinterviu" data-idx="<?php echo $arrLoc['idxangajator']; ?>">
                            <img src="<?php echo qurl_f('images/icon_next_normal.png'); ?>" class="normal" />
                            <img src="<?php echo qurl_f('images/icon_next_mouseover.png'); ?>" class="over" />
                            <span>Inițiază interviu</span>
                        </a>
                    </td>
                </tr>
                <tr><td colspan="2"><hr /></td></tr>
                <?php
                        } // end foreach
                    }else{
                ?>
                    <div class="center-text">Nu există niciun rezultat momentan !</div>
                <?php
                    }
                ?>
            </table>
        </div>
    </div>
    -->
    <script type="text/javascript">
        $( document ).ready(function () {
            $('.btn-aplica').click(function (Event) {
                $this = $(this)
                $this.html('<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>Loading...').attr('disabled', true);
                $.ajax({
                    url: "<?= qurl_s('api/web-cautalocmunca-initinterviu') ?>",
                    type: "POST",
                    data: {
                        idxlocmunca: $this.data('idx'),
                    },
                }).done(function (data) {
                    $this.html('Aplică').attr('disabled', false);
                    bootbox.alert({
                        closeButton: false,
                        message: "Ai aplicat cu success la acest loc de muncă",
                    })
                }).fail(function (e) {
                    $this.html('Aplică').attr('disabled', false);
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

            $('.btn-salveaza').click(function (Event) {
                $this = $(this)
                $this.html('<span class="spinner-border spinner-border-sm mx-2" role="status" aria-hidden="true"></span>Loading...').attr('disabled', true);
                $.ajax({
                    url: "<?= qurl_s('api/web-cautalocmunca-salveaza') ?>",
                    type: "POST",
                    data: {
                        idxlocmunca: $this.data('idx'),
                    },
                }).done(function (data) {
                    $this.html('Salvează job').attr('disabled', false);
                    bootbox.alert({
                        closeButton: false,
                        message: "Locul de muncă a fost salvat cu succes",
                    })
                }).fail(function (e) {
                    $this.html('Salvează job').attr('disabled', false);
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
        })

        $('a.initinterviu').click(function(kEvent){
            kEvent.preventDefault();

            var nIdx = $(this).data('idx');
            var kElem = $(this);

            var jqXHR=$.post("<?php echo qurl_s('api/web-cautaoportunitati-initinterviu'); ?>",
                {
                    idxangajator: nIdx
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

            jqXHR.fail(function(a,b,c){
                alert("AJAX err: "+a+' - '+b);
            });
        });

    </script>
