@extends('layouts.app')
@section('title', 'Request Data Access')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-span-12 lg:col-span-8 lg:col-start-3">
        <div class="mb-8 text-center">
            <h2 class="text-xl font-semibold">Data Access Request</h2>
            <p class="text-sm text-slate-500">
                Submit a formal request to access restricted leak data.
            </p>
        </div>

        <form id="request-form" class="space-y-6" autocomplete="off">
            <div class="box box--stacked p-8">
                @if($log)
                    <div class="mb-6 p-4 bg-slate-50 border border-slate-200 rounded-md">
                        <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Target Reference</div>
                        <div class="text-sm font-medium text-slate-700 flex items-center gap-2">
                             <input type="hidden" name="leak_check_log_id" value="{{ $log->id }}">
                            <span class="truncate">{{ $log->keyword ?? 'N/A' }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-200 text-slate-600">ID: {{ $log->id }}</span>
                        </div>
                    </div>
                @endif

                <div class="space-y-5">
                     {{-- Generic Query if no log is attached --}}
                     @if(!$log)
                        <div>
                            <label class="form-label">Search Query / Subject</label>
                            <input type="text" name="query" class="form-control" placeholder="e.g. leaked_email@example.com" required>
                        </div>
                    @else
                        <input type="hidden" name="query" value="{{ $log->keyword }}">
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" placeholder="Your Full Name" value="{{ Auth::user()->name }}" required>
                        </div>
                        <div>
                            <label class="form-label">Official Email</label>
                            <input type="email" name="email" class="form-control" placeholder="name@agency.gov" value="{{ Auth::user()->email }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label">Department / Unit</label>
                            <input type="text" name="department" class="form-control" placeholder="e.g. Cyber Intelligence">
                        </div>
                        <div>
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone_number" class="form-control" placeholder="+62...">
                        </div>
                    </div>
                     
                    <div>
                        <label class="form-label">Position / Status</label>
                        <input type="text" name="requester_status" class="form-control" placeholder="e.g. Senior Analyst">
                    </div>

                    <div>
                        <label class="form-label">Justification / Reason</label>
                        <textarea name="reason" rows="4" class="form-control" placeholder="Please provide a detailed reason for accessing this restricted data..." required></textarea>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" id="btn-submit-request" class="btn btn-primary w-full md:w-auto">
                        <span class="btn-text">Submit Request</span>
                        <svg id="btn-spinner" class="hidden w-4 h-4 animate-spin ml-2" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" opacity="0.25" />
                            <path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="4" />
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const form = document.getElementById('request-form');
        const submitBtn = document.getElementById('btn-submit-request');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnSpinner = document.getElementById('btn-spinner');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            submitBtn.disabled = true;
            btnText.textContent = 'Submitting...';
            btnSpinner.classList.remove('hidden');

            // Remove previous error styles
            form.querySelectorAll('.border-danger').forEach(el => el.classList.remove('border-danger'));

            try {
                const formData = new FormData(form);
                const response = await axios.post('/leak-request', Object.fromEntries(formData));

                if (typeof showToast === 'function') {
                    showToast('success', 'Request Sent', response.data.message);
                } else {
                    alert('Success: ' + response.data.message);
                }
                
                setTimeout(() => {
                    // Redirect or Reset
                    window.location.href = '/leak-request'; 
                }, 1500);

            } catch (error) {
                console.error(error);
                if (error.response?.status === 422) {
                    const errors = error.response.data.errors;
                    const messages = Object.values(errors).flat().join('<br>');
                    
                    if (typeof showToast === 'function') {
                        showToast('failed', 'Validation Error', messages);
                    } else {
                        alert('Validation Error: ' + messages);
                    }

                     Object.keys(errors).forEach(key => {
                        const input = form.querySelector(`[name="${key}"]`);
                        if(input) input.classList.add('border-danger');
                    });
                } else {
                     if (typeof showToast === 'function') {
                        showToast('failed', 'Error', 'System error occurred.');
                    } else {
                        alert('Error submitting request.');
                    }
                }
            } finally {
                submitBtn.disabled = false;
                btnText.textContent = 'Submit Request';
                btnSpinner.classList.add('hidden');
            }
        });
    </script>
@endpush
