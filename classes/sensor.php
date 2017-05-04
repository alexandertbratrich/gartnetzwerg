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
	
	public function update(){
		$this->set_value(random_int(0,255));//value = random_int(0,255);
		//TODO: Sensordaten aktualisieren
		// https://www.sitepoint.com/powering-raspberry-pi-projects-with-php/
		// https://github.com/ronanguilloux/php-gpio
				
		$this->set_gpio_pin_id(10);				//TODO: neeeds the right value
		//$gpio->setup(gpio_pin_id, "in");	//nicht auskommentieren, falls der Code auf PiZero is, aber der GPIO-Pin noch nicht klar ist
		//$this->value = ;
		
		echo "Unexporting all pins\n";
		//$gpio->unexportAll();
	}
}


?>