@extends('layouts.app')

@section('title', 'Preview BAP - Report System')
@section('header', 'Preview BAP')

@section('styles')
  .preview-card{
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 32px;
    max-width: 800px;
    margin: 0 auto;
    box-shadow: var(--shadow-md);
  }

  .preview-header{
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
    padding-bottom: 20px;
    border-bottom: 2px solid var(--primary);
  }

  .preview-logo{
    height: 50px;
  }

  .preview-title{
    text-align: center;
    margin-bottom: 24px;
  }

  .preview-title h1{
    font-size: 16px;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 4px;
  }

  .preview-title h2{
    font-size: 14px;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 16px;
  }

  .preview-nomor{
    font-size: 13px;
    font-weight: 600;
    color: var(--primary);
  }

  .preview-content{
    font-size: 13px;
    line-height: 1.8;
    color: var(--text);
    text-align: justify;
  }

  .preview-content p{
    margin-bottom: 16px;
  }

  .preview-table{
    margin: 16px 0;
  }

  .preview-table td{
    padding: 4px 8px 4px 0;
    vertical-align: top;
    font-size: 13px;
    border: none;
  }

  .preview-table td:first-child{
    width: 120px;
    color: var(--text);
  }

  .preview-list{
    margin: 16px 0;
    padding-left: 0;
    list-style: none;
  }

  .preview-list li{
    margin-bottom: 12px;
    padding-left: 24px;
    position: relative;
  }

  .preview-list li::before{
    content: attr(data-num) ".";
    position: absolute;
    left: 0;
    font-weight: 600;
  }

  .preview-signature{
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-top: 48px;
    text-align: center;
  }

  .signature-box{
    font-size: 13px;
  }

  .signature-title{
    font-weight: 600;
    margin-bottom: 4px;
  }

  .signature-role{
    color: var(--muted);
    margin-bottom: 60px;
  }

  .signature-name{
    font-weight: 700;
    border-top: 1px solid var(--text);
    padding-top: 8px;
    display: inline-block;
    min-width: 150px;
  }

  .action-bar{
    display: flex;
    gap: 12px;
    justify-content: center;
    margin-bottom: 24px;
  }
@endsection

@section('content')

<div class="action-bar">
  <a href="{{ route('bap.word', $bap) }}" class="btn btn-primary">
    Download Word
  </a>
  <a href="{{ route('bap.index') }}" class="btn btn-secondary">
    Kembali
  </a>
</div>

