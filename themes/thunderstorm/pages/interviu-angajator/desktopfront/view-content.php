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
                <?php else: ?>

                <div class="card shadow">
                    <div class="table-vcenter table-mobile-md table-responsive">
                        <table class="table b-table m-0">
                            <thead>
                                <tr>
                                    <th>Candidat</th>
                                    <th>Data și ora</th>
                                    <th>Loc munca</th>
                                    <th class="w-1 text-right"></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($this->DATA['locuri'] as $loc): ?>
                                <tr>
                                    <td class="py-4"><strong><?= $loc['nume'] ?></strong></td>
                                    <td data-label="Domeniu"><?= $loc['dataora'] ?></td>
                                    <td data-label="Loc munca"><?= $loc['locmunca'] ?></td>
                                    <td align="right">
                                        <a class="btn btn-primary rounded-pill px-3" href="<?= qurl_l('cv-video/' . $loc['idxauthnevazator'] ) ?>">
                                            Vizionează CV-ul video
                                        </a>
                                    </td>
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


