
    <div class="master-container center-page center-text">
        <h1 class="bold space-2040 center-text">Bine ai venit !</h1>

        <div id="prelogin-section" class="center-text">
            <p>Ai deja cont BlindHub ?</p>
            <input type="button" value="DA" id="hButtonDa" class="standard-button rounded" />
            <input type="button" value="NU" id="hButtonNu" class="standard-button rounded" />
        </div>

        <div id="login-section" class="invisible">
            <form method="post" enctype="multipart/form-data" action="<?php echo qurl_l(URL_SELF); ?>">
                <div class="space-2020">E-mail:</div>
                <input type="email" name="hSpecial_AUTH_User" id="hSpecial_AUTH_User" value="" class="center-text rounded space-0040 w40lst" placeholder="email" />

                <div>Parolă:</div>
                <input type="password" name="hSpecial_AUTH_Pass" id="hSpecial_AUTH_Pass" value="" class="center-text rounded space-0040 w40lst" placeholder="parola" />

                <a href="<?php echo qurl_l('creare-cont-angajat'); ?>" class="bold block space-2020">CREARE CONT</a>
                <a href="<?php echo qurl_l('resetare-parola'); ?>" class="bold block">AM UITAT PAROLA</a>

                <input type="submit" name="hButtonLogin" id="hButtonLogin" value="INTRĂ ÎN CONT" class="standard-button rounded space-2020" />
                <input type="hidden" name="hSpecial_AUTH_Action" value="login" />
            </form>
        </div>

        <div><?php if ($this->DATA['login-failed']) echo 'EROARE: Nu se poate efectua autentificarea. Vă rugăm să verificați emailul și parola !'; ?></div>
    </div>

    <script type="text/javascript">

        $('#hButtonDa').click(function(){
            $('#prelogin-section').addClass('invisible');
            $('#login-section').removeClass('invisible');
        });

        $('#hButtonNu').click(function(){
            window.location = '<?php echo qurl_l('creare-cont-angajat'); ?>';
        });

    </script>