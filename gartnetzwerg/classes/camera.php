<?php
require_once 'sensor.php';
class Camera extends Sensor{
	/*public function update(){
		/*TODO: (wurde aber noch nicht auf'm Zero getestet)
		
		if($rReturn != ""){
			$this->value = $rReturn;
		}
	}*/
	
	public function take_pic($mac_address,$plant_id,$nickname){
		
		$cmd = __DIR__."/../get_ip_address.sh ".$mac_address;
		$ip = shell_exec($cmd);
		
		$cmd = "ssh -i /home/pi/.ssh/id_rsa pi@".$ip." -t /home/pi/gartnetzwerg/take_picture.py";
		shell_exec($cmd);
				
		$cmd = "scp -i /home/pi/.ssh/id_rsa pi@".$ip.":/home/pi/Pictures/* '/home/pi/Pictures/".$plant_id."_".$nickname."/'";
		shell_exec($cmd);
		
		$cmd =  "ssh -i /home/pi/.ssh/id_rsa pi@".$ip." -t /home/pi/gartnetzwerg/remove_picture.sh";
		shell_exec($cmd);

		$cmd = "sudo -u root chown -R www-data:www-data /home/pi/Pictures/";
		shell_exec($cmd);
	}
	
	public function set_cam(){
		
	}
}

?>
