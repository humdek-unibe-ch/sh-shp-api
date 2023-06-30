<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */
?>
<div class="container my-3">
    <?php $this->output_alert(); ?>
    <div class="jumbotron">
        <h1>API Token</h1>
        <p>In this page the users can generate or revoke their API token.</p>        
    </div>       
</div>
<?php $this->output_view_mode(true); ?> 
<?php $this->output_api_settings_buttons(); ?> 