<?php
if ($error && !empty($error['message'])) {
    echo "<p style='font-weight: bold; color: red;'>" . $error['message']; "</p>";
}
?>

<?php if(\Models\Flash::has('promptupdatepw')): ?>
<section class="col-md-12" id="walletMessage" style="margin-bottom: 50px !important; background-color: #f29400; color: #fff;">
    <strong>Notice</strong>&nbsp;&nbsp;Please update your password from the "Account" tab
</section>
<?php endif; ?>

<?php
if ($admin)
{
  ?>
<p><strong>Admin Links:</strong></p>
  <a href="/admin" class="btn btn-default">Admin Dashboard</a>

<br />
<br />
<p><strong><?php echo lang('WALLET_USERLINKS'); ?></strong></p>
  <?php
}
?>
<div id="vueapp">

  <section class="col-md-12" id="walletOverview" v-show="showtab === 'wallet'">
    <h1><button type="button" class="btn btn-link" style="float: right;" id="donate">Donate to <?=config('app', 'fullname')?>wallet's owner!</button><?php echo lang('WALLET_OVERVIEW_HEADLINE'); ?></h1>

    <div class="row">
    <div class="col-md-4">
      <p><?php echo lang('WALLET_HELLO'); ?>, <strong><?php echo $_SESSION['user_session']; ?></strong>!  <?php if ($admin) {?><strong><font color="red">[Admin]</font><?php }?></strong></p>
      <p><?php echo lang('WALLET_BALANCE'); ?> <strong id="balance"><?php echo satoshitize($balance); ?></strong> <?=config('app', 'short')?></p>
    </div>

    <!-- Send funds -->  
    <div class="col-md-8" id="walletSend">
    <p><strong><?php echo lang('WALLET_SEND'); ?></strong></p>
    <p id="donateinfo" style="display: none;">Type the amount you want to donate and click <strong>Send</strong></p>
    <p id="withdrawinfo">Type the receiver address, the amount you want to send and click <strong>Send</strong></p>
        <form action="/wallet/withdraw" method="POST" class="clearfix" id="withdrawform">
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
            <input type="text" class="form-control" name="address" id="address" placeholder="<?php echo lang('WALLET_ADDRESS'); ?>">
            <input type="text" class="form-control" name="amount" id="amount" placeholder="<?php echo lang('WALLET_AMOUNT_VLU'); ?>">
            <button type="submit" class="btn btn-default" style="margin-top: 10px;"><?php echo lang('WALLET_SENDCONF'); ?></button>
        </form>
        <p id="withdrawmsg"></p>
      </div>
    </div>

  </section>

  <section class="col-md-12" id="walletTransactions" v-show="showtab === 'wallet'">
    <h1><?php echo lang('WALLET_TRANSACTIONS_HEADLINE'); ?></h1>

    <!-- Your addresses -->
    <div style="overflow: hidden;">
      <p><strong><?php echo lang('WALLET_USERADDRESSES'); ?></strong></p>
      <p id="newaddressmsg"></p>
      <table class="table table-bordered table-striped" id="alist">
      <thead>
      <tr>
      <td><?php echo lang('WALLET_ADDRESS'); ?>:</td>
      <td><?php echo lang('WALLET_QRCODE'); ?>:</td>
      </tr>
      </thead>
      <tbody>
      <?php
      foreach ($addressList as $address) {
        echo "<tr><td>".$address."</t>";?>
        <td><a href="/qrcode/?address=<?php echo $address;?>">
          <img src="/qrcode/?address=<?php echo $address;?>" alt="QR Code" style="width:42px;height:42px;border:0;"></td><tr>
        <?php
      }
      ?>
      </tbody>
      </table>
      <form action="/wallet/newaddress" method="POST" id="newaddressform">
        <button type="submit" class="btn btn-default"><?php echo lang('WALLET_NEWADDRESS'); ?></button>
      </form>
    </div>

    <!-- Last 10 transactions -->
    <p><strong><?php echo lang('WALLET_LAST10'); ?></strong></p>
    <table class="table table-bordered table-striped" id="txlist">
    <thead>
       <tr>
          <td nowrap><?php echo lang('WALLET_DATE'); ?></td>
          <td nowrap><?php echo lang('WALLET_ADDRESS'); ?></td>
          <td nowrap><?php echo lang('WALLET_TYPE'); ?></td>
          <td nowrap><?php echo lang('WALLET_AMOUNT'); ?></td>
          <td nowrap><?php echo lang('WALLET_FEE'); ?></td>
          <td nowrap><?php echo lang('WALLET_CONFS'); ?></td>
          <td nowrap><?php echo lang('WALLET_INFO'); ?></td>
       </tr>
    </thead>
    <tbody>
       <?php
       $bold_txxs = "";
       foreach((array)$transactionList as $transaction) {
          if($transaction['category']=="send") { $tx_type = '<b style="color: #FF0000;">Sent</b>'; } else { $tx_type = '<b style="color: #01DF01;">Received</b>'; }
          echo '<tr>
                   <td>'.date('n/j/Y h:i a',$transaction['time']).'</td>
                   <td>'.$transaction['address'].'</td>
                   <td>'.$tx_type.'</td>
                   <td>'.abs($transaction['amount']).'</td>
                   <td>'.$transaction['fee'].'</td>
                   <td>'.$transaction['confirmations'].'</td>
                   <td><a href="' . config('app', 'blockchain_url'),  $transaction['txid'] . '" target="_blank">Info</a></td>
                </tr>';
       }
       ?>
       </tbody>
    </table>

  </section>

  <section class="col-md-12" id="walletAccount" v-show="showtab === 'account'">
    <h1><?php echo lang('WALLET_ACCOUNT_HEADLINE'); ?></h1>
    <div class="row">
      <div class="col-md-4">
        <p id="support-msg" style="display: none">
          Please contact support via email at <?= config('app', 'support') ?>
          <br />Support Key: <?= $_SESSION['user_supportpin'] ?>
        </p>

        <?php if (!$twofactorenabled): ?>
        <form action="/auth/twofactorauth" method="POST" class="twofactor" id="enabletwofactorform">
          <button type="submit" class="btn btn-default"><?php echo lang('WALLET_2FAON'); ?></button>
        </form>
        <?php endif; ?>
        
        <?php if ($twofactorenabled): ?>
        <form action="/auth/twofactorauth" method="DELETE" class="twofactor" id="disabletwofactorform">
          <button type="submit" class="btn btn-default"><?php echo lang('WALLET_2FAOFF'); ?></button>
        </form>
        <?php endif; ?>

        <form action="#" method="POST" id="support-form">
          <button type="submit" class="btn btn-default"><?php echo lang('WALLET_SUPPORT'); ?></button>
        </form>

      </div>
      <div class="col-md-6">

        <p id="twofactor-msg"></p>

        <div id="verifytwofactor">

            <h3>Step 1: Secret Key</h3>
            <p style="font-weight: bold;" id="2factorauth-secret"></p>
            <p style='color: red; font-weight: bold;'>* Please write this down and keep in a secure area *</p><br><br>

            <h3>Step 2: Authenticator</h3>
            <img src="" id="2factorauth-qrcode" /><br><br>
            <p>Please scan this with the Google Authenticator app on your mobile phone. This page will clear on refresh, please be careful.</p><br><br>

            <h3><?php echo lang('WALLET_2FAVERIFY_HEADLINE'); ?></h3>
            <form action="/auth/twofactorauth" method="PUT" id="verifytwofactorform">
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                <p><?php echo lang('WALLET_2FAVERIFY_DESC'); ?></p>
                <p id="verifytwofactor-msg"></p>
                <div class="form-group">
                    <input type="checkbox" required="required" name="accept_secure_key" id="verifytwofactoraccept" placeholder="">
                    <label for="verifytwofactoraccept"><?php echo lang('WALLET_2FACODE_ACCEPT_SECURE_KEY'); ?></label>
                </div>
                <div class="form-group">
                    <label for="verifytwofactorcode"><?php echo lang('WALLET_2FACODE'); ?></label>
                    <input type="text" class="form-control" name="code" id="verifytwofactorcode" placeholder="<?php echo lang('WALLET_2FACODE'); ?>">
                </div>
                <button type="button" class="btn btn-default btn-half-width" id="abortverifybtn"><?php echo lang('WALLET_2FAVERIFY_ABORT'); ?></button>
                <button type="submit" class="btn btn-default btn-half-width" style="color: green"><?php echo lang('WALLET_2FAVERIFY'); ?></button>
            </form>
        </div>

      </div>
    </div>
  </section>

  <section class="col-md-12" id="walletPassword" v-show="showtab === 'account'">
    <h1><?php echo lang('WALLET_PASSUPDATE'); ?></h1>
    <div class="row">
      <div class="col-md-4">
        <form action="/auth/password" method="POST" class="clearfix" id="pwdform">
            <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
            <div class="form-group">
                <label for="oldpassword"><?php echo lang('WALLET_PASSUPDATEOLD'); ?></label>
                <input type="password" class="form-control" name="oldpassword" id="oldpassword" placeholder="<?php echo lang('WALLET_PASSUPDATEOLD'); ?>">
            </div>
            <div class="form-group">
                <label for="newpassword"><?php echo lang('WALLET_PASSUPDATENEW'); ?></label>
                <input type="password" class="form-control" name="newpassword" id="newpassword" placeholder="<?php echo lang('WALLET_PASSUPDATENEW'); ?>">
            </div>
            <div class="form-group">
                <label for="confirmpassword"><?php echo lang('WALLET_PASSUPDATENEWCONF'); ?></label>
                <input type="password" class="form-control" name="confirmpassword" id="confirmpassword" placeholder="<?php echo lang('WALLET_PASSUPDATENEWCONF'); ?>">
            </div>
            <button type="submit" class="btn btn-default btn-updatepw"><?php echo lang('WALLET_PASSUPDATECONF'); ?></button>
        </form>
      </div>
      <div class="col-md-4">
        <p id="pwdmsg"></p>
        <p style="font-size:1em;"><?php echo lang('WALLET_SUPPORTNOTE'); ?></p>
      </div>
    </div>
  </section>

