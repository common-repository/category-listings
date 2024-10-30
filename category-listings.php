<?php
/*
Plugin Name: Category Listings
Plugin URI: http://www.club-wp.com/category-listings-wordpress-plugin/
Description: Promotes content from the category of the post that is currently being viewed. Outputs a list of post titles and snippets from a specified category formatted like a post.
Author: Club Wordpress
Version: 1.1.0
Author URI: http://www.club-wp.com/
*/

/*  
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/*
  Updates:
  1.1.0 - Added the shortcode 'category-listing'.
*/

//vars
$cat_list_options = array();

add_action('init', 'get_cat_list_options');

function get_cat_list_options() {
  global $cat_list_options, $prefix;
  
  $prefix = 'category-listings';
  
  //each array item has option field, option value, label text.
  $cat_list_options = array(
    'post_count' =>         array('option_field' => $prefix.'_post_count', 'option_value' => '', 'type' => '', 'label' => 'Number of posts output', 'param' => 'post_count'),
    'html_before' =>         array('option_field' => $prefix.'_html_before', 'option_value' => '', 'type' => '', 'label' => 'HTML before all output', 'param' => 'html_before'),
    'html_after' =>         array('option_field' => $prefix.'_html_after', 'option_value' => '', 'type' => '', 'label' => 'HTML after all output', 'param' => 'html_after'),
    'html_before_title' =>         array('option_field' => $prefix.'_html_before_title', 'option_value' => '', 'type' => '', 'label' => 'HTML before post title', 'param' => 'html_before_title'),
    'html_after_title' =>         array('option_field' => $prefix.'_html_after_title', 'option_value' => '', 'type' => '', 'label' => 'HTML after post title', 'param' => 'html_after_title'),
    'html_before_excerpt' =>         array('option_field' => $prefix.'_html_before_excerpt', 'option_value' => '', 'type' => '', 'label' => 'HTML before excerpt', 'param' => 'html_before_excerpt'),
    'html_after_excerpt' =>         array('option_field' => $prefix.'_html_after_excerpt', 'option_value' => '', 'type' => '', 'label' => 'HTML after excerpt', 'param' => 'html_after_excerpt')
    
  );

}

register_activation_hook( __FILE__, 'category_listing_activate' );
function category_listing_activate() {

}

register_deactivation_hook( __FILE__, 'category_listing_deactivate' );
function category_listing_deactivate() {

}

add_action('admin_menu', 'category_listings_admin');

function category_listings_admin() {
	if (function_exists('add_submenu_page')) {
    global $cat_list_options, $prefix;

    $prefix = 'category-listings';
    $title = 'Category Listings';
    
    if ( $_GET['page'] == basename(__FILE__) ) {
              
      if ( 'Save Changes' == $_REQUEST['Submit'] ) {
        
        // protect against request forgery
        check_admin_referer('category-listing-save');
                
        // save the options
        foreach ($cat_list_options as $value) {
          if( $value['type'] == 'check') {
            $val = stripslashes($_REQUEST[$value['option_field']]) == 'true' ? 'true' : 'false';
            
            update_option( $value['option_field'], $val );            
          }
          else {
            update_option( $value['option_field'], stripslashes($_REQUEST[$value['option_field']]));
          }
        }
                 
        // return to the options page
        header("Location: options-general.php?page=category-listings&Submit=true");
        die;

      } else if ( 'Reset' == $_REQUEST['Reset'] ) {
              
        // protect against request forgery
        check_admin_referer('category-listing-save');
        
        // delete the options
        foreach ($cat_list_options as $value) {
          delete_option( $value['option_field'] );
        }
        
        add_cat_list_options();
        
        // return to the options page
        header("Location: options-general.php?page=category-listings&Reset=true");
        die;
      }
    }
    
    add_cat_list_options();
    
    add_options_page('Category Listings', 'Category Listings', 8, basename(__FILE__), 'category_listings_admin_page');
    
  }
}

function add_cat_list_options() {
    global $cat_list_options, $prefix;

    add_option($prefix.'_post_count', '5', '', '5');
    add_option($prefix.'_html_before', '', '', '');
    add_option($prefix.'_html_after', '', '', '');

    add_option($prefix.'_html_before_title', "<h2>", '', "<h2>");
    add_option($prefix.'_html_after_title', '</h2>', '', '</h2>');
    add_option($prefix.'_html_before_excerpt', '<p>', '', '<p>');
    add_option($prefix.'_html_after_excerpt', '</p>', '', '</p>');
    
}

