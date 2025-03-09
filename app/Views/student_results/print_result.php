<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Exam Result' ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .school-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .school-address {
            font-size: 14px;
            margin-bottom: 5px;
        }
        .result-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            text-align: center;
        }
        .student-info {
            width: 100%;
            margin-bottom: 20px;
        }
        .student-info td, .student-info th {
            padding: 5px;
            border: 1px solid #ddd;
        }
        .result-table {
            width: 100%;
            border-collapse: collapse;
        }
        .result-table th, .result-table td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }
        .result-table th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
        }
        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-item {
            text-align: center;
            width: 30%;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 30px;
            padding-top: 5px;
        }
        .pass {
            color: green;
            font-weight: bold;
        }
        .fail {
            color: red;
            font-weight: bold;
        }
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name"><?= $school_name ?? 'School Name' ?></div>
        <div class="school-address"><?= $school_address ?? 'School Address' ?></div>
        <div class="result-title"><?= $exam_group->name ?? 'Exam Result' ?></div>
    </div>
    
    <table class="student-info">
        <tr>
            <td width="20%"><strong><?= lang('Student Name') ?>:</strong></td>
            <td width="30%"><?= $examresult->student->firstname ?? '' ?> <?= $examresult->student->middlename ?? '' ?> <?= $examresult->student->lastname ?? '' ?></td>
            <td width="20%"><strong><?= lang('Exam Group') ?>:</strong></td>
            <td width="30%"><?= $exam_group->name ?? '' ?></td>
        </tr>
        <tr>
            <td><strong><?= lang('Admission No') ?>:</strong></td>
            <td><?= $examresult->student->admission_no ?? '' ?></td>
            <td><strong><?= lang('Exam Type') ?>:</strong></td>
            <td><?= $exam_group->exam_type ?? '' ?></td>
        </tr>
        <tr>
            <td><strong><?= lang('Class') ?>:</strong></td>
            <td><?= $examresult->student->class_name ?? '' ?></td>
            <td><strong><?= lang('Session') ?>:</strong></td>
            <td><?= $exam_group->session ?? '' ?></td>
        </tr>
        <tr>
            <td><strong><?= lang('Section') ?>:</strong></td>
            <td><?= $examresult->student->section_name ?? '' ?></td>
            <td><strong><?= lang('Roll Number') ?>:</strong></td>
            <td><?= $examresult->student->exam_roll_no ?? '' ?></td>
        </tr>
    </table>
    
    <?php if (!empty($exam_connections)): ?>
        <?php 
        foreach ($exam_connections as $exam_connection): 
            $exam_id = $exam_connection->exam_group_class_batch_exams_id;
            $exam = $examresult->exams['exam_' . $exam_id] ?? null;
            $exam_result = $examresult->exam_result['exam_result_' . $exam_id] ?? null;
            
            if ($exam && $exam_result):
        ?>
        <div style="margin-bottom: 20px;">
            <h4 style="margin-bottom: 10px;"><?= $exam->exam ?></h4>
            
            <table class="result-table">
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
                    
                    if (!empty($exam_result->exam_results)):
                        foreach ($exam_result->exam_results as $result_item):
                            $total_max_marks += $result_item->max_marks;
                            $total_min_marks += $result_item->min_marks;
                            $total_obtain_marks += $result_item->get_marks;
                            
                            if ($result_item->get_marks < $result_item->min_marks):
                                $result = "Fail";
                            endif;
                    ?>
                    <tr>
                        <td><?= $result_item->name ?> (<?= $result_item->code ?>)</td>
                        <td><?= $result_item->max_marks ?></td>
                        <td><?= $result_item->min_marks ?></td>
                        <td><?= $result_item->get_marks ?></td>
                        <td>
                            <?php if ($result_item->get_marks < $result_item->min_marks): ?>
                                <span class="fail"><?= lang('Fail') ?></span>
                            <?php else: ?>
                                <span class="pass"><?= lang('Pass') ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= $result_item->note ?></td>
                    </tr>
                    <?php
                        endforeach;
                    else:
                    ?>
                    <tr>
                        <td colspan="6" style="text-align: center;"><?= lang('No results found') ?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th><?= lang('Total') ?></th>
                        <th><?= $total_max_marks ?></th>
                        <th><?= $total_min_marks ?></th>
                        <th><?= $total_obtain_marks ?></th>
                        <th>
                            <?php if ($result == "Pass"): ?>
                                <span class="pass"><?= lang('Pass') ?></span>
                            <?php else: ?>
                                <span class="fail"><?= lang('Fail') ?></span>
                            <?php endif; ?>
                        </th>
                        <th></th>
                    </tr>
                    <tr>
                        <th colspan="2"><?= lang('Percentage') ?></th>
                        <th colspan="4">
                            <?php
                            if ($total_max_marks > 0):
                                $percentage = ($total_obtain_marks / $total_max_marks) * 100;
                                echo number_format($percentage, 2) . '%';
                                
                                // Display grade
                                if (!empty($exam_grades)):
                                    foreach ($exam_grades as $grade):
                                        if ($percentage >= $grade['percentage_from'] && $percentage <= $grade['percentage_upto']):
                                            echo ' (' . $grade['name'] . ')';
                                            break;
                                        endif;
                                    endforeach;
                                endif;
                            else:
                                echo '0%';
                            endif;
                            ?>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php 
            endif;
        endforeach; 
        ?>
    <?php endif; ?>
    
    <div class="signature">
        <div class="signature-item">
            <div class="signature-line"><?= lang('Class Teacher') ?></div>
        </div>
        <div class="signature-item">
            <div class="signature-line"><?= lang('Exam Coordinator') ?></div>
        </div>
        <div class="signature-item">
            <div class="signature-line"><?= lang('Principal') ?></div>
        </div>
    </div>
    
    <div class="footer">
        <p><?= lang('This is a computer-generated result. No signature is required.') ?></p>
        <p><?= lang('Printed on') ?>: <?= date('d-m-Y H:i:s') ?></p>
    </div>
    
    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer;">
            <?= lang('Print Result') ?>
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background-color: #f44336; color: white; border: none; cursor: pointer; margin-left: 10px;">
            <?= lang('Close') ?>
        </button>
    </div>
</body>
</html> 