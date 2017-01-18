<?php 

namespace App\Test;
use App\ClockAngle;

Class ClockAngleTest extends \PHPUnit_Framework_TestCase 
{
	public function setUp() {
		$this->clockAngle = new ClockAngle();
		$this->reflector  = new \ReflectionClass($this->clockAngle);
	}

	/**
	 * @dataProvider timeInputs
	 */
	public function test_it_can_get_angle_degree_between_clock_hands($input, $expected, $message) {
		$angle = $this->clockAngle->getAngleBetweenClockHands($input);
		$this->assertEquals($angle, $expected, $message);
	}

	public function timeInputs() {
		return array(
			'1:00'  => array('1:00',  30, 	 'The angle between 1 and 00 is 30'),
			'1:15'  => array('1:15',  52.5,  'The angle between 1 and 15 is 52.5'),
			'2:15'  => array('2:15',  22.5,  'The angle between 2 and 15 is 22.5'),
			'2:20'  => array('2:20',  50,    'The angle between 2 and 20 is 50'),
			'2:59'  => array('2:59',  95.5,  'The angle between 2 and 59 is 95.5'),
			'3:15'  => array('3:15',  7.5,   'The angle between 3 and 15 is 7.5'),
			'7:15'  => array('7:15',  127.5, 'The angle between 7 and 15 is 127.5'),
			'10:16' => array('10:16', 148,   'The angle between 10 and 16 is 148'),
			'6:30'  => array('6:30',   15,   'The angle between 6 and 30 is 15'),
		);
	}

	public function test_hour_hand_is_at_10_and_angle_degree_should_be_300() {
		$expected = 300;
		$hour = 10;
		$minute = 0;

		$this->assertHourHandAngleDegree($hour, $minute, $expected);
	}

	public function test_hour_hand_is_at_1_and_angle_degree_should_be_30() {
		$expected = 30;
		$hour = 1;
		$minute = 0;

		$this->assertHourHandAngleDegree($hour, $minute, $expected);
	}

	public function test_hour_hand_is_at_5_and_angle_degree_should_be_150() {
		$expected = 150;
		$hour = 5;
		$minute = 0;

		$this->assertHourHandAngleDegree($hour, $minute, $expected);
	}

	public function test_minute_hand_is_at_40_and_angle_degree_should_be_240() {
		$expected = 240;
		$hour = 0;
		$minute = 40;

		$this->assertMinuteHandAngleDegree($hour, $minute, $expected);
	}

	public function test_minute_hand_is_at_33_and_angle_degree_should_be_198() {
		$expected = 198;
		$hour = 0;
		$minute = 33;

		$this->assertMinuteHandAngleDegree($hour, $minute, $expected);
	}

	public function test_minute_hand_is_at_5_and_angle_degree_should_be_90() {
		$expected = 90;
		$hour = 0;
		$minute = 15;

		$this->assertMinuteHandAngleDegree($hour, $minute, $expected);
	}

	public function test_calculate_angle_degree_between_hour_hand_is_at_1_and_minute_hand_is_at_12_in_half_rotation() {
		$expected = 36;
		$hour = 1;
		$minute = 12;

		$this->setHourAndMinuteProperty($hour, $minute);
		$calculateAngleBetweenHands = $this->getPrivateMethod('calculateAngleBetweenHands');
		$result = $calculateAngleBetweenHands->invokeArgs($this->clockAngle, array());

		$this->assertEquals($expected, $result); 
	}

	public function test_calculate_angle_degree_between_hour_hand_is_at_1_and_minute_hand_is_at_45_in_full_rotation() {
		$expected = 142.5;
		$hour = 1;
		$minute = 45;

		$this->setHourAndMinuteProperty($hour, $minute);
		$calculateAngleBetweenHands = $this->getPrivateMethod('calculateAngleBetweenHands');
		$result = $calculateAngleBetweenHands->invokeArgs($this->clockAngle, array());

		$this->assertEquals($expected, $result); 
	}

	public function test_when_angle_is_less_than_half_rotation() {
		$angle = 160;

		$isAngleMoreThanHalfRotation = $this->getPrivateMethod('isAngleMoreThanHalfRotation');
		$result = $isAngleMoreThanHalfRotation->invokeArgs($this->clockAngle, array($angle));
		$this->assertFalse($result);
	}

	public function test_when_angle_is_greater_than_half_rotation() {
		$angle = 190;

		$isAngleMoreThanHalfRotation = $this->getPrivateMethod('isAngleMoreThanHalfRotation');
		$result = $isAngleMoreThanHalfRotation->invokeArgs($this->clockAngle, array($angle));
		$this->assertTrue($result);
	}

	private function assertHourHandAngleDegree($hour, $minute, $expected) {
		$this->setHourAndMinuteProperty($hour, $minute);
		$angleBetweenTwelveAndHourHand = $this->getPrivateMethod('angleBetweenTwelveAndHourHand');
		$result = $angleBetweenTwelveAndHourHand->invokeArgs($this->clockAngle, array());
		$this->assertEquals($expected, $result);
	}

	private function assertMinuteHandAngleDegree($hour, $minute, $expected) {
		$this->setHourAndMinuteProperty($hour, $minute);
		$angleBetweenTwelveAndMinuteHand = $this->getPrivateMethod('angleBetweenTwelveAndMinuteHand');
		$result = $angleBetweenTwelveAndMinuteHand->invokeArgs($this->clockAngle, array());
		$this->assertEquals($expected, $result);
	}

	private function setHourAndMinuteProperty($hour, $minute) {
		$minute_ = $minute;
		$hour_ = $hour;

		$minute = $this->reflector->getProperty('minute');
		$minute->setAccessible(true);
		$minute->setValue($this->clockAngle, $minute_);

		$hour = $this->reflector->getProperty('hour');
		$hour->setAccessible(true);
		$hour->setValue($this->clockAngle, $hour_);
	}

	private function getPrivateMethod($methodName) {
		$method = $this->reflector->getMethod($methodName);
		$method->setAccessible(true);

		return $method;
	}
}