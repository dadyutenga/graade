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
        // Check if user is logged in
        if (!auth()->loggedIn()) {
            return $this->failUnauthorized('You must be logged in to access this resource');
        }
        
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
    
    public function index()
    {
        // Check if user is logged in
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }
        
        // Check if student_id is provided in GET request
        $studentId = $this->request->getGet('student_id');
        if ($studentId) {
            return redirect()->to("/student-results/{$studentId}");
        }
        
        // This method could display a form to search for a student
        return view('student_results/index', [
            'user' => auth()->user()
        ]);
    }
    
    public function show($studentId = null)
    {
        // Check if user is logged in
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }
        
        if (!$studentId) {
            return redirect()->to('/student-results')->with('error', 'No student ID provided');
        }
        
        // Fetch student details
        $student = $this->studentModel->getStudentWithClass($studentId);
        if (!$student) {
            return redirect()->to('/student-results')->with('error', 'Student not found');
        }

        // Fetch exam groups
        $examGroups = $this->examGroupModel->getExamGroupsForStudent($studentId);
        
        // Fetch exam results
        $examResults = $this->examResultModel->getExamResultsForStudent($studentId);
        
        // Pass data to view
        $data = [
            'student' => $student,
            'examGroups' => $examGroups,
            'examResults' => $examResults,
            'user' => auth()->user()
        ];
        
        return view('student_results/show', $data);
    }
} 