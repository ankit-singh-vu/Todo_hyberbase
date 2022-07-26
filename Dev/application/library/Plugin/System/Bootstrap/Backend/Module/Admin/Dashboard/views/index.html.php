<?php
if (!file_exists($dashLayout)) {
?>
    <div class="container">
        <div class="card shadow col-md-8 col-sm-12 mx-auto" style="margin-top:5%;">
            <div class="card-body text-center">
                <img src="/assets/img/soon.png" style="height: 80px;">
                <h2 class="mt-3" style="color:#000;text-align:center;">Dashboard is coming soon!</h2>
            </div>
        </div>
    </div>

<?php

} else {
    include_once $dashLayout;
}
