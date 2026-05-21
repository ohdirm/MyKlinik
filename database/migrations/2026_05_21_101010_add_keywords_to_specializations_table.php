<?php

use App\Models\Specialization;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('specializations', function (Blueprint $table) {
            $table->json('keywords')->nullable()->after('label');
        });

        // Seed keywords for existing specializations
        $keywordMap = [
            'UMUM' => ['demam', 'flu', 'batuk', 'pilek', 'pusing', 'lemas', 'masuk angin', 'mual', 'diare', 'sakit kepala', 'capek', 'lelah', 'tidak enak badan', 'meriang', 'muntah'],
            'SPESIALIS_ANAK' => ['anak', 'bayi', 'balita', 'demam anak', 'batuk anak', 'imunisasi', 'tumbuh kembang', 'asi', 'rewel', 'ruam anak', 'campak', 'cacar'],
            'SPESIALIS_KANDUNGAN' => ['hamil', 'kehamilan', 'kandungan', 'haid', 'menstruasi', 'kontrasepsi', 'kb', 'keguguran', 'persalinan', 'usg', 'nyeri haid', 'telat haid', 'keputihan', 'rahim'],
            'SPESIALIS_PENYAKIT_DALAM' => ['lambung', 'maag', 'diabetes', 'kolesterol', 'darah tinggi', 'hipertensi', 'asam urat', 'ginjal', 'liver', 'hati', 'kencing manis', 'perut', 'pencernaan', 'usus', 'anemia', 'tipes', 'typhus'],
            'SPESIALIS_BEDAH' => ['operasi', 'benjolan', 'tumor', 'luka', 'patah', 'fraktur', 'hernia', 'usus buntu', 'ambeien', 'wasir', 'abses', 'kecelakaan', 'jahit'],
            'SPESIALIS_MATA' => ['mata', 'penglihatan', 'buram', 'rabun', 'katarak', 'glaukoma', 'mata merah', 'silinder', 'minus', 'plus', 'iritasi mata', 'perih mata'],
            'SPESIALIS_THT' => ['telinga', 'hidung', 'tenggorokan', 'amandel', 'sinusitis', 'polip', 'tuli', 'pendengaran', 'mimisan', 'radang tenggorokan', 'suara serak', 'bersin'],
            'SPESIALIS_KULIT' => ['kulit', 'gatal', 'jerawat', 'eksim', 'alergi kulit', 'ruam', 'jamur', 'kutil', 'psoriasis', 'dermatitis', 'bisul', 'herpes', 'cacar air', 'panu', 'kurap', 'biduran'],
            'SPESIALIS_JANTUNG' => ['jantung', 'dada', 'sesak', 'nyeri dada', 'berdebar', 'tekanan darah', 'kolesterol tinggi', 'stroke', 'pembuluh darah', 'aritmia'],
            'SPESIALIS_BEDAH_SARAF' => ['saraf', 'kebas', 'mati rasa', 'sakit pinggang', 'saraf kejepit', 'kelumpuhan', 'tremor', 'kejang', 'epilepsi', 'migrain berat', 'tulang belakang'],
        ];

        foreach ($keywordMap as $value => $keywords) {
            Specialization::where('value', $value)
                ->update(['keywords' => json_encode($keywords)]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('specializations', function (Blueprint $table) {
            $table->dropColumn('keywords');
        });
    }
};
