    <div class="row" style="max-width: 600px; float: none; margin: auto;">
        <section class="col-md-12" id="knowYourCustomerSection">
            <h1><?php echo lang('FORM_KYC_HEADLINE'); ?></h1>
            <p><?php echo lang('FORM_KYC_EXPLANATION'); ?></p>
            <br>
            <?php include __DIR__ . '/parts/particulars_form.php'; ?>
            <a href="/kyc/skip" class="btn btn-default" style="float: right; margin-top: 20px;"><?php echo lang('FORM_KYC_SKIP'); ?></a>
            </div>
        </section>