<form action="/user/profile" method="POST" class="clearfix" id="particularsform">
    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
    <div class="form-group">
        <label for="first_name"><?php echo lang('WALLET_PARTICULARS_FIRST_NAME'); ?></label>
        <input type="text" class="form-control" name="first_name" id="first_name" value="<?php echo $user['first_name']; ?>" placeholder="<?php echo lang('WALLET_PARTICULARS_FIRST_NAME'); ?>"<?php echo (isset($required) ? ' required="required"' : ''); ?>>
    </div>
    <div class="form-group">
        <label for="last_name"><?php echo lang('WALLET_PARTICULARS_LAST_NAME'); ?></label>
        <input type="text" class="form-control" name="last_name" id="last_name" value="<?php echo $user['last_name']; ?>" placeholder="<?php echo lang('WALLET_PARTICULARS_LAST_NAME'); ?>"<?php echo (isset($required) ? ' required="required"' : ''); ?>>
    </div>
    <div class="form-group">
        <label for="address_1"><?php echo lang('WALLET_PARTICULARS_ADDRESS_1'); ?></label>
        <input type="text" class="form-control" name="address_1" id="address_1" value="<?php echo $user['address_1']; ?>" placeholder="<?php echo lang('WALLET_PARTICULARS_ADDRESS_1'); ?>"<?php echo (isset($required) ? ' required="required"' : ''); ?>>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="address_2" id="address_2" value="<?php echo $user['address_2']; ?>" placeholder="<?php echo lang('WALLET_PARTICULARS_ADDRESS_2'); ?>">
    </div>
    <div class="form-group">
        <label for="zip_code"><?php echo lang('WALLET_PARTICULARS_ZIP_CODE'); ?></label>
        <input type="text" class="form-control" name="zip_code" id="zip_code" value="<?php echo $user['zip_code']; ?>" placeholder="<?php echo lang('WALLET_PARTICULARS_ZIP_CODE'); ?>"<?php echo (isset($required) ? ' required="required"' : ''); ?>>
    </div>
    <div class="form-group">
        <label for="city"><?php echo lang('WALLET_PARTICULARS_CITY'); ?></label>
        <input type="text" class="form-control" name="city" id="city" value="<?php echo $user['city']; ?>" placeholder="<?php echo lang('WALLET_PARTICULARS_CITY'); ?>"<?php echo (isset($required) ? ' required="required"' : ''); ?>>
    </div>
    <div class="form-group">
        <label for="state"><?php echo lang('WALLET_PARTICULARS_STATE'); ?></label>
        <input type="text" class="form-control" name="state" id="state" value="<?php echo $user['state']; ?>" placeholder="<?php echo lang('WALLET_PARTICULARS_STATE'); ?>">
    </div>
    <div class="form-group">
        <label for="country"><?php echo lang('WALLET_PARTICULARS_COUNTRY'); ?></label>
        <select class="form-control" name="country" id="country"<?php echo (isset($required) ? ' required="required"' : ''); ?>>
        <?php include __DIR__ . '/country_options_list.php'; ?>
    </select>
    </div>
    <div class="form-group">
        <label for="email"><?php echo lang('WALLET_PARTICULARS_EMAIL'); ?></label>
        <input type="email" class="form-control" name="email" id="email" value="<?php echo $user['email']; ?>" placeholder="<?php echo lang('WALLET_PARTICULARS_EMAIL'); ?>"<?php echo (isset($required) ? ' required="required"' : ''); ?>>
    </div>
    <div class="form-group">
        <label for="phone_number"><?php echo lang('WALLET_PARTICULARS_PHONE_NUMBER'); ?></label>
        <input type="phone_number" class="form-control" name="phone_number" id="phone_number" value="<?php echo $user['phone_number']; ?>" placeholder="<?php echo lang('WALLET_PARTICULARS_PHONE_NUMBER'); ?>"<?php echo (isset($required) ? ' required="required"' : ''); ?>>
    </div>
    <button type="submit" class="btn btn-default btn-updateprofile"><?php echo lang('WALLET_PARTICULARS_UPDATE_PROFILE'); ?></button>
</form>