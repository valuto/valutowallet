
    <?php
    if (\Models\Flash::has('error')) {
        echo "<p style='font-weight: bold; color: red; text-align: center; margin-bottom: 30px;'>" . \Models\Flash::showOnce('error') . "</p>";
    }
    ?>

    <section class="col-md-6 col-sm-12" id="loginSection">

        <h1><?php echo lang('FORM_LOGIN'); ?></h1>
        <form action="/auth/login" method="POST" class="clearfix">
            <div class="form-group">
                <label for="loginUsername"><?php echo lang('FORM_USER'); ?></label>
                <input type="text" name="username" class="form-control" id="loginUsername" placeholder="<?php echo lang('FORM_USER'); ?>">
            </div>
            <div class="form-group">
                <label for="loginPassword"><?php echo lang('FORM_PASS'); ?></label>
                <input type="password" name="password" class="form-control" id="loginPassword" placeholder="<?php echo lang('FORM_PASS'); ?>">
            </div>
            <div class="form-group">
                <label for="login2fa"><?php echo lang('FORM_2FA'); ?></label>
                <input type="text" name="auth" class="form-control" id="login2fa" placeholder="<?php echo lang('FORM_2FA_PLACEHOLDER'); ?>" aria-describedby="2faHelp">
                <small id="2faHelp" class="form-text text-muted"><?php echo lang('FORM_2FA_HELP'); ?></small>
            </div>
            <button type="submit" class="btn btn-default btn-login"><?php echo lang('FORM_LOGIN'); ?></button>
        </form>
    </section>

    <section class="col-md-offset-1 col-md-5 col-sm-12" id="signupSection">
        <h1><?php echo lang('FORM_CREATE'); ?></h1>
        <form action="auth/register" method="POST" class="clearfix">
            <div class="form-group">
                <label for="signupUsername"><?php echo lang('FORM_USER'); ?></label>
                <input type="text" name="username" class="form-control" id="signupUsername" placeholder="<?php echo lang('FORM_USER'); ?>">
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
            <button type="submit" class="btn btn-default btn-signup"><?php echo lang('FORM_SIGNUP'); ?></button>
        </form>
    </section>
