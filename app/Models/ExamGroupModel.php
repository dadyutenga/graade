<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamGroupModel extends Model
{
    protected $DBGroup          = 'second_db';
    protected $table            = 'exam_groups';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'description'];

    public function getStudentExamGroups($studentId)
    {
        return $this->db->table('exam_group_students')
            ->select('exam_groups.id, exam_groups.name as exam_group')
            ->join('exam_groups', 'exam_groups.id = exam_group_students.exam_group_id')
            ->where('exam_group_students.student_id', $studentId)
            ->get()
            ->getResultArray();
    }
    
    public function getExamGroupsForStudent($studentId)
    {
        return $this->getStudentExamGroups($studentId);
    }
}
