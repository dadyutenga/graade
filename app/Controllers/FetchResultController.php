<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\StudentModel;
use App\Models\ExamGroupModel;
use App\Models\ExamResultModel;

class StudentResultsController extends ResourceController
{
    protected $studentModel;
    protected $examGroupModel;
    protected $examResultModel;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->examGroupModel = new ExamGroupModel();
        $this->examResultModel = new ExamResultModel();
    }

    public function getStudentResults($studentId)
    {
        // Fetch student details
        $student = $this->studentModel->getStudentWithClass($studentId);
        if (!$student) {
            return $this->failNotFound("Student not found");
        }

        // Fetch exam groups
        $examGroups = $this->examGroupModel->getExamGroupsForStudent($studentId);
        
        // Fetch exam results
        $examResults = $this->examResultModel->getExamResultsForStudent($studentId);
        
        // Structure response
        $student['exam_groups'] = $examGroups;
        $student['exam_results'] = $examResults;

        return $this->respond($student);
    }
}
