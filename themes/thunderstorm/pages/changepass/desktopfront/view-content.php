<?php
////////////////////////////////////////////////////////////////////////////////
// Part of theme Thunderstorm, of Quick Web Frame
// -- MIT Licensed. License details in LICENSE.txt file on the root folder.

?>
<div class="content">
    <form action="<?php echo qurl_l(URL_SELF); ?>" method="post" enctype="multipart/form-data">
    <table>
        <tr>
            <td>Username:</td>
            <td><?php echo $this->AUTH->GetUser(); ?></td>
        </tr>
        <tr>
            <td>Old Pass:</td>
            <td>
                <input type="password" name="hEditOldPass" value="" />
            </td>
        </tr>
        <tr>
            <td>New Pass:</td>
            <td>
                <input type="password" name="hEditNewPass" value="" />
            </td>
        </tr>
        <tr>
            <td>New Pass Again:</td>
            <td>
                <input type="password" name="hEditNewPassAgain" value="" />
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="Change" class="standard-button" />
            </td>
        </tr>
    </table>
    
    <input type="hidden" name="hValidatorChangePass" value="changepass" />
    </form>
</div>
