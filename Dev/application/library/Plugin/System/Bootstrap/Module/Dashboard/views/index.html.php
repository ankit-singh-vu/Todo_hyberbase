<?php
  if(!file_exists($dashLayout)) {
?>
  <h3 style="color:#cc0000;text-align:center;margin-top:5%"> <span class="fa fa-warning"></span> Dashboard Layout Not Found !!!</h3>

      <?php if($kernel->config('app.mode.development') == true) { ?>
      <pre style="width:auto;text-align:center;line-height:17px;">
        The template "<?=$dashLayout?>" was not found.
        It is expected that the application developer would add the above given dashboard template.
        The name of the dashboard template file can be changed from application config.
      </pre>
      <?php } ?>
<?php

} else {
    include_once $dashLayout;
}