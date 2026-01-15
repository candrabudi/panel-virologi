@extends('layouts.app')
@section('title', 'Import Data Serangan Cyber')
@section('content')
    <div class="col-span-12">
        <div class="flex flex-col gap-y-3 md:h-10 md:flex-row md:items-center">
            <div class="text-base font-medium group-[.mode--light]:text-white">Import Data Serangan Cyber</div>
            <div class="flex flex-col gap-x-3 gap-y-2 sm:flex-row md:ml-auto">
                <a href="/cyber-attacks"
                    class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-arrow-left mr-2 h-4 w-4 stroke-[1.3]">
                        <path d="m12 19-7-7 7-7"></path>
                        <path d="M19 12H5"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="mt-3.5 flex flex-col gap-5">
            <!-- Main Import Box -->
            <div class="box box--stacked">
                <div class="p-7">
                    <!-- Step 1: Download Template -->
                    <div class="mb-6 pb-6 border-b">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-sm font-semibold">
                                1
                            </div>
                            <h3 class="text-base font-semibold">Download Template</h3>
                        </div>
                        <p class="text-sm text-slate-600 mb-4 ml-11">
                            Pilih salah satu template yang sesuai dengan kebutuhan Anda:
                        </p>
                        <div class="ml-11 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Template Format -->
                            <div class="border rounded-lg p-4 hover:border-primary/50 transition">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-600">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-semibold text-slate-800 mb-1">Format Template</h4>
                                        <p class="text-xs text-slate-600 mb-3">Header kolom saja (kosong). Cocok untuk input data sendiri.</p>
                                        <a href="/cyber-attacks/download-template"
                                            class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-primary border border-primary rounded-md hover:bg-primary hover:text-white transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="7 10 12 15 17 10"></polyline>
                                                <line x1="12" x2="12" y1="15" y2="3"></line>
                                            </svg>
                                            Download Format
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Sample Data -->
                            <div class="border rounded-lg p-4 hover:border-success/50 transition border-success/20 bg-success/5">
                                <div class="flex items-start gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-success/20 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-success">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" x2="8" y1="13" y2="13"></line>
                                            <line x1="16" x2="8" y1="17" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h4 class="text-sm font-semibold text-slate-800">Sample Data</h4>
                                            <span class="text-[10px] px-1.5 py-0.5 bg-success text-white rounded-full font-semibold">Recommended</span>
                                        </div>
                                        <p class="text-xs text-slate-600 mb-3">15 data dummy untuk testing. Bisa langsung diimport!</p>
                                        <a href="/cyber-attacks/download-sample"
                                            class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-white bg-success rounded-md hover:bg-success/90 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="7 10 12 15 17 10"></polyline>
                                                <line x1="12" x2="12" y1="15" y2="3"></line>
                                            </svg>
                                            Download Sample
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Upload File -->
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-sm font-semibold">
                                2
                            </div>
                            <h3 class="text-base font-semibold">Upload File</h3>
                        </div>

                        <form id="import-form" enctype="multipart/form-data" class="ml-11">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="inline-block mb-2 text-sm font-medium">
                                        Pilih File Excel/CSV
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" name="file" id="file-input" accept=".xlsx,.xls,.csv" required
                                        class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90">
                                    <div class="mt-2 text-xs text-slate-500" id="file-info"></div>
                                    <p class="mt-2 text-xs text-slate-500">Format: .xlsx, .xls, .csv | Max: 10MB</p>
                                </div>

                                <!-- Progress Bar -->
                                <div id="progress-container" class="hidden">
                                    <div class="mb-2 flex justify-between text-sm">
                                        <span class="font-medium text-slate-700">Importing data...</span>
                                        <span class="font-semibold text-primary" id="progress-text">0%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
                                        <div id="progress-bar" class="bg-primary h-3 rounded-full transition-all duration-300" style="width: 0%"></div>
                                    </div>
                                    <div class="mt-2 text-xs text-slate-600" id="progress-detail">
                                        0 / 0 records imported
                                    </div>
                                </div>

                                <div id="error-message" class="hidden p-3 bg-danger/10 border border-danger/20 rounded-md mt-5">
                                    <div class="flex items-start gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="text-danger flex-shrink-0 mt-0.5">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="12" x2="12" y1="8" y2="12"></line>
                                            <line x1="12" x2="12.01" y1="16" y2="16"></line>
                                        </svg>
                                        <div class="text-sm text-danger" id="error-text"></div>
                                    </div>
                                </div>

                                <div id="success-message" class="hidden p-3 bg-success/10 border border-success/20 rounded-md mt-5">
                                    <div class="flex items-start gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="text-success flex-shrink-0 mt-0.5">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                        </svg>
                                        <div class="text-sm text-success">
                                            <div class="font-semibold mb-1">Import Berhasil!</div>
                                            <div><span id="success-count" class="font-bold">0</span> data berhasil diimport ke database.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex gap-2 pt-2">
                                    <button type="submit" id="submit-btn"
                                        class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-4 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 bg-primary border-primary text-white hover:bg-opacity-90 disabled:opacity-70 disabled:cursor-not-allowed">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" class="mr-2">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="17 8 12 3 7 8"></polyline>
                                            <line x1="12" x2="12" y1="3" y2="15"></line>
                                        </svg>
                                        <span id="submit-text">Upload & Import</span>
                                    </button>
                                    <a href="/cyber-attacks" id="cancel-btn"
                                        class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-4 rounded-md font-medium cursor-pointer border-slate-300 text-slate-600 hover:bg-slate-100">
                                        Batal
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Collapsible Info -->
            <div class="box box--stacked">
                <button type="button" id="toggle-info" class="w-full p-5 flex items-center justify-between text-left hover:bg-slate-50 transition">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 16v-4"></path>
                            <path d="M12 8h.01"></path>
                        </svg>
                        <span class="font-medium text-sm">Lihat Format Kolom & Contoh Data</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="transition-transform text-slate-400" id="info-icon">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>
                <div id="info-content" class="hidden px-5 pb-5">
                    <div class="border-t pt-4 space-y-3">
                        <div>
                            <p class="text-sm font-medium text-slate-700 mb-2">Kolom yang diperlukan:</p>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                <code class="text-xs bg-slate-100 px-2 py-1 rounded">attack_id</code>
                                <code class="text-xs bg-slate-100 px-2 py-1 rounded">source_ip</code>
                                <code class="text-xs bg-slate-100 px-2 py-1 rounded">destination_ip</code>
                                <code class="text-xs bg-slate-100 px-2 py-1 rounded">source_country</code>
                                <code class="text-xs bg-slate-100 px-2 py-1 rounded">destination_country</code>
                                <code class="text-xs bg-slate-100 px-2 py-1 rounded">protocol</code>
                                <code class="text-xs bg-slate-100 px-2 py-1 rounded">source_port</code>
                                <code class="text-xs bg-slate-100 px-2 py-1 rounded">destination_port</code>
                                <code class="text-xs bg-slate-100 px-2 py-1 rounded">attack_type</code>
                                <code class="text-xs bg-slate-100 px-2 py-1 rounded">payload_size_bytes</code>
                                <code class="text-xs bg-slate-100 px-2 py-1 rounded">detection_label</code>
                                <code class="text-xs bg-slate-100 px-2 py-1 rounded">confidence_score</code>
                                <code class="text-xs bg-slate-100 px-2 py-1 rounded">ml_model</code>
                                <code class="text-xs bg-slate-100 px-2 py-1 rounded">affected_system</code>
                                <code class="text-xs bg-slate-100 px-2 py-1 rounded">port_type</code>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto border rounded-md mt-3">
                            <table class="w-full text-xs">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left border-r font-medium">attack_id</th>
                                        <th class="px-3 py-2 text-left border-r font-medium">source_ip</th>
                                        <th class="px-3 py-2 text-left border-r font-medium">attack_type</th>
                                        <th class="px-3 py-2 text-left border-r font-medium">protocol</th>
                                        <th class="px-3 py-2 text-left font-medium">confidence</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white">
                                    <tr class="border-t">
                                        <td class="px-3 py-2 border-r">ATK001</td>
                                        <td class="px-3 py-2 border-r font-mono">192.168.1.100</td>
                                        <td class="px-3 py-2 border-r">DDoS</td>
                                        <td class="px-3 py-2 border-r">TCP</td>
                                        <td class="px-3 py-2">0.95</td>
                                    </tr>
                                    <tr class="border-t bg-slate-50/30">
                                        <td class="px-3 py-2 border-r">ATK002</td>
                                        <td class="px-3 py-2 border-r font-mono">203.122.45.89</td>
                                        <td class="px-3 py-2 border-r">Malware</td>
                                        <td class="px-3 py-2 border-r">UDP</td>
                                        <td class="px-3 py-2">0.87</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/papaparse@5.4.1/papaparse.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
    <script>
        const fileInput = document.getElementById('file-input')
        const fileInfo = document.getElementById('file-info')
        const importForm = document.getElementById('import-form')
        const submitBtn = document.getElementById('submit-btn')
        const submitText = document.getElementById('submit-text')
        const cancelBtn = document.getElementById('cancel-btn')
        const errorMessage = document.getElementById('error-message')
        const errorText = document.getElementById('error-text')
        const successMessage = document.getElementById('success-message')
        const successCount = document.getElementById('success-count')
        const progressContainer = document.getElementById('progress-container')
        const progressBar = document.getElementById('progress-bar')
        const progressText = document.getElementById('progress-text')
        const progressDetail = document.getElementById('progress-detail')

        let importCancelled = false

        fileInput.addEventListener('change', (e) => {
            const file = e.target.files[0]
            if (file) {
                const sizeMB = (file.size / 1024 / 1024).toFixed(2)
                fileInfo.textContent = `📄 ${file.name} (${sizeMB} MB)`
                fileInfo.classList.add('text-slate-700', 'font-medium')
                
                if (file.size > 10 * 1024 * 1024) {
                    fileInfo.textContent += ' - ⚠️ Terlalu besar!'
                    fileInfo.classList.remove('text-slate-700')
                    fileInfo.classList.add('text-danger')
                }
            }
        })

        importForm.addEventListener('submit', async (e) => {
            e.preventDefault()

            const file = fileInput.files[0]
            if (!file) {
                showError('Silakan pilih file terlebih dahulu')
                return
            }

            if (file.size > 10 * 1024 * 1024) {
                showError('Ukuran file maksimal 10MB')
                return
            }

            // Reset state
            importCancelled = false
            errorMessage.classList.add('hidden')
            successMessage.classList.add('hidden')
            progressContainer.classList.remove('hidden')
            
            submitBtn.disabled = true
            submitText.textContent = 'Parsing file...'
            cancelBtn.textContent = 'Cancel'

            try {
                const records = await parseFile(file)
                
                if (records.length === 0) {
                    showError('File tidak memiliki data yang valid')
                    resetUI()
                    return
                }

                // Start importing one by one
                await importRecords(records)

            } catch (error) {
                console.error('Import error:', error)
                showError(error.message || 'Terjadi kesalahan saat memproses file')
                resetUI()
            }
        })

        // Cancel button handler
        cancelBtn.addEventListener('click', (e) => {
            if (submitBtn.disabled && !importCancelled) {
                e.preventDefault()
                importCancelled = true
                submitText.textContent = 'Cancelling...'
            }
        })

        async function parseFile(file) {
            return new Promise((resolve, reject) => {
                const ext = file.name.split('.').pop().toLowerCase()

                if (ext === 'csv') {
                    // Parse CSV
                    Papa.parse(file, {
                        header: true,
                        skipEmptyLines: true,
                        complete: (results) => {
                            if (results.errors.length > 0) {
                                reject(new Error('Error parsing CSV: ' + results.errors[0].message))
                                return
                            }
                            resolve(results.data)
                        },
                        error: (error) => reject(error)
                    })
                } else if (ext === 'xlsx' || ext === 'xls') {
                    // Parse Excel
                    const reader = new FileReader()
                    reader.onload = (e) => {
                        try {
                            const data = new Uint8Array(e.target.result)
                            const workbook = XLSX.read(data, { type: 'array' })
                            const firstSheet = workbook.Sheets[workbook.SheetNames[0]]
                            const records = XLSX.utils.sheet_to_json(firstSheet)
                            resolve(records)
                        } catch (err) {
                            reject(new Error('Error parsing Excel: ' + err.message))
                        }
                    }
                    reader.onerror = () => reject(new Error('Error reading file'))
                    reader.readAsArrayBuffer(file)
                } else {
                    reject(new Error('Format file tidak didukung'))
                }
            })
        }

        async function importRecords(records) {
            const total = records.length
            let success = 0
            let failed = 0

            submitText.textContent = `Importing (0/${total})...`

            for (let i = 0; i < records.length; i++) {
                if (importCancelled) {
                    showError(`Import dibatalkan. ${success} dari ${total} data berhasil diimport.`)
                    break
                }

                const record = records[i]
                const current = i + 1
                const percentage = Math.round((current / total) * 100)

                // Update progress bar
                progressBar.style.width = percentage + '%'
                progressText.textContent = percentage + '%'
                progressDetail.textContent = `${current} / ${total} records processed`
                submitText.textContent = `Importing (${current}/${total})...`

                try {
                    await insertSingleRecord(record)
                    success++
                } catch (error) {
                    console.error(`Failed to insert record ${current}:`, error)
                    failed++
                }

                // Small delay to prevent overwhelming the server
                await sleep(50)
            }

            // Import completed
            if (!importCancelled) {
                submitText.textContent = 'Import completed!'
                successCount.textContent = success
                successMessage.classList.remove('hidden')

                if (failed > 0) {
                    showError(`${failed} record gagal diimport dari total ${total}`)
                }

                if (typeof showToast === 'function') {
                    showToast('success', 'Berhasil', `${success} data berhasil diimport!`)
                }

                // Show view data button instead of auto-redirect
                submitBtn.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="mr-2">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                        <polyline points="10 17 15 12 10 7"></polyline>
                        <line x1="15" x2="3" y1="12" y2="12"></line>
                    </svg>
                    Lihat Data
                `
                submitBtn.disabled = false
                submitBtn.onclick = () => window.location.href = '/cyber-attacks'
                
                // Reset form for another import
                fileInput.value = ''
                fileInfo.textContent = ''
                cancelBtn.textContent = 'Import Lagi'
                cancelBtn.onclick = (e) => {
                    e.preventDefault()
                    window.location.reload()
                }
            } else {
                resetUI()
            }
        }

        async function insertSingleRecord(record) {
            const payload = {
                attack_id: record.attack_id || null,
                source_ip: record.source_ip || null,
                destination_ip: record.destination_ip || null,
                source_country: record.source_country || null,
                destination_country: record.destination_country || null,
                protocol: record.protocol || null,
                source_port: record.source_port ? parseInt(record.source_port) : null,
                destination_port: record.destination_port ? parseInt(record.destination_port) : null,
                attack_type: record.attack_type || null,
                payload_size_bytes: record.payload_size_bytes ? parseInt(record.payload_size_bytes) : null,
                detection_label: record.detection_label || null,
                confidence_score: record.confidence_score ? parseFloat(record.confidence_score) : null,
                ml_model: record.ml_model || null,
                affected_system: record.affected_system || null,
                port_type: record.port_type || null,
            }

            const response = await axios.post('/cyber-attacks/insert-single', payload, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })

            if (!response.data.status) {
                throw new Error(response.data.message || 'Insert failed')
            }

            return response.data
        }

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms))
        }

        function showError(message) {
            errorText.textContent = message
            errorMessage.classList.remove('hidden')
            
            if (typeof showToast === 'function') {
                showToast('error', 'Error', message)
            }
        }

        function resetUI() {
            submitBtn.disabled = false
            submitText.textContent = 'Upload & Import'
            cancelBtn.textContent = 'Batal'
            progressContainer.classList.add('hidden')
            progressBar.style.width = '0%'
            progressText.textContent = '0%'
            progressDetail.textContent = '0 / 0 records imported'
        }

        // Toggle info section
        const toggleInfo = document.getElementById('toggle-info')
        const infoContent = document.getElementById('info-content')
        const infoIcon = document.getElementById('info-icon')

        toggleInfo?.addEventListener('click', () => {
            infoContent.classList.toggle('hidden')
            if (infoContent.classList.contains('hidden')) {
                infoIcon.style.transform = 'rotate(0deg)'
            } else {
                infoIcon.style.transform = 'rotate(180deg)'
            }
        })
    </script>
@endpush
