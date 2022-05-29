    <div id="content" class="container my-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Ofertele mele</h1>
                <?php if (count($this->DATA['locuri'])): ?>
                <div class="card shadow-lg">
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
                                        <a class="btn btn-danger btn-delete rounded-pill px-3" data-idx="<?= $loc['idx'] ?>">
                                            Șterge
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

    <script>
    $( document ).ready(function () {
        function deleteFaculty (id, onSuccess) {
            $.ajax({
                url: "<?= qurl_s('api/web-universitate-stergeoferta') ?>",
                data: {
                    idxloc: idx,
                },
                type: "POST",
            }).done(function (data) {
                onSuccess()
            }).fail(function (e) {
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

        $(".btn-delete").click(function () {
            idx = $(this).data('idx')
            bootbox.confirm({
                message: "Sunteți sigur că doriți să ștergeți acestă ofertă?",
                closeButton: false,
                buttons: {
                    confirm: {
                        label: 'Da',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'Nu',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if (result) {
                        deleteFaculty(idx, function () {
                            bootbox.alert({
                                closeButton: false,
                                message: 'Oferta a fost ștersă cu succes!',
                                callback: function () {
                                    window.location = '<?= qurl_l('universitate-oferte'); ?>';
                                }
                            })
                        })
                    }
                }
            })
        })
    })
</script>