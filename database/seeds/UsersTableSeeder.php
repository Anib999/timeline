<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Position;

class UsersTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        $luniva = User::create([
            'name' => "Luniva Tech",
            'username' => 'Luniva',
            'email' => 'luniva@gmail.com',
            'password' => bcrypt('luniva@123'),
            'isSuperAdmin' => true,
            'isAdmin' => true,
        ]);

        $ceoadmin = User::create([
            'name' => "CEO Admin",
            'username' => 'ceoadmin',
            'email' => 'ceo@gmail.com',
            'password' => bcrypt('ceoadmin@123'),
            'isSuperAdmin' => false,
            'isAdmin' => true,
        ]);

        $hradmin = User::create([
            'name' => "HR Admin",
            'username' => 'hradmin',
            'email' => 'hradmin@gmail.com',
            'password' => bcrypt('hradmin@123'),
            'isSuperAdmin' => false,
            'isAdmin' => true,
        ]);

        $superAdmin = Role::create([
            'name' => 'SuperAdmin',
            'details' => 'All Access'
        ]);
        
        // Role::create([
        //     'name' => 'ViewEmployee',
        //     'details' => 'ViewEmployee'
        // ]);
        // Role::create([
        //     'name' => 'ViewReport',
        //     'details' => 'View all Employee attendance'
        // ]);
        
        $ceoAdmin = Role::create([
            'name' => 'CEOAdmin',
            'details' => 'All Access'
        ]);

        $hrAdmin = Role::create([
            'name' => 'HRAdmin',
            'details' => 'HR Admin'
        ]);

        Role::create([
            'name' => 'Supervisor',
            'details' => 'supervisor'
        ]);

        // $addFieldVisit = Role::create([
        //     'name' => 'AddFieldVisit',
        //     'details' => 'Add Field Visit'
        // ]);


        $ceoPosition = Position::create([
            'name' => 'CEOAdmin',
            'details' => 'Ceo admin',
            'rank' => 14,
        ]);

        $hrPosition = Position::create([
            'name' => 'HRAdmin',
            'details' => 'Hr admin',
            'rank' => 12,
        ]);
        
        

        $luniva->roles()->attach($superAdmin);
        
        $ceoadmin->roles()->attach($ceoAdmin);
        $ceoadmin->positions()->attach($ceoPosition);

        $hradmin->roles()->attach($hrAdmin);
        $hradmin->positions()->attach($hrPosition);

        // $luniva->roles()->attach($addFieldVisit);

        // DB::table('settings')->insert([
        //     'check_in_setting_status' => 1,
        // ]);

        DB::table('settings')->insert([
            'setting_name' => 'check_in_without_admin_approval_setting',
            'status' => 1
        ]);
        DB::table('settings')->insert([
            'setting_name' => 'work_detail_user_accessable_setting',
            'status' => 1
        ]);
    }

}