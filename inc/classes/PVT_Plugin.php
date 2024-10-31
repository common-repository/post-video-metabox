<?php

/**
 * Project : post-video-metabox
 * User: palagornp
 * Date: 7/12/2017 AD
 * Time: 2:39 PM
 */

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

class PVT_Plugin {

	/**
	 * PVT_Plugin constructor.
	 */
	public function __construct() {

	  if ( is_plugin_active( 'classic-editor/classic-editor.php' )) {
	    add_action( 'add_meta_boxes_post', [$this, 'add_post_video_meta_boxes_classics'] );
    }
	  else {
	    add_action( 'add_meta_boxes_post', [$this, 'add_post_video_meta_boxes_block_editor'] );
    }

		add_action( 'save_post', [$this, 'save_post_video_meta_box_data'] );

		add_action( 'admin_enqueue_scripts', [$this, 'enqueue_admin_scripts'] );
		add_action( 'wp_enqueue_scripts', [$this, 'enqueue_frontend_scripts'] );

		add_filter( 'post_thumbnail_html', [$this, 'post_video_thumbnail_filter'], 10, 2);
	}

	/**
	 * Add video meta box
	 *
	 * @param post $post The post object
	 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/add_meta_boxes
	 */
	function add_post_video_meta_boxes_classics( $post ){
		add_meta_box( 'pvt_post_video_meta_box_classic', __( 'Post Video', 'pvt' ), [$this, 'post_video_build_meta_box_classic'], 'post', 'normal', 'low' );
	}//function add_post_video_meta_boxes_classics

  function add_post_video_meta_boxes_block_editor ($post){
	  add_meta_box( 'pvt_post_video_meta_box', __( 'Post Video', 'pvt' ), [$this, 'post_video_build_meta_box_block_editor'], 'post', 'side', 'high', array(
	    '__block_editor_compatible_meta_box' => true,
    ));
  }


	/**
	 * Build custom field meta box
	 *
	 * @param post $post The post object
	 */
	function post_video_build_meta_box_classic( $post ){

		// make sure the form request comes from WordPress
		wp_nonce_field( basename( __FILE__ ), 'pvt_post_video_meta_box_nonce' );

		// retrieve the pvt_post_video_url current value
		$pvt_post_video_url = get_post_meta( $post->ID, '_pvt_post_video_url', true );

		?>
		<div class="pvt-metaboxes-classic">
			<span><?php _e( 'Paste your video url or upload your video', 'pvt' ); ?></span>
			<div class="form-group">
				<input type="text" name="pvt_post_video_url" class="add-post-video-text" value="<?php echo $pvt_post_video_url; ?>" />
				<a href="#" id="pvt-post-video-button" class="button add-post-video-button" data-editor="content"><?php echo __('Upload Video','pvt') ?> <span class="dashicons dashicons-format-video"></span></a>
			</div>
		</div>
		<?php

	}//function pvt_post_video_build_meta_box

  function post_video_build_meta_box_block_editor($post){
	  // make sure the form request comes from WordPress
	  wp_nonce_field( basename( __FILE__ ), 'pvt_post_video_meta_box_nonce' );

	  // retrieve the pvt_post_video_url current value
	  $pvt_post_video_url = get_post_meta( $post->ID, '_pvt_post_video_url', true );

	  ?>
    <div class="pvt-metaboxes">
      <span><?php _e( 'Paste your video url', 'pvt' ); ?></span>
      <div class="form-group">
        <input type="text" name="pvt_post_video_url" class="add-post-video-text" value="<?php echo $pvt_post_video_url; ?>" />
      </div>
    </div>
	  <?php
  }

	/**
	 * Store custom field meta box data
	 *
	 * @param int $post_id The post ID.
	 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/save_post
	 */
	function save_post_video_meta_box_data( $post_id ){
		// verify taxonomies meta box nonce
		if ( !isset( $_POST['pvt_post_video_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['pvt_post_video_meta_box_nonce'], basename( __FILE__ ) ) ){
			return;
		}

		// return if autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
			return;
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ){
			return;
		}

		// store custom fields values
		// pvt_post_video_url string
		if ( isset( $_REQUEST['pvt_post_video_url'] ) ) {
			update_post_meta( $post_id, '_pvt_post_video_url', sanitize_text_field( $_POST['pvt_post_video_url'] ) );
		}

	}//function save_post_video_meta_box_data

	/**
	 * Enqueue scripts and styles for admin.
	 */
	function enqueue_admin_scripts($hook) {
		wp_enqueue_style( 'pvt-admin-style', __PVT_PLUGIN_URL__ . '/css/admin/admin.min.css' );
		wp_enqueue_script( 'pvt-admin-script', __PVT_PLUGIN_URL__ . '/js/admin/admin.min.js' );
	}

	public function post_video_thumbnail_filter($html, $post_id){
		$video_url = get_post_meta( $post_id, '_pvt_post_video_url', true );
		$has_thumbnail = has_post_thumbnail();
		$post_type = get_post_type();

		if($this->checkThumbnailWidth($html) && is_single() && isset($video_url) && ($video_url != '') && $has_thumbnail && ($post_type == 'post')) {
			$output = '<div class="pvt-video-thumbnail">';
			$output.= $this->get_embeded_video_tag($video_url,$html);
			$output .= '</div>';

			return $output;
        }
        else{
		    return $html;
        }
	}

	public function get_embeded_video_tag($video_url,$html){
		$embed_video = wp_oembed_get( $video_url );
		$embed_video = str_replace('feature=oembed', 'feature=oembed&autoplay=1', $embed_video);
		$embed_src = substr($embed_video,stripos($embed_video,'src="') + 5);
		$embed_src = substr($embed_src,0,strpos($embed_src,'"'));

		$embed_video = str_replace($embed_src,'',$embed_video);


		$html .= '<div class="pvt-video-thumbnail-overlay">';
		$html .= '<img src="'.__PVT_PLUGIN_URL__.'/images/video-icon.svg" class="video-icon">';
		$html .= '</div>';
		$html .= '<div class="pvt-video-thumbnail-src">';

		$html .= $embed_src;

		$html .= '</div>';

		$html .= $embed_video;
        return $html;
    }

    public function enqueue_frontend_scripts(){
	    wp_enqueue_style( 'pvt-frontend-style', __PVT_PLUGIN_URL__ . '/css/frontend/plugin.min.css' );
	    wp_enqueue_script( 'pvt-admin-script', __PVT_PLUGIN_URL__ . '/js/frontend/frontend.min.js', ['jquery'] );
    }

    public function checkThumbnailWidth($html){
    	$sub = substr($html,strpos($html,'width="')+7);
    	$value = substr($sub,0,strpos($sub,'"'));
    	return intval($value) > 300;
    }
}
