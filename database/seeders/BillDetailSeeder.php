<?php

namespace Database\Seeders;

use App\Models\BillDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BillDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $from = count(BillDetail::all());
        for ($i = $from; $i < $from + 10; $i++) {
            DB::table('bill_details')->insert([
                'bill_id'    => rand(1, 10),
                'song_id'    => rand(1, 10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }
}
