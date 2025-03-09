<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><?= $exam_group->name ?? '' ?></h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th><?= lang('Student Name') ?></th>
                                <td><?= $examresult->student->firstname ?? '' ?> <?= $examresult->student->middlename ?? '' ?> <?= $examresult->student->lastname ?? '' ?></td>
                            </tr>
                            <tr>
                                <th><?= lang('Admission No') ?></th>
                                <td><?= $examresult->student->admission_no ?? '' ?></td>
                            </tr>
                            <tr>
                                <th><?= lang('Class') ?></th>
                                <td><?= $examresult->student->class_name ?? '' ?></td>
                            </tr>
                            <tr>
                                <th><?= lang('Section') ?></th>
                                <td><?= $examresult->student->section_name ?? '' ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th><?= lang('Exam Group') ?></th>
                                <td><?= $exam_group->name ?? '' ?></td>
                            </tr>
                            <tr>
                                <th><?= lang('Exam Type') ?></th>
                                <td><?= $exam_group->exam_type ?? '' ?></td>
                            </tr>
                            <tr>
                                <th><?= lang('Session') ?></th>
                                <td><?= $exam_group->session ?? '' ?></td>
                            </tr>
                            <tr>
                                <th><?= lang('Roll Number') ?></th>
                                <td><?= $examresult->student->exam_roll_no ?? '' ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <?php if (!empty($exam_connections)) { ?>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs" id="examTabs" role="tablist">
                            <?php 
                            $first_tab = true;
                            foreach ($exam_connections as $exam_connection) { 
                                $exam_id = $exam_connection->exam_group_class_batch_exams_id;
                                $exam = $examresult->exams['exam_' . $exam_id] ?? null;
                                if ($exam) {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link <?= $first_tab ? 'active' : '' ?>" id="exam-<?= $exam_id ?>-tab" data-toggle="tab" href="#exam-<?= $exam_id ?>" role="tab" aria-controls="exam-<?= $exam_id ?>" aria-selected="<?= $first_tab ? 'true' : 'false' ?>">
                                    <?= $exam->exam ?>
                                </a>
                            </li>
                            <?php 
                                    $first_tab = false;
                                }
                            } 
                            ?>
                        </ul>
                        <div class="tab-content" id="examTabsContent">
                            <?php 
                            $first_tab = true;
                            foreach ($exam_connections as $exam_connection) { 
                                $exam_id = $exam_connection->exam_group_class_batch_exams_id;
                                $exam = $examresult->exams['exam_' . $exam_id] ?? null;
                                $exam_result = $examresult->exam_result['exam_result_' . $exam_id] ?? null;
                                
                                if ($exam && $exam_result) {
                            ?>
                            <div class="tab-pane fade <?= $first_tab ? 'show active' : '' ?>" id="exam-<?= $exam_id ?>" role="tabpanel" aria-labelledby="exam-<?= $exam_id ?>-tab">
                                <div class="mt-3">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th><?= lang('Subject') ?></th>
                                                <th><?= lang('Max Marks') ?></th>
                                                <th><?= lang('Min Marks') ?></th>
                                                <th><?= lang('Marks Obtained') ?></th>
                                                <th><?= lang('Result') ?></th>
                                                <th><?= lang('Note') ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $total_max_marks = 0;
                                            $total_min_marks = 0;
                                            $total_obtain_marks = 0;
                                            $result = "Pass";
                                            
                                            if (!empty($exam_result->exam_results)) {
                                                foreach ($exam_result->exam_results as $result_item) {
                                                    $total_max_marks += $result_item->max_marks;
                                                    $total_min_marks += $result_item->min_marks;
                                                    $total_obtain_marks += $result_item->get_marks;
                                                    
                                                    if ($result_item->get_marks < $result_item->min_marks) {
                                                        $result = "Fail";
                                                    }
                                            ?>
                                            <tr>
                                                <td><?= $result_item->name ?> (<?= $result_item->code ?>)</td>
                                                <td><?= $result_item->max_marks ?></td>
                                                <td><?= $result_item->min_marks ?></td>
                                                <td><?= $result_item->get_marks ?></td>
                                                <td>
                                                    <?php if ($result_item->get_marks < $result_item->min_marks) { ?>
                                                        <span class="text-danger"><?= lang('Fail') ?></span>
                                                    <?php } else { ?>
                                                        <span class="text-success"><?= lang('Pass') ?></span>
                                                    <?php } ?>
                                                </td>
                                                <td><?= $result_item->note ?></td>
                                            </tr>
                                            <?php
                                                }
                                            } else {
                                            ?>
                                            <tr>
                                                <td colspan="6" class="text-center"><?= lang('No results found') ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th><?= lang('Total') ?></th>
                                                <th><?= $total_max_marks ?></th>
                                                <th><?= $total_min_marks ?></th>
                                                <th><?= $total_obtain_marks ?></th>
                                                <th>
                                                    <?php if ($result == "Pass") { ?>
                                                        <span class="text-success"><?= lang('Pass') ?></span>
                                                    <?php } else { ?>
                                                        <span class="text-danger"><?= lang('Fail') ?></span>
                                                    <?php } ?>
                                                </th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <th colspan="2"><?= lang('Percentage') ?></th>
                                                <th colspan="4">
                                                    <?php
                                                    if ($total_max_marks > 0) {
                                                        $percentage = ($total_obtain_marks / $total_max_marks) * 100;
                                                        echo number_format($percentage, 2) . '%';
                                                        
                                                        // Display grade
                                                        if (!empty($exam_grades)) {
                                                            foreach ($exam_grades as $grade) {
                                                                if ($percentage >= $grade['percentage_from'] && $percentage <= $grade['percentage_upto']) {
                                                                    echo ' (' . $grade['name'] . ')';
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                    } else {
                                                        echo '0%';
                                                    }
                                                    ?>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <?php 
                                    $first_tab = false;
                                }
                            } 
                            ?>
                        </div>
                    </div>
                </div>
                <?php } else { ?>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <?= lang('No exam connections found for this exam group') ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>