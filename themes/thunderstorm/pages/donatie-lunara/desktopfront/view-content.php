<div id="content">
     <div class="container mt-5">
        <div class="row">
            <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10">
                <?php if (isset($this->GLOBAL['errormsg'])): ?>
                <div class="alert alert-danger mb-5" role="alert">
                    <?= $this->GLOBAL['errormsg'] ?>
                </div>
                <?php else: ?>
                <form id="frm_payment" method="post" enctype="multipart/form-data" action="https://secure.euplatesc.ro/tdsprocess/tranzactd.php">
                    <input name="amount" value="<?= $this->DATA['amount'] ?>" hidden required>
                    <input name="curr" value="<?= $this->DATA['curr'] ?>" hidden required>
                    <input name="invoice_id" value="<?= $this->DATA['invoice_id'] ?>" hidden required>
                    <input name="order_desc" value="<?= $this->DATA['order_desc'] ?>" hidden required>
                    <input name="merch_id" value="<?= $this->DATA['merch_id'] ?>" hidden required>
                    <input name="timestamp" value="<?= $this->DATA['timestamp'] ?>" hidden required>
                    <input name="nonce" value="<?= $this->DATA['nonce'] ?>" hidden required>
                    <input name="recurent_freq" value="<?= $this->DATA['recurent_freq'] ?>" hidden required>
                    <input name="recurent_exp" value="<?= $this->DATA['recurent_exp'] ?>" hidden required>
                    <input name="fp_hash" value="<?= $this->DATA['fp_hash'] ?>" hidden required>
                    <input name="recurent" value="<?= $this->DATA['recurent'] ?>" hidden required>
                    <input name="fname" value="<?= $this->DATA['fname'] ?>" hidden required>
                    <input name="lname" value="<?= $this->DATA['lname'] ?>" hidden required>
                    <input name="email" value="<?= $this->DATA['email'] ?>" hidden required>
                    <input name="phone" value="<?= $this->DATA['phone'] ?>" hidden required>
                    <input name="add" value="<?= $this->DATA['address'] ?>" hidden required>
                    <input name="city" value="<?= $this->DATA['city'] ?>" hidden required>
                    <input name="country" value="<?= $this->DATA['country'] ?>" hidden required>
                    <input name="zip" value="<?= $this->DATA['zip'] ?>" hidden required>
                    <input name="ExtraData[silenturl]" value="<?= $this->DATA['ExtraData']['silenturl'] ?>" hidden required>
                    <input name="ExtraData[successurl]" value="<?= $this->DATA['ExtraData']['successurl'] ?>" hidden required>
                    <input name="ExtraData[failedurl]" value="<?= $this->DATA['ExtraData']['failedurl'] ?>" hidden required>
                    <input name="ExtraData[backtosite]" value="<?= $this->DATA['ExtraData']['backtosite'] ?>" hidden required>
                    <input name="lang" value="ro" hidden required>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if (!$this->GLOBAL['errormsg']): ?>
<script type="text/javascript">
    $( document ).ready(function () {
        $("#content").LoadingOverlay("show")
        $("#frm_payment").submit()
    })
</script>
<?php endif; ?>
