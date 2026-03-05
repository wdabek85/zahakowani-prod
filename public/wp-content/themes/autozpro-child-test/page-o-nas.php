<?php
/**
 * Template for the "O nas" (About us) page — slug: o-nas.
 *
 * WordPress automatically picks this file for pages with slug "o-nas".
 * We close .col-full opened by header.php so the page can go full-width,
 * then reopen it before footer.php which expects to close it.
 *
 * @package autozpro-child-test
 */

get_header(); ?>

<?php // Close .col-full opened by header.php ?>
</div><!-- .col-full -->

<?php get_template_part( 'template-parts/page/about-page' ); ?>

<?php // Reopen .col-full for footer.php to close ?>
<div class="col-full">

<?php get_footer(); ?>
