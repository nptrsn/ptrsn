<?php
/**
 * Plugin Name: Setabolics
 * Description: Provides a simple API for creating &amp; sanitizing custom settings pages
 * Version:     1.1
 * Author:      Bryan Turley
 * Author URI:  http://www.bryanturley.com
 * License:     GPL2
 */

if ( ! class_exists( 'Setabolics' ) ) {

    class Setabolics {
        static protected $instances = 0;
        private $version = 1.1;
        private $options;
        private $pages;
        private $tabs;
        private $fields;

        /* -- Public Methods -- */

        /**
         * Create a new Setabolics object
         * @param string $menu_id     A unique ID for this settings page
         * @param string $menu_title  The title as will appear in the WP Admin Sidebar
         * @param string $page_title  The title as will appear on the top of the settings page
         * @param array  $args        An array of additional arguments
         */
        function __construct( $menu_id, $menu_title, $page_title, $args = array() ) {
            self::$instances++;

            $defaults = array(
                'icon'       => '',                     // The menu icon of the settings menu
                'capability' => 'edit_theme_options',   // The required user capability to view the settings
                'position'   => NULL,                   // The menu position within the admin sidebar
                'item_title' => $menu_title,            // The title of the first-level menu item
            );
            $args = wp_parse_args( $args, $defaults );

            $this->options = array(
                'menu_title'  => $menu_title,
                'page_title'  => $page_title,
                'menu_id'     => $menu_id,
                'icon'        => $args['icon'],
                'capability'  => $args['capability'],
                'position'    => $args['position'],
            );

            // Initialize arrays
            $this->pages = $this->tabs = $this->fields = array();

            // Add an initial page for the default view
            $this->add_page( $menu_id, $args['item_title'], $page_title );

            // Add action hooks
            add_action( 'admin_menu', array( &$this, '_add_pages' ), 1 );
            add_action( 'admin_enqueue_scripts', array( &$this, '_enqueue_scripts' ) );
            add_action( 'admin_init', array( &$this, '_register_setting' ) );
        }


        /**
         * Decrements the instance count
         */
        function __destruct() {
            self::$instances--;
        }

        /**
         * Adds a new settings subpage
         * @param string $page_id    A unique page ID
         * @param string $menu_title The title as will appear in the WP Admin sidebar
         * @param string $page_title The title as will appear at the top of the settings page
         */
        function add_page( $page_id, $menu_title, $page_title ) {
            $this->pages[$page_id] = array(
                'page_title'  => $page_title,
                'menu_title'  => $menu_title,
                'tabs'        => array(),
            );
        }

        /**
         * Adds a new tab on the given settings page
         * @param string $page_id     The page ID of the settings page to add the tab to
         * @param string $tab_id      A unique tab ID
         * @param string $title       The tab button text
         * @param string $tab_title   The tab title text
         * @param string $description A description of this tab
         */
        function add_tab( $page_id, $tab_id, $title, $description = '' ) {
            $this->tabs[$tab_id] = array(
                'page_id'     => $page_id,
                'title'       => $title,
                'description' => $description,
                'fields'      => array(),
            );
            $this->pages[$page_id]['tabs'][] = $tab_id;
        }

        /**
         * Adds a new field to a given tab
         * @param array $args An array of field settings
         */
        function add_field( $args ) {
            // Parse the args against the default values
            $defaults = array(
                'type'          => 'text',
                'id'            => '',
                'title'         => '',
                'tab'           => '',
                'size'          => 'regular',
                'rows'          => 5,
                'description'   => '',
                'options'       => array(),
                'default'       => '',
                'break'         => false,
                'output_func'   => '',
                'sanitize_func' => '',
            );
            $args = wp_parse_args( $args, $defaults );

            // Add the field to the tab
            $this->tabs[$args['tab']]['fields'][] = $args['id'];

            // Create a new field
            $this->fields[$args['id']] = $args;
        }


        /* -- Private Methods -- */

        /**
         * Enqueues any need Javascripts and CSS
         */
        function _enqueue_scripts() {
            global $plugin_page;

            $root_path = plugin_dir_url( __FILE__ );
            $root_path = apply_filters( 'setabolics_root_path', $root_path );
            $css_path  = $root_path . 'css/';
            $js_path   = $root_path . 'js/';

            if ( isset( $this->pages[$plugin_page] ) ) {
                wp_enqueue_script( 'jquery-ui-tabs', false, array( 'jquery', 'jquery-ui-core' ), $this->version, true );
                wp_enqueue_script( 'setabolics', $js_path . 'setabolics.min.js', array(), $this->version, true );
                wp_enqueue_style( 'setabolics', $css_path . 'setabolics.min.css', false, $this->version );
            }
        }

        /**
         * Register the settings field and add the sections
         */
        function _register_setting() {
            // Create the settings
            foreach( $this->pages as $page_id => $page )
                register_setting( $page_id, $page_id, array( &$this, '_sanitize_fields' ) );

            // Iterate through the tabs and add a section for each one
            foreach( $this->tabs as $tab_id => $tab ) {
                add_settings_section( $tab_id, $tab['title'], array( &$this, '_do_setting_section' ), $tab['page_id'] );

                // Add each tab's fields as new settings field
                foreach( $tab['fields'] as $field_id ) {
                    $field = $this->fields[$field_id];
                    add_settings_field( $field_id, $field['title'], array( &$this, '_do_setting_field' ), $tab['page_id'], $tab_id, $field );
                }
            }
        }

        /**
         * Adds all the pages as settings pages
         */
        function _add_pages() {
            $options = $this->options;

            // Add a new top-level menu
            add_menu_page( $options['page_title'], $options['menu_title'], $options['capability'], $options['menu_id'], NULL, $options['icon'], $options['position'] );

            // Add each subpage
            foreach( $this->pages as $page_id => $page )
                add_submenu_page( $options['menu_id'], $page['page_title'], $page['menu_title'], $options['capability'], $page_id, array( &$this, '_do_page' ) );

        }

        /**
         * Outputs each page's content
         */
        function _do_page() {
            global $plugin_page;
            $page = $this->pages[$plugin_page]; ?>
            <div class="wrap">
                <div class="icon32" id="icon-options-general"></div>
                <h2><?php echo $page['page_title']; ?></h2>
                <form action="options.php" method="post" enctype="multipart/form-data">

                    <?php // Output settings fields ?>
                    <?php settings_fields( $plugin_page ); ?>
                    <div class="setabolics-tabs">

                        <?php // Add the tab navigation ?>
                        <ul class="setabolics-tabs-nav">
                            <?php foreach( $page['tabs'] as $key => $tab ) : ?>
                                <li><a href="#<?php echo $tab; ?>" title="<?php echo $this->tabs[$tab]['title']; ?>">
                                    <?php echo $this->tabs[$tab]['title']; ?>
                                </a></li>
                            <?php endforeach; ?>
                        </ul><!-- / .setabolics-tabs-nav -->

                        <?php // Output the fields ?>
                        <?php do_settings_sections( $plugin_page ); ?>
                    </div><!-- / .setabolics-tabs -->
                    <p class="submit"><input name="submit" type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'setabolics' ); ?>" /></p>
                </form>
            </div><!-- / .wrap -->
        <?php
        }

        /**
         * Outputs the description for each setting section
         * @param  array $data An array of data about the current section
         */
        function _do_setting_section( $data ) {
            if ( $desc = $this->tabs[$data['id']]['description'] )
                echo '<p class="description">' . $desc . '</p>';
        }

        /**
         * Outputs the HTML for each setting field
         * @param  array $field The array of field data
         */
        function _do_setting_field( $field ) {
            $tab   = $this->tabs[$field['tab']];
            $saved = get_option( $tab['page_id'] );

            $output = "";
            switch ( $field['type'] ) {
                case 'custom':
                    $saved_value = isset( $saved[$field['id']] ) ? $saved[$field['id']] : NULL;
                    call_user_func( $field['output_func'], $field, $saved_value );
                    break;

                // Heading
                case 'heading':
                    $output = '</td></tr><tr class="has-heading" valign="top"><td colspan="2"><h4 class="setabolics-heading">' . $field['description'] . '</h4>';
                    break;

                case 'message':
                    $output = "<p id='{$field['id']}' class='setabolics-message'>{$field['description']}</p>";
                    break;

                // Images
                case 'image':
                    // TODO: WP Image Uploader
                    break;

                // Single Checkbox
                case 'checkbox':
                    if ( isset( $saved[$field['id']] ) )
                        $checked = $saved[$field['id']] ? 'checked="checked"' : '';
                    else
                        $checked = $field['default'] ? 'checked="checked"' : '';
                    $output  = '<label for="' . $field['id'] . '" >';
                    $output .= '<input type="checkbox"
                                       id="' . $field['id'] . '"
                                       name="' . $tab['page_id'] . '[' . $field['id'] . ']"'
                                       . $checked .
                                       'value="true" />';
                    $output .= $field['description'];
                    $output .= '</label>';
                    break;

                // Checkgroup
                case 'checkgroup':
                    $saved_values = isset( $saved[$field['id']] ) ? $saved[$field['id']] : array();
                    $output = '<div class="setabolics-group">';
                    foreach( $field['options'] as $key => $option ) {
                        if ( isset( $saved_values[$option['value']] ) )
                            $checked = $saved_values[$option['value']] ? 'checked="checked"' : '';
                        else
                            $checked = isset( $option['selected'] ) && $option['selected'] ? 'checked="checked"' : '';

                        $disabled = isset( $option['disabled'] ) && $option['disabled'] ? 'disabled="disabled"' : '';

                        $output .= '<label for="' . $field['id'] . '_' . $key . '">';

                        if ( ! $disabled ) {
                            $output .= '<input type="checkbox"
                                               id="' . $field['id'] . '_' . $key . '"
                                               name="' . $tab['page_id'] . '[' . $field['id'] . '][' . $key . ']"' .
                                               $checked .
                                               'value="' . $option['value'] . '" />';
                        } else {
                            $output .= '<input type="checkbox"
                                               id="' . $field['id'] . '_' . $key . '"' .
                                               $checked . $disabled . ' />';
                            $output .= '<input type="hidden"
                                               name="' . $tab['page_id'] . '[' . $field['id'] . '][' . $key . ']"
                                               value="' . $option['value'] . '" />';
                        }

                        $output .= $option['label'];
                        $output .= '</label>';
                    }
                    $output .= '</div>';
                    if ( $field['description'] )
                        $output .= '<p class="description">' . $field['description'] . '</p>';
                    break;

                // Radio Group
                case 'radio':
                    $output = '<div class="setabolics-group">';
                    foreach( $field['options'] as $key => $option ) {
                        if ( isset( $saved[$field['id']] ) )
                            $checked = $option['value'] == $saved[$field['id']] ? 'checked="checked"' : '';
                        else
                            $checked = $option['value'] == $field['default'] ? 'checked="checked"' : '';
                        $output .= '<label for="' . $field['id'] . '_' . $key . '">';
                        $output .= '<input type="radio"
                                           id="' . $field['id'] . '_' . $key . '"
                                           name="' . $tab['page_id'] . '[' . $field['id'] . ']"'
                                           . $checked .
                                           'value="' . $option['value'] . '" />';
                        $output .= $option['label'];
                        $output .= '</label>';
                    }
                    $output .= '</div>';
                    if ( $field['description'] ) {
                        $output .= $field['break'] ? '<br />' : '';
                        $output .= '<span class="description">' . $field['description'] . '</span>';
                    }
                    break;

                // Select
                case 'select':
                    $output = '<select id="' . $field['id'] . '" name="' . $tab['page_id'] . '[' . $field['id'] . ']">';
                    foreach( $field['options'] as $key=> $option ) {
                        $selected = isset( $saved[$field['id']] ) && $option['value'] == $saved[$field['id']] ? ' selected="selected"' : '';
                        $output  .= '<option value="' . $option['value'] . '"' . $selected . '>' . $option['label'] . '</option>';
                    }
                    $output .= '</select>';
                    if ( $field['description'] ) {
                        $output .= $field['break'] ? '<br />' : '';
                        $output .= '<span class="description">' . $field['description'] . '</span>';
                    }
                    break;

                // Numbers
                case 'decimal':
                case 'integer':
                    $step   = '';
                    $step   = 'decimal' == $field['type'] ? 'step="any"' : $step;
                    $step   = 'integer' == $field['type'] ? 'step="1"' : $step;
                    $output = '<input type="number"
                                      class="' . $field['size'] . '-text"
                                      id="' . $field['id'] . '"
                                      name="' . $tab['page_id'] . '[' . $field['id'] . ']"' .
                                      $step .
                                      ( 'decimal' == $field['type'] ? 'pattern="\d*(\.\d+)?"' : '' ) .
                                      ( isset( $field['min'] ) ? 'min="' . $field['min'] . '"' : '' ) .
                                      ( isset( $field['max'] ) ? 'max="' . $field['max'] . '"' : '' ) .
                                      'value="' . ( isset( $saved[$field['id']] ) ? $saved[$field['id']] : $field['default'] ) . '" />';
                    if ( $field['description'] ) {
                        $output .= $field['break'] ? '<br />' : '';
                        $output .= '<span class="description">' . $field['description'] . '</span>';
                    }
                    break;

                // Textareas
                case 'code':
                case 'textarea':
                    $output  = '<textarea class="widefat' . ( 'code' == $field['type'] ? ' setabolics-code' : '' ) . '"
                                          id="' . $field['id'] . '"
                                          name="' . $tab['page_id'] . '[' . $field['id'] . ']"
                                          rows="' . $field['rows'] . '">';
                    $output .= isset( $saved[$field['id']] ) ? $saved[$field['id']] : $field['default'];
                    $output .= '</textarea>';
                    if ( $field['description'] ) {
                        $output .= $field['break'] ? '<br />' : '';
                        $output .= '<span class="description">' . $field['description'] . '</span>';
                    }
                    break;

                // Text fields
                case 'url':
                case 'text':
                default:
                    $value = isset( $saved[$field['id']] ) ? $saved[$field['id']] : $field['default'];
                    $output = '<input class="' . $field['size'] . '-text"
                                      type="' . $field['type'] . '"
                                      id="' . $field['id'] . '"
                                      name="' . $tab['page_id'] . '[' . $field['id'] . ']"
                                      value="' . $value . '" />';

                    if ( $field['description'] ) {
                        $output .= $field['break'] ? '<br />' : '';
                        $output .= '<span class="description">' . $field['description'] . '</span>';
                    }
                    break;
            }

            echo $output;
        }

        /**
         * Validates and sanitizes each setting field
         * @param  array $inputs An array of the entered values
         * @return array         The valid entries to be saved
         */
        function _sanitize_fields( $inputs ) {
            if ( ! $inputs ) return;
            $valid = array();

            foreach( $inputs as $id => $value ) {

                // Use custom sanitize function if it's defined
                if ( $this->fields[$id]['sanitize_func'] ) {
                    $valid[$id] = call_user_func( $this->fields[$id]['sanitize_func'], $id, $value );

                } else {
                    switch( $this->fields[$id]['type'] ) {
                        case 'custom':
                            // Custom field types must define sanitize functions
                            break;

                        case 'checkbox':
                            $valid[$id] = $value == 'true' ? true : false;
                            break;

                        case 'image':
                            // TODO: Sanitize images
                            break;

                        case 'checkgroup':
                            $checkboxes = array();
                            foreach( $this->fields[$id]['options'] as $key => $check )
                                $checkboxes[$check['value']] = isset( $value[$key] );
                            $valid[$id] = $checkboxes;
                            break;

                        case 'url':
                            $valid[$id] = esc_url( $value );
                            break;

                        case 'integer':
                            $valid[$id] = intval( $value );
                            break;

                        case 'decimal':
                            $valid[$id] = floatval( $value );
                            break;

                        case 'textarea':
                            $valid[$id] = esc_textarea( $value );
                            break;

                        case 'code':
                            $valid[$id] = current_user_can( 'unfiltered_html' ) ? $value : wp_kses_post( $value );
                            break;

                        case 'select':
                        case 'radio':
                        case 'text':
                        default:
                            $valid[$id] = esc_html( $value );
                            break;
                    }
                }
            }

            // Checkgroup fix
            foreach( $this->fields as $id => $field ) {
                if ( $field['type'] == 'checkgroup' && ! isset( $valid[$id] ) ) {
                    foreach( $field['options'] as $option )
                        $valid[$id][$option['value']] = false;
                }

                if ( $field['type'] == 'checkbox' && ! isset( $valid[$id] ) ) {
                    $valid[$id] = false;
                }
            }

            return $valid;
        }
    }
  }
  ?>