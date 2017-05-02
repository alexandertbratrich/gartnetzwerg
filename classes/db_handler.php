<?php
require_once (__DIR__.'/../config.php');
require_once 'plant.php';

class DB_Handler{
	
	private $mysqli;
	private $plants;
	private $plant_ids;
	private $sensorunits;
	
	
	public function connect_sql(){
		
		
		$this->mysqli = mysqli_connect(HOST, USER, PASS, DATABASE);
		
		// Logging
		$logtext = "\n".date('c')."	Connect to Database\n";
		$this->write_log($logtext);
		
	}
	
	public function disconnect_sql(){
		
		mysqli_close($this->mysqli);
		
		// Logging
		$logtext = date('c')."	Disconnect Database\n\n";
		$this->write_log($logtext);
	}
	
	public function fetch_plant_ids(){
		
		$query = "SELECT plant_id FROM plants;";
		$result = mysqli_query($this->mysqli, $query);
		
		//$plant_ids = mysqli_fetch_all($result);
		
		$this->plant_ids = [];
		while($plant_ids = mysqli_fetch_array($result,MYSQLI_NUM)){
			$this->plant_ids[] = $plant_ids[0];
		}
		
		// Logging
		$logtext = "\n".date('c')."	fetch_plant_ids()\n";
		$logtext = $logtext.date('c')."  	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."  	Result:	";
		for($i = 0; $i < count($this->plant_ids); $i++){
			$logtext = $logtext."[".$this->plant_ids[$i][0]."]";
		}
		$logtext = $logtext."\n\n";
		$this->write_log($logtext);
		
	}
	
	public function fetch_family(){
		
	}
	
	public function fetch_genus(){
		
	}
	
	public function fetch_all_plants(){
		
		// Logging
		$logtext = date('c')." 	fetch_all_plants()\n";
		$this->write_log($logtext);
		
		$this->fetch_plant_ids();	
		$plant_ids = $this->plant_ids;
		$season_id = $this->fetch_season();
		
		for($i = 0; $i < count($plant_ids); $i++){
			
			$plant_id = $this->plant_ids[$i];
			
			$plant = new Plant();
			
			$plant->set_plant_id($plant_id);
			
			$species_id = $this->fetch_species_id($plant_id);
			$plant->set_species_id($species_id);
			
			$scientific_name = $this->fetch_scientific_name($species_id);
			$plant->set_scientific_name($scientific_name);
			
			$nickname = $this->fetch_nickname($plant_id);
			$plant->set_nichname($nickname);
	
			$min_light_hours = $this->fetch_min_light_hours($species_id, $season_id);
			$max_light_hours = $this->fetch_max_light_hours($species_id, $season_id);
			$plant->set_light_hours($min_light_hours, $max_light_hours);
			
			$min_soil_humidity = $this->fetch_min_soil_humidity($species_id, $season_id);
			$max_soil_humidity = $this->fetch_max_soil_humidity($species_id, $season_id);
			$plant->set_soil_humidity($min_soil_humidity, $max_soil_humidity);
			
			$tolerated_waterlogging = $this->fetch_tolerated_waterlogging($species_id, $season_id);
			$plant->set_tolerated_waterlogging($tolerated_waterlogging);
			
			$min_temperature = $this->fetch_min_temperature($species_id, $season_id);
			$max_temperature = $this->fetch_max_temperature($species_id, $season_id);
			$plant->set_temperature($min_temperature, $max_temperature);
			
			$min_watering_period = $this->fetch_min_watering_period($species_id, $season_id);
			$max_watering_period = $this->fetch_max_watering_period($species_id, $season_id);
			$plant->set_watering_period($min_watering_period, $max_watering_period);
			
			$min_fertilizer_period = $this->fetch_min_fertilizer_period($species_id, $season_id);
			$max_fertilizer_period = $this->fetch_max_fertilizer_period($species_id, $season_id);
			$plant->set_fertilizer_period($min_fertilizer_period, $max_fertilizer_period);
			
			$indoor = $this->fetch_indoor($plant_id);
			$plant->set_is_indoor($indoor);
			
			$location = $this->fetch_location($plant_id);
			$plant->set_location($location);
			
			$birthdate = $this->fetch_birthdate($plant_id);
			$plant->set_birthdate($birthdate);
			
			$this->plants[$plant_id] = $plant;
		}
		
	}
	
	public function fetch_species_id($plant_id){
		
		$query = "SELECT species_id FROM plants WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$species_id = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_species_id(plant_id: ".$plant_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')." 	Result: ".$species_id[0]."\n\n";
		$this->write_log($logtext);
		
		return $species_id[0];
		
	}
	
	public function fetch_name(){
		
	}
	
	public function fetch_nickname($plant_id){
		
		$query = "SELECT nickname FROM plants WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$nickname = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_nickname(plant_id: ".$plant_id.")\n";
		$logtext = $logtext.date('c')." 	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$nickname[0]."\n\n";
		$this->write_log($logtext);
		
		return $nickname[0];
		
	}
	
