
<div id="content">
     <div class="container mt-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Donează</h1>

                <h6 class="text-uppercase mb-3"><strong>Donația ta</strong></h6>
                <ul class="nav  nav-tabs " id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-4" id="home-tab" data-bs-toggle="tab" data-bs-target="#donatie-singulara" type="button" role="tab" aria-controls="donatie-singulara" aria-selected="true"><h5 class="m-0"><strong>Singulară</strong></h5></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4" id="profile-tab" data-bs-toggle="tab" data-bs-target="#donatie-lunara" type="button" role="tab" aria-controls="donatie-lunara" aria-selected="false"><h5 class="m-0"><strong>Lunară</strong></h5></button>
                    </li>
                </ul>

                <div class="tab-content mt-5" id="myTabContent">
                    <div class="tab-pane fade show active" id="donatie-singulara" role="tabpanel" aria-labelledby="home-tab">
                        <form id="frm_donatie_singulara" method="post" enctype="multipart/form-data" action="<?= qurl_l('donatie-singulara') ?>">
                            <div class="form-group mb-4">
                                <label class="form-label">
                                    <strong>Vreau să donez suma:</strong>
                                </label>
                                <div class="input-group input-group-lg  shadow" style="width:300px">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><strong>LEI</strong></span>
                                    </div>
                                    <input type="number" name="amount" class="form-control" required />
                                </div>
                            </div>

                            <div class="form-group">
                                <a class="btn btn-primary my-4 px-4 rounded-pill btn-continue">
                                    Continuă
                                </a>
                            </div>

                            <div class="card shadow-lg my-5 collapse payment-details">
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
                    <div class="tab-pane fade" id="donatie-lunara" role="tabpanel" aria-labelledby="profile-tab">
                        <form id="frm_donatie_lunara" method="post" enctype="multipart/form-data" action="<?= qurl_l('donatie-lunara') ?>">
                            <ul class="list-checkbox-img m-0 p-0">
                                <li>
                                    <input type="radio" id="myCheckbox1" name="amount_fixed" value="30"/>
                                    <label for="myCheckbox1">
                                        <img class="img-fluid" src= "<?= qurl_f('images/inima30.png') ?>"/>
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="myCheckbox2" name="amount_fixed" value="50"/>
                                    <label for="myCheckbox2">
                                        <img class="img-fluid" src= "<?= qurl_f('images/inima50.png') ?>"/>
                                    </label>
                                </li>
                                <li>
                                    <input type="radio" id="myCheckbox3" name="amount_fixed" value="70"/>
                                    <label class="img-fluid" for="myCheckbox3">
                                        <img src= "<?= qurl_f('images/inima70.png') ?>"/>
                                    </label>
                                </li>
                            </ul>
                            <div class="form-group my-4">
                                <label class="form-label">
                                    <strong>Vreau să donez suma:</strong>
                                </label>
                                <div class="input-group input-group-lg shadow" style="width:300px">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><strong>LEI</strong></span>
                                    </div>
                                    <input type="number" name="amount" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <a class="btn btn-primary my-4 px-4 rounded-pill btn-continue">
                                    Continuă
                                </a>
                            </div>

                            <div class="card shadow-lg my-5 collapse payment-details">
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
    </div>
</div>

<style>
.nav-tabs .nav-link.active {
    color: #fff;
    background-color: #013289;
    border-color: #dee2e6 #dee2e6 #fff;
}
</style>

<script type="text/javascript">
    $( document ).ready(function () {
        $(".btn-img img, .img-hover").hover(function () {
            $(this).attr("src", $(this).attr("src-over"))
	    })

        $(".btn-img img, .img-hover").mouseout(function () {
            $(this).attr("src", $(this).attr("src-normal"))
        })

        $("#frm_donatie_singulara .btn-continue, #frm_donatie_lunara .btn-continue").click(function () {
            $(this).fadeOut()
            $(this).closest('form').find('.collapse').collapse()
        })

        $("#frm_donatie_singulara").validate({
            errorClass: "text-danger",
            errorPlacement: function (error, element) {
                var inputContainer = element.closest('.form-group')
                inputContainer.append(error)
            },
            submitHandler: function (form) {
                form.submit()
            }
        })

        $("#frm_donatie_lunara").validate({
            rules: {
                amount: {
                    required: function(element) {
                        return !$('input[name="amount_fixed"]').is(":checked")
                    }
                }
            },
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