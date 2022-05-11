    <div id="content" class="container my-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Ofertele mele</h1>
                <?php if (count($this->DATA['locuri'])): ?>
                <div class="card shadow">
                    <div class="table-vcenter table-mobile-md table-responsive">
                        <table class="table b-table m-0">
                            <thead>
                                <tr>
                                    <th>Nume</th>
                                    <th>Domeniu</th>
                                    <th>Oraș</th>
                                    <th>Număr de locuri</th>
                                    <th class="w-1 text-right"></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($this->DATA['locuri'] as $loc): ?>
                                <tr>
                                    <td class="py-4"><strong><?= $loc['facultate'] ?></strong></td>
                                    <td data-label="Domeniu"><?= $loc['domeniu_universitate'] ?></td>
                                    <td data-label="Oraș"><?= $loc['oras'] ?></td>
                                    <td data-label="Număr de locuri"><?= $loc['numarlocuri'] ?></td>
                                    <td align="right">
                                        <a class="btn btn-primary rounded-pill px-3" href="<?= qurl_l('universitate-editloc/' . $loc['idx'] ) ?>">
                                            Editează
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php else: ?>
                <h4><strong>Nu aveți nicu o ofertă!</strong></h4>
                <?php endif; ?>
            </div>
        </div>
    </div>
