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

}
