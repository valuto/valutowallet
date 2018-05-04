
    <script>
        var onloadCallback = function() {
            $(".g-recaptcha").each(function() {
                var $el = $(this);
                grecaptcha.render($el.attr("id"), {
                    "sitekey" : '<?php echo config('captcha', 'site_key'); ?>',
                    "callback" : function(token) {
                        $el.parent().find(".g-recaptcha-response").val(token);
                        $el.parent().submit();
                    }
                });
            });
        };
    </script>

    <div class="row">
        <section class="col-md-12" id="choosePasswordSection">
            <h1><?php echo lang('FORM_ACTIVATION_HEADLINE'); ?></h1>
            <form action="/api/v1/user/activate" method="POST" class="clearfix" id="choosepasswordform">
                <input type="hidden" name="token" value="<?php echo $params['token']; ?>">
                <input type="hidden" name="user_id" value="<?php echo $params['user_id']; ?>">
                <div class="form-group">
                    <label for="signupUsername"><?php echo lang('FORM_USER'); ?></label>
                    <input type="text" disabled="disabled" name="username" class="form-control" value="<?php echo $user['username']; ?>">
                </div>
                <div class="form-group">
                    <label for="signupPassword"><?php echo lang('FORM_PASS'); ?></label>
                    <input type="password" name="password" class="form-control" id="signupPassword" placeholder="<?php echo lang('FORM_PASS'); ?>">
                </div>
                <div class="form-group">
                    <label for="signupPasswordConf"><?php echo lang('FORM_PASSCONF'); ?></label>
                    <input type="password" name="confirmPassword" class="form-control" id="signupPasswordConf" placeholder="<?php echo lang('FORM_PASSCONF'); ?>" aria-describedby="signupPasswordConfHelp">
                    <small id="signupPasswordConfHelp" class="form-text text-muted"><?php echo lang('FORM_PASSCONF_HELP'); ?></small>
                </div>
                <button class="g-recaptcha btn btn-default btn-signup" id="signupsubmitbtn"><?php echo lang('FORM_ACTIVATE'); ?></button>
            </form>
        </section>
    </div>