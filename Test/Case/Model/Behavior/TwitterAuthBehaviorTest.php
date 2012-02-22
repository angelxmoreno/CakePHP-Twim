<?php

/**
 * Twitter Authenticatable Behavior Test Case
 *
 * CakePHP 2.0
 * PHP version 5
 *
 * Copyright 2012, nojimage (http://php-tips.com/)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @version   2.0
 * @author    nojimage <nojimage at gmail.com>
 * @copyright 2012 nojimage (http://php-tips.com/)
 * @license   http://www.opensource.org/licenses/mit-license.php The MIT License
 * @package   Twim
 * @since     File available since Release 1.0
 *
 */
App::uses('TwimConnectionTestCase', 'Twim.TestSuite');
App::uses('Model', 'Model');

class TwitterAuthBehaviorTestModel extends Model {

	public $name = 'TwitterUser';

	public $alias = 'TwitterUser';

	public $useTable = false;

	public $actsAs = array(
		'Twim.TwitterAuth',
	);

	public $_schema = array(
		'id' => true,
		'username' => true,
		'password' => true,
		'oauth_token' => true,
		'oauth_token_secret' => true,
	);

}

/**
 * @property TwitterAuthBehaviorTestModel $Model
 */
class TwitterAuthBehaviorTest extends TwimConnectionTestCase {

	/**
	 * startTest method
	 *
	 * @access public
	 * @return void
	 */
	public function setUp() {
		parent::setUp();
		$this->Model = ClassRegistry::init('TwitterAuthBehaviorTestModel');
	}

	/**
	 * endTest method
	 *
	 * @access public
	 * @return void
	 */
	public function tearDown() {
		unset($this->Model);
		parent::tearDown();
	}

	public function testCreateSaveDataByToken() {
		$data = array(
			'user_id' => '123456789',
			'screen_name' => 'dummy_user',
			'oauth_token' => 'dummy token',
			'oauth_token_secret' => 'dummy secret token',
		);
		$ok = array(
			'TwitterUser' => array(
				'id' => '123456789',
				'username' => 'dummy_user',
				'oauth_token' => 'dummy token',
				'oauth_token_secret' => 'dummy secret token',
				'password' => 'ae9277742549f954cb43408b44fd3610a5b5e9db',
			),
		);
		$this->assertSame($ok, $this->Model->createSaveDataByToken($data));
	}

}
