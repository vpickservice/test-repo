<?php
/*
Plugin Name: Reviews Custom Post Types
Plugin URI: http://demo.djmimi.net/themes/reviews
Description: Reviews Custom Post Types
Version: 1.4
Author: DJMiMi
Author URI: http://themeforest.net/user/DJMiMi/
License: GNU General Public License version 3.0
*/

function reviews_register_types(){
	
	$review_args = array(
		'labels' => array(
			'name' => __( 'Reviews', 'reviews-cpt' ),
			'singular_name' => __( 'Review', 'reviews-cpt' )
		),
		'public' => true,
		'menu_icon' => 'dashicons-star-filled',
		'has_archive' => false,
		'supports' => array(
			'title',
			'editor',
			'thumbnail',
			'excerpt',
			'comments',
			'author'
		)
	);

	if( class_exists('ReduxFramework') && function_exists('reviews_get_option') ){
		$trans_review = reviews_get_option( 'trans_review' );
		if( !empty( $trans_review ) ){
			$review_args['rewrite'] = array( 'slug' => $trans_review );
		}
	}
	register_post_type( 'review', $review_args );

	register_post_type( 'mega_menu', array(
		'labels' => array(
			'name' => __( 'Mega Menus', 'reviews-cpt' ),
			'singular_name' => __( 'Mega Menu', 'reviews-cpt' )
		),
		'public' => true,
		'menu_icon' => 'dashicons-editor-insertmore',
		'has_archive' => false,
		'supports' => array(
			'title',
			'editor',
		)
	));

	$taxonomies = array(
		array(
			'slug' => 'review-category',
			'plural' => __( 'Categories', 'reviews-cpt' ),
			'singular' => __( 'Category', 'reviews-cpt' ),
			'hierarchical' => true
		),
		array(
			'slug' => 'review-tag',
			'plural' => __( 'Tags', 'reviews-cpt' ),
			'singular' => __( 'Tag', 'reviews-cpt' ),
			'hierarchical' => false
		),
	);
	if( class_exists('ReduxFramework') && function_exists('reviews_get_option') ){
		$trans_review_category = reviews_get_option( 'trans_review-category' );
		if( !empty( $trans_review_category ) ){
			$taxonomies[0]['rewrite'] = $trans_review_category;
		}

		$trans_review_tag = reviews_get_option( 'trans_review-tag' );
		if( !empty( $trans_review_tag ) ){
			$taxonomies[1]['rewrite'] = $trans_review_tag;
		}		
	}	

	for( $i=0; $i<sizeof( $taxonomies ); $i++ ){
		$val = $taxonomies[$i];
		$tax_args = array(
			'label' => $val['plural'],
			'hierarchical' => $val['hierarchical'],
			'labels' => array(
				'name' 							=> $val['plural'],
				'singular_name' 				=> $val['singular'],
				'menu_name' 					=> $val['singular'],
				'all_items'						=> __( 'All ', 'reviews-cpt' ).$val['plural'],
				'edit_item'						=> __( 'Edit ', 'reviews-cpt' ).$val['singular'],
				'view_item'						=> __( 'View ', 'reviews-cpt' ).$val['singular'],
				'update_item'					=> __( 'Update ', 'reviews-cpt' ).$val['singular'],
				'add_new_item'					=> __( 'Add New ', 'reviews-cpt' ).$val['singular'],
				'new_item_name'					=> __( 'New ', 'reviews-cpt').$val['singular'].__( ' Name', 'reviews-cpt' ),
				'parent_item'					=> __( 'Parent ', 'reviews-cpt' ).$val['singular'],
				'parent_item_colon'				=> __( 'Parent ', 'reviews-cpt').$val['singular'].__( ':', 'reviews-cpt' ),
				'search_items'					=> __( 'Search ', 'reviews-cpt' ).$val['plural'],
				'popular_items'					=> __( 'Popular ', 'reviews-cpt' ).$val['plural'],
				'separate_items_with_commas'	=> __( 'Separate ', 'reviews-cpt').strtolower( $val['plural'] ).__( ' with commas', 'reviews-cpt' ),
				'add_or_remove_items'			=> __( 'Add or remove ', 'reviews-cpt' ).strtolower( $val['plural'] ),
				'choose_from_most_used'			=> __( 'Choose from the most used ', 'reviews-cpt' ).strtolower( $val['plural'] ),
				'not_found'						=> __( 'No ', 'rereviews-cptiews' ).strtolower( $val['plural'] ).__( ' found', 'reviews-cpt' ),
			),
			'rewrite' => !empty( $val['rewrite'] ) ? $val['rewrite'] : $val['slug']

		);
	
		if( !empty( $val['rewrite'] ) ){
			$tax_args['rewrite'] = array( 'slug' => $val['rewrite'] );
		}

		if( $val['hierarchical'] === true ){
			if( is_array( $tax_args['rewrite'] ) ){
				$tax_args['rewrite']['hierarchical'] = true;
			}
			else{
				$tax_args['rewrite'] = array( 'hierarchical' => true );
			}
		}
		

		register_taxonomy( $val['slug'], array( 'review' ), $tax_args );
	}
}

add_action( 'init', 'reviews_register_types' );

add_action( 'plugins_loaded', 'reviews_plugin_domain' );
function reviews_plugin_domain() {
  load_plugin_textdomain( 'reviews-cpt', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

?>