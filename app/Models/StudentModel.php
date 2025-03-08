<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $DBGroup          = 'second_db';
    protected $table            = 'students';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['firstname', 'lastname', 'email', 'guardian_id'];

    public function getStudentWithClass($studentId)
    {
        return $this->db->table('students')
            ->select('students.id, students.firstname, students.lastname, student_session.class_id, classes.class')
            ->join('student_session', 'student_session.student_id = students.id')
            ->join('classes', 'classes.id = student_session.class_id')
            ->where('students.id', $studentId)
            ->get()
            ->getRowArray();
    }
}
