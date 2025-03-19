<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamResultModel extends Model
{
    protected $DBGroup = 'second_db';
    protected $table = 'exam_results';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'exam_schedule_id',
        'student_id',
        'get_marks',
        'note',
        'attendence',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $current_session;

    public function __construct()
    {
        parent::__construct();
        // Get the current session from settings
        $settingModel = new \App\Models\SettingModel();
        $this->current_session = $settingModel->getCurrentSession();
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id)
    {
        $db = \Config\Database::connect('second_db');
        $builder = $db->table('exam_results');
        $builder->where('id', $id);
        $builder->delete();
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data)
    {
        $db = \Config\Database::connect('second_db');
        $builder = $db->table('exam_results');
        
        if (isset($data['id'])) {
            $builder->where('id', $data['id']);
            $builder->update($data);
        } else {
            $builder->insert($data);
            return $db->insertID();
        }
    }

    public function add_exam_result($data)
    {
        $db = \Config\Database::connect('second_db');
        $builder = $db->table('exam_results');
        
        $builder->where('exam_schedule_id', $data['exam_schedule_id']);
        $builder->where('student_id', $data['student_id']);
        $query = $builder->get();
        $result = $query->getRow();
        
        if ($query->getNumRows() > 0) {
            $builder->where('id', $result->id);
            $builder->update($data);
            if ($result->get_marks != $data['get_marks']) {
                return $result->id;
            }
        } else {
            $builder->insert($data);
            return $db->insertID();
        }
        return false;
    }

    public function get_exam_result($exam_schedule_id = null, $student_id = null)
    {
        $db = \Config\Database::connect('second_db');
        $builder = $db->table('exam_results');
        
        $builder->where('exam_schedule_id', $exam_schedule_id);
        $builder->where('student_id', $student_id);
        $query = $builder->get();
        
        if ($query->getNumRows() > 0) {
            return $query->getRow();
        } else {
            $obj = new \stdClass();
            $obj->attendence = 'pre';
            $obj->get_marks = "0.00";
            return $obj;
        }
    }

    public function get_result($exam_schedule_id = null, $student_id = null)
    {
        $db = \Config\Database::connect('second_db');
        $builder = $db->table('exam_results');
        
        $builder->where('exam_schedule_id', $exam_schedule_id);
        $builder->where('student_id', $student_id);
        $query = $builder->get();
        
        if ($query->getNumRows() > 0) {
            return $query->getRow();
        }
        return null;
    }

    public function checkexamresultpreparebyexam($exam_id, $class_id, $section_id)
    {
        $db = \Config\Database::connect('second_db');
        
        $sql = "SELECT count(*) `counter` FROM `exam_results`,exam_schedules,student_session 
                WHERE exam_results.exam_schedule_id=exam_schedules.id 
                AND student_session.student_id=exam_results.student_id 
                AND student_session.class_id=" . $db->escape($class_id) . " 
                AND student_session.section_id=" . $db->escape($section_id) . " 
                AND exam_schedules.session_id=" . $db->escape($this->current_session) . " 
                AND exam_schedules.exam_id=" . $db->escape($exam_id);
                
        $query = $db->query($sql);
        $result = $query->getRow();
        
        return ($result->counter > 0);
    }

    public function getStudentExamResultByStudent($exam_id, $student_id, $exam_schedule)
    {
        $db = \Config\Database::connect('second_db');
        
        $sql = "SELECT exam_schedules.id as `exam_schedules_id`,
                exam_results.id as `exam_results_id`,
                exam_schedules.exam_id,
                exam_schedules.date_of_exam,
                exam_schedules.full_marks,
                exam_schedules.passing_marks,
                exam_results.student_id,
                exam_results.get_marks,
                students.firstname,
                students.middlename,
                students.lastname,
                students.guardian_phone,
                students.email,
                exams.name as `exam_name` 
                FROM `exam_schedules` 
                INNER JOIN exams on exams.id=exam_schedules.exam_id 
                INNER JOIN exam_results ON exam_results.exam_schedule_id=exam_schedules.id 
                INNER JOIN students on students.id=exam_results.student_id 
                WHERE exam_schedules.session_id =" . $db->escape($this->current_session) . " 
                AND exam_schedules.exam_id =" . $db->escape($exam_id) . " 
                AND exam_results.student_id =" . $db->escape($student_id) . " 
                AND exam_schedules.id in (" . $exam_schedule . ") 
                ORDER BY `exam_results`.`id` ASC";

        $query = $db->query($sql);
        return $query->getResultArray();
    }

    public function getExamResults($exam_id, $post_exam_group_id, $students)
    {
        $result = array(
            'exam_connection' => 0, 
            'students' => array(), 
            'exams' => array(), 
            'exam_connection_list' => array()
        );
        
        $examgroup_model = new \App\Models\ExamGroupModel();
        $examstudent_model = new \App\Models\ExamStudentModel();
        
        $exam_connection = false;
        $exam_connections = $examgroup_model->getExamGroupConnectionList($post_exam_group_id);
        
        if (!empty($exam_connections)) {
            $lastkey = key(array_slice($exam_connections, -1, 1, true));
            if ($exam_connections[$lastkey]->exam_group_class_batch_exams_id == $exam_id) {
                $exam_connection = true;
                $result['exam_connection'] = 1;
            }
        }
        
        $result['exam_connection_list'] = $exam_connections;
        
        foreach ($students as $student_key => $student_value) {
            $student = $examstudent_model->getExamStudentByID($student_value);

            $student['exam_result'] = array();
            if ($exam_connection) {
                foreach ($exam_connections as $exam_connection_key => $exam_connection_value) {
                    $exam_group_class_batch_exam_student = $examstudent_model->getStudentByExamAndStudentID(
                        $student['student_id'], 
                        $exam_connection_value->exam_group_class_batch_exams_id
                    );
                    
                    if (!empty($exam_group_class_batch_exam_student)) {
                        $exam = $examgroup_model->getExamByID($exam_connection_value->exam_group_class_batch_exams_id);
    
                        $student['exam_result']['exam_roll_no_' . $exam_connection_value->exam_group_class_batch_exams_id] = $student['roll_no'];
    
                        $student['exam_result']['exam_result_' . $exam_connection_value->exam_group_class_batch_exams_id] = 
                            $this->getStudentResultByExam(
                                $exam_connection_value->exam_group_class_batch_exams_id, 
                                $exam_group_class_batch_exam_student->id
                            );
    
                        $result['exams']['exam_' . $exam_connection_value->exam_group_class_batch_exams_id] = $exam;
                    }
                }
                $result['students'][] = $student;
            } else {
                $student['exam_roll_no'] = $student['roll_no'];
                $student['exam_result'] = $this->getStudentResultByExam($exam_id, $student['id']);
                $result['students'][] = $student;
            }
        }

        return $result;
    }

    public function updaterank($update_array, $exam_group_class_batch_exam_id)
    {     
        $db = \Config\Database::connect('second_db');
        
        if (!empty($update_array)) {
            $data_update = array('is_rank_generated' => 1);   
            
            $builder = $db->table('exam_group_class_batch_exams');
            $builder->where('id', $exam_group_class_batch_exam_id);
            $builder->update($data_update);
            
            $builder = $db->table('exam_group_class_batch_exam_students');
            $builder->updateBatch($update_array, 'id');
        }
    }

    public function getStudentResultByExam($exam_id, $student_id)
    {
        $db = \Config\Database::connect('second_db');
        
        $sql = "SELECT exam_group_class_batch_exam_subjects.*,
                exam_group_exam_results.id as `exam_group_exam_results_id`,
                exam_group_exam_results.attendence,
                exam_group_exam_results.get_marks,
                exam_group_exam_results.note,
                subjects.name,
                subjects.code,
                exam_group_class_batch_exam_students.rank 
                FROM `exam_group_class_batch_exam_subjects` 
                INNER JOIN exam_group_exam_results on exam_group_exam_results.exam_group_class_batch_exam_subject_id=exam_group_class_batch_exam_subjects.id 
                INNER JOIN exam_group_class_batch_exam_students on exam_group_exam_results.exam_group_class_batch_exam_student_id=exam_group_class_batch_exam_students.id 
                AND exam_group_class_batch_exam_students.id=" . $db->escape($student_id) . " 
                INNER JOIN subjects on subjects.id=exam_group_class_batch_exam_subjects.subject_id 
                WHERE exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id=" . $db->escape($exam_id);
                
        $query = $db->query($sql);
        return $query->getResult();
    }

    public function getStudentExamResults($exam_id, $post_exam_group_id, $exam_group_class_batch_exam_student_id, $student_id)
    {
        $examstudent_model = new \App\Models\ExamStudentModel();
        $examgroup_model = new \App\Models\ExamGroupModel();
        
        $student = $examstudent_model->getExamStudentByID($exam_group_class_batch_exam_student_id);
        $result = array(
            'student' => $student, 
            'exam_connection' => 0, 
            'result' => array(), 
            'exams' => array(), 
            'exam_connection_list' => array()
        );
        
        $exam_connection = false;
        $exam_connections = $examgroup_model->getExamGroupConnectionList($post_exam_group_id);
        
        if (!empty($exam_connections)) {
            $lastkey = key(array_slice($exam_connections, -1, 1, true));
            if ($exam_connections[$lastkey]->exam_group_class_batch_exams_id == $exam_id) {
                $exam_connection = true;
                $result['exam_connection'] = 1;
            }
        }
        
        $result['exam_connection_list'] = $exam_connections;
        
        if ($exam_connection) {
            foreach ($exam_connections as $exam_connection_key => $exam_connection_value) {
                $exam_group_class_batch_exam_student = $examstudent_model->getStudentByExamAndStudentID(
                    $student_id, 
                    $exam_connection_value->exam_group_class_batch_exams_id
                );

                $exam = $examgroup_model->getExamByID($exam_connection_value->exam_group_class_batch_exams_id);

                if (!empty($exam_group_class_batch_exam_student->id)) {
                    $result['exam_result']['exam_roll_no_' . $exam_connection_value->exam_group_class_batch_exams_id] = $student['roll_no'];
                    $result['exam_result']['exam_result_' . $exam_connection_value->exam_group_class_batch_exams_id] = 
                        $this->getStudentResultByExam(
                            $exam_connection_value->exam_group_class_batch_exams_id, 
                            $exam_group_class_batch_exam_student->id
                        );
                }
                
                $result['exams']['exam_' . $exam_connection_value->exam_group_class_batch_exams_id] = $exam;
            }
        } else {
            $result['exam_connection_list'] = $exam_connections;
            $result['student']['exam_roll_no'] = $student['roll_no'];
            $result['result'] = $this->getStudentResultByExam($exam_id, $exam_group_class_batch_exam_student_id);
        }

        return $result;
    }

    /**
     * This function will return the exam results
     * @param $id
     */
    public function get($id = null)
    {
        $db = \Config\Database::connect('second_db');
        $builder = $db->table('exam_results');
        
        if ($id != null) {
            $builder->where('id', $id);
            $query = $builder->get();
            return $query->getRowArray();
        } else {
            $builder->orderBy('id');
            $query = $builder->get();
            return $query->getResultArray();
        }
    }
}
