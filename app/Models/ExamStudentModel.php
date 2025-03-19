<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamStudentModel extends Model
{
    protected $DBGroup = 'second_db';
    protected $table = 'exam_group_class_batch_exam_students';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'exam_group_class_batch_exam_id', 
        'student_id', 
        'student_session_id',
        'roll_no'
    ];

    // Remove SettingModel dependency and use a default session
    protected $current_session = 1;

    public function __construct()
    {
        parent::__construct();
        // Remove SettingModel usage
    }

    // Fix the insert method to match parent class signature
    public function insert($data = null, bool $returnID = true)
    {
        if (empty($data)) {
            return false;
        }

        $db = \Config\Database::connect('second_db');
        $builder = $db->table($this->table);
        
        $builder->where('exam_group_class_batch_exam_id', $data['exam_group_class_batch_exam_id']);
        $builder->where('student_session_id', $data['student_session_id']);
        $query = $builder->get();
        
        if ($query->getNumRows() == 0) {
            return parent::insert($data, $returnID);
        }
        return true;
    }

    public function searchExamStudents($class_id, $section_id, $exam_id)
    {
        $db = \Config\Database::connect('second_db');
        
        $builder = $db->table('students');
        $builder->select('classes.id AS `class_id`, student_session.id as student_session_id, students.id, classes.class, sections.id AS `section_id`, sections.section, students.id, students.admission_no, students.roll_no, students.admission_date, students.firstname, students.middlename, students.lastname, students.image, students.mobileno, students.email, students.state, students.city, students.pincode, students.religion, students.dob, students.current_address, students.permanent_address, IFNULL(students.category_id, 0) as `category_id`, IFNULL(categories.category, "") as `category`, students.adhar_no, students.samagra_id, students.bank_account_no, students.bank_name, students.ifsc_code, students.guardian_name, students.guardian_relation, students.guardian_phone, students.guardian_address, students.is_active, students.created_at, students.updated_at, students.father_name, students.rte, students.gender, IFNULL(exam_group_class_batch_exam_students.id, 0) as onlineexam_student_id, IFNULL(exam_group_class_batch_exam_students.student_session_id, 0) as onlineexam_student_session_id');
        
        $builder->join('student_session', 'student_session.student_id = students.id');
        $builder->join('classes', 'student_session.class_id = classes.id');
        $builder->join('sections', 'sections.id = student_session.section_id');
        $builder->join('categories', 'students.category_id = categories.id', 'left');
        $builder->join('exam_group_class_batch_exam_students', "exam_group_class_batch_exam_students.student_session_id = student_session.id and exam_group_class_batch_exam_students.exam_group_class_batch_exam_id = $exam_id", 'left');
        
        $builder->where('student_session.session_id', $this->current_session);
        $builder->where('student_session.class_id', $class_id);
        $builder->where('student_session.section_id', $section_id);
        $builder->where('students.is_active', 'yes');
        $builder->orderBy('students.admission_no');
        
        $query = $builder->get();
        return $query->getResultArray();
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

    public function add_student($insert_array, $exam_group_class_batch_exam_id, $all_students)
    {
        $db = \Config\Database::connect('second_db');
        $delete_array = array();
        $inserted_array = array();
        
        $db->transBegin();
        
        if (!empty($insert_array)) {
            foreach ($insert_array as $insert_key => $insert_value) {
                $this->insert($insert_value);
                $inserted_array[] = $insert_value['student_session_id'];
            }
        }

        $delete_array = array_diff($all_students, $inserted_array);

        if (!empty($delete_array)) {
            $builder = $db->table('exam_group_class_batch_exam_students');
            $builder->where('exam_group_class_batch_exam_id', $exam_group_class_batch_exam_id);
            $builder->whereIn('student_session_id', $delete_array);
            $builder->delete();
        }

        if ($db->transStatus() === false) {
            $db->transRollback();
            return false;
        } else {
            $db->transCommit();
            return true;
        }
    }

    public function checkStudentExists($check_alreay_inserted_students, $exam_group_class_batch_exam_id)
    {
        $db = \Config\Database::connect('second_db');
        
        $builder = $db->table('exam_group_class_batch_exam_students');
        $builder->where('exam_group_class_batch_exam_id', $exam_group_class_batch_exam_id);
        $builder->whereIn('student_id', $check_alreay_inserted_students);
        $query = $builder->get();
        return $query->getResult();
    }

    public function getBatchStudentDetail($exam_group_class_batch_exam_student_id)
    {
        $db = \Config\Database::connect('second_db');
        
        $sql = "SELECT exam_group_class_batch_exam_students.*,sessions.session, exam_group_class_batch_exams.exam,exam_group_class_batch_exams.session_id, students.admission_no, students.id as `student_id`, students.roll_no,students.admission_date,students.firstname,students.middlename, students.lastname,students.image, students.mobileno, students.email ,students.state, students.city, students.pincode, students.religion,students.dob, students.current_address, students.permanent_address,students.category_id, IFNULL(categories.category, '') as `category`, students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code, students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active,`students`.`father_name`,`students`.`gender` FROM `exam_group_class_batch_exam_students` INNER join students on students.id=exam_group_class_batch_exam_students.student_id INNER JOIN exam_group_class_batch_exams on exam_group_class_batch_exams.id=exam_group_class_batch_exam_students.exam_group_class_batch_exam_id LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` INNER JOIN sessions on sessions.id=exam_group_class_batch_exams.session_id WHERE exam_group_class_batch_exam_students.id=" . $db->escape($exam_group_class_batch_exam_student_id);
        
        $query = $db->query($sql);
        return $query->getRow();
    }

    public function getStudentByExamAndStudentID($student_id, $exam_group_class_batch_exam_id)
    {
        $db = \Config\Database::connect('second_db');
        
        $builder = $db->table('exam_group_class_batch_exam_students');
        $builder->where('student_id', $student_id);
        $builder->where('exam_group_class_batch_exam_id', $exam_group_class_batch_exam_id);
        $query = $builder->get();
        
        if ($query->getNumRows() > 0) {
            return $query->getRow();
        }
        return false;
    }

    public function getStudentsAdmitCardByExamAndStudentID($students_array, $exam_group_class_batch_exam_id)
    {
        $db = \Config\Database::connect('second_db');
        
        $sql = "SELECT * FROM `exam_group_class_batch_exam_students` where exam_group_class_batch_exam_id=" . $exam_group_class_batch_exam_id . " and (roll_no IS NULL OR roll_no = 0)";
        $query = $db->query($sql);
        $results = $query->getResult();
        
        if (!empty($results)) {
            $maxid = $db->query('SELECT MAX(roll_no) AS `maxid` FROM `exam_group_class_batch_exam_students` where exam_group_class_batch_exam_id=' . $exam_group_class_batch_exam_id)->getRow()->maxid;

            if ($maxid == 0) {
                $update_roll_no = 100001;
            } else {
                $update_roll_no = $maxid + 1;
            }
            
            $update_student = array();
            foreach ($results as $res_key => $res_value) {
                $update_student[] = array('id' => $res_value->id, 'roll_no' => $update_roll_no);
                $update_roll_no++;
            }
            
            $db->table('exam_group_class_batch_exam_students')->updateBatch($update_student, 'id');
        }

        $student_details = array();
        if (!empty($students_array)) {
            foreach ($students_array as $student_key => $student_value) {
                $student_details[] = $this->getStudentDetailsByExamAndStudentID($student_value, $exam_group_class_batch_exam_id);
            }
        }

        return $student_details;
    }

    public function getStudentDetailsByExamAndStudentID($student_id, $exam_group_class_batch_exam_id)
    {
        $db = \Config\Database::connect('second_db');
        
        $sql = "SELECT exam_group_class_batch_exam_students.*,students.admission_no, students.id as `student_id`,students.admission_date,students.roll_no as `profile_roll_no`, students.firstname,students.middlename, students.lastname,students.image, students.mobileno, students.email ,students.state, students.city, students.pincode, students.religion,students.dob, students.current_address, students.permanent_address,students.category_id, IFNULL(categories.category, '') as `category`, students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code, students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active,`students`.`father_name`,`students`.`mother_name`,`students`.`gender`,classes.class,sections.section FROM `exam_group_class_batch_exam_students` INNER JOIN student_session on student_session.id=exam_group_class_batch_exam_students.student_session_id INNER JOIN students on students.id=student_session.student_id LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` INNER JOIN classes on classes.id=student_session.class_id INNER JOIN sections on sections.id=student_session.section_id WHERE `exam_group_class_batch_exam_students`.`student_id` = " . $db->escape($student_id) . " AND `exam_group_class_batch_exam_students`.`exam_group_class_batch_exam_id` = " . $db->escape($exam_group_class_batch_exam_id);
        
        $query = $db->query($sql);
        return $query->getRow();
    }

    public function getStudentdetailByExam($student_id, $exam_group_class_batch_exam_id)
    {
        $db = \Config\Database::connect('second_db');
        
        $sql = "SELECT exam_group_class_batch_exam_students.*,students.admission_no, students.roll_no,students.id as `student_id`,students.admission_date,students.firstname,students.middlename, students.lastname,students.image, students.mobileno, students.email ,students.state, students.city, students.pincode, students.religion,students.dob, students.current_address, students.permanent_address,students.category_id, IFNULL(categories.category, '') as `category`, students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code, students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active,`students`.`father_name`,`students`.`mother_name`,`students`.`gender`,student_session.class_id,student_session.section_id,classes.class,sections.section FROM `exam_group_class_batch_exam_students` INNER JOIN student_session on student_session.id=exam_group_class_batch_exam_students.student_session_id INNER JOIN students on students.id=student_session.student_id LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` INNER JOIN classes on classes.id=student_session.class_id INNER JOIN sections on sections.id=student_session.section_id WHERE `exam_group_class_batch_exam_students`.`student_id` = " . $db->escape($student_id) . " AND `exam_group_class_batch_exam_students`.`exam_group_class_batch_exam_id` = " . $db->escape($exam_group_class_batch_exam_id);
        
        $query = $db->query($sql);
        return $query->getRowArray();
    }

    public function getExamStudentByID($exam_group_class_batch_exam_id)
    {
        $db = \Config\Database::connect('second_db');
        
        $sql = "SELECT exam_group_class_batch_exam_students.*,students.admission_no, students.roll_no as `student_roll_no`,students.id as `student_id`,students.admission_date,students.firstname,students.middlename, students.lastname,students.image, students.mobileno, students.email ,students.state, students.city, students.pincode, students.religion,students.dob, students.current_address, students.permanent_address,students.category_id, IFNULL(categories.category, '') as `category`, students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code, students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active,`students`.`father_name`,`students`.`mother_name`,`students`.`gender`,student_session.class_id,student_session.section_id,classes.class,sections.section FROM `exam_group_class_batch_exam_students` INNER JOIN student_session on student_session.id=exam_group_class_batch_exam_students.student_session_id INNER JOIN students on students.id=student_session.student_id LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` INNER JOIN classes on classes.id=student_session.class_id INNER JOIN sections on sections.id=student_session.section_id WHERE `exam_group_class_batch_exam_students`.`id` = " . $db->escape($exam_group_class_batch_exam_id);
        
        $query = $db->query($sql);
        return $query->getRowArray();
    }

    public function getstudentexam($admission_no)
    {
        $db = \Config\Database::connect('second_db');
        
        $builder = $db->table('exam_group_class_batch_exam_students');
        $builder->select('exam_group_class_batch_exams.exam, exam_group_class_batch_exams.passing_percentage, exam_group_class_batch_exams.id, exam_group_class_batch_exam_students.student_session_id, students.firstname, students.middlename, students.lastname, students.roll_no, students.admission_no, classes.class as class_name, sections.section as section_name');
        $builder->join('exam_group_class_batch_exams', 'exam_group_class_batch_exams.id=exam_group_class_batch_exam_students.exam_group_class_batch_exam_id', 'inner');
        $builder->join('students', 'students.id=exam_group_class_batch_exam_students.student_id', 'inner');
        $builder->join('student_session', 'student_session.id=exam_group_class_batch_exam_students.student_session_id', 'inner');
        $builder->join('classes', 'classes.id=student_session.class_id', 'inner');
        $builder->join('sections', 'sections.id=student_session.section_id', 'inner');
        $builder->where('students.admission_no', $admission_no);
        $builder->where('exam_group_class_batch_exams.session_id', $this->current_session);
        
        $query = $builder->get();
        return $query->getResultArray();
    }
    
    public function getstudentsessionidbyadmissionno($admission_no)
    {
        $db = \Config\Database::connect('second_db');
        
        $builder = $db->table('student_session');
        $builder->select('student_session.id');
        $builder->join('students', 'students.id = student_session.student_id', 'inner');
        $builder->where('students.admission_no', $admission_no);
        $builder->where('student_session.session_id', $this->current_session);
        
        $query = $builder->get();
        $result = $query->getRowArray();
        return $result['id'];
    }
}
