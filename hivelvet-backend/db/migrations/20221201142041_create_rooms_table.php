<?php

declare(strict_types=1);

/*
 * Hivelvet open source platform - https://riadvice.tn/
 *
 * Copyright (c) 2022 RIADVICE SUARL and by respective authors (see below).
 *
 * This program is free software; you can redistribute it and/or modify it under the
 * terms of the GNU Lesser General Public License as published by the Free Software
 * Foundation; either version 3.0 of the License, or (at your option) any later
 * version.
 *
 * Hivelvet is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along
 * with Hivelvet; if not, see <http://www.gnu.org/licenses/>.
 */

use Phinx\Migration\AbstractMigration;

final class CreateRoomsTable extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('rooms');
        $table->addColumn('name', 'string', ['limit' => 256, 'null' => false])
            ->addColumn('short_link', 'string', ['limit' => 32, 'null' => false])
            ->addColumn('preset_id', 'integer', ['null' => true])
            ->addColumn('created_on', 'datetime', ['default' => '0001-01-01 00:00:00', 'timezone' => true])
            ->addColumn('updated_on', 'datetime', ['default' => '0001-01-01 00:00:00', 'timezone' => true])
            ->addIndex('name', ['unique' => true, 'name' => 'idx_rooms_name'])
            ->addForeignKey(['preset_id'], 'presets', ['id'], ['constraint' => 'room_preset_id'])
            ->save()
        ;
    }

    public function down(): void
    {
        $this->table('rooms')->drop()->save();
    }
}