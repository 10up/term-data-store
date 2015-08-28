<?php

namespace TDS;

use TDS\Tools\TestCase;
use WP_Mock;

class SaveTermCallbackTest extends TestCase {

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
		call_user_func( get_save_term_hook( 'foo', 'bar' ), 1 );
		$this->assertConditionsMet();
	}

	public function test_callback_noop_when_term_is_empty() {
		WP_Mock::userFunction( 'wp_set_object_terms', array( 'times' => 0 ) );
		call_user_func( get_save_term_hook( 'foo', 'bar' ), 0 );
		$this->assertConditionsMet();
	}

	public function test_callback_noop_when_term_has_objects_of_post_type() {
		$term_id      = rand( 1, 9 );
		$post_type    = 'post';
		$taxonomy     = 'category';
		$post1        = $this->mockPost( array( 'ID' => rand( 10, 19 ), 'post_type' => 'page' ) );
		$post2        = $this->mockPost( array( 'ID' => rand( 20, 29 ), 'post_type' => 'post' ) );
		$term_objects = array( $post1->ID, $post2->ID );
		WP_Mock::wpFunction( 'get_objects_in_term', array(
			'times'  => 1,
			'args'   => array( $term_id, $taxonomy ),
			'return' => $term_objects,
		) );
		WP_Mock::wpFunction( 'get_post_type', array(
			'times'  => 1,
			'args'   => array( $post1->ID ),
			'return' => $post1->post_type,
		) );
		WP_Mock::wpFunction( 'get_post_type', array(
			'times'  => 1,
			'args'   => array( $post2->ID ),
			'return' => $post2->post_type,
		) );
		WP_Mock::userFunction( 'wp_set_object_terms', array( 'times' => 0 ) );

		call_user_func( get_save_term_hook( $post_type, $taxonomy ), $term_id );
		$this->assertConditionsMet();
	}

}
