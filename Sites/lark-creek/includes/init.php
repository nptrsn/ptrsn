<?php
/**
 * File:        init.php
 * Desc:        Initializes the theme includes
 * Author:      Lion Associates
 * Author URI:  http://www.lionassociates.com
 */


class LACore {

  /**
   * Tracks if the theme is in developer mode
   * @var boolean
   */
  public $dev = false;

  /**
   * An array of files or directories to ignore when including files
   * @var array
   */
  private $ignores = array();

  /**
   * Handles the theme core initialization
   * @author bturley
   * @param  string/array $ignores  A list of files to ignore when including
   *                                php files.  If a string, the file names
   *                                separated with "&".
   */
  public function __construct( $ignores = array() ) {
    // Set up inclides
    $this->ignores   = is_string( $ignores ) ? explode( '&', $ignores ) : $ignores;
    $this->ignores[] = __FILE__;
    $this->_include_files();
  }


  /**
   * Recursively finds all the files in a given directory tree
   * Largely from: http://www.php.net/manual/en/function.glob.php#87221
   * @return array  The array of files
   */
  private function _rglob( $pattern = '*', $flags = 0, $path = '' ) {
    $paths = glob( $path . '*', GLOB_MARK | GLOB_ONLYDIR | GLOB_NOSORT );
    $files = glob( $path . $pattern, $flags );
    $files = $files ? $files : array();

    foreach ( $paths as $path ) {
      $info = pathinfo( $path );
      if ( ! in_array( $path , $this->ignores ) && 
           ! in_array( $info['basename'], $this->ignores ) ) {
        $files = array_merge( $files, $this->_rglob( $pattern, $flags, $path ) ); 
      }
    }
    return $files;
  }


  /**
   * Includes all files with the base theme core directory
   * @author bturley
   */
  private function _include_files() {
    $files = $this->_rglob( '*.php', 0, dirname( __FILE__ ) );
    foreach( $files as $file ) {
      $info = pathinfo( $file );
      if ( ! in_array( $file , $this->ignores ) && 
           ! in_array( $info['basename'], $this->ignores ) ) {
        include $file;
      }
    }
  }
}

?>