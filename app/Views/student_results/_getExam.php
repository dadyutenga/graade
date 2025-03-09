<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><?= $examresult->exam ?? '' ?></h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th><?= lang('Student Name') ?></th>
                                <td><?= $examresult->firstname ?? '' ?> <?= $examresult->middlename ?? '' ?> <?= $examresult->lastname ?? '' ?></td>
                            </tr>
                            <tr>
                                <th><?= lang('Admission No') ?></th>
                                <td><?= $examresult->admission_no ?? '' ?></td>
                            </tr>
                            <tr>
                                <th><?= lang('Class') ?></th>
                                <td><?= $examresult->class_name ?? '' ?></td>
                            </tr>
                            <tr>
                                <th><?= lang('Section') ?></th>
                                <td><?= $examresult->section_name ?? '' ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th><?= lang('Exam') ?></th>
                                <td><?= $examresult->exam ?? '' ?></td>
                            </tr>
                            <tr>
                                <th><?= lang('Session') ?></th>
                                <td><?= $examresult->session ?? '' ?></td>
                            </tr>
                            <tr>
                                <th><?= lang('Roll Number') ?></th>
                                <td><?= $examresult->roll_no ?? '' ?></td>
                            </tr>
                            <tr>
                                <th><?= lang('Exam From') ?></th>
                                <td><?= $examresult->exam_from ?? '' ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-12">
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
                                
                                if (!empty($examresult->exam_results)) {
                                    foreach ($examresult->exam_results as $exam_result) {
                                        $total_max_marks += $exam_result->max_marks;
                                        $total_min_marks += $exam_result->min_marks;
                                        $total_obtain_marks += $exam_result->get_marks;
                                        
                                        if ($exam_result->get_marks < $exam_result->min_marks) {
                                            $result = "Fail";
                                        }
                                ?>
                                <tr>
                                    <td><?= $exam_result->name ?> (<?= $exam_result->code ?>)</td>
                                    <td><?= $exam_result->max_marks ?></td>
                                    <td><?= $exam_result->min_marks ?></td>
                                    <td><?= $exam_result->get_marks ?></td>
                                    <td>
                                        <?php if ($exam_result->get_marks < $exam_result->min_marks) { ?>
                                            <span class="text-danger"><?= lang('Fail') ?></span>
                                        <?php } else { ?>
                                            <span class="text-success"><?= lang('Pass') ?></span>
                                        <?php } ?>
                                    </td>
                                    <td><?= $exam_result->note ?></td>
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
            </div>
        </div>
    </div>
</div> 