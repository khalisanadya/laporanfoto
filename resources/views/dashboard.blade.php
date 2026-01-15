@extends('layouts.app')

@section('title', 'Dashboard - Report System')
@section('header', 'Dashboard')

@section('styles')
  .stats-grid{
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 28px;
  }

  @media (max-width: 1100px){
    .stats-grid{
      grid-template-columns: repeat(2, 1fr);
    }
  }

  @media (max-width: 600px){
    .stats-grid{
      grid-template-columns: 1fr;
    }
  }

  .stat-card{
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 22px;
    box-shadow: var(--shadow);
    transition: all .2s ease;
  }

  .stat-card:hover{
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
  }

  .stat-icon{
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-bottom: 14px;
  }

  .stat-icon.blue{
    background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    color: #1d4ed8;
  }

  .stat-icon.green{
    background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
    color: #059669;
  }

  .stat-icon.red{
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    color: #dc2626;
  }

  .stat-icon.yellow{
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    color: #d97706;
  }

  .stat-value{
    font-size: 32px;
    font-weight: 800;
    color: var(--text);
    line-height: 1;
    margin-bottom: 6px;
  }

  .stat-label{
    font-size: 13px;
    color: var(--muted);
    font-weight: 500;
  }

  /* Quick Actions */
  .quick-actions{
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 28px;
  }

  .action-btn{
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 14px 20px;
    background: #fff;
    border: 1.5px solid var(--border);
    border-radius: 12px;
    text-decoration: none;
    color: var(--text);
    font-weight: 600;
    font-size: 14px;
    transition: all .2s ease;
    box-shadow: var(--shadow);
  }

  .action-btn:hover{
    border-color: var(--primary);
    background: var(--primary-bg);
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
  }

  .action-btn .icon{
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%);
    color: #fff;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
  }

  /* Recent Table */
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
  }

  td{
    font-size: 14px;
    color: var(--text);
  }

  tr:hover td{
    background: #fafbfc;
  }

  .badge{
    display: inline-block;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
  }

  .badge-success{
    background: var(--success-bg);
    color: var(--success);
    border: 1px solid var(--success-border);
  }

  .badge-danger{
    background: var(--danger-bg);
    color: var(--danger);
    border: 1px solid var(--danger-border);
  }

  .badge-info{
    background: #f0f9ff;
    color: #0369a1;
    border: 1px solid #bae6fd;
  }

  .empty-state{
    text-align: center;
    padding: 48px 20px;
    color: var(--muted);
  }

  .empty-state-icon{
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
  }

  .empty-state p{
    margin-bottom: 20px;
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
    color: var(--text);
  }

  .btn-icon:hover{
    background: var(--primary-bg);
    border-color: var(--primary);
    color: var(--primary);
  }

  .btn-icon.pdf{
    color: #dc2626;
    border-color: #fecaca;
  }

  .btn-icon.pdf:hover{
    background: #fef2f2;
    border-color: #dc2626;
  }

  .btn-icon.word{
    color: #2563eb;
    border-color: #bfdbfe;
  }

  .btn-icon.word:hover{
    background: #eff6ff;
    border-color: #2563eb;
  }

  /* Filter Bar */
  .filter-bar{
    display: flex;
    gap: 12px;
    align-items: flex-end;
    flex-wrap: wrap;
    padding: 16px 20px;
    background: #f8fafc;
    border-bottom: 1px solid var(--border);
  }

  .filter-group{
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .filter-label{
    font-size: 11px;
    font-weight: 600;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .filter-input{
    padding: 10px 14px;
    border: 1.5px solid var(--border);
    border-radius: 8px;
    font-size: 13px;
    font-family: inherit;
    background: #fff;
    min-width: 180px;
    height: 38px;
    transition: all .2s ease;
  }

  .filter-input:focus{
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(14,165,233,.15);
  }

  .filter-input.search{
    min-width: 250px;
  }

  .filter-btn{
    padding: 10px 20px;
    background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s ease;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .filter-btn:hover{
    box-shadow: 0 4px 12px rgba(3,105,161,.3);
    transform: translateY(-1px);
  }

  .filter-btn.reset{
    background: #fff;
    color: var(--text);
    border: 1.5px solid var(--border);
  }

  .filter-btn.reset:hover{
    background: #f1f5f9;
    box-shadow: none;
  }

  .pagination-wrap{
    display: flex;
    justify-content: center;
    padding: 20px 0;
  }

  .result-info{
    padding: 12px 20px;
    background: var(--primary-bg);
    border-bottom: 1px solid var(--primary-border);
    font-size: 13px;
    color: var(--primary-dark);
  }

  .result-info strong{
    font-weight: 700;
  }
@endsection

@section('content')

<!-- Stats Cards -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon blue">≡</div>
    <div class="stat-value">{{ $totalReports }}</div>
    <div class="stat-label">Total Laporan</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon green">✓</div>
    <div class="stat-value">{{ $kondisiBaik }}</div>
    <div class="stat-label">Kondisi Baik</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon red">!</div>
    <div class="stat-value">{{ $kondisiProblem }}</div>
    <div class="stat-label">Kondisi Problem</div>
  </div>

  <div class="stat-card">
    <div class="stat-icon yellow">◉</div>
    <div class="stat-value">{{ $bulanIni }}</div>
    <div class="stat-label">Bulan Ini</div>
  </div>
</div>

<!-- Recent Reports -->
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Semua Laporan</h3>
  </div>

  <!-- Filter Bar -->
  <form class="filter-bar" method="GET" action="{{ route('dashboard') }}">
    <div class="filter-group">
      <label class="filter-label">Cari Nama/Nomor</label>
      <input type="text" name="search" class="filter-input search" placeholder="Ketik nama laporan..." value="{{ $search ?? '' }}">
    </div>

    <div class="filter-group">
      <label class="filter-label">Jenis Laporan</label>
      <select name="jenis_laporan" class="filter-input">
        <option value="all" {{ ($jenisLaporan ?? '') === 'all' ? 'selected' : '' }}>Semua Jenis</option>
        <option value="report" {{ ($jenisLaporan ?? '') === 'report' ? 'selected' : '' }}>Report Kegiatan</option>
        <option value="bap" {{ ($jenisLaporan ?? '') === 'bap' ? 'selected' : '' }}>BAP</option>
        <option value="utilization" {{ ($jenisLaporan ?? '') === 'utilization' ? 'selected' : '' }}>Utilization Report</option>
      </select>
    </div>

    <div class="filter-group">
      <label class="filter-label">Bulan</label>
      <select name="bulan" class="filter-input">
        <option value="">Semua Bulan</option>
        @foreach($availableMonths ?? [] as $month)
          @php
            $monthDate = \Carbon\Carbon::createFromFormat('Y-m', $month);
          @endphp
          <option value="{{ $month }}" {{ ($bulanFilter ?? '') === $month ? 'selected' : '' }}>
            {{ $monthDate->translatedFormat('F Y') }}
          </option>
        @endforeach
      </select>
    </div>

    <button type="submit" class="filter-btn">
      Cari
    </button>
  </form>

  @if($allItemsPaginated->total() > 0)
    <div class="result-info">
      Menampilkan <strong>{{ $allItemsPaginated->firstItem() }} - {{ $allItemsPaginated->lastItem() }}</strong> dari <strong>{{ $allItemsPaginated->total() }}</strong> laporan
    </div>
  @endif

  @if($allItemsPaginated->count() > 0)
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Nama/Nomor</th>
            <th>Jenis Laporan</th>
            <th>Tanggal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($allItemsPaginated as $index => $item)
            <tr>
              <td>{{ $allItemsPaginated->firstItem() + $index }}</td>
              <td><strong>{{ $item->nama }}</strong></td>
              <td>
                @if($item->type === 'report')
                  <span class="badge badge-success">Report Kegiatan</span>
                @elseif($item->type === 'bap')
                  <span class="badge badge-info">BAP</span>
                @elseif($item->type === 'utilization')
                  <span class="badge badge-danger">Utilization Report</span>
                @endif
              </td>
              <td>{{ $item->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</td>
              <td>
                <div class="action-buttons">
                  @if($item->type === 'report')
                    <a href="{{ route('reports.show', $item->id) }}" class="btn-icon" title="Preview">
                      ◎
                    </a>
                    <a href="{{ route('reports.pdf', $item->id) }}" class="btn-icon pdf" title="Download PDF">
                      ↓
                    </a>
                    <a href="{{ route('reports.word', $item->id) }}" class="btn-icon word" title="Download Word">
                      W
                    </a>
                  @elseif($item->type === 'bap')
                    <a href="{{ route('bap.show', $item->id) }}" class="btn-icon" title="Preview">
                      ◎
                    </a>
                    <a href="{{ route('bap.word', $item->id) }}" class="btn-icon word" title="Download Word">
                      W
                    </a>
                  @elseif($item->type === 'utilization')
                    <a href="{{ route('utilization.show', $item->id) }}" class="btn-icon" title="Preview">
                      ⊙
                    </a>
                    <a href="{{ route('utilization.excel', $item->id) }}" class="btn-icon excel" title="Download Excel">
                      E
                    </a>
                  @endif
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    @if($allItemsPaginated->hasPages())
      <div class="pagination-wrap">
        {{ $allItemsPaginated->links() }}
      </div>
    @endif
  @else
    <div class="empty-state">
      <div class="empty-state-icon">≡</div>
      <p>
        @if($search || $jenisLaporan || $bulanFilter)
          Tidak ada laporan yang sesuai dengan filter
        @else
          Belum ada laporan yang dibuat
        @endif
      </p>
    </div>
  @endif
</div>

@endsection
