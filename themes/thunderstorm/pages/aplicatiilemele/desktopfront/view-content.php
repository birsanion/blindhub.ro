
    <div class="master-container center-page">
            <div class="center-text"><h1 class="bold space-2040">APLICAȚIILE MELE</h1></div>
            
            <div class="w80lst center-page">
                <table id="tbl-rezultate-locmunca" class="fullwidth">
                    <?php
                        if (count($this->DATA['locuri']) > 0){
                            foreach ($this->DATA['locuri'] as $arrLoc){
                    ?>
                    <tr>
                        <td style="width: 50%; padding: 5px;"><h1><?php echo $arrLoc['nume']; ?></h1></td>
                        <td>
                            <ol>
                                <li><?php echo $arrLoc['firmaprotejata']; ?></li>
                                <li><?php echo $arrLoc['dimensiunefirma']; ?></li>
                                <li><?php echo $arrLoc['vechimeanunt']; ?></li>
                            </ol>
                        </td>
                    </tr>
                    <tr><td colspan="2"><hr /></td></tr>
                    <?php
                            }
                        }else{
                    ?>
                    <tr><td colspan="2">Până acum nu ați aplicat la nici o slujbă.</td></tr>
                    <?php
                        }
                    ?>
                </table>
            </div>
        </div>