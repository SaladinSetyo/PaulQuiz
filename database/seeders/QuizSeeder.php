<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Content; // Import Content model

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the first module
        $module = Module::where('title', 'Apa itu fintech')->first();

        if ($module) {
            // Create a quiz for the module
            $quiz = Quiz::firstOrCreate(
                ['module_id' => $module->id, 'title' => 'Kuis Dasar Fintech'],
                ['description' => 'Uji pemahaman dasar Anda tentang apa itu Fintech.']
            );

            // Create a Content entry for this quiz
            Content::firstOrCreate(
                ['module_id' => $module->id, 'quiz_id' => $quiz->id],
                [
                    'title' => $quiz->title,
                    'type' => 'quiz',
                    'body' => $quiz->description,
                    'order' => 100, // Place quizzes at the end of the module content
                ]
            );

            // Question 1
            $q1 = Question::firstOrCreate(
                ['quiz_id' => $quiz->id, 'question_text' => 'Apa kepanjangan dari Fintech?']
            );
            Answer::firstOrCreate(['question_id' => $q1->id, 'answer_text' => 'Financial Technology'], ['is_correct' => true]);
            Answer::firstOrCreate(['question_id' => $q1->id, 'answer_text' => 'Finance and Technique'], ['is_correct' => false]);
            Answer::firstOrCreate(['question_id' => $q1->id, 'answer_text' => 'Financial Technicality'], ['is_correct' => false]);
            Answer::firstOrCreate(['question_id' => $q1->id, 'answer_text' => 'Future Technology'], ['is_correct' => false]);

            // Question 2
            $q2 = Question::firstOrCreate(
                ['quiz_id' => $quiz->id, 'question_text' => 'Manakah di bawah ini yang BUKAN merupakan contoh layanan Fintech?'
                ]);
            Answer::firstOrCreate(['question_id' => $q2->id, 'answer_text' => 'Dompet Digital'], ['is_correct' => false]);
            Answer::firstOrCreate(['question_id' => $q2->id, 'answer_text' => 'Pinjaman Online (P2P)'], ['is_correct' => false]);
            Answer::firstOrCreate(['question_id' => $q2->id, 'answer_text' => 'Pembukaan rekening di kantor cabang bank'], ['is_correct' => true]);
            Answer::firstOrCreate(['question_id' => $q2->id, 'answer_text' => 'Investasi Saham Online'], ['is_correct' => false]);
            
            // Question 3
            $q3 = Question::firstOrCreate(
                ['quiz_id' => $quiz->id, 'question_text' => 'Tujuan utama dari Fintech adalah...']
            );
            Answer::firstOrCreate(['question_id' => $q3->id, 'answer_text' => 'Membuat layanan keuangan lebih eksklusif'], ['is_correct' => false]);
            Answer::firstOrCreate(['question_id' => $q3->id, 'answer_text' => 'Membuat layanan keuangan lebih efisien dan mudah diakses'], ['is_correct' => true]);
            Answer::firstOrCreate(['question_id' => $q3->id, 'answer_text' => 'Menggantikan peran bank sepenuhnya'], ['is_correct' => false]);
            Answer::firstOrCreate(['question_id' => $q3->id, 'answer_text' => 'Menurunkan tingkat keamanan transaksi'], ['is_correct' => false]);
        }
    }
}
