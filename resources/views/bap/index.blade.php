@extends('layouts.app')

@section('title', 'Riwayat BAP - Report System')
@section('header', 'Riwayat Berita Acara Pemeriksaan')

@section('styles')
  .search-box{
    display: flex;
    gap: 12px;
    margin-bottom: 16px;
  }

  .search-input{
    flex: 1;
    padding: 12px 16px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-size: 14px;
    font-family: inherit;
    transition: all .2s ease;
  }

  .search-input:focus{
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-bg);
  }

  .table-wrap{
    overflow-x: auto;
    margin-top: 16px;
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

  .btn-icon.word{
    color: #2563eb;
    border-color: #bfdbfe;
  }

  .btn-icon.word:hover{
    background: #eff6ff;
    border-color: #2563eb;
  }

  .btn-icon.excel{
    color: #16a34a;
    border-color: #bbf7d0;
  }

  .btn-icon.excel:hover{
    background: #f0fdf4;
    border-color: #16a34a;
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

  .pagination-wrap{
    display: flex;
    justify-content: center;
    padding: 20px 0;
  }
@endsection

@section('content')

<div class="card">
  <div class="card-header">
    <div class="card-header-left">
      <h3 class="card-title">Daftar BAP</h3>
    </div>
    <a href="{{ route('bap.create') }}" class="btn btn-primary btn-sm">+ Buat BAP Baru</a>
  </div>

  @if($baps->count() > 0)
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Nomor BAP</th>
            <th>Tanggal BAP</th>
            <th>Nomor Surat Permohonan</th>
            <th>Tanggal Dibuat</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($baps as $index => $bap)
            <tr>
              <td>{{ $baps->firstItem() + $index }}</td>
              <td><strong>{{ $bap->nomor_bap }}</strong></td>
              <td>{{ $bap->tanggal_bap->format('d M Y') }}</td>
              <td>{{ $bap->nomor_surat_permohonan }}</td>
              <td>{{ $bap->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }}</td>
              <td>
                <div class="action-buttons">
                  <a href="{{ route('bap.show', $bap) }}" class="btn-icon" title="Preview">
                    ◎
                  </a>
                  <a href="{{ route('bap.word', $bap) }}" class="btn-icon word" title="Download Word">
                    W
                  </a>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    @if($baps->hasPages())
      <div class="pagination-wrap">
        {{ $baps->links() }}
      </div>
    @endif
  @else
    <div class="empty-state">
      <div class="empty-state-icon">📄</div>
      <p>Belum ada BAP yang dibuat</p>
    </div>
  @endif
</div>

@endsection
