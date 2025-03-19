<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamSubjectModel extends Model
{
    protected $DBGroup = 'second_db';
    protected $table = 'exam_group_class_batch_exam_subjects';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'exam_group_class_batch_exams_id',
        'subject_id',
        'date_from',
        'time_from',
        'duration',
        'room_no',
        'max_marks',
        'min_marks',
        'credit_hours'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function add($insert_array, $update_array, $not_be_del, $exam_id)
    {
        $db = \Config\Database::connect('second_db');
        
        if (!empty($insert_array)) {
            foreach ($insert_array as $insert_key => $insert_value) {
                $builder = $db->table('exam_group_class_batch_exam_subjects');
                $builder->insert($insert_array[$insert_key]);
                $not_be_del[] = $db->insertID();
            }
        }
        
        if (!empty($update_array)) {
            $builder = $db->table('exam_group_class_batch_exam_subjects');
            $builder->updateBatch($update_array, 'id');
        }

        if (!empty($not_be_del)) {
            $builder = $db->table('exam_group_class_batch_exam_subjects');
            $builder->where('exam_group_class_batch_exams_id', $exam_id);
            $builder->whereNotIn('id', $not_be_del);
            $builder->delete();
        }
        
        return true;
    }
    
    public function getExamSubjects($exam_id)
    {
        $db = \Config\Database::connect('second_db');
        
        $builder = $db->table('exam_group_class_batch_exam_subjects');
        $builder->select('exam_group_class_batch_exam_subjects.*, subjects.name, subjects.code');
        $builder->join('subjects', 'subjects.id = exam_group_class_batch_exam_subjects.subject_id');
        $builder->where('exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id', $exam_id);
        $builder->orderBy('subjects.name', 'ASC');
        
        $query = $builder->get();
        return $query->getResultArray();
    }
    
    public function getSubjectByExamSubjectId($exam_subject_id)
    {
        $db = \Config\Database::connect('second_db');
        
        $builder = $db->table('exam_group_class_batch_exam_subjects');
        $builder->select('exam_group_class_batch_exam_subjects.*, subjects.name, subjects.code');
        $builder->join('subjects', 'subjects.id = exam_group_class_batch_exam_subjects.subject_id');
        $builder->where('exam_group_class_batch_exam_subjects.id', $exam_subject_id);
        
        $query = $builder->get();
        return $query->getRowArray();
    }
    
    public function getExamSubjectsByExamId($exam_id)
    {
        $db = \Config\Database::connect('second_db');
        
        $builder = $db->table('exam_group_class_batch_exam_subjects');
        $builder->where('exam_group_class_batch_exams_id', $exam_id);
        
        $query = $builder->get();
        return $query->getResultArray();
    }
}