<div class="preview-card">
  <div class="preview-header">
    <img src="{{ asset('images/logo-gasnet.png') }}" alt="Gasnet Logo" class="preview-logo" onerror="this.style.display='none'">
  </div>

  <div class="preview-title">
    <h1>BERITA ACARA PEMERIKSAAN</h1>
    <h2>JASA INSTALASI DAN MANAGED SERVICE ACCESS POINT (AP)<br>PGNMAS SITE GS8 (JAKARTA) DAN KEBONWARU (BANDUNG)</h2>
    <div class="preview-nomor">Nomor : {{ $bap->nomor_bap }}</div>
  </div>

  <div class="preview-content">
    @php
      $tanggalBap = $bap->tanggal_bap;
      $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][$tanggalBap->dayOfWeek];
      $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$tanggalBap->month];
    @endphp

    <p>
      Pada hari ini, <strong>{{ $hari }}</strong> tanggal 
      <strong>{{ $tanggalBap->day }}</strong> bulan 
      <strong>{{ $bulan }}</strong> tahun 
      <strong>{{ $tanggalBap->year }}</strong>
      ({{ $tanggalBap->format('d-m-Y') }}), telah dilaksanakan pemeriksaan terhadap pekerjaan 
      <strong>Jasa Instalasi dan Managed Service Access Point (AP) PGNMAS site GS8 (Jakarta) dan Kebonwaru (Bandung)</strong> oleh:
    </p>

    <table class="preview-table">
      <tr><td>Nama</td><td>: Angga Galih Perdana</td></tr>
      <tr><td>Jabatan</td><td>: Department Head Network Operation & Reliability</td></tr>
      <tr><td>Perusahaan</td><td>: PT Telemedia Dinamika Sarana</td></tr>
    </table>

    <p>Selanjutnya disebut sebagai "<strong>Pihak Pertama</strong>", dan</p>

    <table class="preview-table">
      <tr><td>Nama</td><td>: Nini Jaya</td></tr>
      <tr><td>Jabatan</td><td>: Direktur</td></tr>
      <tr><td>Perusahaan</td><td>: PT Telemedia Mitra Elektrotama</td></tr>
    </table>

    <p>Selanjutnya disebut sebagai "<strong>Pihak Kedua</strong>".</p>

    <p>Berita Acara ini dibuat berdasarkan:</p>

    @php
      $tglSurat = $bap->tanggal_surat_permohonan;
      $bulanSurat = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$tglSurat->month];
    @endphp

    <ol class="preview-list">
      <li data-num="1">
        <em><strong>Surat Perintah Kerja</strong></em> yang dikeluarkan PT Telemedia Dinamika Sarana Nomor : 
        <strong>152600.SPK/LG.01.03/UT/2025</strong> tanggal <strong>01 Oktober 2025</strong> untuk 
        <strong>Jasa Instalasi dan Managed Service Access Point (AP) PGNMAS site GS8 (Jakarta) dan Kebonwaru (Bandung)</strong> ("Kontrak");
      </li>
      <li data-num="2">
        Surat dari PT.Telemedia Mitra Elektrotama Nomor: 
        <strong>{{ $bap->nomor_surat_permohonan }}</strong> tanggal 
        <strong>{{ $tglSurat->day }} {{ $bulanSurat }} {{ $tglSurat->year }}</strong>
        perihal Surat Permohonan Pemeriksaan Pekerjaan;
      </li>
      <li data-num="3">
        <em><strong>Laporan Pekerjaan</strong></em> Jasa Instalasi dan Managed Service Access Point (AP) PGNMAS site GS8 (Jakarta) dan Kebonwaru (Bandung) Periode Desember 2025 dari pihak Kedua;
      </li>
    </ol>

    <p>
      Dan berdasarkan hasil pemeriksaan maka Pihak Pertama dan Pihak Kedua menyimpulkan/menyetujui hal-hal sebagai berikut:
    </p>

    <ol class="preview-list">
      <li data-num="1">
        Penyedia Jasa telah menyelesaikan pekerjaan <strong>Jasa Instalasi dan Managed Service Access Point (AP) PGNMAS site GS8 (Jakarta) dan Kebonwaru (Bandung)</strong> periode Desember 2025 sesuai dengan syarat-syarat yang ditentukan dalam Surat Perintah Kerja.
      </li>
      <li data-num="2">
        Penyedia Jasa berhak menerima pembayaran periode Desember 2025 yaitu sebesar: 
        <strong>Rp. 8.900.000,- (Delapan Juta Sembilan Ratus Ribu Rupiah)</strong>, belum termasuk PPN dan pajak – pajak yang berlaku sesuai ketentuan.
      </li>
    </ol>

    <p>
      Demikian Berita Acara ini dibuat rangkap 2 (dua) dan ditandatangani untuk dapat diketahui serta dipergunakan sebagaimana mestinya.
    </p>

    <div class="preview-signature">
      <div class="signature-box">
        <div class="signature-title">PT Telemedia Mitra Elektrotama</div>
        <div class="signature-role">Direktur</div>
        <div class="signature-name">Nini Jaya</div>
      </div>
      <div class="signature-box">
        <div class="signature-title">PT Telemedia Dinamika Sarana</div>
        <div class="signature-role">Department Head Network Operation & Reliability</div>
        <div class="signature-name">Angga Galih Perdana</div>
      </div>
    </div>
  </div>
</div>

@endsection