//Display the admin page
function category_listings_admin_page() {
  global $cat_list_options;

  if ( $_REQUEST['Submit'] ) echo '<div id="message" class="updated fade"><p><strong>Category Listings settings saved.</strong></p></div>';
  if ( $_REQUEST['Reset'] ) echo '<div id="message" class="updated fade"><p><strong>Category Listings settings reset.</strong></p></div>';
  ?>

  <div class="wrap nosubsub"> 
    <div id="icon-options-general" class="icon32"><br /></div> 
    <h2>Category Listings</h2> 
     
  </div>  

  <form method="post"> 

  <p class="submit"> 
    <input type="submit" name="Submit" class="button-primary" value="Save Changes" /> 
    <input type="submit" name="Reset" class="button-primary" value="Reset" /> 
  </p>
  
  <?php wp_nonce_field('category-listing-save'); ?>
  
  
  
  <table class="form-table"> 

  <?php
  $first = true;
  
  foreach($cat_list_options as $option) {
    
    echo '<tr valign="top">';
    echo '<th scope="row"><label for="blogname">'.$option['label'].'</label></th>'; 
    echo '<td>';

    switch ($option['type'])
    {
      case 'check':
        $checked = get_option($option['option_field'], $option['option_value']) == 'true' ? 'checked' : '';
        echo '<input name="'.$option['option_field'].'" id="'.$option['option_field'].'" type="checkbox" value="true" '.$checked.'/>';        
        break;
      case 'select':
        $control = '<select name="'.$option['option_field'].'" id="'.$option['option_field'].'">';
        foreach( $option['options'] as $o ) {
          $selected = $o == get_option($option['option_field'], $option['option_value']) ? ' selected="yes"' : '';
          $control .= '<option'.$selected.'>'.$o.'</option>';
        }
        $control .= '</select>';
        echo $control;
        break;
      default:
        echo '<input name="'.$option['option_field'].'" id="'.$option['option_field'].'" type="text" value="'.get_option($option['option_field'], $option['option_value']).'" size="50" />';
        break;
    }
    
    echo '</td>';
    
    if( $first ) {
      echo '<td rowspan='.count($cat_list_options).'>'.get_cat_list_about_text().'<td>';
      
      $first = false;
    }    
    
    ?>
    
      
      
    </tr>   
  
  
    
    <?php
  }
  ?>
   
   </table>

  <p class="submit"> 
    <input type="submit" name="Submit" class="button-primary" value="Save Changes" /> 
    <input type="submit" name="Reset" class="button-primary" value="Reset" /> 
  </p>

  </form>
  
  <?php
}

add_shortcode('category-listing', 'output_category_listing');

function output_category_listing() {
  global $cat_list_options, $prefix;
      
  $categories = get_the_category();
    
  if (count($categories) <= 0)
    return $result;
  
  //get the first category
  $category = $categories[0];
  
  $option = $cat_list_options['post_count'];   
  $num_posts .= get_option($option['option_field'], $option['option_value']);
    
  $option = $cat_list_options['html_before']; 
  $result .= get_option($option['option_field'], $option['option_value']);
  
  $option = $cat_list_options['html_before_title']; 
  $before_title .= get_option($option['option_field'], $option['option_value']);
  
  $option = $cat_list_options['html_after_title']; 
  $after_title .= get_option($option['option_field'], $option['option_value']);
  
  $option = $cat_list_options['html_before_excerpt']; 
  $before_excerpt .= get_option($option['option_field'], $option['option_value']);
  
  $option = $cat_list_options['html_after_excerpt']; 
  $after_excerpt .= get_option($option['option_field'], $option['option_value']);
  
  $cat_id = $category->cat_ID;
  
  $posts = get_posts('numberposts='.$num_posts.'&cat='.$cat_id);
  
  $post_id = get_the_ID();
  
  foreach ($posts as $post){ 
    if ($post_id != $post->ID) {
      $permalink = get_permalink( $post->ID );
      
      $result .= $before_title.'<a href="'.$permalink.'">'.$post->post_title.'</a>'.$after_title;
      $result .= $before_excerpt.$post->post_excerpt.'</p>'.$after_excerpt;
    }
  }
  
  $cat_link = get_category_link( $cat_id );
  
  //add the link to the category archive
  //$result .= '<a href="'.$cat_link.'">Read more from the '.$category->name.' category</a>';
  
  $option = $cat_list_options['html_after']; 
  $result .= get_option($option['option_field'], $option['option_value']);
  
  
  return $result;
}

function get_cat_list_about_text() {
  $about = '<p>Category Listings plugin is developed by <a href="http://www.club-wp.com/" target="_blank">Club Wordpress</a>.</p>
            <p>Visit us to find out more about our:</p>
            <ul>
              <li><a href="http://www.club-wp.com/category/themes/" target="_blank">WordPress Themes</a></li>
              <li><a href="http://www.club-wp.com/category/plugins/" target="_blank">WordPress plugins</a></li>
              <li><a href="http://www.club-wp.com/wordpress-services/" target="_blank">WordPress Services</a></li>
              <li><a href="http://feeds.feedburner.com/ClubWordpress" target="_blank">RSS Feed</a></li>
            <ul>
             <p>You can also find us on:</p>
            <ul>
              <li><a href="http://www.facebook.com/pages/Club-Wordpress-Wordpress-Themes-Plugins-and-Tutorials/171743681365" target="_blank">FaceBook</a></li>
              <li><a href="http://twitter.com/clubwordpress" target="_blank">Twitter</a></li>
            <ul>';
  
  
  return $about;
}
