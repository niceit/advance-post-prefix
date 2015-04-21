<article id="entry-<?php the_ID(); ?>" class="post-<?php the_ID(); ?> post type-post status-publish format-standard has-post-thumbnail hentry category-php entry">
    <div class="entry-inner clearfix">
        <div class="entry-wrap-small">
            <header>
                <h2 class="entry-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title();  ?></a>
                </h2>
                <div class="clear"></div>
            </header>
            <div class="text">
                <p><?php the_excerpt(); ?></p>
            </div>
        </div><!--/entry-wrap-->
    </div><!--/entry-inner-->
</article>