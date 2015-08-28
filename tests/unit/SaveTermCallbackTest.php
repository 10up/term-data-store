<?php

namespace TDS;

use TDS\Tools\TestCase;

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
		$this->markTestIncomplete();
	}

}
