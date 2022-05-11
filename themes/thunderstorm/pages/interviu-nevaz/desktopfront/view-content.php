  <div id="content" class="container mt-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Agendă</h1>

                <?php if ($this->GLOBAL['errormsg']): ?>
                <div class="alert alert-danger mb-5" role="alert">
                    <?= $this->GLOBAL['errormsg'] ?>
                </div>
                <?php endif; ?>

                <?php if (!count($this->DATA['locuri'])): ?>
                <h4><strong>Nu aveți interviuri planificate.</strong></h4>
                <p>Vă recomand să aplicați la cât mai multe anunțuri pentru a fi invitat la un interviu video.</p>
                <?php else: ?>

                <div class="card shadow">
                    <div class="table-vcenter table-mobile-md table-responsive">
                        <table class="table b-table m-0">
                            <thead>
                                <tr>
                                    <th>Candidat</th>
                                    <th>Data și ora</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($this->DATA['locuri'] as $loc): ?>
                                <tr>
                                    <td class="py-4"><strong><?= $loc['nume'] ?></strong></td>
                                    <td data-label="Domeniu"><?= $loc['dataora'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<!--    <div class="master-container center-page center-text">
            <h1 class="bold space-2040">INTERVIURI</h1>

            <ol>
                <?php
                    if (count($this->DATA['locuri']) > 0){
                        foreach ($this->DATA['locuri'] as $arrLoc){
                ?>
                <li><h1 class="space-2020">Aveți interviu video stabilit în data de <?php
                    echo $arrLoc['dataora']; ?> cu firma <strong><?php
                        echo htmlspecialchars($arrLoc['nume']) . '</strong> (' .
                            $arrLoc['dimensiune'] . ', ' . $arrLoc['firmaprotejata'] . ')';
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
        </div>-->