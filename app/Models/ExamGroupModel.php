<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamGroupModel extends Model
{
    protected $table = 'exam_groups';
    protected $primaryKey = 'id';

    public function getStudentExamGroups($studentId)
    {
        return $this->db->table('exam_group_students')
            ->select('exam_groups.id, exam_groups.name as exam_group')
            ->join('exam_groups', 'exam_groups.id = exam_group_students.exam_group_id')
            ->where('exam_group_students.student_id', $studentId)
            ->get()
            ->getResultArray();
    }
}
