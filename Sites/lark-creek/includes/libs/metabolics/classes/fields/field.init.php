<?php
/**
 * Includes all files in the fields directory exluding those beginning with a '.'
 * 
 * @author Bryan Turley <bryan@lionassociates.com>
 * @copyright Copyright (c) 2013, Lion Associates
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPL2
 * 
 * @package metabolics
 * @since 2.0
 */


// Get the current directory
$dir = dirname( __FILE__ );

// Open the directory
if ( $handle = opendir( $dir ) ) {

  // Iterate over the files and include them
  while ( false !== ( $file = readdir( $handle ) ) ) {
    if ( preg_match( '/^\..*/', $file ) ) continue;
    include_once $dir . '/' . $file;
  }

} ?>