<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Http\Modules\Authentication\Models\User;

class AdminPermissionsSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions for role
        Permission::create(['name' => 'roles.index']);
        Permission::create(['name' => 'roles.update']);
        Permission::create(['name' => 'roles.store']);
        Permission::create(['name' => 'roles.create']);
        Permission::create(['name' => 'roles.edit']);
        Permission::create(['name' => 'roles.addPermissionsToRole']);

        // create permissions for permission
        Permission::create(['name' => 'permissions.index']);
        Permission::create(['name' => 'permissions.update']);
        Permission::create(['name' => 'permissions.create']);
        Permission::create(['name' => 'permissions.store']);
        Permission::create(['name' => 'permissions.edit']);

        // create permissions for User
        Permission::create(['name' => 'user.index']);
        Permission::create(['name' => 'user.update']);
        Permission::create(['name' => 'user.store']);
        Permission::create(['name' => 'user.create']);
        Permission::create(['name' => 'user.edit']);
        Permission::create(['name' => 'user.profile']);
        Permission::create(['name' => 'user.profileUpdate']);

        // create roles-permission for User
        Permission::create(['name' => 'roles-permission.index']);
        Permission::create(['name' => 'roles-permission.update']);
        Permission::create(['name' => 'roles-permission.store']);
        Permission::create(['name' => 'roles-permission.create']);
        Permission::create(['name' => 'roles-permission.edit']);

        // create user and assign existing permissions
        $role1 = Role::create([
            'name' => 'SUPER_ADMIN'
        ]);

        //$role1 = Role::where('name', 'SUPER_ADMIN')->first();

        $role1->givePermissionTo('permissions.index');
        $role1->givePermissionTo('permissions.update');
        $role1->givePermissionTo('permissions.create');
        $role1->givePermissionTo('permissions.store');
        $role1->givePermissionTo('permissions.edit');

        $role1->givePermissionTo('roles.index');
        $role1->givePermissionTo('roles.update');
        $role1->givePermissionTo('roles.store');
        $role1->givePermissionTo('roles.create');
        $role1->givePermissionTo('roles.edit');
        $role1->givePermissionTo('roles.addPermissionsToRole');

        $role1->givePermissionTo('user.index');
        $role1->givePermissionTo('user.update');
        $role1->givePermissionTo('user.store');
        $role1->givePermissionTo('user.create');
        $role1->givePermissionTo('user.edit');
        $role1->givePermissionTo('user.profile');
        $role1->givePermissionTo('user.profileUpdate');

        $role1->givePermissionTo('roles-permission.index');
        $role1->givePermissionTo('roles-permission.update');
        $role1->givePermissionTo('roles-permission.store');
        $role1->givePermissionTo('roles-permission.create');
        $role1->givePermissionTo('roles-permission.edit');

        $user = User::create([
            'email' => 'superadmin@gmail.com',
            'mobile' => '1234567890',
            'password' => Hash::make('123456'),
        ]);
        //$user = User::where('email', 'superadmin@gmail.com')->first();

        $user->assignRole($role1);


        Permission::create(['name' => 'CasesController.getBriefDetails']);

        // QA KYC Role

        // create roles and assign existing permissions


        // create permissions for permission
        Permission::create(['name' => 'BasicKycDocController.getFarmList']);
        Permission::create(['name' => 'BasicKycDocController.getWarehouseFarmerList']);
        Permission::create(['name' => 'BasicKycDocController.getWarehouseCompanyList']);
        Permission::create(['name' => 'BasicKycDocController.getDetails']);
        Permission::create(['name' => 'BasicKycDocController.storeDetails']);
        Permission::create(['name' => 'BasicKycDocController.rejectDocument']);
        Permission::create(['name' => 'BasicKycDocController.approveDocument']);


        $role2 = Role::create([
            'name' => 'VALIDATE_CASE_KYC'
        ]);


        //$role2 = Role::where('name', 'VALIDATE_CASE_KYC')->first();

        $role2->givePermissionTo('CasesController.getBriefDetails');
        $role2->givePermissionTo('BasicKycDocController.getFarmList');
        $role2->givePermissionTo('BasicKycDocController.getWarehouseFarmerList');
        $role2->givePermissionTo('BasicKycDocController.getWarehouseCompanyList');
        $role2->givePermissionTo('BasicKycDocController.getDetails');
        $role2->givePermissionTo('BasicKycDocController.storeDetails');
        $role2->givePermissionTo('BasicKycDocController.rejectDocument');
        $role2->givePermissionTo('BasicKycDocController.approveDocument');



        $user = User::create([
            'email' => 'qa_kyc@gmail.com',
            'mobile' => '+919878767889',
            'password' => Hash::make('KYC@123'),
        ]);
        $user = User::where('email', 'qa_kyc@gmail.com')->first();
        $user->assignRole($role2);

        // QA_FINANCE

        // create permissions for permission
        Permission::create(['name' => 'LandDocController.getList']);
        Permission::create(['name' => 'LandDocController.getOwnedList']);
        Permission::create(['name' => 'LandDocController.getLeasedList']);
        Permission::create(['name' => 'LandDocController.getDetails']);
        Permission::create(['name' => 'LandDocController.storeDetails']);

        Permission::create(['name' => 'CompanyDocController.getList']);
        Permission::create(['name' => 'CompanyDocController.getDetails']);
        Permission::create(['name' => 'CompanyDocController.storeDetails']);

        Permission::create(['name' => 'BankAccountDocController.getFarmList']);
        Permission::create(['name' => 'BankAccountDocController.getWarehouseFarmerList']);
        Permission::create(['name' => 'BankAccountDocController.getWarehouseCompanyList']);

        Permission::create(['name' => 'BankAccountDocController.getDetails']);
        Permission::create(['name' => 'BankAccountDocController.storeDetails']);
        Permission::create(['name' => 'BankAccountDocController.getBankBranchDetailsByIfsc']);
        Permission::create(['name' => 'LandDocController.rejectDocument']);
        Permission::create(['name' => 'CompanyDocController.rejectDocument']);
        Permission::create(['name' => 'BankAccountDocController.rejectDocument']);
        Permission::create(['name' => 'LandDocController.approveDocument']);
        Permission::create(['name' => 'CompanyDocController.approveDocument']);
        Permission::create(['name' => 'BankAccountDocController.approveDocument']);

        // create roles and assign existing permissions
        $role3 = Role::create([
            'name' => 'VALIDATE_CASE_FINANCE'
        ]);
       // $role3 = Role::where('name', 'VALIDATE_CASE_FINANCE')->first();

        $role3->givePermissionTo('CasesController.getBriefDetails');
        $role3->givePermissionTo('LandDocController.getList');
        $role3->givePermissionTo('LandDocController.getOwnedList');
        $role3->givePermissionTo('LandDocController.getLeasedList');

        $role3->givePermissionTo('LandDocController.getDetails');
        $role3->givePermissionTo('LandDocController.storeDetails');

        $role2->givePermissionTo('CompanyDocController.getList');
        $role3->givePermissionTo('CompanyDocController.getDetails');
        $role3->givePermissionTo('CompanyDocController.storeDetails');

        $role3->givePermissionTo('BankAccountDocController.getFarmList');
        $role3->givePermissionTo('BankAccountDocController.getWarehouseFarmerList');
        $role3->givePermissionTo('BankAccountDocController.getWarehouseCompanyList');

        $role3->givePermissionTo('BankAccountDocController.getDetails');
        $role3->givePermissionTo('BankAccountDocController.storeDetails');
        $role3->givePermissionTo('BankAccountDocController.getBankBranchDetailsByIfsc');
        $role3->givePermissionTo('LandDocController.rejectDocument');
        $role3->givePermissionTo('CompanyDocController.rejectDocument');
        $role3->givePermissionTo('BankAccountDocController.rejectDocument');
        $role3->givePermissionTo('LandDocController.approveDocument');
        $role3->givePermissionTo('CompanyDocController.approveDocument');
        $role3->givePermissionTo('BankAccountDocController.approveDocument');

       $user = User::create([
            'email' => 'qa_finance@gmail.com',
            'mobile' => '+919767675456',
            'password' => Hash::make('FINANCE@123'),
        ]);
        //$user = User::where('email', 'qa_finance@gmail.com')->first();
        $user->assignRole($role3);

        // QA_WAREHOUSE

        // create permissions for permission

        Permission::create(['name' => 'WarehouseDocController.getWarehouseFarmerList']);
        Permission::create(['name' => 'WarehouseDocController.getWarehouseCompanyList']);
        Permission::create(['name' => 'WarehouseDocController.getDetails']);
        Permission::create(['name' => 'WarehouseDocController.storeDetails']);
        Permission::create(['name' => 'WarehouseDocController.storeStackDetails']);
        Permission::create(['name' => 'WarehouseDocController.rejectDocument']);
        Permission::create(['name' => 'WarehouseDocController.approveDocument']);

        // create roles and assign existing permissions
        $role4 = Role::where('name', 'VALIDATE_CASE_WAREHOUSE')->first();

        $role4->givePermissionTo('CasesController.getBriefDetails');
        $role4->givePermissionTo('WarehouseDocController.getWarehouseFarmerList');
        $role4->givePermissionTo('WarehouseDocController.getWarehouseCompanyList');
        $role4->givePermissionTo('WarehouseDocController.getDetails');
        $role4->givePermissionTo('WarehouseDocController.storeDetails');
        $role4->givePermissionTo('WarehouseDocController.storeStackDetails');
        $role4->givePermissionTo('WarehouseDocController.rejectDocument');
        $role4->givePermissionTo('WarehouseDocController.approveDocument');

        $user = User::create([
            'email' => 'qa_warehouse@gmail.com',
            'mobile' => '+918987856567',
            'password' => Hash::make('WAREHOUSE@123'),
        ]);
       // $user = User::where('email', 'qa_warehouse@gmail.com')->first();
        $user->assignRole($role4);

        // QA_KML

        // create permissions for permission
        Permission::create(['name' => 'GeoPlottingController.getList']);
        Permission::create(['name' => 'GeoPlottingController.getDetails']);
        Permission::create(['name' => 'GeoPlottingController.geoPlotReject']);
        Permission::create(['name' => 'GeoPlottingController.geoPlotApprove']);
        Permission::create(['name' => 'GeoTagController.getWarehouseFarmerList']);
        Permission::create(['name' => 'GeoTagController.getWarehouseCompanyList']);
        Permission::create(['name' => 'GeoTagController.getHarvestedList']);
        Permission::create(['name' => 'GeoTagController.getDetails']);
        Permission::create(['name' => 'GeoTagController.geoTagReject']);
        Permission::create(['name' => 'GeoTagController.geoTagApprove']);

        // create roles and assign existing permissions
        $role5 = Role::create([
            'name' => 'VALIDATE_CASE_KML'
        ]);
        //$role5 = Role::where('name', 'VALIDATE_CASE_KML')->first();

        $role5->givePermissionTo('CasesController.getBriefDetails');
        $role5->givePermissionTo('GeoPlottingController.getList');
        $role5->givePermissionTo('GeoPlottingController.getDetails');
        $role5->givePermissionTo('GeoPlottingController.geoPlotReject');
        $role5->givePermissionTo('GeoPlottingController.geoPlotApprove');
        $role5->givePermissionTo('GeoTagController.getWarehouseFarmerList');
        $role5->givePermissionTo('GeoTagController.getWarehouseCompanyList');
        $role5->givePermissionTo('GeoTagController.getHarvestedList');
        $role5->givePermissionTo('GeoTagController.getDetails');
        $role5->givePermissionTo('GeoTagController.geoTagReject');
        $role5->givePermissionTo('GeoTagController.geoTagApprove');



        $user = User::create([
            'email' => 'qa_kml@gmail.com',
            'mobile' => '+919678987678',
            'password' => Hash::make('KML@123'),
        ]);
        //$user = User::where('email', 'qa_kml@gmail.com')->first();
        $user->assignRole($role5);

        // QA_SCHEDULING

        // create permissions for permission
        Permission::create(['name' => 'ScheduledVisitController.getFarmList']);
        Permission::create(['name' => 'ScheduledVisitController.getWarehouseFarmerList']);
        Permission::create(['name' => 'ScheduledVisitController.getWarehouseCompanyList']);
        Permission::create(['name' => 'ScheduledVisitController.getDetails']);
        Permission::create(['name' => 'ScheduledVisitController.rescheduling']);

        // create roles and assign existing permissions
        $role6 = Role::create([
            'name' => 'VALIDATE_CASE_SCHEDULING'
        ]);
        //$role6 = Role::where('name', 'VALIDATE_CASE_SCHEDULING')->first();

        $role6->givePermissionTo('CasesController.getBriefDetails');
        $role6->givePermissionTo('ScheduledVisitController.getFarmList');
        $role6->givePermissionTo('ScheduledVisitController.getWarehouseFarmerList');
        $role6->givePermissionTo('ScheduledVisitController.getWarehouseCompanyList');
        $role6->givePermissionTo('ScheduledVisitController.getDetails');
        $role6->givePermissionTo('ScheduledVisitController.rescheduling');

        $user = User::create([
            'email' => 'qa_scheduling@gmail.com',
            'mobile' => '+918678987678',
            'password' => Hash::make('SCHEDULING@123'),
        ]);
        //$user = User::where('email', 'qa_scheduling@gmail.com')->first();
        $user->assignRole($role6);

        // create permissions
        Permission::create(['name' => 'IncompleteCaseController.getPendingList']);
        Permission::create(['name' => 'IncompleteCaseController.getUnpaidList']);

        // create roles and assign existing permissions

        $role = Role::create([
            'name' => 'INCOMPLETE_CASE'
        ]);

        //$role = Role::where('name', 'INCOMPLETE_CASE')->first();

        $role->givePermissionTo('IncompleteCaseController.getPendingList');
        $role->givePermissionTo('IncompleteCaseController.getUnpaidList');
        $user = User::create([
            'email' => 'qa_inoomplete_case@gmail.com',
            'mobile' => '+919767898767',
            'password' => Hash::make('INCOMPLETE@123'),
        ]);
        //$user = User::where('email', 'qa_inoomplete_case@gmail.com')->first();
        $user->assignRole($role);


