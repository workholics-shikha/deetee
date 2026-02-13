<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PassSheetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pass_sheets')->insert(
            [
                ['title' => '1T1&T3( ST-UP TO 3.00MM)'],
                ['title' => '1T1&T3( ST- 3.10 TO 5.70MM)'],
                ['title' => '1T2'],
                ['title' => '1B1'],
                ['title' => '2L/R'],
                ['title' => '3T1'],
                ['title' => '3B1'],
                ['title' => '4L/R'],
                ['title' => '5T1'],
                ['title' => '5B1'],
                ['title' => '6L/R'],
                ['title' => '7T1'],
                ['title' => '7B1'],
                ['title' => '8L/R'],
                ['title' => '9L/R'],
                ['title' => '10L/R'],
                ['title' => '11T1'],
                ['title' => '11T2-FIN'],
                ['title' => '11T3'],
                ['title' => '11B1'],
                ['title' => '12L/R'],
                ['title' => '13T1'],
                ['title' => '13T2-FIN'],
                ['title' => '13T3'],
                ['title' => '13B1'],
                ['title' => '14L/R'],
                ['title' => '15T1'],
                ['title' => '15T2-FIN'],
                ['title' => '15T3'],
                ['title' => '15B1'],
                ['title' => '16SG-L/R'],
                ['title' => '16SG-T1&T2'],
                ['title' => '17SQ-L/R'],
                ['title' => '17SQ-T'],
                ['title' => '18IR-T/B'],
                ['title' => '19L/R'],
                ['title' => '20T/B'],
                ['title' => '21L/R'],
                ['title' => '22T/B'],
                ['title' => '23L/R'],
                ['title' => '24T/B'],
                ['title' => '25TH1-T/B'],
                ['title' => '25TH1-L/R'],
                ['title' => '26TH2-T/B'],
                ['title' => '26TH2-L/R'],
            ]);
    }
}
