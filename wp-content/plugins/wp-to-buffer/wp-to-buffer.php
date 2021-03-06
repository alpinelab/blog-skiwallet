<?php
/**
* Plugin Name: WP to Buffer
* Plugin URI: http://www.wpcube.co.uk/plugins/wp-to-buffer-pro
* Version: 2.3.2
* Author: WP Cube
* Author URI: http://www.wpcube.co.uk
* Description: Send WordPress Pages, Posts or Custom Post Types to your Buffer (bufferapp.com) account for scheduled publishing to social networks.
* Text Domain: wp-to-buffer
* License: GPL2
*/

/*  Copyright 2014 WP Cube (email : support@wpcube.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
* WP to Buffer Class
* 
* @package WP Cube
* @subpackage WP to Buffer
* @author Tim Carr
* @version 2.3.2
* @copyright WP Cube
*/
class WPToBuffer {
    /**
    * Constructor.
    */
    function WPToBuffer() {
        // Plugin Details
        $this->plugin = new stdClass;
        $this->plugin->name = 'wp-to-buffer'; // Plugin Folder
        $this->plugin->settingsName = 'wp-to-buffer';
        $this->plugin->displayName = 'WP to Buffer'; // Plugin Name
        $this->plugin->version = '2.3.2';
        $this->plugin->folder = WP_PLUGIN_DIR.'/'.$this->plugin->name; // Full Path to Plugin Folder
        $this->plugin->url = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
        $this->plugin->upgradeReasons = array(
        	array(__('Settings per Account'), __('Each social media account for each Post Type can have its own settings defined for status updates, images, filtering etc.')),
        	array(__('Optional Featured Image'), __('Choose to include your featured image or not in each status update.')),
        	array(__('Send Multiple Times'), __('Send your publish or update status 1, 2 or 3 times to Buffer.')),
        	array(__('Enhanced Tag Interface'), __('All available tags and taxonomy tags are available, and can be added to your publish and update status messages with a single mouse click.')),
        	array(__('Send Immediately'), __('Choose to send Posts, Pages and Custom Post Types immediately through your Buffer account.')),
        	array(__('Taxonomy Level Filtering'), __('Advanced controls to only publish Posts, Pages and/or Custom Post Types that match Taxonomy Term(s).')),
        	array(__('Post Overrides'), __('Choose to override plugin wide settings on every Page, Post and Custom Post Type, allowing you to define a custom status message, number of times to send, and which accounts to send to.')),
        );
        $this->plugin->upgradeURL = 'http://www.wpcube.co.uk/plugins/wp-to-buffer-pro';
        
		$this->plugin->ignorePostTypes = array('attachment','revision','nav_menu_item');      
		$this->plugin->publishDefaultString = 'New Post: {title} {url}';
		$this->plugin->updateDefaultString = 'Updated Post: {title} {url}';
		
        // Dashboard Submodule
        if (!class_exists('WPCubeDashboardWidget')) {
			require_once($this->plugin->folder.'/_modules/dashboard/dashboard.php');
		}
		$dashboard = new WPCubeDashboardWidget($this->plugin); 
		
		// Hooks
		add_action('wp_loaded', array(&$this, 'registerPublishHooks'));
        add_action('admin_enqueue_scripts', array(&$this, 'adminScriptsAndCSS'));
        add_action('admin_menu', array(&$this, 'adminPanelsAndMetaBoxes'));
        add_action('admin_notices', array(&$this, 'AdminNotices')); 
        add_action('plugins_loaded', array(&$this, 'loadLanguageFiles'));
    }
    
    /**
    * Registers publish hooks against all public Post Types
    */
    function registerPublishHooks() {    	
    	$types = get_post_types(array(
    		'public' => true,
    	));
    	foreach ($types as $type) {
    		add_action('publish_'.$type, array(&$this, 'publishToBufferNow'));
			add_action('publish_future_'.$type, array(&$this, 'publishToBufferFuture'));	
    	}	
    }
    
