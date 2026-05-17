<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use App\Models\QuizResponse;
use App\Models\Answer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ──
        User::create([
            'name'      => 'Admin KuisYuk',
            'email'     => 'admin@kuisyuk.com',
            'password'  => Hash::make('admin123'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        // ── Demo Creator ──
        $creator = User::create([
            'name'      => 'Demo Creator',
            'email'     => 'creator@kuisyuk.com',
            'password'  => Hash::make('creator123'),
            'role'      => 'creator',
            'is_active' => true,
            'bio'       => 'Kreator kuis demo untuk testing KuisYuk!',
        ]);

        // ========================================
        // QUIZ 1: Sejarah Indonesia
        // ========================================
        $q1 = Quiz::create([
            'user_id'             => $creator->id,
            'title'               => 'Kuis Sejarah Indonesia',
            'description'         => 'Uji pengetahuanmu tentang sejarah Indonesia dari masa penjajahan hingga kemerdekaan.',
            'slug'                => 'SEJARAH',
            'is_active'           => true,
            'primary_key_enabled' => true,
            'primary_key_label'   => 'NIS',
            'primary_key_unique'  => true,
            'time_limit'          => 15,
        ]);

        $sejarahQuestions = [
            ['Siapa proklamator kemerdekaan Indonesia?', ['Soekarno & Hatta','Soeharto & Adam Malik','Sudirman & Sjahrir','Sultan Hamengkubuwono'], 0],
            ['Kapan Indonesia merdeka?', ['17 Agustus 1944','17 Agustus 1945','18 Agustus 1945','1 Juni 1945'], 1],
            ['Apa nama ibu kota Indonesia saat ini?', ['Surabaya','Bandung','Jakarta','Yogyakarta'], 2],
            ['Siapa presiden pertama Indonesia?', ['Soeharto','Soekarno','Habibie','Megawati'], 1],
            ['Apa semboyan negara Indonesia?', ['Bhinneka Tunggal Ika','Pancasila','Garuda Pancasila','Merah Putih'], 0],
        ];

        foreach ($sejarahQuestions as $i => [$qText, $opts, $correct]) {
            $q = Question::create([
                'quiz_id'       => $q1->id,
                'question_text' => $qText,
                'question_type' => 'multiple_choice',
                'order'         => $i + 1,
            ]);
            foreach ($opts as $j => $opt) {
                Option::create(['question_id'=>$q->id,'option_text'=>$opt,'is_correct'=>$j===$correct,'order'=>$j]);
            }
        }

        $names1 = ['Andi Saputra','Budi Santoso','Citra Dewi','Deni Rahman','Eka Putri','Fajar Nugroho'];
        foreach ($names1 as $idx => $name) {
            $resp = QuizResponse::create([
                'quiz_id'           => $q1->id,
                'respondent_name'   => $name,
                'primary_key_value' => '202300'.str_pad($idx+1,3,'0',STR_PAD_LEFT),
                'ip_address'        => '127.0.0.1',
                'submitted_at'      => now()->subHours(rand(1,72)),
            ]);
            foreach ($q1->questions as $question) {
                Answer::create(['quiz_response_id'=>$resp->id,'question_id'=>$question->id,'option_id'=>$question->options->random()->id]);
            }
        }

        // ========================================
        // QUIZ 2: Matematika Dasar
        // ========================================
        $q2 = Quiz::create([
            'user_id'             => $creator->id,
            'title'               => 'Matematika Dasar Kelas 7',
            'description'         => 'Kuis matematika untuk menguji pemahaman dasar siswa kelas 7 SMP.',
            'slug'                => 'MATDAS',
            'is_active'           => true,
            'primary_key_enabled' => false,
            'time_limit'          => 20,
        ]);

        $mathQuestions = [
            ['Berapa hasil dari 15 × 8?', ['110','120','130','125'], 1],
            ['Apa nilai dari √144?', ['11','12','13','14'], 1],
            ['Jika x + 7 = 15, berapa nilai x?', ['6','7','8','9'], 2],
            ['Berapa hasil dari 2³?', ['6','8','9','12'], 1],
            ['Pecahan 3/4 diubah ke desimal menjadi?', ['0.5','0.6','0.75','0.8'], 2],
            ['Keliling persegi dengan sisi 6 cm adalah?', ['18 cm','24 cm','36 cm','12 cm'], 1],
        ];

        foreach ($mathQuestions as $i => [$qText, $opts, $correct]) {
            $q = Question::create(['quiz_id'=>$q2->id,'question_text'=>$qText,'question_type'=>'multiple_choice','order'=>$i+1]);
            foreach ($opts as $j => $opt) {
                Option::create(['question_id'=>$q->id,'option_text'=>$opt,'is_correct'=>$j===$correct,'order'=>$j]);
            }
        }

        $names2 = ['Gilang Ramadhan','Hani Rahayu','Irfan Maulana','Joko Susilo','Kartika Sari','Lia Amelia','Maman Suryadi','Nita Wulandari'];
        foreach ($names2 as $name) {
            $resp = QuizResponse::create(['quiz_id'=>$q2->id,'respondent_name'=>$name,'ip_address'=>'127.0.0.1','submitted_at'=>now()->subHours(rand(1,48))]);
            foreach ($q2->questions as $question) {
                Answer::create(['quiz_response_id'=>$resp->id,'question_id'=>$question->id,'option_id'=>$question->options->random()->id]);
            }
        }

        // ========================================
        // QUIZ 3: Survei Kepuasan (Mixed)
        // ========================================
        $q3 = Quiz::create([
            'user_id'             => $creator->id,
            'title'               => 'Survei Kepuasan Layanan 2025',
            'description'         => 'Bantu kami meningkatkan layanan dengan mengisi survei singkat ini.',
            'slug'                => 'SURVEY1',
            'is_active'           => true,
            'primary_key_enabled' => false,
        ]);

        $sq1 = Question::create(['quiz_id'=>$q3->id,'question_text'=>'Bagaimana penilaian kamu terhadap kualitas layanan kami?','question_type'=>'multiple_choice','order'=>1]);
        foreach (['😍 Sangat Puas','😊 Puas','😐 Cukup','😕 Kurang Puas','😞 Tidak Puas'] as $j => $opt) {
            Option::create(['question_id'=>$sq1->id,'option_text'=>$opt,'is_correct'=>$j===0,'order'=>$j]);
        }
        $sq2 = Question::create(['quiz_id'=>$q3->id,'question_text'=>'Seberapa cepat respons tim kami?','question_type'=>'multiple_choice','order'=>2]);
        foreach (['⚡ Sangat Cepat','✅ Cepat','⏳ Cukup Cepat','🐢 Lambat'] as $j => $opt) {
            Option::create(['question_id'=>$sq2->id,'option_text'=>$opt,'is_correct'=>$j===0,'order'=>$j]);
        }
        $sq3 = Question::create(['quiz_id'=>$q3->id,'question_text'=>'Apa saran kamu untuk meningkatkan layanan kami?','question_type'=>'essay','order'=>3]);
        $sq4 = Question::create(['quiz_id'=>$q3->id,'question_text'=>'Fitur apa yang paling kamu suka?','question_type'=>'essay','order'=>4]);

        $surveyData = [
            ['Respondent Pertama','Sudah bagus, pertahankan responnya yang cepat!','Fitur export Excel'],
            ['Respondent Kedua','Tambahkan fitur laporan mingguan otomatis.','Dashboard analitik'],
            ['Respondent Ketiga','Sangat memuaskan! Terus tingkatkan kualitas.','Tampilan yang bersih'],
            ['Respondent Keempat','Harapannya ada lebih banyak template kuis.','Link pendek yang unik'],
            ['Respondent Kelima','Responnya cepat dan ramah. Pertahankan!','Customer support'],
        ];

        $q3Questions = $q3->questions()->orderBy('order')->get();
        foreach ($surveyData as [$name, $saran, $fitur]) {
            $resp = QuizResponse::create(['quiz_id'=>$q3->id,'respondent_name'=>$name,'ip_address'=>'127.0.0.1','submitted_at'=>now()->subHours(rand(2,96))]);
            foreach ($q3Questions as $question) {
                if ($question->question_type === 'multiple_choice') {
                    Answer::create(['quiz_response_id'=>$resp->id,'question_id'=>$question->id,'option_id'=>$question->options->random()->id]);
                } else {
                    $essay = $question->order === 3 ? $saran : $fitur;
                    Answer::create(['quiz_response_id'=>$resp->id,'question_id'=>$question->id,'essay_answer'=>$essay]);
                }
            }
        }

        // ========================================
        // QUIZ 4: Pengetahuan Umum (nonaktif)
        // ========================================
        $q4 = Quiz::create([
            'user_id'             => $creator->id,
            'title'               => 'Kuis Pengetahuan Umum',
            'description'         => 'Kuis pengetahuan umum untuk semua kalangan.',
            'slug'                => 'UMUM01',
            'is_active'           => false,
            'primary_key_enabled' => false,
        ]);

        $umumQ = [
            ['Apa ibukota Jepang?', ['Beijing','Seoul','Tokyo','Bangkok'], 2],
            ['Planet terbesar di tata surya adalah?', ['Saturnus','Jupiter','Uranus','Neptunus'], 1],
            ['Berapa jumlah benua di dunia?', ['5','6','7','8'], 2],
        ];
        foreach ($umumQ as $i => [$qText, $opts, $correct]) {
            $q = Question::create(['quiz_id'=>$q4->id,'question_text'=>$qText,'question_type'=>'multiple_choice','order'=>$i+1]);
            foreach ($opts as $j => $opt) {
                Option::create(['question_id'=>$q->id,'option_text'=>$opt,'is_correct'=>$j===$correct,'order'=>$j]);
            }
        }
    }
}
