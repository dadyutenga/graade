<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'id';

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