    /**
    * Register and enqueue any JS and CSS for the WordPress Administration
    */
    function adminScriptsAndCSS() {
    	// JS
    	wp_enqueue_script($this->plugin->name.'-admin', $this->plugin->url.'js/admin.js', array('jquery'), $this->plugin->version, true);
    	        
    	// CSS
        wp_enqueue_style($this->plugin->name.'-admin', $this->plugin->url.'css/admin.css', array(), $this->plugin->version); 
    }
    
    /**
    * Register the plugin settings panel
    */
    function adminPanelsAndMetaBoxes() {
        add_menu_page($this->plugin->displayName, $this->plugin->displayName, 'manage_options', $this->plugin->name, array(&$this, 'adminPanel'), $this->plugin->url.'images/icons/small.png');
    }
    
    /**
    * Outputs a notice if:
    * - Buffer hasn't authenticated i.e. we do not have an access token
    * - A Post has been sent to Buffer and we have a valid message response
    */
    function adminNotices() {
        if (isset($_GET['page']) AND $_GET['page'] == $this->plugin->name) return false; // Don't check on plugin main page
        $this->settings = get_option($this->plugin->name); // Get settings
        
        // Check if no access token
        if (!isset($this->settings['accessToken']) OR $this->settings['accessToken'] == '') {
        	echo (' <div class="error"><p>'.$this->plugin->displayName.' requires authorisation with Buffer in order to post updates to your account.
        			Please visit the <a href="admin.php?page='.$this->plugin->name.'" title="Settings">Settings Page</a> to grant access.</p></div>');
            return false;	
        }
        
        // Output success and/or error messages if we are on a post and it has a meta key
        if (isset($_GET['message']) AND isset($_GET['post'])) {
        	// Success
        	$success = get_post_meta($_GET['post'], $this->plugin->settingsName.'-success', true);
        	if ($success == 1) {
        		// Get Message
        		$message = get_post_meta($_GET['post'], $this->plugin->settingsName.'-success-message', true);
        		$message = ((!empty($message) AND trim($message) != '') ? $message : __('Post added to Buffer successfully', $this->plugin->name));
 				
 				// Output + clear meta
        		echo (' <div class="updated success"><p>'.$this->plugin->displayName.': '.$message.'</p></div>');
        		delete_post_meta($_GET['post'], $this->plugin->settingsName.'-success');	
        		delete_post_meta($_GET['post'], $this->plugin->settingsName.'-success-message');
        	}
        	
        	// Error
        	$error = get_post_meta($_GET['post'], $this->plugin->settingsName.'-error', true);
        	if ($error == 1) {
        		echo (' <div class="error"><p>'.get_post_meta($_GET['post'], $this->plugin->settingsName.'-error-message', true).'</p></div>');
        		delete_post_meta($_GET['post'], $this->plugin->settingsName.'-error');
        		delete_post_meta($_GET['post'], $this->plugin->settingsName.'-error-message');	
        	}
        }
    } 
    
    /**
    * Alias function called when a post is published or updated
    *
    * Passes on the request to the main Publish function
    *
    * @param int $postID Post ID
    */
    function publishToBufferNow($postID) {
    	$this->publish($postID);
    }
    
    /**
    * Alias function called when a post, set to be published in the future, reaches the time
    * when it is being published
    *
    * Passes on the request to the main Publish function
    *
    * @param int $postID Post ID
    */
    function publishToBufferFuture($postID) {
    	$this->publish($postID, true);
    }
    
    /**
    * Called when any Page, Post or Custom Post Type is published or updated, live or for a scheduled post
    *
    * @param int $postID Post ID
    */
    function publish($postID, $isPublishAction = false) {
    	$defaults = get_option($this->plugin->settingsName); // Get settings
        if (!isset($defaults['accessToken']) OR empty($defaults['accessToken'])) return false; // No access token so cannot publish to Buffer
        
        // Get post
        $post = get_post($postID);
        
        // If request has come from XMLRPC, force $isPublishAction
        if (defined('XMLRPC_REQUEST')) {
        	$isPublishAction = true;
        }
        
        // Check at least one account is enabled
        if (!isset($defaults['ids'])) {
        	return false;
        }
        if (!isset($defaults['ids'][$post->post_type])) {
        	return false;
        }

		// Determine if this is a publish or update action
        if ($_POST['original_post_status'] == 'draft' OR 
        	$_POST['original_post_status'] == 'auto-draft' OR 
        	$_POST['original_post_status'] == 'pending' OR
        	$_POST['original_post_status'] == 'future' OR
        	$isPublishAction) {
        	
        	// Publish?
        	if ($defaults['enabled'][$post->post_type]['publish'] != '1') return false; // No Buffer needed for publish
        	$updateType = 'publish';
        }
        
		if ($_POST['original_post_status'] == 'publish') {
        	// Update?
        	if ($defaults['enabled'][$post->post_type]['update'] != '1') return false; // No Buffer needed for update
        	$updateType = 'update';
        }
        
		// 1. Get post categories if any exist
		$catNames = '';
		$cats = wp_get_post_categories($postID, array('fields' => 'ids'));
		if (is_array($cats) AND count($cats) > 0) {
			foreach ($cats as $key=>$catID) {
				$cat = get_category($catID);
				$catName = strtolower(str_replace(' ', '', $cat->name));
				$catNames .= '#'.$catName.' ';
			}
		}
		
		// 2. Get author
		$author = get_user_by('id', $post->post_author);
		
		// 3. Check if we have an excerpt. If we don't (i.e. it's a Page or CPT with no excerpt functionality), we need
		// to create an excerpt
		if (empty($post->post_excerpt)) {
			$excerpt = wp_trim_words(strip_shortcodes($post->post_content));
		} else {
			$excerpt = $post->post_excerpt;
		}
		
		// 4. Parse text and description
		$params['text'] = $defaults['message'][$post->post_type][$updateType];
		$params['text'] = str_replace('{sitename}', get_bloginfo('name'), $params['text']);
		$params['text'] = str_replace('{title}', html_entity_decode(apply_filters('the_title', $post->post_title)), $params['text']);
		$params['text'] = str_replace('{excerpt}', $excerpt, $params['text']);
		$params['text'] = str_replace('{category}', trim($catNames), $params['text']);
		$params['text'] = str_replace('{date}', date('dS F Y', strtotime($post->post_date)), $params['text']);
		$params['text'] = str_replace('{url}', rtrim(get_permalink($post->ID), '/'), $params['text']);
		$params['text'] = str_replace('{author}', $author->display_name, $params['text']);
		
		// 5. Check if we can include the Featured Image (if available) in the media parameter
		// If not, just attach the Post URL
		$media['link'] = rtrim(get_permalink($post->ID), '/');
		$featuredImageID = get_post_thumbnail_id($postID);
		if ($featuredImageID > 0) {
			// Get image source
			$featuredImageSrc = wp_get_attachment_image_src($featuredImageID, 'large');
			if (is_array($featuredImageSrc)) {
				$media['title'] = $post->post_title; // Required for LinkedIn to work
				$media['picture'] = $featuredImageSrc[0];
				$media['thumbnail'] = $featuredImageSrc[0];
				$media['description'] = $post->post_title;
				unset($media['link']); // Important: if set, this attaches a link and drops the image!
			}
		}
		
		// Assign media array to media argument
		$params['media'] = $media;
		
		// 6. Add profile IDs
		foreach ($defaults['ids'][$post->post_type] as $profileID=>$enabled) {
			if ($enabled) $params['profile_ids'][] = $profileID; 
		}
		
		// If text is empty, something went wrong
		if (trim($params['text']) == '') {
			return false;
		}
		
		// 7. Send to Buffer
		delete_post_meta($postID, $this->plugin->settingsName.'-success');
		delete_post_meta($postID, $this->plugin->settingsName.'-error');
		$result = $this->request($defaults['accessToken'], 'updates/create.json', 'post', $params);
		
		if (is_object($result)) {
			update_post_meta($postID, $this->plugin->settingsName.'-success', 1);
		} else {
			update_post_meta($postID, $this->plugin->settingsName.'-error', 1);
		}
    }
    
	/**
    * Output the Administration Panel
    * Save POSTed data from the Administration Panel into a WordPress option
    */
    function adminPanel() {
        // Save Settings
        if (isset($_POST['submit'])) {
        	// Check the access token, in case it hasn't been copied / pasted correctly
        	// This happens when you double click the Access Token on http://bufferapp.com/developers/apps, which doesn't
        	// quite select the entire access token
        	$tokenLength = strlen($_POST[$this->plugin->name]['accessToken']);
        	if ($tokenLength > 0) {
        		// Check if token is missing 1/ at the start
        		if (substr($_POST[$this->plugin->name]['accessToken'], 0, 2) != '1/') {
        			// Missing
        			$this->errorMessage = __('Oops - you\'ve not quite copied your access token from Buffer correctly. It should start with 1/. Please try again.');
        		} elseif (substr($_POST[$this->plugin->name]['accessToken'], $tokenLength-4, 4) == 'Edit') {
        			$this->errorMessage = __('Oops - you\'ve not quite copied your access token from Buffer correctly. It should not end with the word Edit. Please try again.');
        		}
        	} else {
        		$this->errorMessage = __('Please enter an access token to use this plugin. You can obtain one by following the instructions below.');
        	}
        	
        	// Test access token to make sure it's valid
        	if (!isset($this->errorMessage)) {
        		$user = $this->Request($_POST[$this->plugin->name]['accessToken'], 'user.json');
        		if (!is_object($user)) {
        			$this->errorMessage = $user;
        		} else {
        			// Ok - save
        			update_option($this->plugin->name, $_POST[$this->plugin->name]);
            		$this->message = __('Settings Updated.');
        		}	
            }
        }
        
        // Disconnect?
        if (isset($_GET['disconnect'])) {
        	$this->settings = get_option($this->plugin->name);
        	$this->settings['accessToken'] = '';
        	update_option($this->plugin->name, $this->settings);	
        }
        
        // Get latest settings
        $this->settings = get_option($this->plugin->name);
        
        // If we have an access token, try to get the user's profile listing their accounts
        $this->buffer = new stdClass;
        if ($this->settings['accessToken'] != '') {
        	$profiles = $this->Request($this->settings['accessToken'], 'profiles.json');
        	if (is_wp_error($profiles)) {
        		$this->errorMessage = $profiles->get_error_message().'. '.__('Some functionality on this screen may not work correctly.');
        	} else {
        		$this->buffer->accounts = $profiles;
        	}
        }
        
        // Get selected tab
		$this->tab = (isset($_GET['tab']) ? $_GET['tab'] : 'auth');
        
		// Load Settings Form
        include_once(WP_PLUGIN_DIR.'/'.$this->plugin->name.'/views/settings.php');  
    }
    
    /**
    * Loads plugin textdomain
    */
    function loadLanguageFiles() {
    	load_plugin_textdomain($this->plugin->name, false, $this->plugin->name.'/languages/');
    }
    
    /**
    * Sends a GET request to the Buffer API
    *
    * @param string $accessToken Access Token
    * @param string $cmd Command
    * @param string $method Method (get|post)
    * @param array $params Parameters (optional)
    * @return mixed JSON decoded object or error string
    */
    function request($accessToken, $cmd, $method = 'get', $params = array()) {
    	// Check for access token
    	if ($accessToken == '') return __('Invalid access token', $this->plugin->name);
		
		// Send request
		switch ($method) {
			case 'get':
				$result = wp_remote_get('https://api.bufferapp.com/1/'.$cmd.'?access_token='.$accessToken, array(
		    		'body' => $params,
		    		'sslverify' => false
		    	));
				break;
			case 'post':
				$result = wp_remote_post('https://api.bufferapp.com/1/'.$cmd.'?access_token='.$accessToken, array(
		    		'body' => $params,
		    		'sslverify' => false
		    	));
				break;
		}
    	
    	// Check the request is valid
    	if (is_wp_error($result)) return $result;
		if ($result['response']['code'] != 200) return 'Error '.$result['response']['code'].' whilst trying to authenticate: '.$result['response']['message'].'. Please try again.';

		return json_decode($result['body']);		
    }
}
$WPToBuffer = new WPToBuffer(); // Invoke class
?>
