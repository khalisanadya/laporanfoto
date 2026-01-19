@extends('layouts.app')

@section('title', 'Buat Utilization Report - Report System')
@section('header', 'Utilization Report')

@section('styles')
<style>
  .form-section {
    margin-bottom: 28px;
  }

  .form-section-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 16px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--primary-bg);
  }

  .form-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-bottom: 16px;
  }

  @media (max-width: 700px) {
    .form-row {
      grid-template-columns: 1fr;
    }
  }

  .form-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .form-group.full {
    grid-column: 1 / -1;
  }

  .form-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--text);
  }

  .form-label span {
    color: var(--danger);
  }

  .form-input {
    padding: 12px 14px;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    font-size: 14px;
    font-family: inherit;
    background: #fff;
    transition: all .2s ease;
  }

  .form-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px var(--primary-bg);
  }

  .form-hint {
    font-size: 12px;
    color: var(--muted);
  }

  .form-actions {
    display: flex;
    gap: 12px;
    padding-top: 20px;
    border-top: 1px solid var(--border);
    margin-top: 28px;
  }

  /* Section Container */
  .section-box {
    background: #f8fafc;
    border: 1.5px solid var(--border);
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 16px;
  }

  .section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
  }

  .section-inputs {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
  }

  .color-picker {
    width: 42px;
    height: 42px;
    border: 2px solid var(--border);
    border-radius: 8px;
    cursor: pointer;
    padding: 2px;
  }

  /* Item Container */
  .item-box {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 12px;
  }

  .item-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px dashed var(--border);
  }

  .item-badge {
    background: var(--primary-bg);
    color: var(--primary);
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
  }

  .item-badge.label {
    background: #fef3c7;
    color: #92400e;
  }

  /* Stats Grid */
  .stats-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-bottom: 12px;
  }

  .stats-group {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 8px;
    padding: 12px;
  }

  .stats-group.outbound {
    background: #eff6ff;
    border-color: #bfdbfe;
  }

  .stats-group-title {
    font-size: 11px;
    font-weight: 700;
    color: #166534;
    margin-bottom: 8px;
    text-transform: uppercase;
  }

  .stats-group.outbound .stats-group-title {
    color: #1e40af;
  }

  .stats-inputs {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
  }

  .stats-inputs input {
    padding: 8px 10px;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 12px;
    width: 100%;
  }

  .stats-inputs input:focus {
    outline: none;
    border-color: var(--primary);
  }

  .stats-inputs label {
    font-size: 10px;
    color: var(--muted);
    margin-bottom: 4px;
    display: block;
  }

  /* Upload Area */
  .upload-area {
    border: 2px dashed var(--border);
    border-radius: 10px;
    padding: 16px;
    text-align: center;
    cursor: pointer;
    transition: all .2s ease;
    margin-bottom: 8px;
  }

  .upload-area:hover,
  .upload-area.drag-over {
    border-color: var(--primary);
    background: var(--primary-bg);
  }

  .upload-area input[type="file"] {
    display: none;
  }

  /* Preview Container */
  .upload-preview {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  .upload-preview img {
    max-width: 100%;
    max-height: 250px;
    border-radius: 8px;
    margin-bottom: 12px; /* Memberi jarak ke tombol di bawahnya */
  }

  /* Summary Table */
  .summary-box {
    background: #fff8f0;
    border: 1px solid #fed7aa;
    border-radius: 10px;
    padding: 16px;
    margin-top: 16px;
  }

  .summary-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr auto;
    gap: 8px;
    margin-bottom: 8px;
    align-items: center;
  }

  .summary-row input {
    padding: 10px;
    border: 1px solid var(--border);
    border-radius: 6px;
    font-size: 13px;
  }

  /* Buttons */
  .btn-add {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 14px;
    background: var(--primary-bg);
    color: var(--primary);
    border: 1.5px solid var(--primary-border);
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s ease;
  }

  .btn-add:hover {
    background: var(--primary);
    color: #fff;
  }

  .btn-remove {
    padding: 6px 14px;
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s ease;
  }

  .btn-remove:hover {
    background: #dc2626;
    color: #fff;
  }

  /* Paste Box */
  .paste-box {
    border: 2px dashed var(--border);
    border-radius: 8px;
    padding: 12px;
    text-align: center;
    cursor: text;
    transition: all .2s ease;
    margin-top: 8px;
    min-height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--muted);
    font-size: 12px;
  }

  .paste-box:focus {
    outline: none;
    border-color: var(--primary);
    background: var(--primary-bg);
  }
</style>
@endsection

