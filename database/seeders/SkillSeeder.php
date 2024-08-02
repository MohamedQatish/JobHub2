<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            // Programming Languages
            ['name' => 'Laravel'],
            ['name' => 'JavaScript'],
            ['name' => 'REST API'],
            ['name' => 'Python'],
            ['name' => 'CSS'],
            ['name' => 'HTML'],
            ['name' => 'PHP'],
            ['name' => 'SQL'],
            ['name' => 'Java'],
            ['name' => 'C++'],
            ['name' => 'C#'],
            ['name' => 'Ruby'],
            ['name' => 'Swift'],
            ['name' => 'Kotlin'],
            ['name' => 'Go'],
            ['name' => 'TypeScript'],
            ['name' => 'R'],
            ['name' => 'Objective-C'],
            ['name' => 'Perl'],
            ['name' => 'Scala'],

            // Frameworks & Libraries
            ['name' => 'Flutter'],
            ['name' => 'jQuery'],
            ['name' => 'Next.JS'],
            ['name' => 'React.JS'],
            ['name' => 'Nest.JS'],
            ['name' => 'Spring'],
            ['name' => 'Django'],
            ['name' => 'Flask'],
            ['name' => 'Angular'],
            ['name' => 'Vue.JS'],
            ['name' => 'Bootstrap'],
            ['name' => 'Tailwind CSS'],
            ['name' => 'ASP.NET'],
            ['name' => 'Express.JS'],
            ['name' => 'Ruby on Rails'],
            ['name' => 'Symfony'],
            ['name' => 'Laravel Livewire'],

            // Databases
            ['name' => 'MongoDB'],
            ['name' => 'NoSQL'],
            ['name' => 'PostgreSQL'],
            ['name' => 'MySQL'],
            ['name' => 'SQLite'],
            ['name' => 'Oracle Database'],
            ['name' => 'Microsoft SQL Server'],
            ['name' => 'Redis'],
            ['name' => 'Elasticsearch'],
            ['name' => 'Cassandra'],
            ['name' => 'Firebase'],

            // DevOps & Cloud
            ['name' => 'Docker'],
            ['name' => 'Kubernetes'],
            ['name' => 'AWS'],
            ['name' => 'Azure'],
            ['name' => 'Google Cloud'],
            ['name' => 'Jenkins'],
            ['name' => 'Ansible'],
            ['name' => 'Terraform'],
            ['name' => 'CI/CD'],
            ['name' => 'Git'],
            ['name' => 'Linux'],
            ['name' => 'Shell Scripting'],
            ['name' => 'Vagrant'],
            ['name' => 'Puppet'],
            ['name' => 'Nagios'],
            ['name' => 'Prometheus'],
            ['name' => 'Grafana'],
            ['name' => 'Splunk'],

            // Data Science & Machine Learning
            ['name' => 'TensorFlow'],
            ['name' => 'PyTorch'],
            ['name' => 'Scikit-Learn'],
            ['name' => 'Pandas'],
            ['name' => 'NumPy'],
            ['name' => 'Matplotlib'],
            ['name' => 'Seaborn'],
            ['name' => 'Keras'],
            ['name' => 'NLTK'],
            ['name' => 'OpenCV'],
            ['name' => 'Big Data'],
            ['name' => 'Apache Hadoop'],
            ['name' => 'Apache Spark'],
            ['name' => 'Data Visualization'],
            ['name' => 'Data Mining'],
            ['name' => 'Natural Language Processing'],

            // Soft Skills
            ['name' => 'Problem Solving'],
            ['name' => 'Communication'],
            ['name' => 'Time Management'],
            ['name' => 'Leadership'],
            ['name' => 'Teamwork'],
            ['name' => 'Adaptability'],
            ['name' => 'Critical Thinking'],
            ['name' => 'Creativity'],
            ['name' => 'Conflict Resolution'],
            ['name' => 'Project Management'],
            ['name' => 'Emotional Intelligence'],

            // Design & Creative
            ['name' => 'Graphic Design'],
            ['name' => 'UI/UX Design'],
            ['name' => 'Adobe Photoshop'],
            ['name' => 'Adobe Illustrator'],
            ['name' => 'Adobe XD'],
            ['name' => 'Figma'],
            ['name' => 'Sketch'],
            ['name' => 'Blender'],
            ['name' => '3D Modeling'],
            ['name' => 'Video Editing'],
            ['name' => 'Motion Graphics'],
            ['name' => 'Photography'],
            ['name' => 'Content Writing'],
            ['name' => 'Copywriting'],

            // Marketing
            ['name' => 'SEO'],
            ['name' => 'Google Analytics'],
            ['name' => 'Social Media Marketing'],
            ['name' => 'Email Marketing'],
            ['name' => 'Content Marketing'],
            ['name' => 'PPC Advertising'],
            ['name' => 'Conversion Rate Optimization'],
            ['name' => 'Affiliate Marketing'],
            ['name' => 'Brand Management'],

            // Other Technical Skills
            ['name' => 'Network Security'],
            ['name' => 'Penetration Testing'],
            ['name' => 'Blockchain Development'],
            ['name' => 'Cybersecurity'],
            ['name' => 'IoT Development'],
            ['name' => 'Game Development'],
            ['name' => 'Augmented Reality'],
            ['name' => 'Virtual Reality'],
            ['name' => 'Computer Vision'],

            // Other Skills
            ['name' => 'Public Speaking'],
            ['name' => 'Data Entry'],
            ['name' => 'Technical Writing'],
            ['name' => 'Event Planning'],
            ['name' => 'Customer Service'],
            ['name' => 'Sales'],
            ['name' => 'Financial Analysis'],
            ['name' => 'Accounting'],
            ['name' => 'Human Resources'],
            ['name' => 'Recruiting'],
            ['name' => 'Legal Research'],
            ['name' => 'Paralegal Support'],
            ['name' => 'Project Coordination'],
        ];

        DB::table('skills')->insert($skills);
    }
}
