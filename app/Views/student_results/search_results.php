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
                    <div class="card-tools">
                        <a href="<?= site_url('student-results') ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-arrow-left"></i> <?= lang('Back') ?>
                        </a>
                    </div>
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
                    
                    <?php if (!empty($student_exams)): ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5><?= lang('Student Information') ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th><?= lang('Student Name') ?></th>
                                                        <td><?= $student_exams[0]['firstname'] ?? '' ?> <?= $student_exams[0]['middlename'] ?? '' ?> <?= $student_exams[0]['lastname'] ?? '' ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><?= lang('Admission No') ?></th>
                                                        <td><?= $student_exams[0]['admission_no'] ?? '' ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <th><?= lang('Class') ?></th>
                                                        <td><?= $student_exams[0]['class_name'] ?? '' ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><?= lang('Section') ?></th>
                                                        <td><?= $student_exams[0]['section_name'] ?? '' ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5><?= lang('Exam Groups') ?></h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th><?= lang('Exam Group') ?></th>
                                                        <th><?= lang('Exam Type') ?></th>
                                                        <th><?= lang('Action') ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($student_exams as $exam): ?>
                                                    <tr>
                                                        <td><?= $exam['name'] ?></td>
                                                        <td><?= $exam['exam_type'] ?></td>
                                                        <td>
                                                            <button type="button" class="btn btn-primary btn-sm view-result" 
                                                                    data-student-id="<?= $exam['student_id'] ?>" 
                                                                    data-exam-group-id="<?= $exam['exam_group_id'] ?>">
                                                                <i class="fas fa-eye"></i> <?= lang('View Result') ?>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Exam Results Container -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div id="examResults" class="d-none">
                                    <div id="examResultsContent"></div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <?= lang('No exam results found for this student') ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // View result button click
    $('.view-result').click(function() {
        var studentId = $(this).data('student-id');
        var examGroupId = $(this).data('exam-group-id');
        
        $.ajax({
            url: '<?= site_url('student-results/studentresult') ?>',
            type: 'POST',
            data: {
                student_id: studentId,
                exam_group_id: examGroupId
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 1) {
                    $('#examResultsContent').html(response.result);
                    $('#examResults').removeClass('d-none');
                    // Scroll to results
                    $('html, body').animate({
                        scrollTop: $("#examResults").offset().top
                    }, 500);
                } else {
                    alert(response.error ? response.error : '<?= lang('Error fetching results') ?>');
                }
            }
        });
    });
});
</script>
<?= $this->endSection() ?> 