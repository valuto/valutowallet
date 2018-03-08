<?php
if (!empty($error))
{
    echo "<p style='font-weight: bold; color: red;'>" . $error['message']; "</p>";
}
if (!empty($msg))
{
    echo "<p style='font-weight: bold; color: green;'>" . $msg['message']; "</p>";
}
?>
<a href="/" class="btn btn-default">Go back to wallet</a>
<br /><br />
<p>List of all users:</p>
<table class="table table-bordered table-striped" id="userlist">
<thead>
   <tr>
      <td nowrap>Username</td>
      <td nowrap>Created</td>
      <td nowrap>Is admin?</td>
      <td nowrap>Is locked?</td>
      <td nowrap>IP</td>
      <td nowrap>Info</td>
      <td nowrap>Delete</td>
   </tr>
</thead>
<tbody>
   <?php
   foreach($userList as $user) {
      echo '<tr>
               <td>' . $user['username'] . '</td>
               <td>' . $user['date'] . '</td>
               <td>' . ($user['admin'] ? '<strong>Yes</strong> <a href="/admin?i=' . $user['id'] . '" data-method="delete">De-admin</a>' : 'No <a href="/admin?i=' . $user['id'] . '" data-method="post" onclick="alert(\'Please be aware, that you are now making a user admin.\')">Make admin</a>') . '</td>
               <td>' . ($user['locked'] ? '<strong>Yes</strong> <a href="/admin/unlock?i=' . $user['id'] . '" data-method="put">Unlock</a>' : 'No <a href="/admin/lock?i=' . $user['id'] . '" data-method="put">Lock</a>') . '</td>
               <td>' . $user['ip'] . '</td>
               <td>' . '<a href="/admin/info?i=' . $user['id'] . '">Info</a>' . '</td>';

        if (is_null($user['deleted_at'])) {
            echo '<td>' . '<a href="/admin/delete-user?i=' . $user['id'] . '" data-method="delete" data-confirm="Are you sure you really want to delete user ' . $user['username'] . ' (id=' . $user['id'] . ')?">Delete</a>' . '</td>';
        } else {
            echo '<td>User deleted</td>';
        }

       echo '</tr>';
   }
   ?>
   </tbody>
</table>
