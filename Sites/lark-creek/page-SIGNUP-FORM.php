<?php
/**
 * Template Name: Signup Form Page
 * Author:     Lion Associates and David Weinfeld
 * Author URI: http://www.lionassociates.com
 */

get_header();

$meta        = get_post_meta( $post->ID, 'page_metabox', true );
$show_subnav = isset( $meta['show_subnav'] ) && $meta['show_subnav'];

$currentsite = home_url();
$currentsite = preg_replace( '/\/$/', '', $currentsite );

if ( $currentsite == 'http://www.larkcreekkitchen.com' )
  $propertySite = 'Lark Creek Kitchen-San Jose';
elseif ( $currentsite == 'http://lafayette.yankeepier.com' )
  $propertySite = 'Yankee Pier-Lafayette';
elseif ( $currentsite == 'http://www.larkcreekwalnutcreek.com' )
  $propertySite = 'Lark Creek-Walnut Creek';
elseif ( $currentsite == 'http://www.cupolasf.com' )
  $propertySite = 'Cupola-San Francisco';
else
  $propertySite = 'Lark Creek Steak-San Francisco';

$recaptcha  = get_post_meta( $post->ID, 'signup_form', true );
$publickey  = isset( $recaptcha['public_key'] )  ? $recaptcha['public_key']  : '';
$privatekey = isset( $recaptcha['private_key'] ) ? $recaptcha['private_key'] : '';

require_once dirname(__FILE__) . "/includes/recaptchalib.php"; ?>

<section id="page" class="container page-container content">

  <?php get_sidebar(); ?>

  <!-- /\/\/\/\/\/\/\/\/\/\ CONTAINER RIGHT /\/\/\/\/\/\/\/\/\/\ -->
  <div class="container-right">
    <div class="content-right"><?php
      # the response from reCAPTCHA
      $resp = null;
      # the error code from reCAPTCHA, if any
      $error = null;

      // function to validate email address
      function IsValidEmail($input) {
        return !! filter_var( $input, FILTER_VALIDATE_EMAIL );
      }

      $txtEmail = $message = "";

      // edit record
      if ( isset( $_POST['btnSubmit'] ) ) {
        if ( isset( $_POST['txtEmail'] ) )
          $txtEmail = trim( $_POST['txtEmail'] );

        if ( trim( $txtEmail ) == "" )
          $message = $message . "Please enter valid email address.<br />";
        else if ( ! IsValidEmail( trim( $txtEmail ) ) )
          $message = $message . "Please enter valid email address.<br />";

        # was there a reCAPTCHA response?
        if ( $_POST["recaptcha_response_field"] ) {
          $resp = recaptcha_check_answer( $privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"] );

          if ( ! $resp->is_valid ) {
            # set the error code so that we can display it
            $error = $resp->error;
            //$message = $message . $resp->error . "<br />";
            $message = $message . "Please enter valid image verification code.<br />";
          }
        }

        if ( $message == "" ) {
          $location = "http://www.moana-news.com/data/write_email_subscription_lark_creek.php?email=$txtEmail&property=$propertySite&source=website";
          header( "Location: $location" );
          exit();
        }
      } ?>

      <script type="text/javascript">
        var RecaptchaOptions = { theme : 'white' };
      </script>

      <script language="javascript" type="text/javascript">
        // function to trim the input
        function Trim( str, chr ) {
          var rgxtrim = (!chr) ? new RegExp('^\\s+|\\s+$', 'g') : new RegExp('^'+chr+'+|'+chr+'+$', 'g');
          return str.replace(rgxtrim, '');
        }

        // function to check valid email
        function IsValidEmail( input ) {
          var atpos  = input.indexOf("@");
          var dotpos = input.lastIndexOf(".");
          if ( atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= input.length )
            return false;
          return true;
        }

        function IsFormValidated() {
          var isValidated = true;
          var message = "";
          var email = Trim( document.getElementById( "txtEmail" ).value );
          var captcha = Trim( document.getElementById( "recaptcha_response_field" ).value );

          if ( email.length == 0 )
            message = message + "Please enter email.\n";
          else if ( ! IsValidEmail( email ) )
            message = message + "Please enter valid email.\n";

          if (captcha.length == 0)
            message = message + "Please enter image verification code.\n";

          if ( message != "" ) {
            message = "Please correct the following error(s):\n========================\n" + message;
            isValidated = false;
            // alert(message);
          }

          return isValidated;
        }
      </script>

      <style type="text/css">
        #btnSubmit {
          font-size: 20px;
          font-weight: bold;
          padding: 10px 0;
          width: 100%;
          background: #fbdd6b; /* Old browsers */
          background: -moz-linear-gradient(top,  #fbdd6b 0%, #f2c546 37%, #ebac1d 99%); /* FF3.6+ */
          background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#fbdd6b), color-stop(37%,#f2c546), color-stop(99%,#ebac1d)); /* Chrome,Safari4+ */
          background: -webkit-linear-gradient(top,  #fbdd6b 0%,#f2c546 37%,#ebac1d 99%); /* Chrome10+,Safari5.1+ */
          background: -o-linear-gradient(top,  #fbdd6b 0%,#f2c546 37%,#ebac1d 99%); /* Opera 11.10+ */
          background: -ms-linear-gradient(top,  #fbdd6b 0%,#f2c546 37%,#ebac1d 99%); /* IE10+ */
          background: linear-gradient(to bottom,  #fbdd6b 0%,#f2c546 37%,#ebac1d 99%); /* W3C */
          filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fbdd6b', endColorstr='#ebac1d',GradientType=0 ); /* IE6-9 */
          border:#c8a228 solid 1px;
          -moz-border-radius: 5px;
          border-radius: 5px;
          color:#614b00;
          cursor:pointer;
        }
      </style>

      <h3>Newsletter Sign Up</h3>

      <form action="" method="post">
        <?php if ( $message != "" ) : ?>
          <div style="border: 1px solid #CCCCCC; background-color: #FFFF99; color: #008000; padding: 10px; margin-bottom:10px;"><?php echo $message; ?></div>
        <?php endif; ?>
        Email Address:<br />
        <input type="text" style="width:90%; padding:5px 10px; margin-bottom:15px;" id="txtEmail" name="txtEmail" value="<?php echo $txtEmail; ?>" />
        <br />
        <?php echo recaptcha_get_html( $publickey, $error ); ?>
        <br />
        <input type="submit" id="btnSubmit" name="btnSubmit" value="Submit" onclick="javascript:return IsFormValidated();" />
      </form>

    </div><!-- / .content-right -->
  </div><!-- / .container-right -->

</section><!-- / #page .content -->

<?php get_footer(); ?>
