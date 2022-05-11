<div id="content">
    <div class="container mt-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10">
                <h1 class="titlu mb-5">Autentificare</h1>

                <?php if ($this->DATA['login-failed']): ?>
                <div class="alert alert-danger mb-5" role="alert">
                    EROARE: Nu se poate efectua autentificarea. Vă rugăm să verificați emailul și parola!
                </div>
                <?php endif; ?>
            </div>

            <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10">
                <form method="post" enctype="multipart/form-data" action="<?php echo qurl_l(URL_SELF); ?>">
                     <div class="form-group mb-4">
                        <label class="form-label"><strong>E-mail:</strong></label>
                        <input type="email" name="hSpecial_AUTH_User"  class="form-control shadow" placeholder="Email" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label">
                            <strong>Parolă</strong>
                        </label>
                        <input type="password" name="hSpecial_AUTH_Pass" class="form-control shadow" placeholder="parolă" id="hSpecial_AUTH_Pass" required>
                    </div>
                    <a href="<?= qurl_l('resetare-parola') ?>"><strong>Ai uitat parola?</strong></a>
                    <div class="form-group my-4">
                        <input type="submit" name="hButtonLogin" id="hButtonLogin" value="Intră în cont" class="btn btn-primary btn-lg px-4 rounded-pill" />
                        <input type="hidden" name="hSpecial_AUTH_Action" value="login" />
                    </div>
                    <h5 class="mb-5">
                        <strong>
                            Nu ai un cont? <a href="<?= qurl_l('creare-cont-universitate') ?>">Înscrie-te</a>
                        </strong>
                    </h5>
                </form>
            </div>
        </div>
    </div>
</div>

    <!--<div class="master-container center-page center-text">
        <h1 class="bold space-2040 center-text">Bine ai venit !</h1>

        <div id="prelogin-section" class="center-text">
            <p>Ai deja cont BlindHub ?</p>
            <input type="button" value="DA" id="hButtonDa" class="standard-button rounded" />
            <input type="button" value="NU" id="hButtonNu" class="standard-button rounded" />
        </div>

        <div id="login-section" class="invisible">
            <form method="post" enctype="multipart/form-data" action="<?php echo qurl_l(URL_SELF); ?>">
                <div class="space-2020">E-mail:</div>
                <input type="text" name="hSpecial_AUTH_User" id="hSpecial_AUTH_User" value="" class="center-text rounded space-0040 w40lst" placeholder="email" />

                <div>Parolă:</div>
                <input type="password" name="hSpecial_AUTH_Pass" id="hSpecial_AUTH_Pass" value="" class="center-text rounded space-0040 w40lst" placeholder="parola" />

                <a href="<?php echo qurl_l('creare-cont-universitate'); ?>" class="bold block space-2020">CREARE CONT</a>
                <a href="<?php echo qurl_l('resetare-parola'); ?>" class="bold block">AM UITAT PAROLA</a>

                <input type="submit" name="hButtonLogin" id="hButtonLogin" value="INTRĂ ÎN CONT" class="standard-button rounded space-2020" />
                <input type="hidden" name="hSpecial_AUTH_Action" value="login" />
            </form>
        </div>

        <div><?php if ($this->DATA['login-failed']) echo 'EROARE: Nu se poate efectua autentificarea. Vă rugăm să verificați emailul și parola !'; ?></div>
    </div>-->

    <script type="text/javascript">

        $('#hButtonDa').click(function(){
            $('#prelogin-section').addClass('invisible');
            $('#login-section').removeClass('invisible');
        });

        $('#hButtonNu').click(function(){
            window.location = '<?php echo qurl_l('creare-cont-universitate'); ?>';
        });

    </script>