</div>

<script type="text/javascript">
var blockchain_url = "<?=config('app', 'blockchain_url')?>";
$(document).on('click', '#donate', function (e){
  $("#donateinfo").show();
  $("#withdrawinfo").hide();
  $("#withdrawform input[name='address']").val("<?=config('app', 'donation_address')?>");
  $("#withdrawform input[name='amount']").val("0.01");
});
$(document).on('submit', '#withdrawform', function(e) {

    var postData = $(this).serializeArray();
    var formURL  = $(this).attr("action");

    var address = $(this).find('[name="address"]').val();
    var amount  = $(this).find('[name="amount"]').val();

    if (!confirm('Are you sure, you want to send ' + amount + ' VLU to "' + address + '"?\n\nThis action cannot be undone.')) {
        e.preventDefault;
        return false;
    }

    $.ajax({
        url : formURL,
        type: "POST",
        data : postData,
        success:function(data, textStatus, jqXHR) 
        {
            var json = $.parseJSON(data);
            if (json.success) {
              $("#withdrawform input.form-control").val("");
            	$("#withdrawmsg").text(json.message);
            	$("#withdrawmsg").css("color", "green");
            	$("#withdrawmsg").show();
            	updateTables(json);
            } else {
            	$("#withdrawmsg").text(json.message);
            	$("#withdrawmsg").css("color", "red");
            	$("#withdrawmsg").show();
            }
            if (json.newtoken) {
                $('input[name="token"]').val(json.newtoken);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
            //ugh, gtfo    
        }
    });
    e.preventDefault();
});
$(document).on('submit', '#newaddressform', function(e) {
    $.ajax({
        url : $(this).attr('action'),
        type: "POST",
        data : $(this).serializeArray(),
        success:function(data, textStatus, jqXHR) {
            console.log('newaddressform success', data);
            var json = $.parseJSON(data);
            if (json.success)
            {
            	$("#newaddressmsg").text(json.message);
            	$("#newaddressmsg").css("color", "green");
            	$("#newaddressmsg").show();
            	updateTables(json);
            } else {
            	$("#newaddressmsg").text(json.message);
            	$("#newaddressmsg").css("color", "red");
            	$("#newaddressmsg").show();
            }
            if (json.newtoken) {
                $('input[name="token"]').val(json.newtoken);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
            //ugh, gtfo    
        }
    });
    e.preventDefault();
});
$(document).on('submit', '#pwdform', function(e)
{
    var postData = $(this).serializeArray();
    var formURL = $(this).attr("action");
    $.ajax(
    {
        url : formURL,
        type: "PUT",
        data : postData,
        success:function(data, textStatus, jqXHR) 
        {
            var json = $.parseJSON(data);
            if (json.success)
            {
               $("#pwdform input.form-control").val("");
               $("#pwdmsg").text(json.message);
               $("#pwdmsg").css("color", "green");
               $("#pwdmsg").show();
            } else {
               $("#pwdmsg").text(json.message);
               $("#pwdmsg").css("color", "red");
               $("#pwdmsg").show();
            }
            if (json.newtoken) {
                $('input[name="token"]').val(json.newtoken);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
            //ugh, gtfo    
        }
    });
    e.preventDefault();
});

$(document).on('submit', '#support-form', function(e) {
  e.preventDefault();

  $('#support-msg').show(200);

});

$(document).on('submit', 'form.twofactor', function(e) {
  e.preventDefault();

  var $form = $(this);

  $.ajax({
      url : $form.attr("action"),
      type: $form.attr("method"),
      data: $form.serializeArray(),
      success: function(data, textStatus, jqXHR) {
        if ($form.is('#enabletwofactorform')) {
            var json = $.parseJSON(data);
            $('#2factorauth-secret').text(json.secret); 
            $('#2factorauth-qrcode').attr('src', json.qrcode); 
            $('#twofactor-msg').html('');
            $('#verifytwofactor').fadeIn();
        } else {
            $('#verifytwofactor').hide();
            $('#twofactor-msg').html(data);
        }
      }
  });

});

$(document).on('click', '#abortverifybtn', function(e)
{
    $('#disabletwofactorform').submit();
});

$(document).on('submit', '#verifytwofactorform', function(e)
{
    console.log('verify two factor');

    var postData = $(this).serializeArray();
    var formURL = $(this).attr("action");

    $.ajax({
        url : formURL,
        type: "PUT",
        data : postData,
        success: function(data, textStatus, jqXHR) 
        {
            var message;
            var json = $.parseJSON(data);

            if (json.success) {

                $('#verifytwofactor-msg').text("<?php echo lang('WALLET_2FAVERIFY_SUCCESS'); ?>");
                $('#verifytwofactor-msg').css('color', 'green').show();
                setTimeout(function() {
                    window.location.reload();
                }, 2000);

            } else {

                switch (json.error) {
                    case 'VERIFY_NOT_SETUP':
                        message = "<?php echo lang('WALLET_2FAVERIFY_ERROR_NOT_SETUP'); ?>";
                        break;
                    case 'VERIFY_INVALID_CODE':
                        message = "<?php echo lang('WALLET_2FAVERIFY_ERROR_INVALID'); ?>";
                        break;
                    case 'VERIFY_INVALID_INPUT':
                        message = "<?php echo lang('WALLET_2FAVERIFY_ERROR_INPUT'); ?>";
                        break;
                    default:
                        message = 'An error occurred.';
                }
                $('#verifytwofactor-msg').text(message);
                $('#verifytwofactor-msg').css('color', 'red').show();
                $('#verifytwofactorcode').val('');
            }

            if (json.newtoken) {
                $('input[name="token"]').val(json.newtoken);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
            console.log('error', textStatus, errorThrown);
            $('#verifytwofactor-msg').text('An error occurred. Please make sure your session is still active.');
            $('#verifytwofactor-msg').show();
        }
    });

    e.preventDefault();
});

function updateTables(json)
{
  console.log('updateTables', json);
	$("#balance").text(json.balance.toFixed(8));
	$("#alist tbody tr").remove();
	for (var i = json.addressList.length - 1; i >= 0; i--) {
		$("#alist tbody").prepend("<tr><td>" + json.addressList[i] + "</td></tr>");
	}
	$("#txlist tbody tr").remove();

  if (json.transactionList) {

  	for (var i = json.transactionList.length - 1; i >= 0; i--) {
  		var tx_type = '<b style="color: #01DF01;">Received</b>';
  		if(json.transactionList[i]['category']=="send")
  		{
  			tx_type = '<b style="color: #FF0000;">Sent</b>';
  		}
  		$("#txlist tbody").prepend('<tr> \
                 <td>' + moment(json.transactionList[i]['time'], "X").format('l hh:mm a') + '</td> \
                 <td>' + json.transactionList[i]['address'] + '</td> \
                 <td>' + tx_type + '</td> \
                 <td>' + Math.abs(json.transactionList[i]['amount']) + '</td> \
                 <td>' + json.transactionList[i]['fee'] + '</td> \
                 <td>' + json.transactionList[i]['confirmations'] + '</td> \
                 <td><a href="' + blockchain_url.replace("%s", json.transactionList[i]['txid']) + '" target="_blank">Info</a></td> \
              </tr>');
    }
	}
}

</script>
