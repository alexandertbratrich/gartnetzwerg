<?php

//use PhpGpio\Gpio;

abstract class Sensor{
	
	private $sensor_id;
	private $value;
	private $gpio_pin_id;
	//private $gpio = new GPIO();
	
	public function set_sensor_id ($new_sensor_id){
		$this->sensor_id = $new_sensor_id;
	}
	
	public function set_value ($new_value){
		$this->value = $new_value;
	}
	
	public function set_gpio_pin_id($new_gpio_pin_id){
		$this->gpio_pin_id = $new_gpio_pin_id;
	}
	
	public function get_sensor_id(){
		return $this->sensor_id;
	}
	
	public function get_value(){
		return $this->value;
	}
	
	public function get_gpio_pin_id(){
		return $this->gpio_pin_id;
	}
	
	public function update_value(){
		$this->set_value(random_int(0,255));
	}
	
	public function update(){
		$this->update_value();
		//TODO: Sensordaten aktualisieren
		
		/* Für Montag:
		 * - https://www.sitepoint.com/powering-raspberry-pi-projects-with-php/
		 * - https://github.com/ronanguilloux/php-gpio
		 * - https://github.com/PiPHP/GPIO
		 * - https://raspberrypi.stackexchange.com/questions/7365/php-to-execute-python-scripts-for-gpio
		 * - http://wiringpi.com/
		 * - http://www.raspberry-pi-geek.com/Archive/2014/07/PHP-on-Raspberry-Pi
		 * - https://captainbodgit.blogspot.de/2015/04/raspberry-pi-gpio-control-with-php.html
		 */
		
		$this->set_gpio_pin_id(10);	//TODO: neeeds the right pin
		
		//$gpio->setup(gpio_pin_id, "in");	//nicht auskommentieren, falls der Code auf PiZero is, aber der GPIO-Pin noch nicht klar ist
		//$this->value = ;
		
		//exec("gpio read 1", $status);
		//print_r($status); //or var_dump($status);
		
		echo "Unexporting all pins\n";
		//$gpio->unexportAll();
	}
}


?>