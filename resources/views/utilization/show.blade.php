@extends('layouts.app')

@section('title', 'Preview Utilization Report - Report System')
@section('header', 'Preview Utilization Report')

@section('styles')
<style>
  .report-container {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 40px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    max-width: 1100px;
    margin: 20px auto;
    font-family: 'Calibri', sans-serif; /* Mengikuti font Excel */
  }

  .report-title {
    font-size: 26px;
    font-weight: 800;
    color: #000;
    text-align: center;
    text-transform: uppercase;
    margin-bottom: 5px;
  }

  .report-period {
    font-size: 16px;
    color: #000;
    text-align: center;
    margin-bottom: 30px;
  }

  .report-actions {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-bottom: 30px;
    border-bottom: 2px solid #eee;
    padding-bottom: 20px;
  }

  .btn-action.excel {
    background: #16a34a;
    color: #fff;
    padding: 10px 25px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 700;
  }

  /* Section Header - Mirip Excel Banner */
  .section-title-banner {
    padding: 10px 15px;
    font-size: 18px;
    font-weight: 700;
    color: #000;
    margin-bottom: 25px;
    display: inline-block;
    min-width: 200px;
  }

  /* Grid Sistem untuk Gambar Menyamping (2 Kolom) */
  .items-grid {
    display: grid;
    grid-template-columns: 1fr 1fr; /* 2 Kolom menyamping */
    gap: 40px; /* Spacer seperti kolom C di Excel */
    margin-bottom: 30px;
  }

  .item-box {
    width: 100%;
  }

  .graph-img-wrapper {
    text-align: center;
    margin-bottom: 15px;
  }

  .graph-img-wrapper img {
    height: 160px; /* Sesuai setHeight(160) di Excel */
    width: auto;
    max-width: 100%;
    border: 1px solid #ccc;
  }

  .item-label {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 5px;
  }

  /* Tabel Inbound/Outbound per item */
  .item-stats-table {
    width: 100%;
    border-collapse: collapse;
  }

  .item-stats-table td {
    padding: 4px 0;
    font-size: 14px;
  }

  .col-label { font-weight: 400; width: 40%; }
  .col-value { font-weight: 400; width: 60%; }

  /* Summary Table */
  .summary-container {
    margin-top: 20px;
    overflow-x: auto;
  }

  .summary-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
  }

  .summary-table th, .summary-table td {
    border: 1px solid #000;
    padding: 8px;
    text-align: center;
  }

  .summary-table th {
    background: #F4A460; /* Warna F4A460 seperti di Excel */
    font-weight: 700;
  }

  .summary-table td:nth-child(odd) {
    font-weight: 400;
  }

  .summary-table td:nth-child(even) {
    font-weight: 400;
  }
</style>
@endsection

@section('content')
<div class="report-container">
  <!-- Judul Laporan -->
  <h1 class="report-title">{{ strtoupper($utilization->judul) }}</h1>
  <p class="report-period">
    Periode: {{ $utilization->periode_mulai->translatedFormat('d F Y') }} - {{ $utilization->periode_selesai->translatedFormat('d F Y') }}
  </p>

  <div class="report-actions">
    <a href="{{ route('utilization.excel', $utilization) }}" class="btn-action excel">
      Download Excel
    </a>
    <a href="{{ route('utilization.create') }}" style="text-decoration:none; color:#666; font-weight:600; padding:10px;">
     
    </a>
  </div>

  @foreach($utilization->sections as $section)
    <div style="margin-bottom: 50px;">
      <!-- Header Section -->
      <div class="section-title-banner" style="background-color: {{ $section->warna_header }};">
        {{ $section->nama_section }}
      </div>

      <!-- Grid untuk Gambar Side-by-Side (Chunk 2) -->
      @foreach($section->items->chunk(2) as $chunk)
        <div class="items-grid">
          @foreach($chunk as $item)
            <div class="item-box">
              <!-- 1. Gambar -->
              @if($item->gambar_graph)
                <div class="graph-img-wrapper">
                  <img src="{{ asset('storage/' . $item->gambar_graph) }}" alt="Graph">
                </div>
              @endif

              <!-- 2. Label -->
              <div class="item-label">{{ $item->label ?? '-' }}</div>

              <!-- 3. Inbound/Outbound -->
              <table class="item-stats-table">
                <tr>
                  <td class="col-label">INBOUND</td>
                  <td class="col-value">
                    {{ str_contains(strtolower($item->inbound_value), 'mbps') ? $item->inbound_value : ($item->inbound_value ?? '0') . ' Mbps' }}
                  </td>
                </tr>
                <tr>
                  <td class="col-label">OUTBOUND</td>
                  <td class="col-value">
                    {{ str_contains(strtolower($item->outbound_value), 'mbps') ? $item->outbound_value : ($item->outbound_value ?? '0') . ' Mbps' }}
                  </td>
                </tr>
              </table>
            </div>
          @endforeach
        </div>
      @endforeach

      <!-- Tabel Summary -->
      @if($section->summaries->count() > 0)
        <div class="summary-container">
          <table class="summary-table">
            <thead>
              <tr>
                @foreach($section->summaries as $summary)
                  <th colspan="2">{{ $summary->kategori }}</th>
                @endforeach
              </tr>
            </thead>
            <tbody>
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
            </tbody>
          </table>
        </div>
      @endif
    </div>
  @endforeach
</div>
@endsection