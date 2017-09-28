<?php

if ( !class_exists('Shapla_Retina_2x')):

class Shapla_Retina_2x
{
	
	public function __construct() {
        add_filter( 'wp_generate_attachment_metadata', array( $this, 'retina_support_attachment_meta' ), 10, 2 );
		add_filter( 'delete_attachment', array( $this, 'delete_retina_support_images' ) );
	}

	public function retina_support_attachment_meta( $metadata, $attachment_id ) {
        foreach ( $metadata as $key => $value ) {
            if ( is_array( $value ) ) {
                foreach ( $value as $image => $attr ) {
                    if ( is_array( $attr ) )
                        $this->retina_support_create_images( get_attached_file( $attachment_id ), $attr['width'], $attr['height'], true );
                }
            }
        }
     
        return $metadata;
    }

    public function retina_support_create_images( $file, $width, $height, $crop = false ) {
        if ( $width || $height ) {
            $resized_file = wp_get_image_editor( $file );
            if ( ! is_wp_error( $resized_file ) ) {
                $filename = $resized_file->generate_filename( $width . 'x' . $height . '@2x' );
     
                $resized_file->resize( $width * 2, $height * 2, $crop );
                $resized_file->save( $filename );
     
                $info = $resized_file->get_size();
     
                return array(
                    'file' => wp_basename( $filename ),
                    'width' => $info['width'],
                    'height' => $info['height'],
                );
            }
        }
        return false;
    }

    public function delete_retina_support_images( $attachment_id ) {
        $meta = wp_get_attachment_metadata( $attachment_id );
        $upload_dir = wp_upload_dir();
        $path = pathinfo( $meta['file'] );
        foreach ( $meta as $key => $value ) {
            if ( 'sizes' === $key ) {
                foreach ( $value as $sizes => $size ) {
                    $original_filename = $upload_dir['basedir'] . '/' . $path['dirname'] . '/' . $size['file'];
                    $retina_filename = substr_replace( $original_filename, '@2x.', strrpos( $original_filename, '.' ), strlen( '.' ) );
                    if ( file_exists( $retina_filename ) )
                        unlink( $retina_filename );
                }
            }
        }
    }
}

endif;

function run_shapla_retina_2x() {

    $options = get_option('shaplatools_options');

    if( is_admin() && isset($options['retina_graphics']) && $options['retina_graphics'] == 'on' ){
        
        $shapla_retina_2x = new Shapla_Retina_2x();

    }
}

run_shapla_retina_2x();