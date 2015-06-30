<?php


function _la__add_signup_form_meta() {
  if ( ! is_admin() ) return;

  global $post_id;
  if ( ! $post_id ) {
      if ( isset( $_GET['post'] ) ) $post_id = intval( $_GET['post'] );
      elseif ( isset( $_POST['post_ID'] ) ) $post_id = intval( $_POST['post_ID'] );
      else return;
  }

  $template_file = get_post_meta( $post_id, '_wp_page_template', TRUE );
  if ( 'page-SIGNUP-FORM.php' !== $template_file ) return;

  $meta    = new Metabolics( 'signup_form', 'Signup Form Options' );
  $section = $meta->add_section( 'recaptcha', 'ReCAPTCHA' );

  $section->add_field( array(
    'type'  => 'text',
    'id'    => 'public_key',
    'title' => 'Public API Key',
    'size'  => 'large',
  ) );

  $section->add_field( array(
    'type'  => 'text',
    'id'    => 'private_key',
    'title' => 'Private API Key',
    'size'  => 'large',
  ) );

}
add_action( 'admin_init', '_la__add_signup_form_meta' );