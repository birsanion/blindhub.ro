    <div id="content" class="container my-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Caută candidați</h1>
                <?php
                    if (count($this->DATA['locuri']) > 0):
                        foreach ($this->DATA['locuri'] as $arrLoc) :
                ?>
                <div class="card profile-header shadow mb-4">
                    <div class="body">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-12 text-center">
                                <?php if ($arrLoc['cv_fisier_video']): ?>
                                <video height="250px" controls style="max-width: 100%">
                                    <source src="<?= qurl_file('media/uploads/'. $arrLoc['cv_fisier_video']) ?>" type="video/mp4">
                                </video>
                                <?php else: ?>
                                <img class="img-fluid" src="<?= qurl_f('images/novideo.png') ?>" />
                                <?php endif; ?>
                            </div>
                            <div class="col-lg-8 col-md-8 col-12">
                                <h3 class="m-t-0 m-b-0"><strong><?= $arrLoc['nume'] ?></strong></h3>
                                <p class="m-0">Domenii: <?= $arrLoc['domenii_cv'] ?></p>
                                <p class="m-0">Orașe: <?= $arrLoc['orase'] ?></p>
                                <p class="m-0">Grad handicap: <?= $arrLoc['gradhandicap'] ?></p>
                                <p>Nevoi speciale: <?= $arrLoc['nevoispecifice'] ?></p>
                                <div>
                                    <a class="btn btn-primary rounded-pill px-3" href="<?= qurl_l('mesaje/' . $arrLoc['idxauth']) ?>">Trimite mesaj</a>
                                    <button class="btn btn-primary rounded-pill px-3">Inițiază interviu</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                        endforeach;
                    endif;
                ?>
            </div>
        </div>
    </div>

    <!--<div class="master-container center-page">
        <div class="center-text"><h1 class="bold space-4040">POTENȚIALI ANGAJAȚI</h1></div>

        <div class="w80lst center-page">
            <table id="tbl-rezultate-angajati" class="fullwidth">
                <?php
                    if (count($this->DATA['locuri']) > 0){
                        foreach ($this->DATA['locuri'] as $arrLoc) {
                ?>
                <tr>
                    <td style="width: 50%; padding: 5px;">
                        <h1><?php echo $arrLoc['nume']; ?></h1>
                        <br />
                        <?php echo $arrLoc['gradhandicap']; ?><br />
                        <?php echo $arrLoc['nevoispecifice']; ?><br />
                        <br /><br />
                        În data de
                        <input type="text" value="" class="rounded w40lst dateforinterviu" />
                        <select class="w20lst rounded">
                            <option>09:00</option>
                            <option>09:15</option>
                            <option>09:30</option>
                            <option>09:45</option>
                            <option>10:00</option>
                            <option>10:15</option>
                            <option>10:30</option>
                            <option>10:45</option>

                            <option>11:00</option>
                            <option>11:15</option>
                            <option>11:30</option>
                            <option>11:45</option>

                            <option>12:00</option>
                            <option>12:15</option>
                            <option>12:30</option>
                            <option>12:45</option>

                            <option>13:00</option>
                            <option>13:15</option>
                            <option>13:30</option>
                            <option>13:45</option>

                            <option>14:00</option>
                            <option>14:15</option>
                            <option>14:30</option>
                            <option>14:45</option>

                            <option>15:00</option>
                            <option>15:15</option>
                            <option>15:30</option>
                            <option>15:45</option>

                            <option>16:00</option>
                            <option>16:15</option>
                            <option>16:30</option>
                            <option>16:45</option>

                            <option>17:00</option>
                            <option>17:15</option>
                            <option>17:30</option>
                            <option>17:45</option>
                        </select>

                        <a href="#" class="block reference imgtextlink setinterviu" data-idx="<?php echo $arrLoc['idxauth']; ?>">
                            <img src="<?php echo qurl_f('images/icon_next_normal.png'); ?>" class="normal" />
                            <img src="<?php echo qurl_f('images/icon_next_mouseover.png'); ?>" class="over" />
                            <span>Inițiază interviu</span>
                        </a>
                    </td>
                    <td>
                        <?php
                            if (file_exists(qurl_serverfile('media/uploads/nevazator_cv_'. $arrLoc['idxauth'] .'.mp4'))){
                        ?>
                        <video controls style="position: relative; width: 100%;">
                            <source src="<?php echo qurl_file('media/uploads/nevazator_cv_'. $arrLoc['idxauth'] .'.mp4'); ?>" type="video/mp4"></source>
                        </video>
                        <?php
                            }
                        ?>
                    </td>
                </tr>
                <tr><td colspan="2"><hr /></td></tr>
                <?php
                        }
                    }
                ?>
            </table>
        </div>
    </div>-->

    <script type="text/javascript">

        $('input.dateforinterviu').datepicker({
            showWeek: true,
            firstDay: 1,
            showButtonPanel: true,
            dateFormat: 'dd/mm/yy',
            changeMonth: true,
            changeYear: true
        });

        $('a.setinterviu').click(function(){
            var kElem = $(this);
            var strDate = $(this).parent().children('input.dateforinterviu').val();
            var nIdxAngajat = $(this).data('idx');
            var strOra = $(this).parent().children('select').val();

            if (strDate.length > 0){
                var jqXHR=$.post("<?php echo qurl_s('api/web-cautaangajati-initinterviu'); ?>",
                    {
                        datacalend: strDate,
                        idxauthnevazator: nIdxAngajat,
                        ora: strOra
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
            }else{
                kElem.append('<div class="adhocinfo">Selectați o dată din calendar !</div>');
                setTimeout(function(){
                    $('div.adhocinfo').remove();
                }, 5000);
            }
        });

    </script>