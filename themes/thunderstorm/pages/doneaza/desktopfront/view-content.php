
<div id="content">
     <div class="container mt-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Donează</h1>

                <form id="frm_doneaza" method="post" enctype="multipart/form-data" action="https://blindhub.ro/payment">
                    <div class="form-group mb-4">
                        <label class="form-label">
                            <strong>Vreau să donez suma:</strong>
                        </label>
                        <div class="input-group input-group-lg  shadow" style="width:300px">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><strong>LEI</strong></span>
                            </div>
                            <input type="number" name="amount" class="form-control" required value="1" readonly />
                        </div>
                    </div>

                    <div class="form-group">
                        <a class="btn btn-primary my-4 px-4 rounded-pill" id="btn-continue">
                            Continuă
                        </a>
                    </div>

                    <div class="card shadow-lg my-5 collapse" id="payment-details">
                        <div class="card-body">
                            <h5 class="mb-4"><strong>DETALII</strong></h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label"><strong>Prenume:</strong></label>
                                        <input type="text" name="fname" class="form-control shadow" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label"><strong>Nume:</strong></label>
                                        <input type="text" name="lname" class="form-control shadow" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label"><strong>Email:</strong></label>
                                        <input type="email" name="email" class="form-control shadow" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label"><strong>Adresă:</strong></label>
                                        <input type="text" name="address" class="form-control shadow" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label"><strong>Țara:</strong></label>
                                        <input type="text" name="country" class="form-control shadow" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label"><strong>Orașul:</strong></label>
                                        <input type="text" name="city" class="form-control shadow" required>
                                    </div>
                                </div>
                                 <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label"><strong>Cod poștal:</strong></label>
                                        <input type="text" name="zip" class="form-control shadow" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label"><strong>Număr de telefon:</strong></label>
                                        <input type="text" name="phone" class="form-control shadow" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <h5 class="text-uppercase my-4"><strong>Informații despre plată</strong></h5>
                                    <div class="form-group mb-3">
                                        <label class="form-label"><strong>Modalidate de plată</strong></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"  checked>
                                            <label class="form-check-label">
                                                Card de credit / debit
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group my-4">
                                        <button type="submit" class="btn btn-primary px-4 rounded-pill">Donează acum</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready(function () {
        $("#btn-continue").click(function () {
            $(this).fadeOut()
            $('.collapse').collapse()
        })


        $("#frm_doneaza").validate({
            errorClass: "text-danger",
            errorPlacement: function (error, element) {
                var inputContainer = element.closest('.form-group')
                inputContainer.append(error)
            },
            submitHandler: function (form) {
                form.submit()
            }
        })
    })
</script>