@section('content')
<form action="{{ route('utilization.store') }}" method="POST" enctype="multipart/form-data" id="utilizationForm">
  @csrf

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Form Utilization Report</h3>
    </div>

    <!-- Info Dasar -->
    <div class="form-section">
      <div class="form-section-title">Informasi Dasar</div>

      <div class="form-group" style="margin-bottom: 16px;">
        <label class="form-label">Judul Report <span>*</span></label>
        <input type="text" name="judul" id="input-judul" class="form-input" value="" placeholder="Masukkan judul report" required>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Periode Mulai <span>*</span></label>
          <input type="date" name="periode_mulai" id="input-periode-mulai" class="form-input" required>
          <span class="form-hint">Tanggal awal periode laporan</span>
        </div>
        <div class="form-group">
          <label class="form-label">Periode Selesai <span>*</span></label>
          <input type="date" name="periode_selesai" id="input-periode-selesai" class="form-input" required>
          <span class="form-hint">Tanggal akhir periode laporan</span>
        </div>
      </div>
    </div>

    <!-- Sections -->
    <div class="form-section">
      <div class="form-section-title">Section / Lokasi</div>

      <div id="sectionsContainer"></div>

      <button type="button" class="btn-add" onclick="addSection()">
        + Tambah Section
      </button>
    </div>

    <div class="form-actions">
      <button type="submit" class="btn btn-primary">Simpan & Preview</button>
      <a href="{{ route('utilization.index') }}" class="btn btn-secondary">Batal</a>
    </div>
  </div>
</form>

<script>
let sectionIndex = 0;
let itemCounters = {};
let summaryCounters = {};

let lastGraphSecIdx = null;
let lastGraphItemIdx = null;

function addSection() {
  const container = document.getElementById('sectionsContainer');
  const html = `
    <div class="section-box" id="section-${sectionIndex}" data-section-idx="${sectionIndex}">
      <div class="section-header">
        <div class="section-inputs">
          <input type="color" name="sections[${sectionIndex}][warna]" value="#FFA500" class="color-picker">
          <input type="text" name="sections[${sectionIndex}][nama]" placeholder="Nama Section (contoh: CYBER, CITY BATAM)" class="form-input" style="flex:1">
        </div>
        <button type="button" class="btn-remove" onclick="removeSection(${sectionIndex})">Hapus</button>
      </div>

      <div id="items-${sectionIndex}"></div>

      <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 12px;">
        <button type="button" class="btn-add" onclick="addItem(${sectionIndex})">+ Item Graph</button>
      </div>

      <div class="summary-box">
        <div style="font-size: 13px; font-weight: 700; margin-bottom: 12px;">Tabel Summary</div>
        <div id="summaries-${sectionIndex}"></div>
        <button type="button" class="btn-add" onclick="addSummary(${sectionIndex})" style="margin-top: 8px;">+ Kolom</button>
      </div>
    </div>
  `;
  container.insertAdjacentHTML('beforeend', html);
  sectionIndex++;
}

function addItem(secIdx) {
  if (!itemCounters[secIdx]) itemCounters[secIdx] = 0;
  const itemIdx = itemCounters[secIdx];
  const container = document.getElementById(`items-${secIdx}`);

  const html = `
    <div class="item-box" id="item-${secIdx}-${itemIdx}" data-type="graph">
      <div class="item-header">
        <span class="item-badge">Graph Item</span>
        <button type="button" class="btn-remove" onclick="removeItem(${secIdx}, ${itemIdx})">Hapus</button>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Label (IPTR/PGAS IX/dll)</label>
          <input type="text" name="sections[${secIdx}][items][${itemIdx}][label_caption]" class="form-input" placeholder="Contoh: IPTR, PGAS IX, dst">
        </div>
        <div class="form-group">
          <label class="form-label">INBOUND</label>
          <input type="text" name="sections[${secIdx}][items][${itemIdx}][label_inbound]" class="form-input" placeholder="Contoh: 2500 Mbps">
        </div>
        <div class="form-group">
          <label class="form-label">OUTBOUND</label>
          <input type="text" name="sections[${secIdx}][items][${itemIdx}][label_outbound]" class="form-input" placeholder="Contoh: 967 Mbps">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Upload Graph</label>
        <div class="upload-area" onclick="document.getElementById('graph-${secIdx}-${itemIdx}').click()"
             id="upload-area-${secIdx}-${itemIdx}">
          <input type="file" id="graph-${secIdx}-${itemIdx}" name="sections[${secIdx}][items][${itemIdx}][gambar]" accept="image/*" onchange="previewGraph(this, ${secIdx}, ${itemIdx})">
          <div class="upload-preview" id="graph-preview-${secIdx}-${itemIdx}">
            <span style="color: var(--muted); font-size: 13px;">Klik untuk upload atau drag & drop</span>
          </div>
        </div>
        <div class="paste-box" contenteditable="true"
             id="paste-area-${secIdx}-${itemIdx}"
             onpaste="handlePaste(event, ${secIdx}, ${itemIdx})"
             onfocus="this.textContent=''"
             onblur="if(!this.querySelector('img')) this.textContent='Ctrl+V untuk paste screenshot'">
          Ctrl+V untuk paste screenshot
        </div>
      </div>
    </div>
  `;
  container.insertAdjacentHTML('beforeend', html);
  itemCounters[secIdx]++;
}

