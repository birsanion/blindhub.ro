    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.2/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>

    <div id="content" class="container my-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Statistici</h1>
                <table id="table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nr.</th>
                            <th>Email</th>
                            <th>Nume</th>
                            <th>Tip utilizator</th>
                            <th>Orașe</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->DATA['users'] as $index => $user): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $user['username'] ?></td>
                            <td style="white-space: normal;"><?= $user['nume'] ?></td>
                            <td><?= $user['tiputilizator'] ?></td>
                            <td style="white-space: normal;"><?= $user['orase'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#table').DataTable({
                pageLength: 50,
                dom: 'Bfrtip',
                buttons: [
                    'excel'
                ]
            });
        });
    </script>