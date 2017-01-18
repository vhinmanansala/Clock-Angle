<?php 

namespace App;

Class ClockAngle
{
	const FULL_ROTATION_DEGREES = 360;
	const HALF_ROTATION_DEGREES = 180;
	const HOUR_HAND_DEGREE_ROTATION_PER_MINUTES = 0.5;
	const MINUTE_HAND_DEGREE_ROTATION_PER_MINUTES = 6;
	const ONE_HOUR_IN_MINUTES = 60;

	private $hour;
	private $minute;

	public function getAngleBetweenClockHands($time) { 
		$this->hour    = explode(':', $time)[0];
		$this->minute  = explode(':', $time)[1];

		return $this->calculateAngleBetweenHands();
	}

	private function angleBetweenTwelveAndHourHand() {
		return self::HOUR_HAND_DEGREE_ROTATION_PER_MINUTES * (self::ONE_HOUR_IN_MINUTES * $this->hour + $this->minute);
	}

	private function angleBetweenTwelveAndMinuteHand() {
		return self::MINUTE_HAND_DEGREE_ROTATION_PER_MINUTES * $this->minute;
	}

	private function calculateAngleBetweenHands() {
		$angle = abs($this->angleBetweenTwelveAndHourHand() - $this->angleBetweenTwelveAndMinuteHand());

		if ($this->isAngleMoreThanHalfRotation($angle)) {
			return self::FULL_ROTATION_DEGREES - $angle;
		}

		return $angle;
	}

	private function isAngleMoreThanHalfRotation($angle) {
		return $angle > self::HALF_ROTATION_DEGREES;
	}
}