<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\StudentModel;
use App\Models\ExamResultModel;
use App\Models\ExamStudentModel;
use App\Models\ExamSubjectModel;

class StudentResultsController extends ResourceController
{
    protected $studentModel;
    protected $examResultModel;
    protected $examStudentModel;
    protected $examSubjectModel;

    public function __construct()
    {
        // Initialize models with try/catch to prevent fatal errors
        try {
            $this->studentModel = new StudentModel();
            $this->examResultModel = new ExamResultModel();
            $this->examStudentModel = new ExamStudentModel();
            $this->examSubjectModel = new ExamSubjectModel();
        } catch (\Exception $e) {
            // Log error but continue execution
            log_message('error', 'Error initializing models: ' . $e->getMessage());
        }
    }

    /**
     * Get students by class, section and session
     * 
     * @return mixed
     */
    public function getStudentByClassBatch()
    {
        // Check if user is logged in
        if (!auth()->loggedIn()) {
            return $this->failUnauthorized('You must be logged in to access this resource');
        }
        
        try {
            $class_id = $this->request->getPost('class_id');
            $section_id = $this->request->getPost('section_id');
            $session_id = $this->request->getPost('session_id');
            
            $studentList = $this->examStudentModel->searchStudentByClassSectionSession($class_id, $section_id, $session_id);
            
            return $this->respond(['studentList' => $studentList]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching students by class batch: ' . $e->getMessage());
            return $this->failServerError('An error occurred fetching students');
        }
    }

    /**
     * Get exam groups for a student
     * 
     * @return mixed
     */
    public function getExamGroupByStudent()
    {
        // Check if user is logged in
        if (!auth()->loggedIn()) {
            return $this->failUnauthorized('You must be logged in to access this resource');
        }
        
        try {
            $student_id = $this->request->getPost('student_id');
            
            $examgrouplist = $this->examStudentModel->getExamGroupByStudent($student_id);
            
            return $this->respond(['examgrouplist' => $examgrouplist]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching exam groups by student: ' . $e->getMessage());
            return $this->failServerError('An error occurred fetching exam groups');
        }
    }

    /**
     * Get student exam results
     * 
     * @return mixed
     */
    public function studentresult()
    {
        // Check if user is logged in
        if (!auth()->loggedIn()) {
            return $this->failUnauthorized('You must be logged in to access this resource');
        }
        
        $rules = [
            'exam_group_id' => 'required|trim',
            'student_id' => 'required|trim'
        ];
        
        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => 0, 
                'error' => $this->validator->getErrors()
            ]);
        }
        
