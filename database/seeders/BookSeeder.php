<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Seed 53 buku fisik dari data perpustakaan.
     */
    public function run(): void
    {
        $category = Category::where('slug', 'physical')->firstOrFail();

        $books = [
            ['code' => '1',  'title' => '100 Tokoh Paling Berpengaruh Dalam Sejarah', 'author' => 'Michael H. Hart', 'publisher' => null, 'year' => 2016, 'stock' => 1],
            ['code' => '2',  'title' => 'The 5 Levels Of Leadership', 'author' => 'John C. Maxwell', 'publisher' => null, 'year' => 2013, 'stock' => 1],
            ['code' => '3',  'title' => 'Ruaya', 'author' => 'Sinta Lya', 'publisher' => null, 'year' => 2014, 'stock' => 1],
            ['code' => '4',  'title' => '24 Hari Jago Jualan', 'author' => 'Dewa Eka Prayoga', 'publisher' => null, 'year' => 2015, 'stock' => 1],
            ['code' => '5',  'title' => '5 Pilar Kepemimpinan Di Abad 21', 'author' => 'Dr. Ir. Agustinus Johannes Siahaya', 'publisher' => null, 'year' => 2016, 'stock' => 1],
            ['code' => '6',  'title' => 'The Principles Of Power - Bahasa Indonesia', 'author' => 'Efon Pujidanto', 'publisher' => null, 'year' => null, 'stock' => 1],
            ['code' => '7',  'title' => 'Ensiklopedia Fiqih Praktek Kitab Thaharah Dan Shalat Jilid 4', 'author' => 'Yusuf bin Hasan bin Ahmad', 'publisher' => null, 'year' => 2016, 'stock' => 1],
            ['code' => '8',  'title' => 'Ensiklopedia Fiqih Praktek Kitab Thaharah Dan Shalat Jilid 5', 'author' => 'Yusuf bin Hasan bin Ahmad', 'publisher' => null, 'year' => 2016, 'stock' => 1],
            ['code' => '9',  'title' => 'Ensiklopedia Fiqih Praktek Kitab Thaharah Dan Shalat Jilid 6', 'author' => 'Yusuf bin Hasan bin Ahmad', 'publisher' => null, 'year' => 2016, 'stock' => 1],
            ['code' => '10', 'title' => 'Ensiklopedia Fiqih Praktek Kitab Thaharah Dan Shalat Jilid 3', 'author' => 'Yusuf bin Hasan bin Ahmad', 'publisher' => null, 'year' => 2016, 'stock' => 1],
            ['code' => '11', 'title' => 'Ensiklopedia Fiqih Praktek Kitab Thaharah Dan Shalat Jilid 2', 'author' => 'Yusuf bin Hasan bin Ahmad', 'publisher' => null, 'year' => 2016, 'stock' => 1],
            ['code' => '12', 'title' => 'Ensiklopedia Fiqih Praktek Kitab Thaharah Dan Shalat Jilid 1', 'author' => 'Yusuf bin Hasan bin Ahmad', 'publisher' => null, 'year' => 2016, 'stock' => 1],
            ['code' => '13', 'title' => 'Seri Khulafaur Rasyidin Ali Bin Thalib - Komik 4', 'author' => 'Riso Kancil Studio', 'publisher' => 'Riso Kancil Studio', 'year' => 2016, 'stock' => 1],
            ['code' => '14', 'title' => 'Seri Khulafaur Rasyidin Utsman Bin Affan - Komik 3', 'author' => 'Riso Kancil Studio', 'publisher' => 'Riso Kancil Studio', 'year' => 2016, 'stock' => 1],
            ['code' => '15', 'title' => 'Seri Khulafaur Rasyidin Umar Bin Khattab - Komik 2', 'author' => 'Riso Kancil Studio', 'publisher' => 'Riso Kancil Studio', 'year' => 2016, 'stock' => 1],
            ['code' => '16', 'title' => 'Seri Khulafaur Rasyidin Abu Bakar - Komik 1', 'author' => 'Riso Kancil Studio', 'publisher' => 'Riso Kancil Studio', 'year' => 2016, 'stock' => 1],
            ['code' => '17', 'title' => 'The Battle Of Rasulullah Perang Badar - Komik', 'author' => 'Riso Kancil Studio', 'publisher' => 'Riso Kancil Studio', 'year' => 2016, 'stock' => 1],
            ['code' => '18', 'title' => 'Mutiara Doa Pilihan', 'author' => 'Mashuri', 'publisher' => null, 'year' => 2013, 'stock' => 1],
            ['code' => '19', 'title' => 'Buku Induk Doa Dan Dzikir Amalan Para Nabi', 'author' => 'Ahmad Syaiful', 'publisher' => null, 'year' => null, 'stock' => 1],
            ['code' => '20', 'title' => 'Doa Doa Rasulullah', 'author' => 'Muslim', 'publisher' => null, 'year' => null, 'stock' => 1],
            ['code' => '21', 'title' => 'Agar Taqwa Di Barokahi', 'author' => 'Ahmad Yani', 'publisher' => null, 'year' => null, 'stock' => 1],
            ['code' => '22', 'title' => 'Wawasan Al-Quran Tentang Doa Dan Zikir', 'author' => 'M. Quraish Shihab', 'publisher' => null, 'year' => null, 'stock' => 1],
            ['code' => '23', 'title' => 'Pengajaran Shalat', 'author' => 'Ahmad Nawawi', 'publisher' => null, 'year' => 2017, 'stock' => 1],
            ['code' => '24', 'title' => 'Tata Cara Shalat Praktis', 'author' => 'Muhammad Nashiruddin Al-Albani', 'publisher' => null, 'year' => 2016, 'stock' => 1],
            ['code' => '25', 'title' => 'Di Balik Kesulitan Ada Kemudahan', 'author' => 'Syekh Abdur Qadir Al Jaelani', 'publisher' => null, 'year' => null, 'stock' => 1],
            ['code' => '26', 'title' => 'Istighfar Kini Sebelum Ajal Menjemput', 'author' => 'Muhammad Nurul Ihsan', 'publisher' => null, 'year' => null, 'stock' => 1],
            ['code' => '27', 'title' => 'Pelajaran Ilmu Tajwid', 'author' => 'Ahmad Shams Madyan', 'publisher' => null, 'year' => null, 'stock' => 1],
            ['code' => '28', 'title' => 'Tathohirun Nafs', 'author' => 'Ibnu Rajab Al Jauzi', 'publisher' => null, 'year' => 2019, 'stock' => 1],
            ['code' => '29', 'title' => 'Umar Ibnoman', 'author' => 'Muhammad Ali', 'publisher' => null, 'year' => 2023, 'stock' => 1],
            ['code' => '30', 'title' => 'Dahsyatnya Shalat Subuh Dhuha Dan Istiqharah', 'author' => 'M. Suhadi', 'publisher' => null, 'year' => null, 'stock' => 1],
            ['code' => '31', 'title' => 'Menghadapi Ujian Dan Cobaan Hidup', 'author' => 'Umar Muhammad Jina Madzhab', 'publisher' => null, 'year' => 2023, 'stock' => 1],
            ['code' => '32', 'title' => 'Urjuzah Habashiyah', 'author' => 'Muhammad Ali Haj Yushin', 'publisher' => null, 'year' => 2023, 'stock' => 1],
            ['code' => '33', 'title' => 'Hadist Arba\'in', 'author' => 'Imam An-Nawawi', 'publisher' => null, 'year' => 2013, 'stock' => 1],
            ['code' => '34', 'title' => 'Kamus 2 Bahasa Arab Inggris Indonesia', 'author' => 'Ahmad Muslim', 'publisher' => null, 'year' => 2017, 'stock' => 1],
            ['code' => '35', 'title' => 'Kamus Arab Indonesia', 'author' => 'Mahmud Yunus', 'publisher' => null, 'year' => null, 'stock' => 1],
            ['code' => '36', 'title' => 'Si Ojanang Di Jaman Maeon Tujino', 'author' => 'Andri Tirta', 'publisher' => null, 'year' => null, 'stock' => 1],
            ['code' => '37', 'title' => 'Gelas-Gelas Kristal', 'author' => 'Dhiny Budi Uswah', 'publisher' => null, 'year' => 2016, 'stock' => 1],
            ['code' => '38', 'title' => 'Kematian Adalah Nikmat', 'author' => 'Al-Qasim Shaqar', 'publisher' => null, 'year' => null, 'stock' => 1],
            ['code' => '39', 'title' => 'Bagi Waris Negara Harus Tegas', 'author' => 'Muhammad Ali Arit Sakura', 'publisher' => null, 'year' => null, 'stock' => 1],
            ['code' => '40', 'title' => 'Biografi Syarah Imam Malik', 'author' => 'Abdul Roy dan Yaslar', 'publisher' => null, 'year' => 2014, 'stock' => 1],
            ['code' => '41', 'title' => 'Halal Haram Dalam Islam', 'author' => 'M. Syarubbni bin Syarif', 'publisher' => null, 'year' => 2018, 'stock' => 1],
            ['code' => '42', 'title' => 'Rida', 'author' => 'Ibnu Daqirin Al Husaini', 'publisher' => null, 'year' => 2024, 'stock' => 1],
            ['code' => '43', 'title' => 'Fiqih Darurat', 'author' => 'Muhammad Said Mahlub', 'publisher' => null, 'year' => null, 'stock' => 1],
            ['code' => '44', 'title' => 'Fiqih Islam', 'author' => 'Sulaiman bin Ahmad', 'publisher' => null, 'year' => null, 'stock' => 1],
            ['code' => '45', 'title' => 'Fiqih Prioritas', 'author' => 'Yusuf Al-Qaradawi', 'publisher' => null, 'year' => null, 'stock' => 1],
            ['code' => '46', 'title' => 'Fiqih Tamkin', 'author' => 'Fathul Amin', 'publisher' => null, 'year' => 2016, 'stock' => 1],
            ['code' => '47', 'title' => 'Fiqih Ibadah Harian', 'author' => 'Hammam Ibnu Shawa', 'publisher' => null, 'year' => null, 'stock' => 1],
            ['code' => '48', 'title' => 'Fiqih Ikhtishar Majelis', 'author' => 'Ahmad bin Hajar Al-Asqalani', 'publisher' => null, 'year' => 2016, 'stock' => 1],
            ['code' => '49', 'title' => 'Fiqih Wanita', 'author' => 'Syikh Ibrahim Muhammad Al-Jamal', 'publisher' => null, 'year' => 2016, 'stock' => 1],
            ['code' => '50', 'title' => 'Fiqih Jinazah', 'author' => 'Thofiq Muhammad Abd Muhaimin Al-Jubba', 'publisher' => null, 'year' => null, 'stock' => 1],
            ['code' => '51', 'title' => 'Fiqih Akhlak', 'author' => 'Al Umar Mustofa', 'publisher' => null, 'year' => 2005, 'stock' => 1],
            ['code' => '52', 'title' => 'Fiqih Muhadharat', 'author' => 'Abdul Hamid Al-Balali', 'publisher' => null, 'year' => 2021, 'stock' => 1],
            ['code' => '53', 'title' => 'Fiqih Zakat', 'author' => 'Suef bin Mahi Hajmauto', 'publisher' => null, 'year' => 2018, 'stock' => 1],
        ];

        foreach ($books as $bookData) {
            Book::firstOrCreate(
                ['code' => $bookData['code']],
                array_merge($bookData, ['category_id' => $category->id])
            );
        }
    }
}
