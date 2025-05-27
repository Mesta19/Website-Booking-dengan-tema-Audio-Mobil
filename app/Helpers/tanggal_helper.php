<?php // app/Helpers/tanggal_helper.php

if (!function_exists('format_indo_datetime')) {
    /**
     * Mengubah format datetime ke format Indonesia yang mudah dibaca.
     * ... (deskripsi fungsi) ...
     */
    function format_indo_datetime(string $datetimeString, ?string $pattern = 'eeee, d MMMM yyyy, HH:mm', string $timezone = 'Asia/Jakarta', string $locale = 'id_ID'): string
    {
        if (empty($datetimeString) || $datetimeString === '0000-00-00 00:00:00' || $datetimeString === null) {
            return '-'; // Ini mengembalikan string, jadi aman
        }

        try {
            // Pastikan ekstensi intl dimuat
            if (!extension_loaded('intl')) {
                log_message('warning', 'Ekstensi PHP intl tidak dimuat. Menggunakan fallback format tanggal sederhana.');
                $date = new DateTime($datetimeString, new DateTimeZone('UTC')); 
                $date->setTimezone(new DateTimeZone($timezone));
                return $date->format('d M Y, H:i'); // Aman, mengembalikan string
            }

            // Buat timestamp dari string datetime
            $utcDate = new DateTime($datetimeString, new DateTimeZone('UTC')); 
            $timestamp = $utcDate->getTimestamp();

            $formatter = new IntlDateFormatter(
                $locale,
                IntlDateFormatter::FULL, 
                IntlDateFormatter::FULL, 
                $timezone,
                IntlDateFormatter::GREGORIAN,
                $pattern 
            );

            if (!$formatter) {
                log_message('error', 'Gagal membuat IntlDateFormatter. Locale: ' . $locale . ', Pattern: ' . $pattern);
                $date = new DateTime($datetimeString, new DateTimeZone('UTC'));
                $date->setTimezone(new DateTimeZone($timezone));
                return $date->format('d M Y, H:i'); // Aman, mengembalikan string
            }
            
            $formattedDate = $formatter->format($timestamp);
            
            // Cek jika hasil format adalah false (bisa terjadi jika ada error internal di intl)
            if ($formattedDate === false) {
                log_message('error', 'IntlDateFormatter::format() mengembalikan false. Error code: ' . $formatter->getErrorCode() . ', Error message: ' . $formatter->getErrorMessage());
                // Fallback jika format() gagal
                $dateObj = new DateTime();
                $dateObj->setTimestamp($timestamp);
                $dateObj->setTimezone(new DateTimeZone($timezone));
                return $dateObj->format('d M Y, H:i'); // Aman, mengembalikan string
            }
            
            // Penanganan tambahan untuk nama hari jika pattern 'eeee' menghasilkan angka
            // Ini bagian yang mungkin perlu return eksplisit jika kondisi tidak terpenuhi
            // Kita akan pastikan $formattedDate selalu string.
            // Jika $formattedDate adalah string kosong atau null dari $formatter->format(), kita berikan fallback
            if (empty($formattedDate) && $formatter->getErrorCode() != 0) {
                 log_message('warning', 'IntlDateFormatter::format() menghasilkan string kosong. Error: ' . $formatter->getErrorMessage());
                 $dateObj = new DateTime();
                 $dateObj->setTimestamp($timestamp);
                 $dateObj->setTimezone(new DateTimeZone($timezone));
                 return $dateObj->format('d M Y, H:i');
            }


            // Jika pattern meminta nama hari 'eeee' dan hasilnya adalah angka hari
            if (strpos((string)$pattern, 'eeee') !== false && is_numeric(explode(',', $formattedDate)[0])) {
                 $dateObj = new DateTime();
                 $dateObj->setTimestamp($timestamp);
                 $dateObj->setTimezone(new DateTimeZone($timezone));
                 $dayOfWeek = (int)$dateObj->format('N'); // 1 (Senin) - 7 (Minggu)
                 $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                 
                 // Pastikan $dayOfWeek-1 adalah indeks yang valid
                 if (isset($days[$dayOfWeek-1])) {
                    $namaHari = $days[$dayOfWeek-1];
                    // Hati-hati saat menggabungkan, pastikan bagian lain dari $formattedDate ada
                    $bagianSisa = substr($formattedDate, strpos($formattedDate, ','));
                    if ($bagianSisa !== false) {
                        return $namaHari . $bagianSisa; // Aman, mengembalikan string
                    } else {
                        // Jika tidak ada koma, mungkin formatnya hanya hari
                        return $namaHari; // Aman
                    }
                 } else {
                    // Jika indeks hari tidak valid (seharusnya tidak terjadi dengan format 'N')
                    // kembalikan $formattedDate yang asli (yang mungkin berupa angka hari)
                    // atau fallback ke format sederhana
                    log_message('warning', 'Indeks hari tidak valid saat mencoba mengganti nama hari manual.');
                    return $formattedDate; // Ini sudah string
                 }
            }
            
            // Jika semua berjalan lancar dan $formattedDate adalah string yang valid
            return (string)$formattedDate; // Pastikan selalu mengembalikan string

        } catch (Exception $e) {
            log_message('error', 'Exception di format_indo_datetime: ' . $e->getMessage() . ' untuk input: ' . $datetimeString);
            // Fallback jika ada exception lain
            // Coba format sederhana dengan DateTime PHP standar jika Intl gagal total
            try {
                $fallbackDate = new DateTime($datetimeString);
                return $fallbackDate->format('d M Y, H:i'); // Aman, mengembalikan string
            } catch (Exception $ex) {
                // Jika DateTime juga gagal (input string benar-benar rusak)
                return $datetimeString; // Kembalikan input asli sebagai string
            }
        }
        // Tambahkan return statement di akhir sebagai jaring pengaman terakhir,
        // meskipun idealnya semua cabang try-catch sudah mengembalikan string.
        // Namun, karena kita sudah return di semua cabang, ini mungkin tidak akan pernah tercapai.
        // Jika Anda ingin lebih aman, bisa return string default di sini.
        // return '- fallback akhir -'; 
    }
}

// Fungsi format_indo_date tetap sama
if (!function_exists('format_indo_date')) {
    function format_indo_date(string $dateString, ?string $pattern = 'd MMMM yyyy', string $timezone = 'Asia/Jakarta', string $locale = 'id_ID'): string
    {
        return format_indo_datetime($dateString . ' 00:00:00', $pattern, $timezone, $locale);
    }
}