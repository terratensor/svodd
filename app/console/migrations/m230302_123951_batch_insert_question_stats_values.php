<?php

use yii\db\Migration;

/**
 * Class m230302_123951_batch_insert_question_stats_values
 */
class m230302_123951_batch_insert_question_stats_values extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $date = (new DateTimeImmutable())->format('Y-m-d H:i:s');
        $this->batchInsert(
            '{{%question_stats}}',
            ['question_id', 'number', 'title', 'description', 'url', 'comments_count', 'updated_at'],
            [
                [44538, '01', '28.02.2022', '', 'https://фкт-алтай.рф/qa/question/view-44538', 1312, $date],
                [44612, '02', '03.03.2022', '', 'https://фкт-алтай.рф/qa/question/view-44612', 843, $date],
                [44707, '03', '07.03.2022', '', 'https://фкт-алтай.рф/qa/question/view-44707', 827, $date],
                [44757, '04', '17.03.2022', '', 'https://фкт-алтай.рф/qa/question/view-44757', 749, $date],
                [44883, '05', '23.03.2022', '', 'https://фкт-алтай.рф/qa/question/view-44883', 469, $date],
                [44962, '06', '30.03.2022', '', 'https://фкт-алтай.рф/qa/question/view-44962', 658, $date],
                [45044, '07', '08.04.2022', '', 'https://фкт-алтай.рф/qa/question/view-45044', 1259, $date],
                [35650, '08', '13.04.2022', '', 'https://фкт-алтай.рф/qa/question/view-35650', 735, $date],
                [35298, '09', '20.04.2022', '', 'https://фкт-алтай.рф/qa/question/view-35298', 728, $date],
                [4604, '10', '02.05.2022', '', 'https://фкт-алтай.рф/qa/question/view-4604', 770, $date],
                [7533, '11', '08.05.2022', '', 'https://фкт-алтай.рф/qa/question/view-7533', 754, $date],
                [23174, '12', '18.05.2022', '', 'https://фкт-алтай.рф/qa/question/view-23174', 749, $date],
                [37945, '13', '26.05.2022', '', 'https://фкт-алтай.рф/qa/question/view-37945', 752, $date],
                [12422, '14', '02.06.2022', '', 'https://фкт-алтай.рф/qa/question/view-12422', 793, $date],
                [25867, '15', '15.06.2022', '', 'https://фкт-алтай.рф/qa/question/view-25867', 793, $date],
                [14365, '16', '24.06.2022', '', 'https://фкт-алтай.рф/qa/question/view-14365', 880, $date],
                [34312, '17', '10.07.2022', '', 'https://фкт-алтай.рф/qa/question/view-34312', 903, $date],
                [37694, '18', '25.07.2022', '', 'https://фкт-алтай.рф/qa/question/view-37694', 761, $date],
                [7279, '19', '09.08.2022', '', 'https://фкт-алтай.рф/qa/question/view-7279', 889, $date],
                [2656, '20', '04.09.2022', '', 'https://фкт-алтай.рф/qa/question/view-2656', 968, $date],
                [12734, '21', '16.09.2022', '', 'https://фкт-алтай.рф/qa/question/view-12734', 961, $date],
                [3893, '22', '24.09.2022', '', 'https://фкт-алтай.рф/qa/question/view-3893', 1050, $date],
                [4910, '23', '04.10.2022', '', 'https://фкт-алтай.рф/qa/question/view-4910', 972, $date],
                [3467, '24', '16.10.2022', '', 'https://фкт-алтай.рф/qa/question/view-3467', 1004, $date],
                [21294, '25', '29.10.2022', '', 'https://фкт-алтай.рф/qa/question/view-21294', 930, $date],
                [41574, '26', '20.11.2022', '', 'https://фкт-алтай.рф/qa/question/view-41574', 1046, $date],
                [12703, '27', '07.12.2022', '', 'https://фкт-алтай.рф/qa/question/view-12703', 819, $date],
                [8820, '28', '09.01.2023', '', 'https://фкт-алтай.рф/qa/question/view-8820', 720, $date],
                [12348, '29', '08.02.2023', '', 'https://фкт-алтай.рф/qa/question/view-12348', 781, $date],
                [8162, '30', 'Текущая активная тема', '', 'https://фкт-алтай.рф/qa/question/view-8162', 542, $date],
            ],
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->truncateTable('{{%question_stats}}');
    }
}
