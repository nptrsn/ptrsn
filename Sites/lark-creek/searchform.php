<?php
/**
 * File:       searchform.php
 * Desc:       Site search template
 * Author:     Lion Associates
 * Author URI: http://www.lionassociates.com
 */
?>

<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>" class="searchform">
  <label class="is-hidden" for="s"><?php _e( 'Search for:', 'la' ); ?></label>
  <input type="search" value="" name="s" id="s" placeholder="<?php _e( 'search', 'la' ); ?>&hellip;" />
  <input type="submit" id="searchsubmit" value="<?php _e( 'Search', 'la' ); ?>" />
</form><!-- / #searchform -->