/*
        Permission::create(['name' => 'CompanyDocController.getList']);
        $role2 = Role::where('name', 'VALIDATE_CASE_FINANCE')->first();
        $role2->givePermissionTo('CompanyDocController.getList');

        // create permissions for permission
        Permission::create(['name' => 'BasicKycDocController.rejectDocument']);
        Permission::create(['name' => 'BasicKycDocController.approveDocument']);
        $role2 = Role::where('name', 'VALIDATE_CASE_KYC')->first();
        $role2->givePermissionTo('BasicKycDocController.rejectDocument');
        $role2->givePermissionTo('BasicKycDocController.approveDocument');

        Permission::create(['name' => 'LandDocController.rejectDocument']);
        Permission::create(['name' => 'CompanyDocController.rejectDocument']);
        Permission::create(['name' => 'BankAccountDocController.rejectDocument']);

        Permission::create(['name' => 'LandDocController.approveDocument']);
        Permission::create(['name' => 'CompanyDocController.approveDocument']);
        Permission::create(['name' => 'BankAccountDocController.approveDocument']);

        $role2 = Role::where('name', 'VALIDATE_CASE_FINANCE')->first();
        $role2->givePermissionTo('LandDocController.rejectDocument');
        $role2->givePermissionTo('CompanyDocController.rejectDocument');
        $role2->givePermissionTo('BankAccountDocController.rejectDocument');
        $role2->givePermissionTo('LandDocController.approveDocument');
        $role2->givePermissionTo('CompanyDocController.approveDocument');
        $role2->givePermissionTo('BankAccountDocController.approveDocument');


        Permission::create(['name' => 'WarehouseDocController.rejectDocument']);
        Permission::create(['name' => 'WarehouseDocController.approveDocument']);
        $role2 = Role::where('name', 'VALIDATE_CASE_WAREHOUSE')->first();
        $role2->givePermissionTo('WarehouseDocController.rejectDocument');
        $role2->givePermissionTo('WarehouseDocController.approveDocument');*/
    }
}
