
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
    
    <script type="text/javascript">
        
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