	public function fetch_scientific_name($species_id){
		
		$query = "SELECT scientific_name FROM species WHERE species_id = ".$species_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$scientific_name = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_scientific_name(species_id: ".$species_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$scientific_name[0]."\n";
		$this->write_log($logtext);
		
		return $scientific_name[0];
	}
	
	public function fetch_min_light_hours($species_id, $season_id){
		
		$query = "SELECT min_light_hours FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$min_light_hours = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_min_light_hours(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$min_light_hours[0]."\n";
		$this->write_log($logtext);
		
		return $min_light_hours[0];
	}
	
	public function fetch_max_light_hours($species_id, $season_id){
		
		$query = "SELECT max_light_hours FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$max_light_hours = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_max_light_hours(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$max_light_hours[0]."\n";
		$this->write_log($logtext);
		
		return $max_light_hours[0];
	}
	
	public function fetch_min_soil_humidity($species_id, $season_id){
		
		$query = "SELECT min_soil_humidity FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$min_soil_humidity = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_min_soil_humidity(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$min_soil_humidity[0]."\n";
		$this->write_log($logtext);
		
		return $min_soil_humidity[0];
	}
	
	public function fetch_max_soil_humidity($species_id, $season_id){
		
		$query = "SELECT max_soil_humidity FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$max_soil_humidity = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_max_soil_humidity(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$max_soil_humidity[0]."\n";
		$this->write_log($logtext);
		
		return $max_soil_humidity[0];
	}
	
	public function fetch_tolerated_waterlogging($species_id, $season_id){
		
		$query = "SELECT waterlogging FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$waterlogging = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_waterlogging(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$waterlogging[0]."\n";
		$this->write_log($logtext);
		
		return $waterlogging[0];
	
	}
	
	public function fetch_min_temperature($species_id, $season_id){
		
		$query = "SELECT min_temp FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$min_temperature = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_min_temperature(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$min_temperature[0]."\n";
		$this->write_log($logtext);
		
		return $min_temperature[0];
		
	}
	
	public function fetch_max_temperature($species_id, $season_id){
		
		$query = "SELECT max_temp FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$max_temperature = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_max_temperature(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$max_temperature[0]."\n";
		$this->write_log($logtext);
		
		return $max_temperature[0];
		
	}
	
	public function fetch_lux(){
		
	}
	
	public function fetch_min_watering_period($species_id, $season_id){
		
		$query = "SELECT min_watering_period FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$min_watering_period = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_min_watering_period(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$min_watering_period[0]."\n";
		$this->write_log($logtext);
		
		return $min_watering_period[0];
		
	}
	
	public function fetch_max_watering_period($species_id, $season_id){
		
		$query = "SELECT max_watering_period FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$max_watering_period = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_max_watering_period(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$max_watering_period[0]."\n";
		$this->write_log($logtext);
		
		return $max_watering_period[0];
		
	}
	
	public function fetch_min_fertilizer_period($species_id, $season_id){
		
		$query = "SELECT min_fertilizing_period FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$min_fertilizer_period = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_min_fertilizer_period(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$min_fertilizer_period[0]."\n";
		$this->write_log($logtext);
		
		return $min_fertilizer_period[0];
		
	}
	
	public function fetch_max_fertilizer_period($species_id, $season_id){
		
		$query = "SELECT max_fertilizing_period FROM brawndo WHERE species_id = ".$species_id." AND season_id = ".$season_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$max_fertilizer_period = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_max_fertilizer_period(species_id: ".$species_id.", season_id: ".$season_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$max_fertilizer_period[0]."\n";
		$this->write_log($logtext);
		
		return $max_fertilizer_period[0];
		
	}
	
	public function fetch_indoor($plant_id){
		
		$query = "SELECT is_indoor from plants WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$is_indoor = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_indoor(plant_id: ".$plant_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$is_indoor[0]."\n";
		$this->write_log($logtext);
		
		return $is_indoor[0];
		
	}
	
	public function fetch_location($plant_id){
		
		$query = "SELECT location from plants WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$location = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_location(plant_id: ".$plant_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$location[0]."\n";
		$this->write_log($logtext);
		
		return $location[0];
		
	}
	
	public function fetch_birthdate($plant_id){
		
		$query = "SELECT birthday from plants WHERE plant_id = ".$plant_id.";";
		$result = mysqli_query($this->mysqli, $query);
		$birthdate = mysqli_fetch_array($result);
		
		// Logging
		$logtext = "\n".date('c')."	fetch_birthdate(plant_id: ".$plant_id.")\n";
		$logtext = $logtext.date('c')."	SQL Query: ".$query."\n";
		$logtext = $logtext.date('c')."	Result: ".$birthdate[0]."\n";
		$this->write_log($logtext);
		
		return $birthdate[0];
		
	}
	
	public function fetch_season(){
		
		$season_id = 1;
		if(date('m')> 3){
			$season_id = 2;
			if(date('m')>10){
				$season_id = 1;
			}
		}
		// Logging
		$logtext = "\n".date('c')."	fetch_season()\n";
		$logtext = $logtext.date('c')."	season_id: ".$season_id."\n";
		$this->write_log($logtext);
		
		return $season_id;
	}
	
	public function init(){
		
	}
	
	public function put_soil_humidity_top(){
		
	}
	
	public function put_soil_humidity_bottom(){
		
	}
	
	public function put_air_moisture(){
		
	}
	
	public function put_lux(){
		
	}
	
	public function put_temperature(){
		
	}
	
	public function put_all(){
		
	}
	
	public function get_plant_ids(){
		return $this->plant_ids;
	}
	
	public function get_plants(){
		return $this->plants;
	}
	
	public function write_log($logtext){
		
		$logfile = fopen("/var/log/gartnetzwerg/db_handler_log.".date('w'), "a");
		
		fwrite($logfile, $logtext);
		
		fclose($logfile);
	}
	
}

?>