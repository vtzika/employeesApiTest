<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Tests for EmployeesAPI
 */

class test_employees_api extends CodeIgniterUnitTestCase {

	public function __construct()
	{

	}

	public function setUp()
	{

    }


	private function _addEmployee($name, $description, $picture)
	{
	    $curl_handle = curl_init();
	    curl_setopt($curl_handle, CURLOPT_URL, 'http://localhost:8000/employeesapi/employee/format/json');
	    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curl_handle, CURLOPT_POST, 1);
	    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, array(
	        'name' => $name,
	        'description' => $description,
	        'picture' => $picture
	    ));
	       
	    $buffer = curl_exec($curl_handle);
	    curl_close($curl_handle);
	     
	    $result = json_decode($buffer);
	 
	    if(isset($result->status) && $result->status == 'success')
	    {
	        echo 'User has been updated.';
	    }
	     
	    else
	    {
	        echo 'Something has gone wrong';
	    }
	}

	public function _deleteEmployee($name) 
    {
		$postdata = http_build_query(
		    array(
		      	'name' => $name
		    )
		);

		$opts = array('http' =>
		    array(
		        'method'  => 'DELETE',
		        'header'  => 'Content-type: application/x-www-form-urlencoded',
		        'content' => $postdata
		    )
		);

		$context  = stream_context_create($opts);

		$employee = json_decode(file_get_contents('http://localhost:8000/employeesapi/employee/format/json', false, $context));
		var_dump($employee);
	}


	public function test_get_all_employees()
	{
		$employees = json_decode( file_get_contents('http://localhost:8000/employeesapi/employees/format/json') );
 
		$this->assertEqual(3, count($employees), 'The result');

	}


	public function test_get_param()
	{
		$employees = json_decode( file_get_contents('http://localhost:8000/employeesapi/employees/format/json') );
 		foreach($employees as $employee) 
 		{
			$this->assertTrue(property_exists($employee, 'name'), "The name property is not existed on the API response");
			$this->assertTrue(property_exists($employee, 'description'), "The description property is not existed on the API response");
			$this->assertTrue(property_exists($employee, 'picture'), "The picture property is not existed on the API response");
		}
	}

	public function test_get_employee_by_name()
	{
		$name = 'Bieraholic';
		$description = 'Ik hou van pilje';
		$picture = "https://scontent-ams.xx.fbcdn.net/hphotos-prn2/v/t1.0-9/544842_10201020593219460_1119535722_n.jpg?oh=52e30b5e0f53ed35c6ccfc450add1ff4&oe=554CBDE6";

		//$this->_deleteEmployee($name);


		//$employee = $this->_addEmployee($name, $description, $picture);
		$employee = json_decode( file_get_contents('http://localhost:8000/employeesapi/employee/name/bieraholic/format/json') );

		$this->assertEqual($employee[0]->name, $name, "The name property is not correct, it should be correct");
		$this->assertEqual($employee[0]->description, $description, "The description property is not correct");
		$this->assertEqual($employee[0]->picture, $picture, "The picture property is not correct");
	}

}