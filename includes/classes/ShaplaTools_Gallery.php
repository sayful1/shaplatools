<?php

/** 
 * The Class.
 */
class ShaplaTools_Gallery {

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {

		add_action( 'add_meta_boxes', array( $this, 'gallery_meta_box' ) );
		add_action( 'wp_ajax_save_images', array( $this, 'save_images' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'metabox_scripts' ) );
	}

	public function gallery_meta_box() {

	    $screens = array('post', 'page');

	    foreach ( $screens as $screen) {

	        add_meta_box(
	            'shaplatools_gallery',
	            __( 'Image Gallery', 'shapla' ),
	            array( $this, 'shaplatools_image_gallery_callback'),
	            $screen,
	            'normal',
	            'low'
	        );
	    }

	}

	public function shaplatools_image_gallery_callback(){

	    global $post;
	?>
	<table class="form-table">
			<tr valign="top">
				<script>
					jQuery(function($){
						var frame,
						 	images = '<?php echo get_post_meta( get_the_ID(), '_shaplatools_image_gallery', true ); ?>',
						    selection = loadImages(images);

						$('#shaplatools_images_upload').on('click', function(e) {
						    e.preventDefault();
						    var options = {
						        title: '<?php _e("Create Featured Gallery", "sp-image-carousel"); ?>',
						        state: 'gallery-edit',
						        frame: 'post',
						        selection: selection
						    };

						    if( frame || selection ) {
						        options['title'] = '<?php _e("Edit Featured Gallery", "sp-image-carousel"); ?>';
						    }

						    frame = wp.media(options).open();
						            
						    // Tweak Views
						    frame.menu.get('view').unset('cancel');
						    frame.menu.get('view').unset('separateCancel');
						    frame.menu.get('view').get('gallery-edit').el.innerHTML = '<?php _e("Edit Featured Gallery", "sp-image-carousel"); ?>';
						    frame.content.get('view').sidebar.unset('gallery'); // Hide Gallery Settings in sidebar

						    // when editing a gallery
						    overrideGalleryInsert();
						    frame.on( 'toolbar:render:gallery-edit', function() {
						        overrideGalleryInsert();
						    });

						    frame.on( 'content:render:browse', function( browser ) {
						        if ( !browser ) return;
						        // Hide Gallery Settings in sidebar
						        browser.sidebar.on('ready', function(){
						            browser.sidebar.unset('gallery');
						        });
						        // Hide filter/search as they don't work
						        browser.toolbar.on('ready', function(){
						            if(browser.toolbar.controller._state == 'gallery-library'){
						                browser.toolbar.$el.hide();
						            }
						        });
						    });

						    // All images removed
						    frame.state().get('library').on( 'remove', function() {
						        var models = frame.state().get('library');
						        if(models.length == 0){
						            selection = false;
						            $.post(ajaxurl, { ids: '', action: 'save_images', post_id: shapla_meta_ajax.post_id, nonce: shapla_meta_ajax.nonce });
						        }
						    });

				            function overrideGalleryInsert(){
				                frame.toolbar.get('view').set({
				                    insert: {
				                        style: 'primary',
				                        text: '<?php _e("Save Featured Gallery", "sp-image-carousel"); ?>',
				                        click: function(){
				                            var models = frame.state().get('library'),
				                                ids = '';

				                            models.each( function( attachment ) {
				                                ids += attachment.id + ','
				                            });

				                            this.el.innerHTML = '<?php _e("Saving...", "sp-image-carousel"); ?>';

				                            $.ajax({
				                                type: 'POST',
				                                url: ajaxurl,
				                                data: { 
				                                    ids: ids, 
				                                    action: 'save_images', 
				                                    post_id: shapla_meta_ajax.post_id, 
				                                    nonce: shapla_meta_ajax.nonce 
				                                },
				                                success: function(){
				                                    selection = loadImages(ids);
				                                    $('#_shaplatools_image_gallery').val( ids );
				                                    frame.close();
				                                },
				                                dataType: 'html'
				                            }).done( function( data ) {
				                                $('.spic-gallery-thumbs').html( data );
				                                console.log(data);
				                            });
				                        }
				                    }
				                });
				            }

				        });

				        function loadImages(images){
				            if (images){
				                var shortcode = new wp.shortcode({
				                    tag:      'gallery',
				                    attrs:    { ids: images },
				                    type:     'single'
				                });

				                var attachments = wp.media.gallery.attachments( shortcode );

				                var selection = new wp.media.model.Selection( attachments.models, {
				                    props:    attachments.props.toJSON(),
				                    multiple: true
				                });

				                selection.gallery = attachments.gallery;

				                selection.more().done( function() {
				                    // Break ties with the query.
				                    selection.props.set({ query: false });
				                    selection.unmirror();
				                    selection.props.unset('orderby');
				                });
				                
				                return selection;
				            }
				            return false;
				        }
				    });
				    </script>
				    <?php

				    $meta = get_post_meta( get_the_ID(), '_shaplatools_image_gallery', true );
				    $thumbs_output = '';
				    $button_text = __('Add Gallery', 'sp-image-carousel');
				    if( $meta ) {
				    	$button_text = __('Edit Gallery', 'sp-image-carousel');
				        $thumbs = explode(',', $meta);
				        $thumbs_output = '';
				        foreach( $thumbs as $thumb ) {
				            $thumbs_output .= '<li>' . wp_get_attachment_image( $thumb, 'thumbnail' ) . '</li>';
				        }
				    }
				    ?>

				    <td class="spic-box-images">
				        <ul class="spic-gallery-thumbs"><?php echo $thumbs_output; ?></ul>
				        <p>
				        <input type="button" class="button" name="_shaplatools_image_gallery" id="shaplatools_images_upload" value="<?php echo $button_text; ?>" />
				        <input type="hidden" name="shapla_meta[_shaplatools_image_gallery]" id="_shaplatools_image_gallery" value="<?php echo ($meta ? $meta : 'false'); ?>" />
				        </p>
				    </td>
				</tr>
			</table>
	<?php
	}

	public function save_images(){
	    if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
	        return;
	    }

	    if ( !isset($_POST['ids']) || !isset($_POST['nonce']) || !wp_verify_nonce( $_POST['nonce'], 'shapla_meta-ajax' ) ){
	        return;
	    }

	    if ( !current_user_can( 'edit_posts' ) ) return;

	    $ids = strip_tags(rtrim($_POST['ids'], ','));
	    update_post_meta($_POST['post_id'], '_shaplatools_image_gallery', $ids);

	    $thumbs = explode(',', $ids);
	    $thumbs_output = '';
	    foreach( $thumbs as $thumb ) {
	        $thumbs_output .= '<li>' . wp_get_attachment_image( $thumb, 'thumbnail' ) . '</li>';
	    }

	    echo $thumbs_output;

	    die();
	}

	public function metabox_scripts(){
	    global $post;
	    if( isset($post) ) {
	        wp_localize_script( 'jquery', 'shapla_meta_ajax', array(
	            'post_id' => $post->ID,
	            'nonce' => wp_create_nonce( 'shapla_meta-ajax' )
	        ) );
	    }
	}

} // end class ShaplaTools_Gallery()

if( is_admin() )
    $shaplatools_gallery = new ShaplaTools_Gallery();