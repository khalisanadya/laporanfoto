@extends('layouts.app')

@section('title', 'Preview Utilization Report - Report System')
@section('header', 'Preview Utilization Report')

@section('styles')
<style>
  .report-container {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 14px;
    overflow: hidden;
    box-shadow: var(--shadow);
  }

  .report-header {
    padding: 24px;
    border-bottom: 1px solid var(--border);
    background: #f8fafc;
  }

  .report-title {
    font-size: 20px;
    font-weight: 800;
    color: var(--text);
    text-align: center;
    margin-bottom: 8px;
  }

  .report-period {
    font-size: 14px;
    color: var(--muted);
    text-align: center;
  }

  .report-actions {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-top: 16px;
  }

  .btn-action {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all .2s ease;
  }

  .btn-action.excel {
    background: #16a34a;
    color: #fff;
  }

  .btn-action.excel:hover {
    background: #15803d;
    box-shadow: 0 4px 12px rgba(22,163,74,.3);
  }

  .section-block {
    margin: 24px;
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
  }

  .section-header {
    padding: 14px 20px;
    font-size: 16px;
    font-weight: 700;
    color: #000;
  }

  .section-content {
    padding: 20px;
  }

  .item-block {
    background: #f8fafc;
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 16px;
  }

  .interface-name {
    font-size: 12px;
    font-family: monospace;
    color: var(--text);
    background: #fff;
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid var(--border);
    margin-bottom: 12px;
  }

  .graph-container {
    text-align: center;
    margin-bottom: 16px;
  }

  .graph-image {
    max-width: 100%;
    max-height: 250px;
    border: 1px solid var(--border);
    border-radius: 8px;
  }

  .stats-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
  }

  .stats-table td {
    padding: 8px 12px;
    border: 1px solid var(--border);
  }

  .stats-table .inbound {
    background: #dcfce7;
    color: #166534;
  }

  .stats-table .outbound {
    background: #dbeafe;
    color: #1e40af;
  }

  .stats-table .label-cell {
    font-weight: 600;
    width: 80px;
  }

  .label-block {
    background: #fefce8;
    border: 1px solid #fef08a;
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 16px;
  }

  .label-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--text);
    margin-bottom: 12px;
  }

  .label-values {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
  }

  .label-value {
    display: flex;
    justify-content: space-between;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 13px;
  }

  .label-value.inbound {
    background: #dcfce7;
    color: #166534;
  }

  .label-value.outbound {
    background: #dbeafe;
    color: #1e40af;
  }

  .summary-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 16px;
    font-size: 13px;
  }

  .summary-table th,
  .summary-table td {
    padding: 10px 14px;
    border: 1px solid #d97706;
    text-align: center;
  }

  .summary-table th {
    background: #fed7aa;
    font-weight: 700;
  }

  .summary-table td {
    background: #fff;
  }

  .summary-table tr:first-child td {
    font-weight: 600;
  }

  .empty-message {
    text-align: center;
    padding: 40px;
    color: var(--muted);
  }
</style>
@endsection

@section('content')
<div class="report-container">
  <div class="report-header">
    <h1 class="report-title">{{ $utilization->judul }}</h1>
    <p class="report-period">
      Periode: {{ $utilization->periode_mulai->translatedFormat('d F Y') }} - {{ $utilization->periode_selesai->translatedFormat('d F Y') }}
    </p>
    <div class="report-actions">
      <a href="{{ route('utilization.excel', $utilization) }}" class="btn-action excel">
        📊 Download Excel
      </a>
    </div>
  </div>

  @if($utilization->sections->count() > 0)
    @foreach($utilization->sections as $section)
      <div class="section-block">
        <div class="section-header" style="background-color: {{ $section->warna_header }};">
          {{ $section->nama_section }}
        </div>

        <div class="section-content">
          @foreach($section->items as $item)
            @if($item->nama_interface)
              <!-- Graph Item -->
              <div class="item-block">
                <div class="interface-name">{{ $item->nama_interface }}</div>

                @if($item->gambar_graph)
                  <div class="graph-container">
                    <img src="{{ asset('storage/' . $item->gambar_graph) }}" alt="Traffic Graph" class="graph-image">
                  </div>
                @endif

                @if($item->inbound_current || $item->inbound_average || $item->inbound_maximum)
                  <table class="stats-table">
                    <tr class="inbound">
                      <td class="label-cell">🟢 Inbound</td>
                      <td>Current: {{ $item->inbound_current }}</td>
                      <td>Average: {{ $item->inbound_average }}</td>
                      <td>Maximum: {{ $item->inbound_maximum }}</td>
                    </tr>
                    <tr class="outbound">
                      <td class="label-cell">🔵 Outbound</td>
                      <td>Current: {{ $item->outbound_current }}</td>
                      <td>Average: {{ $item->outbound_average }}</td>
                      <td>Maximum: {{ $item->outbound_maximum }}</td>
                    </tr>
                  </table>
                @endif
              </div>
            @endif

            @if($item->label)
              <!-- Label Summary Item -->
              <div class="label-block">
                <div class="label-title">{{ $item->label }}</div>
                <div class="label-values">
                  <div class="label-value inbound">
                    <span>INBOUND</span>
                    <strong>{{ $item->inbound_value }}</strong>
                  </div>
                  <div class="label-value outbound">
                    <span>OUTBOUND</span>
                    <strong>{{ $item->outbound_value }}</strong>
                  </div>
                </div>
              </div>
            @endif
          @endforeach

          @if($section->summaries->count() > 0)
            <table class="summary-table">
              <tr>
                @foreach($section->summaries as $summary)
                  <th colspan="2">{{ $summary->kategori }}</th>
                @endforeach
              </tr>
              <tr>
                @foreach($section->summaries as $summary)
                  <td>INBOUND</td>
                  <td>{{ $summary->inbound_value }}</td>
                @endforeach
              </tr>
              <tr>
                @foreach($section->summaries as $summary)
                  <td>OUTBOUND</td>
                  <td>{{ $summary->outbound_value }}</td>
                @endforeach
              </tr>
            </table>
          @endif
        </div>
      </div>
    @endforeach
  @else
    <div class="empty-message">
      <p>Belum ada section dalam report ini</p>
    </div>
  @endif
</div>
@endsection
