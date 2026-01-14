@extends('layouts.app')

@section('title', 'Preview Report - Report System')
@section('header', 'Preview Report Kegiatan')

@section('styles')
  .preview-card{
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 32px;
    max-width: 900px;
    margin: 0 auto;
    box-shadow: var(--shadow-md);
  }

  .preview-header{
    background: linear-gradient(135deg, var(--primary), #0284c7);
    color: #fff;
    padding: 24px 28px;
    margin: -32px -32px 28px -32px;
    border-radius: 14px 14px 0 0;
    text-align: center;
  }

  .preview-header h1{
    font-size: 22px;
    font-weight: 800;
    margin-bottom: 4px;
    letter-spacing: 1px;
  }

  .preview-header p{
    font-size: 12px;
    opacity: 0.9;
  }

  .info-section{
    margin-bottom: 28px;
  }

  .info-title{
    font-size: 14px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 14px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--primary);
  }

  .info-table{
    width: 100%;
    border-collapse: collapse;
  }

  .info-table td{
    padding: 12px 14px;
    border: 1px solid var(--border);
    font-size: 13px;
  }

  .info-table td:first-child{
    width: 30%;
    font-weight: 600;
    background: #e0f2fe;
    color: #0c4a6e;
  }

  .info-table td:last-child{
    background: #fff;
  }

  .checklist-table{
    width: 100%;
    border-collapse: collapse;
    margin-top: 12px;
  }

  .checklist-table th,
  .checklist-table td{
    border: 1px solid var(--border);
    padding: 12px 10px;
    font-size: 13px;
    text-align: left;
  }

  .checklist-table th{
    background: var(--primary);
    color: #fff;
    font-weight: 600;
    text-align: center;
  }

  .checklist-table td{
    vertical-align: middle;
    background: #fff;
  }

  .checklist-table tr:nth-child(even) td{
    background: #f8fafc;
  }

  .checklist-table .no{
    width: 50px;
    text-align: center;
    font-weight: 700;
    color: var(--primary);
  }

  .checklist-table .cond{
    width: 70px;
    text-align: center;
  }

  .cond-baik{
    color: var(--success);
    font-weight: 700;
    font-size: 18px;
  }

  .cond-problem{
    color: var(--danger);
    font-weight: 700;
    font-size: 18px;
  }

  .photo-section{
    margin-top: 28px;
  }

  .photo-grid{
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-top: 14px;
  }

  @media (max-width: 600px){
    .photo-grid{
      grid-template-columns: 1fr;
    }
  }

  .photo-item{
    background: #f8fafc;
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 12px;
    text-align: center;
  }

  .photo-item img{
    max-width: 100%;
    max-height: 200px;
    border-radius: 8px;
    object-fit: contain;
  }

  .photo-caption{
    margin-top: 10px;
    font-size: 12px;
    font-weight: 600;
    color: var(--text-light);
    padding: 8px;
    background: #e2e8f0;
    border-radius: 6px;
  }

  .action-bar{
    display: flex;
    gap: 12px;
    justify-content: center;
    align-items: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
  }

  .action-bar .btn{
    min-width: 140px;
    text-align: center;
  }

  .footer-info{
    margin-top: 28px;
    padding-top: 16px;
    border-top: 1px solid var(--border);
    font-size: 11px;
    color: var(--text-light);
    text-align: right;
  }
@endsection

@section('content')

<div class="action-bar">
  <a href="{{ route('reports.pdf', $report) }}" class="btn btn-primary">
    Download PDF
  </a>
  <a href="{{ route('reports.word', $report) }}" class="btn btn-success">
    Download Word
  </a>
  <a href="{{ route('reports.riwayat') }}" class="btn btn-secondary">
    Kembali
  </a>
</div>

<div class="preview-card">
  <div class="preview-header">
    <h1>REPORT KEGIATAN</h1>
    <p>Sistem Pelaporan Kegiatan</p>
  </div>

  <div class="info-section">
    <div class="info-title">Informasi Kegiatan</div>
    <table class="info-table">
      <tr>
        <td>Nama Kegiatan</td>
        <td>{{ $report->nama_kegiatan ?? '-' }}</td>
      </tr>
      <tr>
        <td>Waktu Kegiatan</td>
        <td>{{ $report->waktu_kegiatan ?? '-' }}</td>
      </tr>
      <tr>
        <td>Jenis Kegiatan</td>
        <td>{{ $report->jenis_kegiatan ?? '-' }}</td>
      </tr>
      <tr>
        <td>Lokasi Kegiatan</td>
        <td>{{ $report->lokasi_kegiatan ?? '-' }}</td>
      </tr>
    </table>
  </div>

  <div class="info-section">
    <div class="info-title">Checklist Kondisi</div>
    <table class="checklist-table">
      <thead>
        <tr>
          <th rowspan="2" class="no">No</th>
          <th rowspan="2">Deskripsi</th>
          <th colspan="2">Kondisi</th>
          <th rowspan="2">Catatan</th>
        </tr>
        <tr>
          <th class="cond">Baik</th>
          <th class="cond">Problem</th>
        </tr>
      </thead>
      <tbody>
        @forelse($report->items as $item)
          <tr>
            <td class="no">{{ $item->no }}</td>
            <td>{{ $item->deskripsi ?? '-' }}</td>
            <td class="cond">
              @if($item->kondisi === 'baik')
                <span class="cond-baik">✓</span>
              @endif
            </td>
            <td class="cond">
              @if($item->kondisi === 'problem')
                <span class="cond-problem">✓</span>
              @endif
            </td>
            <td>{{ $item->catatan ?? '-' }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="5" style="text-align: center; padding: 20px; color: var(--text-light);">
              Tidak ada checklist.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  @if($report->photos && $report->photos->count())
    <div class="photo-section">
      <div class="info-title">Dokumentasi</div>
      <div class="photo-grid">
        @foreach($report->photos as $photo)
          <div class="photo-item">
            <img src="{{ asset('storage/'.$photo->photo_path) }}" alt="Dokumentasi">
            <div class="photo-caption">{{ $photo->caption ?? 'Dokumentasi' }}</div>
          </div>
        @endforeach
      </div>
    </div>
  @endif

  <div class="footer-info">
    Dibuat: {{ $report->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB | Report System
  </div>
</div>

@endsection
