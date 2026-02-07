{{-- Import Modal --}}
{{-- Usage: @include('partials._import-modal', ['title' => '...', 'importRoute' => '...', 'templateRoute' => '...']) --}}
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title fw-bold">{{ $title ?? 'Import Data' }}</h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
            <form action="{{ $importRoute }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-5">
                        <div class="notice d-flex bg-light-primary rounded border border-primary border-dashed p-6 mb-5">
                            <i class="ki-duotone ki-information-5 fs-2tx text-primary me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                            <div class="d-flex flex-stack flex-grow-1">
                                <div class="fw-semibold">
                                    <div class="fs-6 text-gray-700">
                                        Upload file CSV dengan format yang sesuai. Download template terlebih dahulu untuk memastikan format yang benar.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-5">
                        <label class="form-label fw-semibold required">File CSV</label>
                        <input type="file" name="file" class="form-control form-control-solid" accept=".csv,.txt" required/>
                        <div class="form-text">Format: CSV (maks. 10MB)</div>
                    </div>
                    @if(isset($templateRoute))
                    <div class="mb-0">
                        <a href="{{ $templateRoute }}" class="btn btn-sm btn-light-info">
                            <i class="ki-duotone ki-file-down fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                            Download Template CSV
                        </a>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-duotone ki-file-up fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                        Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
