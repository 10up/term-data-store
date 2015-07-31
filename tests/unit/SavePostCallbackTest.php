<?php

namespace TDS;

use TDS\Tools\TestCase;
use WP_Mock;

class SavePostCallbackTest extends TestCase {

	public function setUp() {
		parent::setUp();
		balancing_relationship( false );
	}

	public function tearDown() {
		balancing_relationship( false );
		parent::tearDown();
	}

	public function test_callback_noop_when_balancing_tags() {
		balancing_relationship( true );
		WP_Mock::userFunction( 'wp_set_object_terms', array( 'times' => 0 ) );
		$post = (object) array( 'ID' => rand( 1, 9 ) );
		call_user_func( get_save_post_hook( 'foo', 'bar' ), $post->ID, $post );
		$this->assertConditionsMet();
	}

	public function test_callback_filter_can_short_circuit() {
		$post_type = 'post';
		$taxonomy  = 'category';
		$post      = (object) array(
			'ID'        => rand( 1, 9 ),
			'post_type' => $post_type,
		);
		balancing_relationship( false );
		WP_Mock::onFilter( 'tds_balancing_from_post' )
		       ->with( false, $post_type, $taxonomy, $post )
		       ->reply( true );
		WP_Mock::userFunction( 'wp_set_object_terms', array( 'times' => 0 ) );
		call_user_func( get_save_post_hook( $post_type, $taxonomy ), $post->ID, $post );
		$this->assertConditionsMet();
	}

	public function test_callback_noop_on_empty_post() {
		WP_Mock::userFunction( 'wp_set_object_terms', array( 'times' => 0 ) );
		call_user_func( get_save_post_hook( 'foo', 'bar' ), 1, null );
		$this->assertConditionsMet();
	}

	public function test_callback_noop_on_wrong_post_type() {
		WP_Mock::userFunction( 'wp_set_object_terms', array( 'times' => 0 ) );
		$post = (object) array( 'ID' => 1, 'post_type' => 'post' );
		call_user_func( get_save_post_hook( 'foo', 'bar' ), $post->ID, $post );
		$this->assertConditionsMet();
	}

	public function test_callback_noop_on_wrong_status() {
		WP_Mock::userFunction( 'wp_set_object_terms', array( 'times' => 0 ) );
		$post = (object) array( 'ID' => 1, 'post_type' => 'post', 'post_status' => 'draft' );
		call_user_func( get_save_post_hook( 'post', 'bar' ), $post->ID, $post );
		$this->assertConditionsMet();
	}

	public function test_callback_noop_on_term_exists() {
		WP_Mock::userFunction( 'wp_set_object_terms', array( 'times' => 0 ) );
		$post = (object) array( 'ID' => 1, 'post_type' => 'post', 'post_status' => 'publish' );
		WP_Mock::userFunction( 'get_the_terms', array(
			'times'  => 1,
			'args'   => array( 1, 'category' ),
			'return' => array(
				(object) array(
					'term_id' => 2,
					'name'    => 'Cat',
					'slug'    => 'cat',
				)
			)
		) );
		call_user_func( get_save_post_hook( 'post', 'category' ), $post->ID, $post );
		$this->assertConditionsMet();
	}

}
