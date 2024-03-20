<?php

/**
 * @var Article[] $articles
 */



use DDaniel\Blog\Articles\Article;

?>
<div class="list-group">
    <?php foreach ($articles as $article) :
        ?>
        <a href="<?php echo $article->get_url() ?>" class="list-group-item list-group-item-action">
            <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-0"><?php echo $article->get_title() ?></h5>
                <small><?php echo $article->get_created_time() ?></small>
            </div>
        <?php
//            <p class="mb-1">Some placeholder content in a paragraph.</p>
//            <small>And some small print.</small>
        ?>
        </a>
        <?php
    endforeach; ?>
</div>
