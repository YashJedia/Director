<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\User;
use App\Models\Language;
use App\Models\Report;
use App\Models\UserInvitation;
use Illuminate\Support\Facades\Hash;

class UpdatedDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or update admins with different roles
        $superAdmin = Admin::updateOrCreate(
            ['email' => 'superadmin@demo.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('demo123'),
                'role' => 'super_admin',
                'two_factor_enabled' => false,
            ]
        );

        $admin = Admin::updateOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'Regular Admin',
                'password' => Hash::make('demo123'),
                'role' => 'admin',
                'two_factor_enabled' => true,
            ]
        );

        $manager = Admin::updateOrCreate(
            ['email' => 'manager@demo.com'],
            [
                'name' => 'Manager',
                'password' => Hash::make('demo123'),
                'role' => 'manager',
                'two_factor_enabled' => false,
            ]
        );

        // Create or update users
        $user1 = User::updateOrCreate(
            ['email' => 'john@demo.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('demo123'),
                'phone' => '+1234567890',
                'department' => 'IT',
                'job_title' => 'Developer',
                'location' => 'New York',
                'bio' => 'Experienced developer with 5+ years in web development.',
                'two_factor_enabled' => false,
                'is_invited' => false,
                'password_set_at' => now(),
            ]
        );

        $user2 = User::updateOrCreate(
            ['email' => 'jane@demo.com'],
            [
                'name' => 'Jane Smith',
                'password' => Hash::make('demo123'),
                'phone' => '+1234567891',
                'department' => 'Marketing',
                'job_title' => 'Marketing Manager',
                'location' => 'Los Angeles',
                'bio' => 'Marketing professional with expertise in digital campaigns.',
                'two_factor_enabled' => true,
                'is_invited' => true,
                'invited_at' => now()->subDays(5),
                'password_set_at' => now()->subDays(3),
            ]
        );

        $invitedUser = User::updateOrCreate(
            ['email' => 'invited@demo.com'],
            [
                'name' => 'Invited User',
                'password' => Hash::make('demo123'),
                'phone' => '+1234567892',
                'department' => 'HR',
                'job_title' => 'HR Specialist',
                'location' => 'Chicago',
                'bio' => 'HR professional specializing in recruitment.',
                'two_factor_enabled' => false,
                'is_invited' => true,
                'invited_at' => now()->subDays(2),
                'password_set_at' => null,
            ]
        );

        // Create languages
        $languages = [
            ['name' => 'Spanish', 'status' => 'active'],
            ['name' => 'French', 'status' => 'active'],
            ['name' => 'German', 'status' => 'active'],
            ['name' => 'Italian', 'status' => 'inactive'],
            ['name' => 'Portuguese', 'status' => 'active'],
            ['name' => 'Chinese', 'status' => 'active'],
            ['name' => 'Japanese', 'status' => 'inactive'],
            ['name' => 'Korean', 'status' => 'active'],
        ];

        foreach ($languages as $langData) {
            Language::updateOrCreate(
                ['name' => $langData['name']],
                $langData
            );
        }

        // Assign languages to users
        $spanish = Language::where('name', 'Spanish')->first();
        $french = Language::where('name', 'French')->first();
        $german = Language::where('name', 'German')->first();
        $portuguese = Language::where('name', 'Portuguese')->first();

        $spanish->update(['assigned_user_id' => $user1->id]);
        $french->update(['assigned_user_id' => $user1->id]);
        $german->update(['assigned_user_id' => $user2->id]);
        $portuguese->update(['assigned_user_id' => $user2->id]);

        // Create or update reports
        $report1 = Report::updateOrCreate(
            [
                'user_id' => $user1->id,
                'language_id' => $spanish->id,
                'quarter' => 'Q3 2025'
            ],
            [
                'title' => 'Spanish Q3 2025 Report',
                'status' => 'submitted',
                'review_status' => 'reviewed',
                'admin_remarks' => 'Good progress on language goals. Keep up the excellent work!',
                'reviewed_at' => now()->subDays(1),
                'reviewed_by' => $admin->id,
                'languages_previous_year' => 2,
                'languages_goal_2025' => 5,
                'languages_goal_q1' => 1,
                'languages_achieved_q1' => 1,
                'volunteers_previous_year' => 10,
                'volunteers_goal_2025' => 25,
                'volunteers_goal_q1' => 5,
                'volunteers_achieved_q1' => 6,
                'facebook_reach' => 1500,
                'instagram_reach' => 800,
                'youtube_reach' => 2000,
                'website_reach' => 3000,
                'evangelistic_students' => 15,
                'discipleship_students' => 8,
                'leadership_students' => 3,
                'evangelistic_conversations' => 25,
                'pastoral_connections' => 12,
                'income_euros' => 5000.00,
                'expenditure_euros' => 3500.00,
                'pr_total_organic_reach' => 7300,
                'personal_fte' => 1.0,
                'new_activity' => 'Launched new online Bible study program for Spanish speakers.',
                'organizational_highlight' => 'Successfully reached 1000+ Spanish speakers through social media campaigns.',
                'organizational_concern' => 'Need more volunteers for the growing Spanish community.',
                'organizational_issues' => 'None at this time.',
            ]
        );

        $report2 = Report::updateOrCreate(
            [
                'user_id' => $user1->id,
                'language_id' => $french->id,
                'quarter' => 'Q3 2025'
            ],
            [
                'title' => 'French Q3 2025 Report',
                'status' => 'submitted',
                'review_status' => 'pending',
                'languages_previous_year' => 1,
                'languages_goal_2025' => 3,
                'languages_goal_q1' => 1,
                'languages_achieved_q1' => 1,
                'volunteers_previous_year' => 5,
                'volunteers_goal_2025' => 15,
                'volunteers_goal_q1' => 3,
                'volunteers_achieved_q1' => 4,
                'facebook_reach' => 800,
                'instagram_reach' => 400,
                'youtube_reach' => 1200,
                'website_reach' => 1500,
                'evangelistic_students' => 8,
                'discipleship_students' => 5,
                'leadership_students' => 2,
                'evangelistic_conversations' => 15,
                'pastoral_connections' => 8,
                'income_euros' => 2500.00,
                'expenditure_euros' => 1800.00,
                'pr_total_organic_reach' => 3900,
                'personal_fte' => 0.5,
                'new_activity' => 'Started French language Bible study group.',
                'organizational_highlight' => 'French community showing strong engagement.',
                'organizational_concern' => 'Limited resources for French language content.',
                'organizational_issues' => 'None at this time.',
            ]
        );

        $report3 = Report::updateOrCreate(
            [
                'user_id' => $user2->id,
                'language_id' => $german->id,
                'quarter' => 'Q3 2025'
            ],
            [
                'title' => 'German Q3 2025 Report',
                'status' => 'submitted',
                'review_status' => 'approved',
                'admin_remarks' => 'Excellent work! All goals exceeded expectations.',
                'reviewed_at' => now()->subDays(3),
                'reviewed_by' => $superAdmin->id,
                'languages_previous_year' => 3,
                'languages_goal_2025' => 8,
                'languages_goal_q1' => 2,
                'languages_achieved_q1' => 3,
                'volunteers_previous_year' => 15,
                'volunteers_goal_2025' => 40,
                'volunteers_goal_q1' => 8,
                'volunteers_achieved_q1' => 10,
                'facebook_reach' => 2500,
                'instagram_reach' => 1200,
                'youtube_reach' => 3500,
                'website_reach' => 4000,
                'evangelistic_students' => 25,
                'discipleship_students' => 15,
                'leadership_students' => 8,
                'evangelistic_conversations' => 45,
                'pastoral_connections' => 20,
                'income_euros' => 8000.00,
                'expenditure_euros' => 5500.00,
                'pr_total_organic_reach' => 11200,
                'personal_fte' => 1.5,
                'new_activity' => 'Expanded German language ministry with new outreach programs.',
                'organizational_highlight' => 'German community growth exceeded all projections.',
                'organizational_concern' => 'Need additional space for growing German congregation.',
                'organizational_issues' => 'None at this time.',
            ]
        );

        // Create or update pending invitations
        UserInvitation::updateOrCreate(
            ['email' => 'newuser@demo.com'],
            [
                'invited_by' => $admin->id,
                'status' => 'pending',
                'expires_at' => now()->addDays(7),
            ]
        );

        UserInvitation::updateOrCreate(
            ['email' => 'another@demo.com'],
            [
                'invited_by' => $superAdmin->id,
                'status' => 'pending',
                'expires_at' => now()->addDays(5),
            ]
        );

        $this->command->info('Demo data created successfully!');
        $this->command->info('Super Admin: superadmin@demo.com / demo123');
        $this->command->info('Admin: admin@demo.com / demo123');
        $this->command->info('Manager: manager@demo.com / demo123');
        $this->command->info('User: john@demo.com / demo123');
        $this->command->info('User: jane@demo.com / demo123');
        $this->command->info('Invited User: invited@demo.com / demo123');
    }
}