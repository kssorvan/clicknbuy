<?php require base_path('views/client/partials/head.php') ?>
<?php require base_path('views/client/partials/nav.php') ?>

<section class="pt-5 mt-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="/cart">Cart</a></li>
                <li class="breadcrumb-item active" aria-current="page">Payment</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Payment with ABA PayWay</h4>
                    </div>
                    <div class="card-body text-center p-5">
                        <p>You will be redirected to ABA PayWay to complete your payment.</p>
                        <p>Your order total is: <strong>$<?= $amount ?></strong></p>
                        
                        <form id="payway-form" method="POST" action="<?= $apiUrl ?>">
                            <input type="hidden" name="merchant_id" value="<?= $merchantId ?>">
                            <input type="hidden" name="req_time" value="<?= $reqTime ?>">
                            <input type="hidden" name="trans_id" value="<?= $transId ?>">
                            <input type="hidden" name="amount" value="<?= $amount ?>">
                            <input type="hidden" name="items" value="<?= $items ?>">
                            <input type="hidden" name="firstname" value="<?= $firstName ?>">
                            <input type="hidden" name="email" value="<?= $email ?>">
                            <input type="hidden" name="return_url" value="<?= $returnUrl ?>">
                            <input type="hidden" name="continue_success_url" value="<?= $continueSuccessUrl ?>">
                            <input type="hidden" name="continue_fail_url" value="<?= $continueFailUrl ?>">
                            <input type="hidden" name="hash" value="<?= $hash ?>">
                            
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Proceed to Payment
                                </button>
                                <a href="/cart" class="btn btn-outline-secondary btn-lg ms-2">
                                    Cancel
                                </a>
                            </div>
                        </form>
                        
                        <!-- Auto-submit form -->
                        <script>
                            // Automatically submit the form after 3 seconds
                            setTimeout(function() {
                                document.getElementById('payway-form').submit();
                            }, 3000);
                        </script>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <h5>Secure Payment with ABA PayWay</h5>
                        <p class="mb-0">Your payment will be processed securely by ABA Bank. We do not store your card details.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require base_path('views/client/partials/footer.php') ?>