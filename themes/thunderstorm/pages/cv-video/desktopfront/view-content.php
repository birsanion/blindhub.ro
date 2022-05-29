  <div id="content" class="container mt-5">
        <div class="row">
            <div class="offset-lg-1 col-lg-10 mb-5">
                <h1 class="titlu mb-5">CV Video</h1>
                <?php if ($this->GLOBAL['errormsg']): ?>
                <div class="alert alert-danger mb-5" role="alert">
                    <?= $this->GLOBAL['errormsg'] ?>
                </div>
                <?php endif; ?>
                <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10">
                <?php if ($this->DATA['cv']): ?>
                <div class="embed-responsive embed-responsive-1by1 h-100" >
                    <iframe class="embed-responsive-item w-100" height="300px" src="<?= qurl_file('media/uploads/'. $this->DATA['cv']) ?>"></iframe>
                </div>
                <?php else: ?>
                <img class="img-fluid" src="<?= qurl_f('images/novideo.png') ?>" />
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>


