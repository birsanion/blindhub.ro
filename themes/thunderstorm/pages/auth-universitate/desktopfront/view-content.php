
    <div class="center-text">
        <h1 class="bold space-2040">AUTENTIFICARE</h1>
        
        <form method="post" enctype="multipart/form-data" action="<?php echo qurl_l(URL_SELF); ?>">
            <div class="space-2020">E-mail:</div>
            <input type="text" name="hSpecial_AUTH_User" id="hSpecial_AUTH_User" value="" class="center-text rounded space-0040 w40lst" placeholder="email" />
            
            <div class="space-2020">Parolă:</div>
            <input type="password" name="hSpecial_AUTH_Pass" id="hSpecial_AUTH_Pass" value="" class="center-text rounded space-0040 w40lst" placeholder="parola" />
            
            <a href="<?php echo qurl_l('creare-cont-universitate'); ?>" class="bold block space-2020">CREARE CONT</a>
            <a href="<?php echo qurl_l('resetare-parola'); ?>" class="bold block">AM UITAT PAROLA</a>
            
            <input type="submit" name="hButtonLogin" id="hButtonLogin" value="INTRĂ ÎN CONT" class="standard-button rounded space-2020" />
            <input type="hidden" name="hSpecial_AUTH_Action" value="login" />
        </form>
        
        <div><?php if ($this->DATA['login-failed']) echo 'EROARE: Nu se poate efectua autentificarea. Vă rugăm să verificați emailul și parola !'; ?></div>
    </div>
    