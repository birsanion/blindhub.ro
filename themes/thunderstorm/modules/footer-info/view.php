<footer id="footer" class="footer sticky-footer">
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="offset-lg-1 col-lg-10">
                    <div class="row">
                        <div class="col-md-4 footer-links">
                            <h6><strong><?= $this->LANG('Vreau să ajut') ?></strong></h6>
                            <ul>
                                <?php if ($this->ROUTE->GetFlagsLanguage() == 'ro') :?>
                                <li><a href="<?= qurl_l('vreausaajut-redirectioneaza') ?>">Redirecționează</a></li>
                                <li><a href="<?= qurl_l('vreausaajut-sponsorizari') ?>">Sponsorizări</a></li>
                                <?php endif; ?>
                                <li><a href="<?= qurl_l('vreausaajut-donatii') ?>"><?= $this->LANG('Donații') ?></a></li>
                            </ul>
                        </div>

                        <div class="col-md-4 footer-links">
                            <h6>
                                <a href="<?= qurl_l('termenisiconditii') ?>"><strong><?= $this->LANG('Termeni și condiții') ?></strong></a>
                            </h6>
                            <h6>
                                <a href="<?= qurl_l('politicaconfidentialitate') ?>"><strong><?= $this->LANG('Politica de confidențialitate') ?></strong></a>
                            </h6>
                        </div>
                        <div class="col-md-4 footer-links">
                            <?php if ($this->ROUTE->GetFlagsLanguage() == 'ro') :?>
                            <h6>
                                <a href="<?= qurl_l('contact') ?>"><strong>Contact</strong></a>
                            </h6>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="copyright text-center">
                                &copy; Copyright <strong><span>2022</span></strong>. All Rights Reserved
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
