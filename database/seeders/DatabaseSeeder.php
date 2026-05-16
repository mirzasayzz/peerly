<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Forum;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Admin User ---
        $admin = User::create([
            'name' => 'Tuba Mirza',
            'username' => 'tuba',
            'email' => 'tuba@peerly.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'reputation' => 500,
            'bio' => 'CS student & Peerly admin. Passionate about building community.',
            'university' => 'FAST NUCES',
            'major' => 'Computer Science',
            'year' => '3rd Year',
        ]);

        // --- Sample Users ---
        $users = collect();
        $sampleUsers = [
            ['name' => 'Ahsan Ali', 'username' => 'ahsan_dev', 'reputation' => 230, 'university' => 'LUMS', 'bio' => 'Full-stack developer focusing on Laravel and Vue. Love contributing to open source.', 'github' => 'https://github.com/ahsan'],
            ['name' => 'Sara Khan', 'username' => 'sarakhan', 'reputation' => 120, 'university' => 'NUST', 'bio' => 'AI/ML enthusiast. Working with Python, TensorFlow, and PyTorch.', 'github' => 'https://github.com/sarakhan'],
            ['name' => 'Bilal Raza', 'username' => 'bilalr', 'reputation' => 75, 'university' => 'COMSATS', 'bio' => 'Frontend dev exploring React and TailwindCSS. Always learning.', 'github' => 'https://github.com/bilalr'],
            ['name' => 'Fatima Noor', 'username' => 'fatima_n', 'reputation' => 340, 'university' => 'IBA Karachi', 'bio' => 'Data Science student. Passionate about data visualization and big data.', 'github' => 'https://github.com/fatima'],
            ['name' => 'Usman Sheikh', 'username' => 'usman_s', 'reputation' => 45, 'university' => 'UET Lahore', 'bio' => 'Mobile app developer using Flutter and Dart. Building cross-platform apps.', 'github' => 'https://github.com/usman'],
            ['name' => 'Zainab Ahmed', 'username' => 'zainab_a', 'reputation' => 150, 'university' => 'Habib University', 'bio' => 'Cybersecurity student. CEH certified. Interested in ethical hacking.', 'github' => 'https://github.com/zainab'],
            ['name' => 'Ali Hassan', 'username' => 'alihassan', 'reputation' => 290, 'university' => 'GIKI', 'bio' => 'Backend engineer. Go, Rust, and PostgreSQL. Building scalable systems.', 'github' => 'https://github.com/ali'],
            ['name' => 'Ayesha Tariq', 'username' => 'ayeshat', 'reputation' => 85, 'university' => 'PUCIT', 'bio' => 'UI/UX designer transitioning to frontend code. Figma to React.', 'github' => 'https://github.com/ayesha'],
            ['name' => 'Hamza Malik', 'username' => 'hamzam', 'reputation' => 410, 'university' => 'FAST NUCES', 'bio' => 'Competitive programmer. Codeforces Master. C++ is my primary language.', 'github' => 'https://github.com/hamza'],
            ['name' => 'Maryam Khan', 'username' => 'maryam_k', 'reputation' => 20, 'university' => 'NED University', 'bio' => 'Just starting my coding journey. Learning HTML, CSS, and JS.', 'github' => 'https://github.com/maryam'],
        ];

        foreach ($sampleUsers as $u) {
            $users->push(User::create(array_merge($u, [
                'email' => $u['username'] . '@peerly.com',
                'password' => bcrypt('password'),
                'major' => 'Computer Science',
                'year' => collect(['1st Year', '2nd Year', '3rd Year', '4th Year'])->random(),
            ])));
        }
        $users->push($admin);

        // --- Tags ---
        $tagNames = ['laravel', 'javascript', 'python', 'data-structures', 'algorithms', 'web-dev', 'database', 'machine-learning', 'career', 'internship', 'exam-prep', 'project-help', 'resources', 'css', 'react'];
        $tagColors = ['#7c5cfc', '#f7df1e', '#3776ab', '#e74c3c', '#2ecc71', '#e67e22', '#9b59b6', '#1abc9c', '#3498db', '#e91e63', '#ff9800', '#00bcd4', '#8bc34a', '#2196f3', '#61dafb'];
        $tags = collect();
        foreach ($tagNames as $i => $name) {
            $tags->push(Tag::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'color' => $tagColors[$i] ?? '#7c5cfc',
            ]));
        }

        // --- Categories & Forums ---
        $categoryData = [
            [
                'name' => 'Academics', 'slug' => 'academics', 'icon' => 'graduation-cap', 'color' => '#7c5cfc', 'sort_order' => 1,
                'forums' => [
                    ['name' => 'Course Discussions', 'slug' => 'courses', 'icon' => 'book-open', 'description' => 'Discuss courses, syllabi, and academic topics.'],
                    ['name' => 'Exam Preparation', 'slug' => 'exams', 'icon' => 'notebook', 'description' => 'Share tips, past papers, and study strategies.'],
                    ['name' => 'Research & Projects', 'slug' => 'research', 'icon' => 'flask', 'description' => 'FYP ideas, research papers, and project collaboration.'],
                ],
            ],
            [
                'name' => 'Programming', 'slug' => 'programming', 'icon' => 'code', 'color' => '#00d4ff', 'sort_order' => 2,
                'forums' => [
                    ['name' => 'Web Development', 'slug' => 'web-dev', 'icon' => 'globe', 'description' => 'HTML, CSS, JS, Laravel, React, and web frameworks.'],
                    ['name' => 'Data Structures & Algorithms', 'slug' => 'dsa', 'icon' => 'tree-structure', 'description' => 'DSA problems, competitive programming, and LeetCode.'],
                    ['name' => 'Mobile Development', 'slug' => 'mobile', 'icon' => 'device-mobile', 'description' => 'Flutter, React Native, Swift, and mobile app dev.'],
                ],
            ],
            [
                'name' => 'Career & Growth', 'slug' => 'career', 'icon' => 'briefcase', 'color' => '#4ade80', 'sort_order' => 3,
                'forums' => [
                    ['name' => 'Internships & Jobs', 'slug' => 'jobs', 'icon' => 'suitcase', 'description' => 'Internship opportunities, job postings, and interview prep.'],
                    ['name' => 'Resume & Portfolio', 'slug' => 'resume', 'icon' => 'file-text', 'description' => 'Get feedback on resumes, portfolios, and LinkedIn profiles.'],
                ],
            ],
            [
                'name' => 'General', 'slug' => 'general', 'icon' => 'chat-circle', 'color' => '#ff6b35', 'sort_order' => 4,
                'forums' => [
                    ['name' => 'Introductions', 'slug' => 'introductions', 'icon' => 'hand-waving', 'description' => 'Introduce yourself to the community!'],
                    ['name' => 'Off-Topic', 'slug' => 'off-topic', 'icon' => 'coffee', 'description' => 'Anything goes — memes, random thoughts, campus life.'],
                ],
            ],
        ];

        $forums = collect();
        foreach ($categoryData as $catData) {
            $forumsList = $catData['forums'];
            unset($catData['forums']);
            $category = Category::create($catData);
            foreach ($forumsList as $f) {
                $forums->push(Forum::create(array_merge($f, ['category_id' => $category->id])));
            }
        }

        // --- Sample Posts ---
        $postData = [
            ['title' => 'How to start learning Laravel from scratch?', 'body' => "I'm a 2nd year CS student and want to learn Laravel for web development. I know basic PHP but have no framework experience.\n\nCan anyone recommend a good learning path? Should I start with the documentation or take a Udemy course?\n\nAlso, what are the prerequisites I should know before diving into Laravel?", 'forum' => 'web-dev', 'tags' => ['laravel', 'web-dev']],
            ['title' => 'Best resources for Data Structures in C++', 'body' => "Looking for comprehensive resources to learn DS in C++. I have my mid-term exam in 3 weeks and need to cover:\n\n- Linked Lists (all types)\n- Stacks and Queues\n- Binary Trees and BST\n- Graphs (BFS, DFS)\n- Hashing\n\nAny YouTube playlists or textbooks that cover these well?", 'forum' => 'dsa', 'tags' => ['data-structures', 'algorithms', 'exam-prep']],
            ['title' => 'Internship experience at Accenture — What to expect?', 'body' => "I recently got selected for a summer internship at Accenture. Has anyone interned there before?\n\nI'd love to know:\n1. What's the work culture like?\n2. What tech stack do they use?\n3. How's the mentorship?\n4. Any tips for making the most of it?", 'forum' => 'jobs', 'tags' => ['internship', 'career']],
            ['title' => 'React vs Vue.js for a beginner in 2026', 'body' => "I want to learn a frontend framework and I'm torn between React and Vue.js.\n\nReact seems more popular and has more job opportunities, but Vue.js looks simpler and more beginner-friendly.\n\nWhat would you recommend for someone who knows basic JavaScript and wants to build projects for their portfolio?", 'forum' => 'web-dev', 'tags' => ['javascript', 'react', 'web-dev']],
            ['title' => 'Tips for solving LeetCode Medium problems consistently', 'body' => "I can solve most Easy problems on LeetCode but struggle with Medium ones. I usually get stuck for 30+ minutes and end up looking at the solution.\n\nHow do you approach Medium problems? Any patterns or techniques I should focus on?", 'forum' => 'dsa', 'tags' => ['algorithms', 'data-structures']],
            ['title' => 'FYP idea: Student Community Platform', 'body' => "For my final year project, I'm thinking of building a student community platform with features like:\n\n- Threaded discussions\n- Resource sharing\n- Study group matching\n- Course reviews\n\nIs this too simple for a FYP or can it be extended? Open to suggestions!", 'forum' => 'research', 'tags' => ['project-help', 'web-dev']],
            ['title' => 'How to optimize SQL queries for large datasets?', 'body' => "Working on a project with a database of 1M+ records. My queries are taking 5-10 seconds to execute.\n\nI've tried adding indexes but the performance improvement is minimal. Any advice on:\n- Query optimization techniques\n- When to use JOINs vs subqueries\n- Caching strategies\n- Database-level optimizations", 'forum' => 'web-dev', 'tags' => ['database', 'web-dev']],
            ['title' => 'Introduction — New member from FAST!', 'body' => "Hey everyone! 👋\n\nI'm a 3rd year CS student at FAST NUCES Islamabad. Interested in web development and machine learning.\n\nExcited to be part of this community. Looking forward to learning and sharing with all of you!", 'forum' => 'introductions', 'tags' => ['resources']],
        ];

        foreach ($postData as $i => $pd) {
            $forum = $forums->firstWhere('slug', $pd['forum']);
            $user = $users->random();
            $post = Post::create([
                'user_id' => $user->id,
                'forum_id' => $forum->id,
                'title' => $pd['title'],
                'slug' => Str::slug($pd['title']) . '-' . Str::random(5),
                'body' => $pd['body'],
                'last_activity_at' => now()->subHours(rand(1, 72)),
                'view_count' => rand(5, 200),
                'is_pinned' => $i === 0,
            ]);

            $postTags = $tags->filter(fn ($t) => in_array($t->name, $pd['tags']));
            $post->tags()->sync($postTags->pluck('id'));

            // Add sample comments
            $commentors = $users->random(rand(1, 3));
            foreach ($commentors as $commentor) {
                Comment::create([
                    'user_id' => $commentor->id,
                    'post_id' => $post->id,
                    'body' => collect([
                        'Great question! I had the same issue when I started learning.',
                        'Check out the official documentation, it\'s really well written.',
                        'I would recommend starting with the basics and building small projects.',
                        'This is really helpful, thanks for sharing!',
                        'I agree with the above. Also, practice is key.',
                        'Here\'s a resource I found useful: just Google it with the right keywords.',
                    ])->random(),
                    'depth' => 0,
                    'created_at' => now()->subHours(rand(1, 48)),
                ]);
            }
        }
    }
}
