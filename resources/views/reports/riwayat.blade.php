@extends('layouts.app')

@section('title', 'Riwayat Laporan - Report System')
@section('header', 'Riwayat Laporan')

@section('styles')
<style>
  .filter-bar{
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 28px;
  }

  .search-box{
    flex: 1;
    min-width: 200px;
    max-width: 400px;
    position: relative;
  }

  .search-box input{
    width: 100%;
    padding: 12px 16px 12px 42px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-size: 14px;
    font-family: inherit;
    background: #fff;
    transition: all .2s ease;
  }

  .search-box input:focus{
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(14,165,233,.15);
  }

  .search-box::before{
    content: '⌕';
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 18px;
    color: var(--muted);
  }

  .table-wrap{
    overflow-x: auto;
  }

  table{
    width: 100%;
    border-collapse: collapse;
  }

  th, td{
    padding: 14px 16px;
    text-align: left;
    border-bottom: 1px solid var(--border);
  }

  th{
    background: #f8fafc;
    font-size: 12px;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    position: sticky;
    top: 0;
  }

  td{
    font-size: 14px;
    color: var(--text);
  }

  tr:hover td{
    background: #fafbfc;
  }

  .report-title{
    font-weight: 600;
    color: var(--text);
    margin-bottom: 2px;
  }

  .report-sub{
    font-size: 12px;
    color: var(--muted);
  }

  .badge{
    display: inline-block;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
  }

  .badge-info{
    background: var(--primary-bg);
    color: var(--primary);
    border: 1px solid var(--primary-border);
  }

  .action-buttons{
    display: flex;
    gap: 8px;
  }

  .btn-icon{
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1.5px solid var(--border);
    background: #fff;
    cursor: pointer;
    transition: all .2s ease;
    text-decoration: none;
    font-size: 14px;
  }

  .btn-icon:hover{
    background: var(--primary-bg);
    border-color: var(--primary);
    color: var(--primary);
  }

  .btn-icon.pdf{
    color: var(--danger);
    border-color: var(--danger-border);
  }

  .btn-icon.pdf:hover{
    background: var(--danger-bg);
    border-color: var(--danger);
  }

  .btn-icon.word{
    color: #2563eb;
    border-color: #bfdbfe;
  }

  .btn-icon.word:hover{
    background: #eff6ff;
    border-color: #2563eb;
  }

  .empty-state{
    text-align: center;
    padding: 60px 20px;
    color: var(--muted);
  }

  .empty-state-icon{
    font-size: 56px;
    margin-bottom: 16px;
    opacity: 0.4;
  }

  .empty-state h3{
    font-size: 18px;
    color: var(--text);
    margin-bottom: 8px;
  }

  .empty-state p{
    margin-bottom: 24px;
    font-size: 14px;
  }

  .pagination-wrap{
    display: flex;
    justify-content: center;
    margin-top: 24px;
  }

  .stats-mini{
    display: flex;
    gap: 16px;
    margin-left: auto;
  }

  .stat-mini{
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--muted);
  }

  .stat-mini strong{
    color: var(--text);
  }
</style>
@endsection

@section('content')

<div class="card">
  <div class="card-header">
    <div class="card-header-left">
      <h3 class="card-title">Daftar Report Kegiatan</h3>
    </div>
    <a href="{{ route('reports.create') }}" class="btn btn-primary btn-sm">+ Buat Report Baru</a>
  </div>

  @if($reports->count() > 0)
    <div class="table-wrap" style="margin-top: 16px;">
      <table>
        <thead>
          <tr>
            <th style="width: 50px;">No</th>
            <th>Kegiatan</th>
            <th>Jenis</th>
            <th>Lokasi</th>
            <th>Waktu</th>
            <th>Dibuat</th>
            <th style="width: 120px;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($reports as $index => $report)
            <tr>
              <td>{{ $reports->firstItem() + $index }}</td>
              <td>
                <div class="report-title">{{ $report->nama_kegiatan ?? '-' }}</div>
              </td>
              <td>
                <span class="badge badge-info">{{ $report->jenis_kegiatan ?? '-' }}</span>
              </td>
              <td>{{ $report->lokasi_kegiatan ?? '-' }}</td>
              <td>{{ $report->waktu_kegiatan ?? '-' }}</td>
              <td>
                <div class="report-sub">{{ $report->created_at->timezone('Asia/Jakarta')->format('d M Y') }}</div>
                <div class="report-sub">{{ $report->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB</div>
              </td>
              <td>
                <div class="action-buttons">
                  <a href="{{ route('reports.show', $report) }}" class="btn-icon" title="Preview">
                    ◎
                  </a>
                  <a href="{{ route('reports.pdf', $report) }}" class="btn-icon pdf" title="Download PDF">
                    ↓
                  </a>
                  <a href="{{ route('reports.word', $report) }}" class="btn-icon word" title="Download Word">
                    W
                  </a>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="pagination-wrap">
      {{ $reports->links() }}
    </div>

  @else
    <div class="empty-state">
      <div class="empty-state-icon">≡</div>
      <h3>Belum Ada Laporan</h3>
      <p>Mulai buat laporan kegiatan pertama kamu</p>
      <a href="{{ route('reports.create') }}" class="btn btn-primary">Buat Laporan Baru</a>
    </div>
  @endif
</div>

@endsection
