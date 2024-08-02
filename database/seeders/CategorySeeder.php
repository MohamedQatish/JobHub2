<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // IT Specialization
            [
                'specialization_id' => 1, // IT
                'name' => 'Backend Development'
            ],
            [
                'specialization_id' => 1,
                'name' => 'Frontend Development'
            ],
            [
                'specialization_id' => 1,
                'name' => 'Full-Stack Development'
            ],
            [
                'specialization_id' => 1,
                'name' => 'Data Science'
            ],
            [
                'specialization_id' => 1,
                'name' => 'DevOps'
            ],
            [
                'specialization_id' => 1,
                'name' => 'Cybersecurity'
            ],
            [
                'specialization_id' => 1,
                'name' => 'Cloud Engineering'
            ],
            [
                'specialization_id' => 1,
                'name' => 'Network Administration'
            ],
            [
                'specialization_id' => 1,
                'name' => 'Database Administration'
            ],
            [
                'specialization_id' => 1,
                'name' => 'Mobile App Development'
            ],

            // Design & Creative Specialization
            [
                'specialization_id' => 2, // Design & Creative
                'name' => 'Graphic Design'
            ],
            [
                'specialization_id' => 2,
                'name' => 'UI/UX Design'
            ],
            [
                'specialization_id' => 2,
                'name' => 'Web Design'
            ],
            [
                'specialization_id' => 2,
                'name' => 'Video Editing'
            ],
            [
                'specialization_id' => 2,
                'name' => 'Illustration'
            ],
            [
                'specialization_id' => 2,
                'name' => 'Animation'
            ],
            [
                'specialization_id' => 2,
                'name' => '3D Design'
            ],
            [
                'specialization_id' => 2,
                'name' => 'Branding & Identity'
            ],
            [
                'specialization_id' => 2,
                'name' => 'Game Art & Design'
            ],
            [
                'specialization_id' => 2,
                'name' => 'Motion Graphics'
            ],

            // Sales & Marketing Specialization
            [
                'specialization_id' => 3, // Sales & Marketing
                'name' => 'SEO'
            ],
            [
                'specialization_id' => 3,
                'name' => 'Content Marketing'
            ],
            [
                'specialization_id' => 3,
                'name' => 'Social Media Marketing'
            ],
            [
                'specialization_id' => 3,
                'name' => 'Email Marketing'
            ],
            [
                'specialization_id' => 3,
                'name' => 'Sales Strategy'
            ],
            [
                'specialization_id' => 3,
                'name' => 'Market Research'
            ],
            [
                'specialization_id' => 3,
                'name' => 'Public Relations'
            ],
            [
                'specialization_id' => 3,
                'name' => 'Brand Management'
            ],
            [
                'specialization_id' => 3,
                'name' => 'E-commerce Marketing'
            ],
            [
                'specialization_id' => 3,
                'name' => 'PPC Advertising'
            ],

            // Writing & Translation Specialization
            [
                'specialization_id' => 4, // Writing & Translation
                'name' => 'Copywriting'
            ],
            [
                'specialization_id' => 4,
                'name' => 'Technical Writing'
            ],
            [
                'specialization_id' => 4,
                'name' => 'Creative Writing'
            ],
            [
                'specialization_id' => 4,
                'name' => 'Translation'
            ],
            [
                'specialization_id' => 4,
                'name' => 'Editing & Proofreading'
            ],
            [
                'specialization_id' => 4,
                'name' => 'Blog Writing'
            ],
            [
                'specialization_id' => 4,
                'name' => 'SEO Writing'
            ],
            [
                'specialization_id' => 4,
                'name' => 'Subtitling'
            ],
            [
                'specialization_id' => 4,
                'name' => 'Ghostwriting'
            ],

            // Customer Support Specialization
            [
                'specialization_id' => 5, // Customer Support
                'name' => 'Technical Support'
            ],
            [
                'specialization_id' => 5,
                'name' => 'Customer Service'
            ],
            [
                'specialization_id' => 5,
                'name' => 'Help Desk'
            ],
            [
                'specialization_id' => 5,
                'name' => 'Live Chat Support'
            ],
            [
                'specialization_id' => 5,
                'name' => 'Customer Success Management'
            ],

            // Finance & Accounting Specialization
            [
                'specialization_id' => 6, // Finance & Accounting
                'name' => 'Accounting'
            ],
            [
                'specialization_id' => 6,
                'name' => 'Financial Analysis'
            ],
            [
                'specialization_id' => 6,
                'name' => 'Tax Preparation'
            ],
            [
                'specialization_id' => 6,
                'name' => 'Bookkeeping'
            ],
            [
                'specialization_id' => 6,
                'name' => 'Financial Planning'
            ],

            // Legal Specialization
            [
                'specialization_id' => 7, // Legal
                'name' => 'Corporate Law'
            ],
            [
                'specialization_id' => 7,
                'name' => 'Intellectual Property'
            ],
            [
                'specialization_id' => 7,
                'name' => 'Family Law'
            ],
            [
                'specialization_id' => 7,
                'name' => 'Criminal Law'
            ],
            [
                'specialization_id' => 7,
                'name' => 'Regulatory Compliance'
            ],

            // Engineering & Architecture Specialization
            [
                'specialization_id' => 8, // Engineering & Architecture
                'name' => 'Civil Engineering'
            ],
            [
                'specialization_id' => 8,
                'name' => 'Mechanical Engineering'
            ],
            [
                'specialization_id' => 8,
                'name' => 'Electrical Engineering'
            ],
            [
                'specialization_id' => 8,
                'name' => 'Structural Engineering'
            ],
            [
                'specialization_id' => 8,
                'name' => 'Architecture'
            ],
            [
                'specialization_id' => 8,
                'name' => 'Urban Planning'
            ],
            [
                'specialization_id' => 8,
                'name' => 'Landscape Architecture'
            ],

            // Education & Training Specialization
            [
                'specialization_id' => 9, // Education & Training
                'name' => 'Curriculum Development'
            ],
            [
                'specialization_id' => 9,
                'name' => 'E-Learning'
            ],
            [
                'specialization_id' => 9,
                'name' => 'Tutoring'
            ],
            [
                'specialization_id' => 9,
                'name' => 'Corporate Training'
            ],
            [
                'specialization_id' => 9,
                'name' => 'Special Education'
            ],

            // Healthcare & Medical Specialization
            [
                'specialization_id' => 10, // Healthcare & Medical
                'name' => 'Nursing'
            ],
            [
                'specialization_id' => 10,
                'name' => 'Medical Research'
            ],
            [
                'specialization_id' => 10,
                'name' => 'Pharmaceuticals'
            ],
            [
                'specialization_id' => 10,
                'name' => 'Telemedicine'
            ],
            [
                'specialization_id' => 10,
                'name' => 'Healthcare Administration'
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}
