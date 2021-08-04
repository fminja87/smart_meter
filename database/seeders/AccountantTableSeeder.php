<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class AccountantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accountant = new Account();
        $accountant->name = "System Accountant";
        $accountant->email = "accountant@system.com";
        $accountant->password = Hash::make("12345678");
        $accountant->save();
    }
}
