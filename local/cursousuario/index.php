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


/**
 *
*
* @package    local
* @subpackage cursousuario
* @copyright  2014 Ilyan Triantafilo
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

//Página para buscar el curso de un alumno.

require_once(dirname(__FILE__) . '/../../config.php'); //obligatorio
require_once($CFG->dirroot.'/local/cursousuario/forms.php');
require_once($CFG->dirroot.'/local/cursousuario/tablas.php');


global $PAGE, $CFG, $OUTPUT, $DB; $USER;
require_login();
$url = new moodle_url('/local/cursousuario/index.php');
$context = context_system::instance();//context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');

//Capabilities
//Valida la capacidad del usuario de poder ver el contenido
//En este caso solo administradores del módulo pueden ingresar
//if(!has_capability('local/cursousuario:viewuser', $context)) {
//	print_error(get_string('INVALID_ACCESS','buscar_cursos'));
//}

$title = 'Ver los cursos de algun usuario';
$PAGE->set_title($title);
$PAGE->set_heading($title);

echo $OUTPUT->header();
echo $OUTPUT->heading($title);

$mform = new formulario();

if($mform->is_cancelled()){
 redirect($returnurl);
}

else if ($fromform = $mform->get_data()){
   
	$alumno = $fromform->alumno;
	
	$results = $DB-> get_records_sql("SELECT c.fullname as 'nombre_curso' 
			FROM {course} c 
			JOIN {enrol} e 
			ON c.id=e.courseid 
			JOIN {user_enrolments} ue 
			ON e.id=ue.enrolid 
			JOIN {user} u 
			ON ue.userid=u.id  
			WHERE '$alumno' like u.username
			ORDER BY u.username");
	$instancename = "Reading Test 1";
	$gradess = $DB->get_record_sql("SELECT g.finalgrade as 'finalgrade'
       		FROM {grade_grades} g
       		JOIN {grade_items} gi
       		ON gi.id=g.itemid
       		WHERE ('$USER->id' = g.userid AND '$instancename'= gi.itemname)");
	
	var_dump($gradess->finalgrade);
    echo $USER->id;


	
	
	if(empty($results)){
		echo "  <div style='color:#DF0101;background-color:#F5A9A9;width:330px;font-size:120%;'>  El alumno $alumno no tiene cursos registrados</div>";
	    echo "<br>";
		echo $OUTPUT->single_button('index.php',get_string('back','local_reservasalas'));
		
	} 
	
	else {
$table = tablas::getCursos($results);
echo html_writer::table($table);
echo $OUTPUT->single_button('index.php', get_string('back','local_reservasalas'));
	}
}

else {
	$mform->set_data($toform);
	$mform->display();
}

echo $OUTPUT->footer();


