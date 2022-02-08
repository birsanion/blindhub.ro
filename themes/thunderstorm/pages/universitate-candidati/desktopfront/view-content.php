
    <div class="master-container center-page">
        <div class="center-text"><h1 class="bold space-4040">CANDIDAȚI</h1></div>
        
        <div class="w80lst center-page">
            <table id="tbl-rezultate-angajati" class="fullwidth">
                <?php
                    if (count($this->DATA['locuri']) > 0){
                        foreach ($this->DATA['locuri'] as $arrLoc){
                ?>
                <tr>
                    <td style="width: 50%; padding: 5px;">
                        <h1><?php echo $arrLoc['nume']; ?></h1>
                        <br />
                        <?php echo $arrLoc['gradhandicap']; ?><br />
                        <?php echo $arrLoc['nevoispecifice']; ?><br />
                        <?php echo 'Candidează în următoarele facultăți: ' . $arrLoc['facultati']; ?><br />
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
                        
                        <a href="#" class="block reference imgtextlink setinterviu" data-idx="<?php echo $arrLoc['idxauthnevazator']; ?>">
                            <img src="<?php echo qurl_f('images/icon_next_normal.png'); ?>" class="normal" />
                            <img src="<?php echo qurl_f('images/icon_next_mouseover.png'); ?>" class="over" />
                            <span>Inițiază interviu</span>
                        </a>
                    </td>
                    <td>
                        <?php
                            if (file_exists(qurl_serverfile('media/uploads/nevazator_cv_'. $arrLoc['idxauthnevazator'] .'.mp4'))){
                        ?>
                        <video controls style="position: relative; width: 100%;">
                            <source src="<?php echo qurl_file('media/uploads/nevazator_cv_'. $arrLoc['idxauthnevazator'] .'.mp4'); ?>" type="video/mp4"></source>
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
    </div>
    
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
                var jqXHR=$.post("<?php echo qurl_s('api/web-universitate-cautacandidati-initinterviu'); ?>",
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