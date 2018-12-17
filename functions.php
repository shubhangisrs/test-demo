<?php
   /*
   Plugin Name: Countdown
   Description: A plugin that records countdown
   Version: 1.0
   Author: XYZ
   */

  //datepicker

  wp_enqueue_script('jquery-ui-datepicker');
  wp_enqueue_style('jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

// create custom plugin settings menu
add_action('admin_menu', 'my_cool_plugin_create_menu');
function my_cool_plugin_create_menu() {

  //create new top-level menu
  add_menu_page('My Countdown Plugin Settings', 'Countdown Settings', 'administrator', __FILE__, 'my_countdown_plugin_settings_page' , plugins_url('/images/icon.png', __FILE__) );

  //call register settings function
  add_action( 'admin_init', 'register_my_cool_plugin_settings' );
}


function register_my_cool_plugin_settings() {
  //register our settings
  register_setting( 'my-cool-plugin-settings-group', 'date_field' );
  register_setting( 'my-cool-plugin-settings-group', 'title_field' );
}

function my_countdown_plugin_settings_page() {
?>
  <script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#date_field').datepicker({
            dateFormat : 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "2000:2050"
        });
    });
  </script>

    <div class="wrap">
      <h1>Countdown</h1>
      <form method="post" action="options.php">
          <?php settings_fields( 'my-cool-plugin-settings-group' ); ?>
          <?php do_settings_sections( 'my-cool-plugin-settings-group' ); ?>
          <table class="form-table">
              <tr valign="top">
              <th scope="row">Date field</th>
              <td><input type="text" id="date_field" name="date_field" value="<?php echo esc_attr( get_option('date_field') ); ?>" /></td>
              </tr>   
              <tr valign="top">
              <th scope="row">Title field</th>
              <td><input type="text" name="title_field" value="<?php echo esc_attr( get_option('title_field') ); ?>" /></td>
              </tr>
          </table>
          <?php submit_button(); ?>
      </form>
    </div>
<?php } ?>
<?php
    //countdown shortcode create
    function content_countdown($atts, $content = null){
      extract(shortcode_atts(array(
         'month' => '',
         'day'   => '',
         'year'  => ''
        ), $atts));
        $remain = ceil((mktime( 0,0,0,(int)$month,(int)$day,(int)$year) - time())/86400);
        if( $remain > 1 ){
            return $daysremain = "<div class=\"event\">Just <b>($remain)</b> days until content is available</div>";
        }else if($remain == 1 ){
        return $daysremain = "<div class=\"event\">Just <b>($remain)</b> day until content is available</div>";
        }else{
            return $content;
        }
  }
  add_shortcode('cdt', 'content_countdown');
//display shorcode onto the all pages
  function my_shortcode_to_a_post( $content ) {
  global $post;
  if( ! $post instanceof WP_Post ) return $content;
 
  switch( $post->post_type ) {
    case 'post':
      return $content . '[cdt month="11" day="15" year="2020"]
This is content that will only be shown after a set number of days.
[/cdt]';
 
    case 'page':
      return $content . '[cdt month="11" day="15" year="2020"]
This is content that will only be shown after a set number of days.
[/cdt]';
 
    default:
      return $content;
  }
}
add_filter( 'the_content', 'my_shortcode_to_a_post' );


//another shortcode to display the title and the date filed entered
add_shortcode( 'date_title', 'wpshout_sample_shortcode' );
function wpshout_sample_shortcode() {

    return "Date ".get_option('date_field')." Title : ".get_option('title_field');
}
  function my_shortcode_to_a_post1( $content ) {
  global $post;
  if( ! $post instanceof WP_Post ) return $content;
 
  switch( $post->post_type ) {
    case 'post':
      return $content . '[date_title][/date_title]';
 
    case 'page':
      return $content . '[date_title][/date_title]';
 
    default:
      return $content;
  }
}
add_filter( 'the_content', 'my_shortcode_to_a_post1' );
?>