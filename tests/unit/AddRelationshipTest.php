<?php

namespace TDS;

use TDS\Tools\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
class AddRelationshipTest extends TestCase {

	/**
	 * @expectedException \TDS\Invalid_Input_Exception
	 * @expectedExceptionMessage TDS\add_relationship() invalid post_type input.
	 */
	public function test_add_relationship_invalid_post_type() {
		$post_type = 'post' . rand( 0, 9 );
		\WP_Mock::userFunction( 'get_post_type_object', array(
			'times'  => 1,
			'args'   => array( $post_type ),
			'return' => null,
		) );
		add_relationship( $post_type, 'taxonomy' );
	}

	/**
	 * @expectedException \TDS\Invalid_Input_Exception
	 * @expectedExceptionMessage TDS\add_relationship() invalid taxonomy input.
	 */
	public function test_add_relationship_invalid_taxonomy() {
		$post_type = 'post' . rand( 0, 9 );
		$taxonomy  = 'post_tag' . rand( 0, 9 );
		\WP_Mock::userFunction( 'get_post_type_object', array( 'return' => (object) array( 'name' => $post_type ) ) );
		\WP_Mock::userFunction( 'get_taxonomy', array(
			'times'  => 1,
			'args'   => array( $taxonomy ),
			'return' => null,
		) );
		add_relationship( $post_type, $taxonomy );
	}

}
