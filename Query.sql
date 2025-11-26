CREATE TABLE admin (
  admin_id INT NOT NULL AUTO_INCREMENT,
  username VARCHAR(30) NOT NULL,
  password VARCHAR(255) NOT NULL, 
  nama_lengkap VARCHAR(60) NOT NULL,
  PRIMARY KEY (admin_id),
  UNIQUE INDEX username_UNIQUE (username ASC)
);

CREATE TABLE penyelenggara (
  penyelenggara_id INT NOT NULL AUTO_INCREMENT,
  username VARCHAR(30) NOT NULL,
  password VARCHAR(255) NOT NULL,
  nama_lengkap VARCHAR(60) NOT NULL,
  PRIMARY KEY (penyelenggara_id),
  UNIQUE INDEX username_UNIQUE (username ASC)
);

CREATE TABLE jenis_acara (
  template_id INT NOT NULL AUTO_INCREMENT,
  nama_template VARCHAR(50) NOT NULL,
  deskripsi TEXT NULL,
  PRIMARY KEY (template_id)
);

CREATE TABLE acara (
  acara_id INT NOT NULL AUTO_INCREMENT,
  penyelenggara_id INT NOT NULL,
  template_id INT NULL, 
  nama_acara VARCHAR(100) NOT NULL,
  tanggal_acara DATE NULL,
  slug_unik VARCHAR(50) NOT NULL,
  PRIMARY KEY (acara_id),
  UNIQUE INDEX slug_unik_UNIQUE (slug_unik ASC),
  INDEX fk_acara_penyelenggara_idx (penyelenggara_id ASC),
  INDEX fk_acara_jenis_acara_idx (template_id ASC),
  CONSTRAINT fk_acara_penyelenggara
    FOREIGN KEY (penyelenggara_id)
    REFERENCES penyelenggara (penyelenggara_id)
    ON DELETE CASCADE 
    ON UPDATE NO ACTION,
  CONSTRAINT fk_acara_jenis_acara
    FOREIGN KEY (template_id)
    REFERENCES jenis_acara (template_id)
    ON DELETE SET NULL 
    ON UPDATE NO ACTION
);

CREATE TABLE tamu (
  tamu_id INT NOT NULL AUTO_INCREMENT,
  acara_id INT NOT NULL,
  nama_tamu VARCHAR(100) NOT NULL,
  alamat_atau_instansi VARCHAR(255) NULL,
  ucapan_atau_pesan TEXT NULL,
  waktu_kehadiran DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (tamu_id),
  INDEX fk_tamu_acara_idx (acara_id ASC),
  CONSTRAINT fk_tamu_acara
    FOREIGN KEY (acara_id)
    REFERENCES acara (acara_id)
    ON DELETE CASCADE 
    ON UPDATE NO ACTION
);

use digitamu;
alter table jenis_acara drop column deskripsi;

select nama_template from jenis_acara;

insert into jenis_acara (template_id, nama_template) VALUES (1, "Pernikahan");
insert into jenis_acara (template_id, nama_template) VALUES (2, "Organisasi/Rapat/Seminar"), (3, "Ulang Tahun/Syukuran"), (4, "Seminar(Proposal, Hasil) / Wisuda"), (5, "Reuni"), (6, "Kustom");

select * from jenis_acara;