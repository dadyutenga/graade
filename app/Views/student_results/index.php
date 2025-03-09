<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= lang('Student Exam Results') ?></h3>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Search Form -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5><?= lang('Search Student Results') ?></h5>
                                </div>
                                <div class="card-body">
                                    <form id="searchForm" action="javascript:void(0)">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="class_id"><?= lang('Class') ?></label>
                                                    <select name="class_id" id="class_id" class="form-control">
                                                        <option value=""><?= lang('Select Class') ?></option>
                                                        <?php foreach ($classes ?? [] as $class): ?>
                                                            <option value="<?= $class['id'] ?>"><?= $class['class'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="section_id"><?= lang('Section') ?></label>
                                                    <select name="section_id" id="section_id" class="form-control">
                                                        <option value=""><?= lang('Select Section') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="session_id"><?= lang('Session') ?></label>
                                                    <select name="session_id" id="session_id" class="form-control">
                                                        <option value=""><?= lang('Select Session') ?></option>
                                                        <?php foreach ($sessions ?? [] as $session): ?>
                                                            <option value="<?= $session['id'] ?>"><?= $session['session'] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary"><?= lang('Search') ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Student List -->
                    <div class="row">
                        <div class="col-md-12">
                            <div id="studentList" class="card d-none">
                                <div class="card-header">
                                    <h5><?= lang('Student List') ?></h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th><?= lang('Admission No') ?></th>
                                                    <th><?= lang('Student Name') ?></th>
                                                    <th><?= lang('Class') ?></th>
                                                    <th><?= lang('Section') ?></th>
                                                    <th><?= lang('Action') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody id="studentListBody">
                                                <!-- Student list will be loaded here via AJAX -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Exam Group Selection -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div id="examGroupSelection" class="card d-none">
                                <div class="card-header">
                                    <h5><?= lang('Select Exam Group') ?></h5>
                                </div>
                                <div class="card-body">
                                    <form id="examGroupForm" action="javascript:void(0)">
                                        <input type="hidden" id="student_id" name="student_id">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exam_group_id"><?= lang('Exam Group') ?></label>
                                                    <select name="exam_group_id" id="exam_group_id" class="form-control">
                                                        <option value=""><?= lang('Select Exam Group') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="exam_id"><?= lang('Exam') ?></label>
                                                    <select name="exam_id" id="exam_id" class="form-control">
                                                        <option value=""><?= lang('Select Exam') ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary"><?= lang('Get Results') ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Exam Results -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div id="examResults" class="card d-none">
                                <div class="card-header">
                                    <h5><?= lang('Exam Results') ?></h5>
                                </div>
                                <div class="card-body" id="examResultsContent">
                                    <!-- Exam results will be loaded here via AJAX -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Class change event
    $('#class_id').change(function() {
        var classId = $(this).val();
        if (classId) {
            $.ajax({
                url: '<?= site_url('sections/get-by-class') ?>',
                type: 'POST',
                data: {class_id: classId},
                dataType: 'json',
                success: function(response) {
                    var html = '<option value=""><?= lang('Select Section') ?></option>';
                    if (response.sections && response.sections.length > 0) {
                        $.each(response.sections, function(index, section) {
                            html += '<option value="' + section.id + '">' + section.section + '</option>';
                        });
                    }
                    $('#section_id').html(html);
                }
            });
        } else {
            $('#section_id').html('<option value=""><?= lang('Select Section') ?></option>');
        }
    });
    
    // Search form submit
    $('#searchForm').submit(function(e) {
        e.preventDefault();
        var classId = $('#class_id').val();
        var sectionId = $('#section_id').val();
        var sessionId = $('#session_id').val();
        
        if (classId && sectionId && sessionId) {
            $.ajax({
                url: '<?= site_url('student-results/get-student-by-class-batch') ?>',
                type: 'POST',
                data: {
                    class_id: classId,
                    section_id: sectionId,
                    session_id: sessionId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.studentList && response.studentList.length > 0) {
                        var html = '';
                        $.each(response.studentList, function(index, student) {
                            html += '<tr>';
                            html += '<td>' + student.admission_no + '</td>';
                            html += '<td>' + student.firstname + ' ' + (student.middlename ? student.middlename + ' ' : '') + student.lastname + '</td>';
                            html += '<td>' + student.class + '</td>';
                            html += '<td>' + student.section + '</td>';
                            html += '<td><button class="btn btn-primary btn-sm select-student" data-id="' + student.id + '">Select</button></td>';
                            html += '</tr>';
                        });
                        $('#studentListBody').html(html);
                        $('#studentList').removeClass('d-none');
                    } else {
                        $('#studentListBody').html('<tr><td colspan="5" class="text-center"><?= lang('No students found') ?></td></tr>');
                        $('#studentList').removeClass('d-none');
                    }
                }
            });
        } else {
            alert('<?= lang('Please select class, section and session') ?>');
        }
    });
    
    // Select student
    $(document).on('click', '.select-student', function() {
        var studentId = $(this).data('id');
        $('#student_id').val(studentId);
        
        $.ajax({
            url: '<?= site_url('student-results/get-exam-group-by-student') ?>',
            type: 'POST',
            data: {student_id: studentId},
            dataType: 'json',
            success: function(response) {
                if (response.examgrouplist && response.examgrouplist.length > 0) {
                    var html = '<option value=""><?= lang('Select Exam Group') ?></option>';
                    $.each(response.examgrouplist, function(index, examGroup) {
                        html += '<option value="' + examGroup.id + '">' + examGroup.name + '</option>';
                    });
                    $('#exam_group_id').html(html);
                    $('#examGroupSelection').removeClass('d-none');
                } else {
                    alert('<?= lang('No exam groups found for this student') ?>');
                }
            }
        });
    });
    
    // Exam group change event
    $('#exam_group_id').change(function() {
        var examGroupId = $(this).val();
        if (examGroupId) {
            $.ajax({
                url: '<?= site_url('exams/get-by-exam-group') ?>',
                type: 'POST',
                data: {exam_group_id: examGroupId},
                dataType: 'json',
                success: function(response) {
                    var html = '<option value=""><?= lang('Select Exam') ?></option>';
                    if (response.exams && response.exams.length > 0) {
                        $.each(response.exams, function(index, exam) {
                            html += '<option value="' + exam.id + '">' + exam.exam + '</option>';
                        });
                    }
                    $('#exam_id').html(html);
                }
            });
        } else {
            $('#exam_id').html('<option value=""><?= lang('Select Exam') ?></option>');
        }
    });
    
    // Exam group form submit
    $('#examGroupForm').submit(function(e) {
        e.preventDefault();
        var studentId = $('#student_id').val();
        var examGroupId = $('#exam_group_id').val();
        var examId = $('#exam_id').val();
        
        if (studentId && examGroupId) {
            $.ajax({
                url: '<?= site_url('student-results/studentresult') ?>',
                type: 'POST',
                data: {
                    student_id: studentId,
                    exam_group_id: examGroupId,
                    exam_id: examId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == 1) {
                        $('#examResultsContent').html(response.result);
                        $('#examResults').removeClass('d-none');
                    } else {
                        alert(response.error ? response.error : '<?= lang('Error fetching results') ?>');
                    }
                }
            });
        } else {
            alert('<?= lang('Please select student and exam group') ?>');
        }
    });
});
</script>
<?= $this->endSection() ?> 