        try {
            $student_id = $this->request->getPost('student_id');
            $exam_group_id = $this->request->getPost('exam_group_id');
            $exam_group_exam_id = $this->request->getPost('exam_id');
            
            $examresult = [];
            $exam_grades = [];
            $data = [];
            
            if (!empty($exam_group_exam_id)) {
                $examresult = $this->examResultModel->getExamResultDetailStudent($exam_group_exam_id, $exam_group_id, $student_id);
                $data['examresult'] = $examresult;
                $exam_grades = $this->examResultModel->getGradesByExamType($examresult->exam_type);
                $data['exam_grades'] = $exam_grades;
                $examresult = view('student_results/_getExam', $data);
            } else {
                $exam_group = $this->examResultModel->getExamGroup($exam_group_id);
                $data['exam_group'] = $exam_group;
                $exam_grades = $this->examResultModel->getGradesByExamType($exam_group->exam_type);
                $data['exam_grades'] = $exam_grades;
                $exam_result = $this->examResultModel->getExamGroupExamsResultByStudentID($exam_group_id, $student_id);
                $data['examresult'] = $exam_result;
                $exam_connections = $this->examResultModel->getExamGroupConnection($exam_group_id);
                $data['exam_connections'] = $exam_connections;
                $examresult = view('student_results/_getExamGroupResult', $data);
            }
            
            $data['exam_grades'] = $exam_grades;
            
            return $this->respond([
                'status' => 1, 
                'result' => $examresult, 
                'message' => 'Success'
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching student result: ' . $e->getMessage());
            return $this->failServerError('An error occurred fetching student result');
        }
    }

    /**
     * Get current result for a student
     * 
     * @return mixed
     */
    public function getStudentCurrentResult()
    {
        // Check if user is logged in
        if (!auth()->loggedIn()) {
            return $this->failUnauthorized('You must be logged in to access this resource');
        }
        
        $rules = [
            'student_session_id' => 'required|trim'
        ];
        
        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => 0, 
                'error' => $this->validator->getErrors()
            ]);
        }
        
        try {
            $student_session_id = $this->request->getPost('student_session_id');
            $data['exam_grades'] = $this->examResultModel->getAllGrades();
            $exam_groups_attempt = $this->examResultModel->getExamGroupByStudentSession($student_session_id);
            
            $data['exam_groups_attempt'] = $exam_groups_attempt;
            $examresult = view('student_results/_getExamGroupResult', $data);
            
            return $this->respond([
                'status' => 1, 
                'result' => $examresult
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching student current result: ' . $e->getMessage());
            return $this->failServerError('An error occurred fetching student current result');
        }
    }

    /**
     * Generate marksheet for students
     * 
     * @return mixed
     */
    public function generatemarksheet()
    {
        // Check if user is logged in
        if (!auth()->loggedIn()) {
            return $this->failUnauthorized('You must be logged in to access this resource');
        }
        
        $rules = [
            'exam_id' => 'required|trim',
            'check' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => 0, 
                'error' => $this->validator->getErrors()
            ]);
        }
        
        try {
            $exam_id = $this->request->getPost('exam_id');
            $students = $this->request->getPost('check');
            
            $exam = $this->examResultModel->getExamByID($exam_id);
            $students_result = [];
            
            if (!empty($students)) {
                foreach ($students as $student_value) {
                    $students_result[] = $this->examResultModel->getStudentExamResult($exam_id, $student_value);
                }
            }
            
            return $this->respond([
                'status' => 1, 
                'results' => $students_result
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error generating marksheet: ' . $e->getMessage());
            return $this->failServerError('An error occurred generating marksheet');
        }
    }

    /**
     * Get exam results for a specific student
     * 
     * @param int $studentId
     * @return mixed
     */
    public function getStudentResults($studentId)
    {
        // Check if user is logged in
        if (!auth()->loggedIn()) {
            return $this->failUnauthorized('You must be logged in to access this resource');
        }
        
        try {
            // Fetch student details
            $student = $this->studentModel->getStudentWithClass($studentId);
            if (!$student) {
                return $this->failNotFound("Student not found");
            }

            // Fetch exam groups
            $examGroups = $this->examStudentModel->getExamGroupsForStudent($studentId);
            
            // Fetch exam results
            $examResults = $this->examResultModel->getExamResultsForStudent($studentId);
            
            // Structure response
            $student['exam_groups'] = $examGroups;
            $student['exam_results'] = $examResults;

            return $this->respond($student);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching student results: ' . $e->getMessage());
            return $this->failServerError('An error occurred fetching student results');
        }
    }
    
    /**
     * Display student results page
     * 
     * @return mixed
     */
    public function index()
    {
        // Check if user is logged in
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }
        
        try {
            $data = [];
            $data['title'] = 'Student Exam Results';
            
            // Remove the student ID lookup and just show the search form
            // Get classes for the dropdown (if needed)
            $data['classes'] = []; // You can populate this if needed
            $data['sessions'] = []; // You can populate this if needed
            
            return view('student_results/index', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error loading student results page: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred loading the student results page');
        }
    }
    
    /**
     * Get detailed exam result for a specific exam
     * 
     * @return mixed
     */
    public function getExamResult()
    {
        // Check if user is logged in
        if (!auth()->loggedIn()) {
            return $this->failUnauthorized('You must be logged in to access this resource');
        }
        
        try {
            $examId = $this->request->getVar('exam_id');
            $studentId = $this->request->getVar('student_id');
            $examGroupId = $this->request->getVar('exam_group_id');
            
            if (!$examId || !$studentId) {
                return $this->failValidationError('Exam ID and Student ID are required');
            }
            
            // Get exam student record
            $examStudent = $this->examStudentModel->getStudentByExamAndStudentID($studentId, $examId);
            
            if (!$examStudent) {
                return $this->failNotFound('Student not enrolled in this exam');
            }
            
            // Get detailed exam results
            $examResults = $this->examResultModel->getStudentExamResults(
                $examId, 
                $examGroupId, 
                $examStudent->id, 
                $studentId
            );
            
            return $this->respond($examResults);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching exam result: ' . $e->getMessage());
            return $this->failServerError('An error occurred fetching the exam result');
        }
    }
    
    /**
     * Get exam results for multiple students
     * 
     * @return mixed
     */
    public function getExamResults()
    {
        // Check if user is logged in
        if (!auth()->loggedIn()) {
            return $this->failUnauthorized('You must be logged in to access this resource');
        }
        
        try {
            $examId = $this->request->getVar('exam_id');
            $examGroupId = $this->request->getVar('exam_group_id');
            $classId = $this->request->getVar('class_id');
            $sectionId = $this->request->getVar('section_id');
            
            if (!$examId || !$examGroupId) {
                return $this->failValidationError('Exam ID and Exam Group ID are required');
            }
            
            // Get students in the class/section
            $students = [];
            if ($classId && $sectionId) {
                $students = $this->examStudentModel->searchExamStudents($classId, $sectionId, $examId);
            }
            
            if (empty($students)) {
                return $this->respond(['message' => 'No students found for this exam']);
            }
            
            // Get exam subjects
            $examSubjects = $this->examSubjectModel->getExamSubjects($examId);
            
            // Prepare result array
            $results = [
                'students' => $students,
                'subjects' => $examSubjects,
                'results' => []
            ];
            
            // Get results for each student
            foreach ($students as $student) {
                $examStudent = $this->examStudentModel->getStudentByExamAndStudentID($student['id'], $examId);
                
                if ($examStudent) {
                    $studentResult = $this->examResultModel->getStudentExamResults(
                        $examId,
                        $examGroupId,
                        $examStudent->id,
                        $student['id']
                    );
                    
                    $results['results'][] = $studentResult;
                }
            }
            
            return $this->respond($results);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching exam results: ' . $e->getMessage());
            return $this->failServerError('An error occurred fetching the exam results');
        }
    }
    
    /**
     * Search for student results by admission number
     * 
     * @return mixed
     */
    public function searchByAdmissionNo()
    {
        // Check if user is logged in
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }
        
        try {
            $admissionNo = $this->request->getVar('admission_no');
            
            if (!$admissionNo) {
                return redirect()->back()->with('error', 'Admission number is required');
            }
            
            // Get student exams by admission number
            $studentExams = $this->examStudentModel->getstudentexam($admissionNo);
            
            if (empty($studentExams)) {
                return redirect()->back()->with('error', 'No exams found for this student');
            }
            
            $data = [];
            $data['title'] = 'Student Exam Results';
            $data['student_exams'] = $studentExams;
            
            return view('student_results/search_results', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error searching student results: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred searching for student results');
        }
    }
}