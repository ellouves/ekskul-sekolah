-- =====================================================
-- DATABASE: Sistem Penilaian Ekstrakurikuler Sekolah
-- =====================================================
DROP DATABASE IF EXISTS db_ekskul_sekolah;

CREATE DATABASE db_ekskul_sekolah
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE db_ekskul_sekolah;

-- Tabel 1: ekskul
CREATE TABLE ekskul (
    id_ekskul     INT          NOT NULL AUTO_INCREMENT,
    nama_ekskul   VARCHAR(50)  NOT NULL,
    pembina       VARCHAR(100) NOT NULL,
    hari_kegiatan VARCHAR(20)  NOT NULL,
    PRIMARY KEY (id_ekskul)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel 2: siswa
CREATE TABLE siswa (
    nis           VARCHAR(20)  NOT NULL,
    nama_siswa    VARCHAR(100) NOT NULL,
    kelas         VARCHAR(20)  NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    PRIMARY KEY (nis)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabel 3: keanggotaan_nilai
CREATE TABLE keanggotaan_nilai (
    id_nilai          INT           NOT NULL AUTO_INCREMENT,
    nis               VARCHAR(20)   NOT NULL,
    id_ekskul         INT           NOT NULL,
    tingkat_keaktifan ENUM('Sangat Aktif', 'Aktif', 'Cukup', 'Kurang') NOT NULL,
    nilai_angka       DECIMAL(5,2)  NOT NULL,
    keterangan        TEXT,
    tanggal_penilaian TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_nilai),

    INDEX idx_nis (nis),
    INDEX idx_id_ekskul (id_ekskul),

    -- Aktifkan jika satu siswa hanya boleh punya satu nilai per ekskul:
    -- UNIQUE KEY uq_siswa_ekskul (nis, id_ekskul),

    -- Ditegakkan mulai MySQL 8.0.16
    CONSTRAINT chk_nilai_angka CHECK (nilai_angka BETWEEN 0.00 AND 100.00),

    CONSTRAINT fk_nilai_siswa
        FOREIGN KEY (nis) REFERENCES siswa (nis)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,

    CONSTRAINT fk_nilai_ekskul
        FOREIGN KEY (id_ekskul) REFERENCES ekskul (id_ekskul)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- DATA CONTOH
-- =====================================================

INSERT INTO ekskul (nama_ekskul, pembina, hari_kegiatan) VALUES
('Pramuka',     'Bpk. Ahmad Fauzi, S.Pd',    'Jumat'),
('Paskibra',    'Bpk. Dedi Kurniawan, S.Pd', 'Sabtu'),
('Rohis',       'Ibu Siti Aminah, S.Ag',     'Jumat'),
('Coding Club', 'Ibu Rina Marlina, S.Kom',   'Rabu');

INSERT INTO siswa (nis, nama_siswa, kelas, jenis_kelamin) VALUES
('2024001', 'Andi Pratama',   'X RPL',   'L'),
('2024002', 'Bunga Lestari',  'X RPL',   'P'),
('2024003', 'Candra Wijaya',  'XI RPL',  'L'),
('2024004', 'Dewi Anggraini', 'XI RPL',  'P'),
('2024005', 'Eko Prasetyo',   'XII RPL', 'L');

INSERT INTO keanggotaan_nilai
    (nis, id_ekskul, tingkat_keaktifan, nilai_angka, keterangan)
VALUES
('2024001', 4, 'Sangat Aktif', 95.50, 'Juara 1 lomba web design tingkat kota'),
('2024001', 1, 'Aktif',        85.00, 'Hadir rutin setiap pertemuan'),
('2024002', 3, 'Aktif',        88.25, 'Aktif sebagai panitia kegiatan Maulid'),
('2024002', 4, 'Cukup',        75.00, 'Kehadiran kurang konsisten'),
('2024003', 2, 'Sangat Aktif', 92.00, 'Komandan upacara HUT RI'),
('2024004', 1, 'Kurang',       60.50, 'Sering tidak hadir tanpa keterangan'),
('2024005', 4, 'Sangat Aktif', 97.00, 'Mentor sesama anggota, finalis LKS');
