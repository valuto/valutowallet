<div class="row" style="max-width: 600px; float: none; margin: auto;">
    <section class="col-md-12" id="knowYourCustomerSection">

        <h1><?php echo lang('FORM_KYC_HEADLINE'); ?></h1>
        <p><?php echo lang('FORM_KYC_EXPLANATION'); ?></p>

        <?php if ($blocked): ?>
            <p><strong><?php echo lang('FORM_KYC_USER_BLOCKED'); ?></strong></p>
        <?php endif; ?>

        <p id="particularsmsg"></p>

        <br>
        <?php include __DIR__ . '/parts/particulars_form.php'; ?>
        
        <?php if ( ! $blocked): ?>

        <form action="/kyc/skip" method="POST">
            <button type="submit" class="btn btn-default skip-kyc-btn" style="float: right; margin-top: 20px;"><?php echo lang('FORM_KYC_SKIP'); ?></button>
        </form>
        <?php endif; ?>

    </section>
</div>

<script type="text/javascript">

    /**
     * Check KYC status for currently logged in user.
     * 
     * @returns void
     */
    var checkKycStatus = function()
    {
        $.ajax({
            url: '/kyc/status',
            type: 'GET',
            success: function(data, textStatus, jqXHR)
            {        
                var json = $.parseJSON(data);

                if (typeof json.verified !== 'undefined' && json.verified === 1) {
                    window.location.href = '/';
                } else {
                    showError('Your user is not yet KYC verified. Please fill out all fields with valid information. Contact info@valuto.io only if the problem persists.');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) 
            {
                alert('Something went wrong. Please try again or contact info@valuto.io.');
            }
        });
    };

    /**
     * Update profile.
     * 
     * @param array postData
     * @param string formURL
     * @returns void
     */
    var updateProfile = function(postData, formURL)
    {
        $.ajax({
            url : formURL,
            type: 'PUT',
            data : postData,
            success: function(data, textStatus, jqXHR) 
            {
                var json = $.parseJSON(data);

                if (typeof json.status !== 'undefined' && json.status === 'success') {
                    checkKycStatus();
                } else {
                    showError(json.message);
                }

                if (json.newtoken) {
                    $('input[name="token"]').val(json.newtoken);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) 
            {
                alert('Something went wrong. Please try again or contact info@valuto.io.');
            }
        });

    };

    /**
     * Show error message.
     * 
     * @param string message
     * @returns void
     */
    var showError = function(message)
    {
        $("#particularsmsg").text(message);
        $("#particularsmsg").css("color", "red");
        $("#particularsmsg").show();
        $([document.documentElement, document.body]).animate({
            scrollTop: $("#knowYourCustomerSection").offset().top
        }, 500);
    };

    $(document).ready(function() {

        /**
         * Update particulars form.
         *
         * @return void
         */
        $(document).on('submit', '#particularsform', function(e) {

            var postData = $(this).serializeArray();
            var formURL = $(this).attr('action');

            updateProfile(postData, formURL);

            e.preventDefault();

        });

    });

</script>