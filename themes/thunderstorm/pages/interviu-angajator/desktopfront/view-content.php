
    <div class="master-container center-page center-text">
        <h1 class="bold space-4040">INTERVIURI</h1>
        
        <ol>
            <?php
                if (count($this->DATA['locuri']) > 0){
                    foreach ($this->DATA['locuri'] as $arrLoc){
            ?>
            <li><h1 class="space-2020">Aveți interviu video stabilit în data de <?php
                echo $arrLoc['dataora']; ?> cu <strong><?php
                    echo htmlspecialchars($arrLoc['nume']) . '</strong> (' .
                        $arrLoc['gradhandicap'] . ', ' . $arrLoc['nevoispecifice'] . ')';
                ?>.</h1></li>
            <?php
                    } // end foreach
                }else{
            ?>
            <li><h1 class="space-2020">Momentan nu aveți niciun interviu stabilit.</h1></li>
            <?php
                } // endif
            ?>
        </ol>
    </div>