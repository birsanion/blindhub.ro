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
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->DATA['users'] as $index => $user): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $user['username'] ?></td>
                            <td><?= $user['nume'] ?></td>
                            <td><?= $user['tiputilizator'] ?></td>
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
                "pageLength": 50
            });
        });
    </script>