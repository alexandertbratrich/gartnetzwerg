<?php
require_once 'sensor.php';
class Watertank_fillage_sensor extends Sensor{
	private $position;
	
	public function set_position($new_position){
		$this->position = $new_position;
	}
	
	public function get_position(){
		return $this->position;
	}
	
	public function update_value(){
		$this->set_value(random_int(0, 1));
		
	}
	
	public function update($ip){
		$path = "/home/pi/gartnetzwerg/sensor_wf.py ".($this->position+3)."";
		$cmd = "sudo /var/www/html/gartnetzwerg/update_sensor.sh ".$ip." ".$path;
		$result = shell_exec($cmd);
		$this->set_value($result);
		
	}
}


?>