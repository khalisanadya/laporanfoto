@extends('layouts.app')

@section('title', 'BAP - Report System')
@section('header', ' Berita Acara Pemeriksaan')

@section('styles')
  .form-section{
    margin-bottom: 28px;
  }

  .form-section-title{
    font-size: 15px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 16px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--primary-bg);
  }

  .form-row{
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-bottom: 16px;
  }

  @media (max-width: 700px){
    .form-row{
      grid-template-columns: 1fr;
    }
  }

  .form-group{
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .form-group.full{
    grid-column: 1 / -1;
  }

  .form-label{
    font-size: 13px;
    font-weight: 600;
    color: var(--text);
  }

  .form-label span{
    color: var(--danger);
  }

  .form-input{
    padding: 12px 14px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-size: 14px;
    font-family: inherit;
    background: #fff;
    transition: all .2s ease;
  }

  .form-input:focus{
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-bg);
  }

  .form-hint{
    font-size: 12px;
    color: var(--muted);
  }

  .form-actions{
    display: flex;
    gap: 12px;
    padding-top: 20px;
    border-top: 1px solid var(--border);
    margin-top: 28px;
  }

  .info-box{
    background: var(--primary-bg);
    border: 1px solid var(--primary-border);
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 24px;
  }

  .info-box-title{
    font-size: 13px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 8px;
  }

  .info-box-content{
    font-size: 13px;
    color: var(--text);
    line-height: 1.6;
  }

  .info-item{
    display: flex;
    gap: 8px;
    margin-bottom: 4px;
  }

  .info-item strong{
    min-width: 140px;
    color: var(--muted);
    font-weight: 500;
  }
@endsection

@section('content')

<form action="{{ route('bap.store') }}" method="POST">
  @csrf

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Form Berita Acara Pemeriksaan</h3>
    </div>

    <!-- Info Box - Data yang sudah fix -->
    <div class="info-box">
      <div class="info-box-title">Informasi Tetap (Tidak Perlu Diisi)</div>
      <div class="info-box-content">
        <div class="info-item">
          <strong>Nama Pekerjaan</strong>
          <span>Jasa Instalasi dan Managed Service Access Point (AP) PGNMAS site GS8 (Jakarta) dan Kebonwaru (Bandung)</span>
        </div>
        <div class="info-item">
          <strong>Pihak Pertama</strong>
          <span>Angga Galih Perdana - Dept Head Network Operation & Reliability, PT Telemedia Dinamika Sarana</span>
        </div>
        <div class="info-item">
          <strong>Pihak Kedua</strong>
          <span>Nini Jaya - Direktur, PT Telemedia Mitra Elektrotama</span>
        </div>
        <div class="info-item">
          <strong>Nomor SPK</strong>
          <span>152600.SPK/LG.01.03/UT/2025 (01 Oktober 2025)</span>
        </div>
        <div class="info-item">
          <strong>Nominal</strong>
          <span>Rp. 8.900.000,- (Delapan Juta Sembilan Ratus Ribu Rupiah)</span>
        </div>
      </div>
    </div>

    <!-- Form Fields -->
    <div class="form-section">
      <div class="form-section-title">Data BAP</div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Tanggal BAP <span>*</span></label>
          <input type="date" name="tanggal_bap" class="form-input" value="{{ old('tanggal_bap', date('Y-m-d')) }}" required>
          <span class="form-hint">Tanggal pembuatan Berita Acara</span>
        </div>

        <div class="form-group">
          <label class="form-label">Nomor BAP <span>*</span></label>
          <input type="text" name="nomor_bap" class="form-input" value="{{ old('nomor_bap') }}" placeholder="Contoh: 000100.BA/OP.01.00/NOR/2026" required>
          <span class="form-hint">Nomor surat BAP</span>
        </div>
      </div>
    </div>

    <div class="form-section">
      <div class="form-section-title">Surat Permohonan Pemeriksaan</div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Nomor Surat Permohonan <span>*</span></label>
          <input type="text" name="nomor_surat_permohonan" class="form-input" value="{{ old('nomor_surat_permohonan') }}" placeholder="Contoh: 1017/PAB/SK/XI/23" required>
          <span class="form-hint">Nomor surat dari PT Telemedia Mitra Elektrotama</span>
        </div>

        <div class="form-group">
          <label class="form-label">Tanggal Surat Permohonan <span>*</span></label>
          <input type="date" name="tanggal_surat_permohonan" class="form-input" value="{{ old('tanggal_surat_permohonan') }}" required>
          <span class="form-hint">Tanggal surat permohonan pemeriksaan</span>
        </div>
      </div>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">
        Simpan & Preview
      </button>
      <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
    </div>
  </div>
</form>

@endsection
