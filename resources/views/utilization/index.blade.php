@extends('layouts.app')

@section('title', 'Riwayat Utilization Report - Report System')
@section('header', 'Riwayat Utilization Report')

@section('styles')
<style>
  .table-wrap {
    overflow-x: auto;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  th, td {
    padding: 14px 16px;
    text-align: left;
    border-bottom: 1px solid var(--border);
  }

  th {
    background: #f8fafc;
    font-size: 12px;
    font-weight: 700;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  td {
    font-size: 14px;
    color: var(--text);
  }

  tr:hover td {
    background: #fafbfc;
  }

  .action-buttons {
    display: flex;
    gap: 8px;
  }

  .btn-icon {
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

  .btn-icon:hover {
    background: var(--primary-bg);
    border-color: var(--primary);
    color: var(--primary);
  }

  .btn-icon.excel {
    color: #16a34a;
    border-color: #bbf7d0;
  }

  .btn-icon.excel:hover {
    background: #f0fdf4;
    border-color: #16a34a;
  }

  .empty-state {
    text-align: center;
    padding: 48px 20px;
    color: var(--muted);
  }

  .empty-state-icon {
    font-size: 48px;
    margin-bottom: 16px;
    opacity: 0.5;
  }

  .pagination-wrap {
    display: flex;
    justify-content: center;
    padding: 20px 0;
  }
</style>
@endsection

@section('content')
<div class="card">
  <div class="card-header">
    <div class="card-header-left">
      <h3 class="card-title">Daftar Utilization Report</h3>
    </div>
    <a href="{{ route('utilization.create') }}" class="btn btn-primary btn-sm">+ Buat Report Baru</a>
  </div>

  @if($reports->count() > 0)
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Periode</th>
            <th>Tanggal Dibuat</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($reports as $index => $report)
            <tr>
              <td>{{ $reports->firstItem() + $index }}</td>
              <td><strong>{{ $report->judul }}</strong></td>
              <td>{{ $report->periode_mulai->format('d M Y') }} - {{ $report->periode_selesai->format('d M Y') }}</td>
              <td>{{ $report->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</td>
              <td>
                <div class="action-buttons">
                  <a href="{{ route('utilization.show', $report) }}" class="btn-icon" title="Preview">
                    ◎
                  </a>
                  <a href="{{ route('utilization.excel', $report) }}" class="btn-icon excel" title="Download Excel">
                    ↓
                  </a>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    @if($reports->hasPages())
      <div class="pagination-wrap">
        {{ $reports->links() }}
      </div>
    @endif
  @else
    <div class="empty-state">
      <div class="empty-state-icon">▢</div>
      <p>Belum ada Utilization Report yang dibuat</p>
    </div>
  @endif
</div>
@endsection
