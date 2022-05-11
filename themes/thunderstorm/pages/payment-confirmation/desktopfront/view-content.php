<div id="content">
     <div class="container mt-5">
        <div class="row">
            <div class="offset-lg-3 col-lg-6 offset-md-1 col-md-10 text-center pt-5">
            	<?php if (isset($this->GLOBAL['errormsg'])): ?>
                <div class="alert alert-danger mb-5" role="alert">
                    <?= $this->GLOBAL['errormsg'] ?>
                </div>
                <?php else: ?>
            	<div id="payment-success-content" style="display: none">
                	<h3 class="mb-4"><strong class="status"></strong></h3>
                	<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2" height="100">
                        <circle class="path circle" fill="none" stroke="#73AF55" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                        <polyline class="path check" fill="none" stroke="#73AF55" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
                    </svg>
            	</div>
            	<div id="payment-error-content" style="display: none" >
                	<h3><strong class="status"></strong></h3>
                	<h5 class="mb-4"><strong class="message"></strong></h5>
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2" height="100">
                        <circle class="path circle" fill="none" stroke="#D06079" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                        <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="34.4" y1="37.9" x2="95.8" y2="92.3"/>
                        <line class="path line" fill="none" stroke="#D06079" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" x1="95.8" y1="38" x2="34.4" y2="92.2"/>
                    </svg>
                </div>
            	<?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php if (isset($_POST['invoice_id']) && isset($_POST['ep_id'])): ?>
<script type="text/javascript">
    var maxRequests = 20;
    var currentRequestCount = 0;

    var getPaymentStatus = function(onSuccess, onError) {
    	$.ajax({
            url: "<?= qurl_s('api/web-payment-status') ?>",
            type: "POST",
            dataType : "json",
            data: {
            	'invoice_id': '<?= $_POST['invoice_id'] ?>',
            	'ep_id': '<?= $_POST['ep_id'] ?>'
            }
        }).done(function (response) {
        	onSuccess(response)
        }).fail(function (e) {
        	onError()
        })
    }

    var checkPaymentStatus = function (onSuccess, onError) {
        getPaymentStatus(function (response) {
            currentRequestCount++;

            switch (response.status) {
            	case 'approved':
            		onSuccess(response);
            		break;
            	case 'failed':
            		onError(response.message);
            		break
            	default:
            		if (currentRequestCount > maxRequests) {
            			onError()
            			break
            		}
            		setTimeout(function() {
            			checkPaymentStatus(onSuccess, onError)
        			}, 4000)
        			break
            }
        }, function() {
            onError()
        })
    }

    $(document).ready(function () {
        var paymentSuccessContent = $('#payment-success-content');
        var paymentErrorContent   = $('#payment-error-content');
		var content = $('#content')

		content.LoadingOverlay("show")
        checkPaymentStatus(function(data) {
			content.LoadingOverlay("hide")
            var status = 'Ai plătit cu succes ' + data.amount + ' lei.'
            paymentSuccessContent.find('.status').html(status)
            paymentSuccessContent.fadeIn()
        }, function(errMessage) {
			content.LoadingOverlay("hide")
            var status = 'Plata nu a putut fi efectuată.'
            paymentErrorContent.find('.status').html(status)
            if (errMessage) {
            	paymentErrorContent.find('.message').html(errMessage)
            }
            paymentErrorContent.fadeIn()
        });
    });
</script>
<?php endif; ?>