<?php
/**
 * Author box — displayed below blog post content.
 *
 * Shows avatar, display name, and biographical info.
 *
 * @package autozpro-child-test
 */

$author_id = get_the_author_meta( 'ID' );
$name      = get_the_author();
$bio       = get_the_author_meta( 'description', $author_id );
?>
<div class="author-box">
    <div class="author-box__avatar">
        <?php echo get_avatar( $author_id, 80 ); ?>
    </div>
    <div class="author-box__info">
        <p class="author-box__name"><?php echo esc_html( $name ); ?></p>
        <?php if ( $bio ) : ?>
            <p class="author-box__bio"><?php echo esc_html( $bio ); ?></p>
        <?php endif; ?>
    </div>
</div>
