<?php
    $store_id = isset($store_id) ? $store_id : 0;
?>

<div id="page_id" class="container" data-module="<?php echo $page_id; ?>" data-store-id="<?php echo $store_id; ?>">
    <div class="header clearfix">
        <nav>
            <ul class="nav nav-pills pull-right">
                <?php 

                    // we need to know which page we're on
                    $page = uri_string();

                    /* Links are defined in application/config.php */
                    foreach ($links as $linkText => $link) {

                        $class = $page === $link || ($page === '' && $link === '') ? 'active' : '';

                        echo '<li role="presentation" class="' . $class . '"><a href="' . base_url() . $link . '">' . $linkText . '</a></li>';
                    }
                ?>
            </ul>
        </nav>
        <h3 class="text-muted"><a href="/" class="homeLink">Movie Store Finder</a></h3>
    </div>
</div>