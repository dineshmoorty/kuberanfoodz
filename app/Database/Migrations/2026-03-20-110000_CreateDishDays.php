<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDishDays extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'dish_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'day_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('dish_id');
        $this->forge->addKey('day_id');
        $this->forge->addUniqueKey(['dish_id', 'day_id']);
        $this->forge->addForeignKey('dish_id', 'dishes', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('day_id', 'days', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('dish_days');

        if ($this->db->tableExists('dishes')) {
            $existingRows = $this->db->table('dishes')
                ->select('id, day_id')
                ->where('day_id IS NOT NULL', null, false)
                ->get()
                ->getResultArray();

            if (!empty($existingRows)) {
                $insertRows = [];

                foreach ($existingRows as $row) {
                    $insertRows[] = [
                        'dish_id' => (int) $row['id'],
                        'day_id' => (int) $row['day_id'],
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                }

                if (!empty($insertRows)) {
                    $this->db->table('dish_days')->ignore(true)->insertBatch($insertRows);
                }
            }
        }
    }

    public function down()
    {
        $this->forge->dropTable('dish_days', true);
    }
}