function addSummary(secIdx) {
  if (!summaryCounters[secIdx]) summaryCounters[secIdx] = 0;
  const sumIdx = summaryCounters[secIdx];
  const container = document.getElementById(`summaries-${secIdx}`);

  const html = `
    <div class="summary-row" id="summary-${secIdx}-${sumIdx}">
      <input type="text" name="sections[${secIdx}][summaries][${sumIdx}][kategori]" placeholder="TOTAL PGAS-IX">
      <input type="text" name="sections[${secIdx}][summaries][${sumIdx}][inbound_value]" placeholder="7375">
      <input type="text" name="sections[${secIdx}][summaries][${sumIdx}][outbound_value]" placeholder="1438">
      <button type="button" class="btn-remove" onclick="removeSummary(${secIdx}, ${sumIdx})">x</button>
    </div>
  `;
  container.insertAdjacentHTML('beforeend', html);
  summaryCounters[secIdx]++;
}

function removeSection(idx) {
  document.getElementById(`section-${idx}`)?.remove();
}

function removeItem(secIdx, itemIdx) {
  document.getElementById(`item-${secIdx}-${itemIdx}`)?.remove();
}

function removeSummary(secIdx, sumIdx) {
  document.getElementById(`summary-${secIdx}-${sumIdx}`)?.remove();
}

function setLastGraph(secIdx, itemIdx) {
  lastGraphSecIdx = secIdx;
  lastGraphItemIdx = itemIdx;
}

// Fungsi pembantu untuk membuat preview (Digunakan oleh upload dan paste)
function createPreviewHTML(src, secIdx, itemIdx) {
  return `
    <div style="display: flex; flex-direction: column; align-items: center; width: 100%;">
      <img src="${src}" alt="Graph" style="max-width: 100%; max-height: 250px; border-radius: 8px;">
      <button type='button' class='btn-remove' onclick='removeGraphImage(${secIdx},${itemIdx}, event)'>Hapus Foto</button>
    </div>
  `;
}

function previewGraph(input, secIdx, itemIdx) {
  setLastGraph(secIdx, itemIdx);
  const preview = document.getElementById(`graph-preview-${secIdx}-${itemIdx}`);
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      preview.innerHTML = createPreviewHTML(e.target.result, secIdx, itemIdx);
      document.getElementById(`paste-area-${secIdx}-${itemIdx}`).textContent = 'Gambar diupload';
    };
    reader.readAsDataURL(input.files[0]);
  }
}

function handlePaste(event, secIdx, itemIdx) {
  setLastGraph(secIdx, itemIdx);
  event.preventDefault();
  const items = event.clipboardData.items;
  for (let i = 0; i < items.length; i++) {
    if (items[i].type.indexOf('image') !== -1) {
      const blob = items[i].getAsFile();
      const reader = new FileReader();
      reader.onload = function(e) {
        const preview = document.getElementById(`graph-preview-${secIdx}-${itemIdx}`);
        preview.innerHTML = createPreviewHTML(e.target.result, secIdx, itemIdx);
        document.getElementById(`paste-area-${secIdx}-${itemIdx}`).textContent = 'Screenshot di-paste!';

        const file = new File([blob], `screenshot-${Date.now()}.png`, { type: 'image/png' });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        document.getElementById(`graph-${secIdx}-${itemIdx}`).files = dataTransfer.files;
      };
      reader.readAsDataURL(blob);
      break;
    }
  }
}

document.addEventListener('paste', function(event) {
  if (lastGraphSecIdx !== null && lastGraphItemIdx !== null) {
   
    if(event.target.tagName !== 'INPUT' && event.target.tagName !== 'TEXTAREA') {
        handlePaste(event, lastGraphSecIdx, lastGraphItemIdx);
    }
  }
}, true);

function removeGraphImage(secIdx, itemIdx, event) {
  if (event) event.stopPropagation(); 
  const preview = document.getElementById(`graph-preview-${secIdx}-${itemIdx}`);
  preview.innerHTML = `<span style='color: var(--muted); font-size: 13px;'>Klik untuk upload atau drag & drop</span>`;
  document.getElementById(`graph-${secIdx}-${itemIdx}`).value = '';
  document.getElementById(`paste-area-${secIdx}-${itemIdx}`).textContent = 'Ctrl+V untuk paste screenshot';
}
</script>
@endsection