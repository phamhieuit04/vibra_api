<?php

namespace Database\Seeders;

use App\Models\Bill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $from = count(Bill::all());
        for ($i = $from; $i < $from + 5; $i++) {
            DB::table('bills')->insert([
                'user_id'     => rand(1, 10),
                'playlist_id' => rand(1, 10),
                'status'      => 1,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now()
            ]);
        }
        for ($i = $from + 5; $i < $from + 10; $i++) {
            DB::table('bills')->insert([
                'user_id'    => rand(1, 10),
                'status'     => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }
}
