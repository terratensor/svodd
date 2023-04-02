<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%svodd_chart_data}}`.
 */
class m230328_134542_create_svodd_chart_data_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%svodd_chart_data}}', [
            'id' => $this->primaryKey(),
            'question_id' => $this->integer(),
            'topic_number' => $this->integer(),
            'title' => $this->string(),
            'start_datetime' => $this->timestamp(),
            'end_datetime' => $this->timestamp(),
            'start_comment_data_id' => $this->integer(),
            'end_comment_data_id' => $this->integer(),
            'comments_count' => $this->integer(),
            'comments_delta' => $this->integer(),
            'active' => $this->boolean(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp()
        ]);

        $created_at = (new DateTimeImmutable())->format('Y-m-d H:i:s');
        $this->batchInsert(
            '{{%svodd_chart_data}}',
            [
                'question_id', 'topic_number', 'title', 'start_comment_data_id', 'end_comment_data_id',
                'comments_count', 'comments_delta', 'active', 'created_at'
            ],
            [
                [44538, 1, '28.02.2022', 391102, 392710, 1312, 0, false, $created_at],
                [44612, 2, '03.03.2022', 392716, 393834, 843, 0, false, $created_at],
                [44707, 3, '07.03.2022', 393797, 394870, 827, 0, false, $created_at],
                [44757, 4, '17.03.2022', 394874, 396164, 749, 0, false, $created_at],
                [44883, 5, '23.03.2022', 396167, 397123, 469, 0, false, $created_at],
                [44962, 6, '30.03.2022', 397095, 398194, 658, 0, false, $created_at],
                [45044, 7, '08.04.2022', 398131, 399937, 1259, 0, false, $created_at],
                [35650, 8, '13.04.2022', 399939, 401009, 735, 0, false, $created_at],
                [35298, 9, '20.04.2022', 401008, 402352, 728, 0, false, $created_at],
                [4604, 10, '02.05.2022', 402183, 403676, 770, 0, false, $created_at],
                [7533, 11, '08.05.2022', 403675, 404788, 754, 0, false, $created_at],
                [23174, 12, '18.05.2022', 404785, 406053, 749, 0, false, $created_at],
                [37945, 13, '26.05.2022', 406052, 407058, 752, 0, false, $created_at],
                [12422, 14, '02.06.2022', 407059, 408051, 793, 0, false, $created_at],
                [25867, 15, '15.06.2022', 408048, 409148, 793, 0, false, $created_at],
                [14365, 16, '24.06.2022', 409149, 410385, 880, 0, false, $created_at],
                [34312, 17, '10.07.2022', 410386, 411742, 903, 0, false, $created_at],
                [37694, 18, '25.07.2022', 411741, 412755, 761, 0, false, $created_at],
                [7279, 19, '09.08.2022', 412754, 413995, 889, 0, false, $created_at],
                [2656, 20, '04.09.2022', 413993, 415637, 968, 0, false, $created_at],
                [12734, 21, '16.09.2022', 415638, 417038, 961, 0, false, $created_at],
                [3893, 22, '24.09.2022', 417039, 418407, 1050, 0, false, $created_at],
                [4910, 23, '04.10.2022', 418408, 419813, 972, 0, false, $created_at],
                [3467, 24, '16.10.2022', 419814, 421284, 1004, 0, false, $created_at],
                [21294, 25, '29.10.2022', 421285, 422592, 930, 0, false, $created_at],
                [41574, 26, '20.11.2022', 422593, 424428, 1046, 0, false, $created_at],
                [12703, 27, '07.12.2022', 424429, 425774, 819, 0, false, $created_at],
                [8820, 28, '09.01.2023', 425775, 427821, 720, 0, false, $created_at],
                [12348, 29, '08.02.2023', 427822, 429991, 781, 0, false, $created_at],
                [8162, 30, '12.03.2023', 429990, 432512, 957, 0, false, $created_at],
                [6006, 31, '27.03.2023', 432513, 434105, 822, 0, false, $created_at],
                [32649, 32, 'Текущая тема', 434106, null, 0, 0, true, $created_at],
            ],
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%svodd_chart_data}}');
    }
}
