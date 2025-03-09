<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamResultModel extends Model
{
    protected $DBGroup          = 'second_db';
    protected $table            = 'exam_group_exam_results';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['exam_group_class_batch_exam_subject_id', 'exam_group_class_batch_exam_student_id', 'get_marks', 'note'];

    public function getStudentExamResults($studentId)
    {
        return $this->db->table('exam_group_exam_results')
            ->select('subjects.name as subject, exam_group_exam_results.get_marks')
            ->join('exam_group_class_batch_exam_subjects', 'exam_group_class_batch_exam_subjects.id = exam_group_exam_results.exam_group_class_batch_exam_subject_id')
            ->join('subjects', 'subjects.id = exam_group_class_batch_exam_subjects.subject_id')
            ->whereIn('exam_group_exam_results.exam_group_class_batch_exam_student_id', function ($builder) use ($studentId) {
                return $builder->table('exam_group_class_batch_exam_students')
                    ->select('id')
                    ->where('student_id', $studentId);
            })
            ->get()
            ->getResultArray();
    }
    
    public function getExamResultsForStudent($studentId)
    {
        return $this->getStudentExamResults($studentId);
    }
}
