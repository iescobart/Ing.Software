
<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
 //a

/**
 * 
 * @package    local
 * @subpackage reservasalas
 * @copyright  2014 Ilyan Triantafilo (itriantafilo@alumnos.uai.cl)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot.'/local/cursousuario/tablas.php');
require_once("$CFG->libdir/formslib.php");

class formulario extends moodleform {
	function definition(){
		global $CFG, $DB; $USER; $PAGE; $COURSE;
		
		
		
		$mform = $this->_form;
		$mform->addElement('text','alumno',get_string('student','local_reservasalas'));
		$mform->setType('alumno', PARAM_TEXT);
		$this->add_action_buttons();
		$mform->addRule('alumno',get_string('notuser','local_reservasalas'),'required');
		}	
		
		function validation($data, $files) {
			global $DB;
			
			$user = $DB->get_record('user',array('username'=>$data[alumno]));
			$errors=array();
			if($user == false && !empty($data[alumno])){
				$errors["alumno"]= get_string('notexiststudent','local_reservasalas');
				return $errors;
			}
			return array();		
}
}





