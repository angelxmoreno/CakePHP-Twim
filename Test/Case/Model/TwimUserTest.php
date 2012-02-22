<?php

/**
 * test TwimUser
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

/**
 *
 * @property TwimUser $User
 */
class TwimUserTestCase extends TwimConnectionTestCase {

	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('Twim.TwimUser');
		$this->User->setDataSource($this->mockDatasourceName);
	}

	public function tearDown() {
		unset($this->User);
		parent::tearDown();
	}

	// =========================================================================

	public function testLookup() {
		$this->User->getDataSource()->expects($this->once())->method('request')->will($this->returnValue(false));
		$this->assertFalse($this->User->find('lookup'));
	}

	public function testLookup_user_id() {
		$this->User->getDataSource()->expects($this->once())->method('request')->will($this->returnValue(false));
		$this->User->find('lookup', array('user_id' => '1234'));
		$this->assertSame('1/users/lookup', $this->User->request['uri']['path']);
		$this->assertSame(array('user_id' => '1234'), $this->User->request['uri']['query']);
	}

	public function testLookup_array_user_id() {
		$this->User->getDataSource()->expects($this->once())->method('request')->will($this->returnValue(false));
		$this->User->find('lookup', array('user_id' => array('1234', '5678', '9876')));
		$this->assertSame('1/users/lookup', $this->User->request['uri']['path']);
		$this->assertSame(array('user_id' => '1234,5678,9876'), $this->User->request['uri']['query']);
	}

	public function testLookup_screen_name() {
		$this->User->getDataSource()->expects($this->once())->method('request')->will($this->returnValue(false));
		$this->User->find('lookup', array('screen_name' => 'abcd'));
		$this->assertSame('1/users/lookup', $this->User->request['uri']['path']);
		$this->assertSame(array('screen_name' => 'abcd'), $this->User->request['uri']['query']);
	}

	public function testLookup_array_screen_name() {
		$this->User->getDataSource()->expects($this->once())->method('request')->will($this->returnValue(false));
		$this->User->find('lookup', array('screen_name' => array('abcd', 'efgh', 'ijkl')));
		$this->assertSame('1/users/lookup', $this->User->request['uri']['path']);
		$this->assertSame(array('screen_name' => 'abcd,efgh,ijkl'), $this->User->request['uri']['query']);
	}

	public function testLookup_real_by_screen_name() {
		$this->User->setDataSource('twitter');
		$results = $this->User->find('lookup', array('screen_name' => array('cakephp', 'nojimage')));
		$results = Set::sort($results, '/id_str', 'asc');
		$screenNames = Set::extract('/screen_name', $results);
		$ids = Set::extract('/id_str', $results);
		$this->assertEquals(array('cakephp', 'nojimage'), $screenNames);
		$this->assertEquals(array('8620662', '15982041'), $ids);
	}

	public function testLookup_real_by_user_id() {
		$this->User->setDataSource('twitter');
		$results = $this->User->find('lookup', array('user_id' => array('8620662', '15982041')));
		$results = Set::sort($results, '/id_str', 'asc');
		$screenNames = Set::extract('/screen_name', $results);
		$ids = Set::extract('/id_str', $results);
		$this->assertEquals(array('cakephp', 'nojimage'), $screenNames);
		$this->assertEquals(array('8620662', '15982041'), $ids);
	}

	// =========================================================================

	public function testProfileImage() {
		$this->User->getDataSource()->expects($this->once())->method('request')->will($this->returnValue(false));
		$this->assertFalse($this->User->find('profileImage'));
	}

	public function testProfileImage_with_screen_name() {
		$this->User->getDataSource()->expects($this->once())->method('request')->will($this->returnValue(false));
		$this->User->find('profileImage', array('screen_name' => 'abcd'));
		$this->assertSame('1/users/profile_image', $this->User->request['uri']['path']);
		$this->assertSame(array('screen_name' => 'abcd'), $this->User->request['uri']['query']);
	}

	public function testProfileImage_with_size() {
		$this->User->getDataSource()->expects($this->once())->method('request')->will($this->returnValue(false));
		$this->User->find('profileImage', array('screen_name' => 'abcd', 'size' => 'bigger'));
		$this->assertSame('1/users/profile_image', $this->User->request['uri']['path']);
		$this->assertSame(array('screen_name' => 'abcd', 'size' => 'bigger'), $this->User->request['uri']['query']);
	}

	public function testProfileImage_real() {
		$this->User->setDataSource($this->testDatasourceName);
		$result = $this->User->find('profileImage', array('screen_name' => 'twitterapi'));
		$this->assertRegExp('!http://a[0-9]+\.twimg\.com/profile_images/[0-9]+/.+\.png!', $result);
	}

	// =========================================================================

	public function testSearch() {
		$this->User->getDataSource()->expects($this->once())->method('request')->will($this->returnValue(false));
		$this->User->find('search', array('q' => 'cake'));
		$this->assertSame('1/users/search', $this->User->request['uri']['path']);
		$this->assertSame(array('page' => 1, 'q' => 'cake'), $this->User->request['uri']['query']);
		$this->assertSame(true, $this->User->request['auth']);
	}

	// =========================================================================

	public function testShow() {
		$this->User->getDataSource()->expects($this->once())->method('request')->will($this->returnValue(false));
		$this->assertFalse($this->User->find('show'));
	}

	public function testShow_user_id() {
		$this->User->getDataSource()->expects($this->once())->method('request')->will($this->returnValue(false));
		$this->User->find('show', array('user_id' => '1234'));
		$this->assertSame('1/users/show', $this->User->request['uri']['path']);
		$this->assertSame(array('user_id' => '1234'), $this->User->request['uri']['query']);
	}

	public function testShow_screen_name() {
		$this->User->getDataSource()->expects($this->once())->method('request')->will($this->returnValue(false));
		$this->User->find('show', array('screen_name' => 'abcd'));
		$this->assertSame('1/users/show', $this->User->request['uri']['path']);
		$this->assertSame(array('screen_name' => 'abcd'), $this->User->request['uri']['query']);
	}

	public function testShow_real_by_screen_name() {
		$this->User->setDataSource($this->testDatasourceName);
		$results = $this->User->find('show', array('screen_name' => 'nojimage'));
		$this->assertEquals('nojimage', $results['screen_name']);
		$this->assertEquals('15982041', $results['id_str']);
	}

	public function testShow_real_by_user_id() {
		$this->User->setDataSource($this->testDatasourceName);
		$results = $this->User->find('show', array('user_id' => '8620662'));
		$this->assertEquals('cakephp', $results['screen_name']);
		$this->assertEquals('8620662', $results['id_str']);
	}

	// =========================================================================

	public function testContributees() {
		$this->User->getDataSource()->expects($this->once())->method('request')->will($this->returnValue(false));
		$this->assertFalse($this->User->find('contributees'));
	}

	public function testContributees_user_id() {
		$this->User->getDataSource()->expects($this->once())->method('request')->will($this->returnValue(false));
		$this->User->find('contributees', array('user_id' => '1234'));
		$this->assertSame('1/users/contributees', $this->User->request['uri']['path']);
		$this->assertSame(array('user_id' => '1234'), $this->User->request['uri']['query']);
	}

	public function testContributees_screen_name() {
		$this->User->getDataSource()->expects($this->once())->method('request')->will($this->returnValue(false));
		$this->User->find('contributees', array('screen_name' => 'abcd'));
		$this->assertSame('1/users/contributees', $this->User->request['uri']['path']);
		$this->assertSame(array('screen_name' => 'abcd'), $this->User->request['uri']['query']);
	}

	public function testContributees_real_by_screen_name() {
		$this->User->setDataSource($this->testDatasourceName);
		$results = $this->User->find('contributees', array('screen_name' => 'themattharris'));
		$this->assertEquals('twitterapi', $results[0]['screen_name']);
	}

	public function testContributees_real_by_user_id() {
		$this->User->setDataSource($this->testDatasourceName);
		$results = $this->User->find('contributees', array('user_id' => '819797'));
		$this->assertEquals('twitterapi', $results[0]['screen_name']);
	}

	// =========================================================================

	public function testContributors() {
		$this->User->getDataSource()->expects($this->once())->method('request')->will($this->returnValue(false));
		$this->assertFalse($this->User->find('contributors'));
	}

	public function testContributors_user_id() {
		$this->User->getDataSource()->expects($this->once())->method('request')->will($this->returnValue(false));
		$this->User->find('contributors', array('user_id' => '1234'));
		$this->assertSame('1/users/contributors', $this->User->request['uri']['path']);
		$this->assertSame(array('user_id' => '1234'), $this->User->request['uri']['query']);
	}

	public function testContributors_screen_name() {
		$this->User->getDataSource()->expects($this->once())->method('request')->will($this->returnValue(false));
		$this->User->find('contributors', array('screen_name' => 'abcd'));
		$this->assertSame('1/users/contributors', $this->User->request['uri']['path']);
		$this->assertSame(array('screen_name' => 'abcd'), $this->User->request['uri']['query']);
	}

	public function testContributors_real_by_screen_name() {
		$this->User->setDataSource($this->testDatasourceName);
		$results = $this->User->find('contributors', array('screen_name' => 'twitter'));
		$this->assertEquals('biz', $results[0]['screen_name']);
	}

	public function testContributors_real_by_user_id() {
		$this->User->setDataSource($this->testDatasourceName);
		$results = $this->User->find('contributors', array('user_id' => '783214'));
		$this->assertEquals('biz', $results[0]['screen_name']);
	}

}
