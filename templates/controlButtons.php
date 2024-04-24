<?php
/**
 * @var \DDaniel\Blog\PageControllers\PageController $pc
 */

use DDaniel\Blog\PageControllers\PageController;

?>

<div class="controlButtons">
    <?php if($pc->is_home_page): ?>
        <button class="btn btn-secondary controlButtons-themeSwitch" data-theme-switch></button>
    <?php else: ?>
        <button class="btn btn-secondary controlButtons-scrollTop" style="opacity: 0" data-scroll-top></button>
    <?php endif; ?>
</div>