<?php

namespace TDS;

use Mockery;
use TDS\Tools\TestCase;
use WP_Mock;

class GetRelatedPostTest extends TestCase {

	public function test_get_related_post_noop_when_empty_taxonomy() {
		$this->assertNull( get_related_post( 1, '' ) );
	}

	public function test_get_related_post_noop_when_term_not_object_or_int() {
		WP_Mock::wpFunction( 'is_wp_error', array( 'return' => false ) );
		$this->assertNull( get_related_post( 'foobar', 'category' ) );
	}

	public function test_get_related_post_noop_when_term_is_wp_error() {
		WP_Mock::wpFunction( 'get_term', array(
			'times'  => 1,
			'args'   => array( 1, 'category' ),
			'return' => Mockery::mock( 'WP_Error' ),
		) );
		WP_Mock::wpFunction( 'is_wp_error', array( 'return' => true ) );
		$this->assertNull( get_related_post( 1, 'category' ) );
	}

	/**
	 * @runInSeparateProcess
	 */
	public function test_get_related_post_noop_when_relationship_not_set() {
		$term = (object) array(
			'term_id'  => rand( 1, 9 ),
			'taxonomy' => 'category',
		);
		WP_Mock::wpFunction( 'is_wp_error', array( 'return' => false ) );
		$this->assertNull( get_related_post( $term, $term->taxonomy ) );
	}

}
