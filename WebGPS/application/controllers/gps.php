<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gps extends CI_Controller {

	function __construct(){
	
	  parent::__construct();
	  
	  $this->load->model('Coordinate','',TRUE);
	
	}
	private function get($key){
		return $this->input->post($key);	
	}
	public function index()
	{	
		/*********** Get all listed devices from database **************/
		$data['devices'] = $this->Coordinate->get_distinct_devices();
		
		if(!empty($this->input->post()))
		/*********** If is an on-demand request, then get co-ordinates for requested device for the selected date interval ***************/
		  $data['coordinates'] = $this->Coordinate->get_list_by_device_id($this->get('device_id'), date("Y-m-d H:i:s", strtotime($this->get('from'))), date("Y-m-d H:i:s", strtotime($this->get('to'))));
		  
		else
		/*********** Nothing to show **************/
		  $data['coordinates'] = null;
		/*********** Load google map **************/
		$this->load->view('map', $data);
		
	}
	public function create(){

		if(!empty($this->input->post())){
			/********** Receive data from mobile device  ***************/
			$data = array('device_id' => $this->get('device_id'),
					'datetime' => date("Y-m-d H:i:s"),
					'latitude' => $this->get('latitude'),
					'longitude' => $this->get('longitude'),
					'altitude' => $this->get('altitude')
					);
			/********** Saved data to database ************************/
			if($this->Coordinate->save($data)){
				$response = array('Success' => true, 'message' => 'Coordinates successfully saved!' )	;		
				echo json_encode($response);
			}
			else {
				$response = array('Success' => false, 'message' => 'Coordinates could not be saved!')	;		
				echo json_encode($response);
			}		
		} else  {
				/************** No post data, invalid call to api *********/
				$response = array('Success' => false, 'message' => 'No data received!')	;		
				echo json_encode($response);		
			}
	
	}
	/************* Get list of coordinates given device id (Returns json) ***************/
	public function list_coordinates($device_id){

		$data['coordinates'] = $this->Coordinate->get_list_by_device_id($device_id);
		$coordinates = array(array());
		foreach($data['coordinates']->result() as $coordinate){
			$coordinates[] = array(	'device_id' => $coordinate->device_id,
						'datetime' => date("Y-m-d H:i:s", $coordinate->datetime),
						'latitude' => $coordinate->latitude,
						'longitude' => $coordinate->longitude,
						'altitude' => $coordinate->altitude
						);
		}
		echo json_encode($coordinates);
	
	}
	/************* Keyhole markup language file generator ***************/
	public function generate_kml($device_id){
		$data['coordinates'] = $this->Coordinate->get_list_by_device_id($device_id);
		$kml = array('<?xml version="1.0" encoding="UTF-8"?>');
		$kml[] = '<kml xmlns="http://earth.google.com/kml/2.1">';
		$kml[] = ' <Folder>';
		$kml[] = ' <Placemark id="linestring1">';
		$kml[] = ' <name>' . $device_id . '</name>';
		$kml[] = ' <description>'. $device_id . ' path </description>';
		$kml[] = ' <LineString>';
		$kml[] = ' <extrude>1</extrude>';
		$kml[] = ' <altitudeMode>relativeToGround</altitudeMode>';
		$kml[] = ' <coordinates>';
		// Iterates through the rows, printing a node for each row.
		foreach($data['coordinates']->result() as $coordinate)
			  $kml[] = $coordinate->longitude . ','  . $coordinate->latitude . ','  . $coordinate->altitude .' ';
		$kml[] = ' </coordinates>';
		// End XML file
		$kml[] = ' </LineString>';
		$kml[] = ' </Placemark>';
		$kml[] = ' </Folder>';
		$kml[] = '</kml>';	
		$kmlOutput = join("\n", $kml);
		header('Content-type: application/vnd.google-earth.kml+xml');
		echo $kmlOutput;
	
	}
}

/* End of file gps.php */
