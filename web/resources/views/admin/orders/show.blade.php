@extends('admin.layouts.app')

@section('title', 'Order #' . $order->order_id)

@php
    $statusBadges = [
        'pending' => 'badge-warning',
        'verified' => 'badge-info',
        'delivered' => 'badge-success',
        'rejected' => 'badge-danger',
    ];
    $statusOrder = [
        'pending' => 1,
        'verified' => 2,
        'delivered' => 3,
        'rejected' => 4,
    ];
    $currentStatus = $order->status;
    $isRejected = $currentStatus === 'rejected';
    $currentStep = $isRejected ? 4 : ($statusOrder[$currentStatus] ?? 1);
@endphp

@section('content')
    <div class="card">
        <div class="card-body">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:1rem; margin-bottom:0.5rem;">
                <div>
                    <h1 style="font-size:1.5rem; font-weight:700;">Order #{{ $order->order_id }}</h1>
                    <p style="color:var(--text-secondary); font-size:0.875rem; margin-top:0.25rem;">Placed {{ $order->created_at->format('F d, Y h:i A') }}</p>
                </div>
                <div style="display:flex; gap:0.5rem; align-items:center;">
                    <span class="badge {{ $statusBadges[$currentStatus] ?? 'badge-secondary' }}" style="font-size:0.875rem; padding:0.375rem 0.75rem;">{{ ucfirst($currentStatus) }}</span>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline">&larr; Back</a>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:1.5rem;">
                <div class="card" style="box-shadow:none;">
                    <div class="card-header"><h3>Product</h3></div>
                    <div class="card-body">
                        @if($order->product)
                            <div style="display:flex; gap:1rem; align-items:center;">
                                @if($order->product->image)
                                    <img src="{{ asset('storage/products/' . $order->product->image) }}" class="thumb thumb-lg">
                                @endif
                                <div>
                                    <div style="font-weight:600; font-size:0.9375rem;">{{ $order->product->title }}</div>
                                    <div style="font-size:0.8125rem; color:var(--text-secondary);">{{ Str::limit($order->product->subtitle, 60) }}</div>
                                </div>
                            </div>
                        @else
                            <p style="color:var(--text-secondary);">Product unavailable</p>
                        @endif
                    </div>
                </div>

                <div class="card" style="box-shadow:none;">
                    <div class="card-header"><h3>Customer</h3></div>
                    <div class="card-body">
                        <div class="form-group" style="margin-bottom:0.75rem;">
                            <label style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em; color:var(--text-secondary);">Email</label>
                            <div style="font-size:0.9375rem;">{{ $order->customer_email }}</div>
                        </div>
                        <div class="form-group" style="margin-bottom:0.75rem;">
                            <label style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em; color:var(--text-secondary);">Sender Number</label>
                            <div style="font-size:0.9375rem;">{{ $order->sender_number }}</div>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em; color:var(--text-secondary);">Transaction ID</label>
                            <div style="font-size:0.9375rem; font-family:monospace;">{{ $order->transaction_id }}</div>
                        </div>
                    </div>
                </div>

                <div class="card" style="box-shadow:none;">
                    <div class="card-header"><h3>Payment</h3></div>
                    <div class="card-body">
                        <div class="form-group" style="margin-bottom:0.75rem;">
                            <label style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em; color:var(--text-secondary);">Method</label>
                            <div><span class="badge badge-secondary">{{ ucfirst($order->payment_method) }}</span></div>
                        </div>
                        <div class="form-group" style="margin-bottom:0.75rem;">
                            <label style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em; color:var(--text-secondary);">Amount</label>
                            <div style="font-size:1.25rem; font-weight:700;">{{ number_format($order->amount, 2) }}</div>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.04em; color:var(--text-secondary);">Date</label>
                            <div style="font-size:0.9375rem;">{{ $order->created_at->format('F d, Y h:i A') }}</div>
                        </div>
                    </div>
                </div>

                <div class="card" style="box-shadow:none;">
                    <div class="card-header"><h3>Status</h3></div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-step {{ $currentStep >= 1 && !$isRejected ? 'completed' : '' }} {{ $currentStatus === 'rejected' && $currentStep == 1 ? '' : '' }} {{ $currentStatus === 'pending' ? 'active' : '' }} {{ $isRejected && $currentStep == 1 ? '' : '' }}">
                                <div class="timeline-dot">1</div>
                                <div class="timeline-label">Pending</div>
                            </div>
                            <div class="timeline-step {{ $currentStep >= 2 && !$isRejected ? 'completed' : '' }} {{ $currentStatus === 'verified' ? 'active' : '' }} {{ $isRejected ? '' : '' }}">
                                <div class="timeline-dot">2</div>
                                <div class="timeline-label">Verified</div>
                            </div>
                            <div class="timeline-step {{ $currentStep >= 3 && !$isRejected ? 'completed' : '' }} {{ $currentStatus === 'delivered' ? 'active' : '' }}">
                                <div class="timeline-dot">3</div>
                                <div class="timeline-label">Delivered</div>
                            </div>
                            <div class="timeline-step {{ $isRejected ? 'active rejected' : '' }}">
                                <div class="timeline-dot">&#10007;</div>
                                <div class="timeline-label">Rejected</div>
                            </div>
                        </div>
                        @if($currentStatus === 'verified')
                            <p style="font-size:0.8125rem; color:var(--text-secondary);">Verified on {{ $order->confirmed_at ? $order->confirmed_at->format('M d, Y h:i A') : 'N/A' }}</p>
                        @endif
                        @if($currentStatus === 'delivered')
                            <p style="font-size:0.8125rem; color:var(--text-secondary);">Delivered on {{ $order->delivered_at ? $order->delivered_at->format('M d, Y h:i A') : 'N/A' }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div style="margin-top:1rem;">
                @if($currentStatus === 'pending')
                    <div class="btn-group">
                        <form action="{{ route('admin.orders.verify', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">&#10003; Verify Payment</button>
                        </form>
                        <form action="{{ route('admin.orders.reject', $order) }}" method="POST" style="display:flex; gap:0.5rem; align-items:center;">
                            @csrf
                            <input type="text" name="reason" placeholder="Rejection reason (shown to customer)" class="form-control" maxlength="500" style="max-width:300px;">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Reject this order?')">&#10007; Reject</button>
                        </form>
                    </div>
                @endif
                @if($currentStatus === 'verified')
                    <div class="btn-group">
                        <form action="{{ route('admin.orders.deliver', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success">&#10003; Mark Delivered</button>
                        </form>
                        <form action="{{ route('admin.orders.reject', $order) }}" method="POST" style="display:flex; gap:0.5rem; align-items:center;">
                            @csrf
                            <input type="text" name="reason" placeholder="Rejection reason (shown to customer)" class="form-control" maxlength="500" style="max-width:300px;">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Reject this order?')">&#10007; Reject</button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="card" style="box-shadow:none; margin-top:1.5rem;">
                <div class="card-header"><h3>Admin Notes</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <textarea id="admin_notes" class="form-control" rows="3">{{ $order->admin_notes }}</textarea>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" onclick="saveNotes()">Save Notes</button>
                </div>
            </div>

            <div class="card" style="box-shadow:none; margin-top:1.5rem;">
                <div class="card-header"><h3>Conversation & Notes</h3></div>
                <div class="card-body">
                    <div id="notesList">
                        @forelse($order->notes as $note)
                            <div class="note-card">
                                <div class="note-card-header">
                                    <span class="author">{{ $note->created_by }}</span>
                                    <span class="date">{{ $note->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                                <p>{{ $note->note }}</p>
                            </div>
                        @empty
                            <p style="color:var(--text-secondary); font-size:0.875rem;" id="noNotes">No notes yet. Add a note below.</p>
                        @endforelse
                    </div>
                    <div style="display:flex; gap:0.5rem; margin-top:1rem;">
                        <input type="text" id="newNote" class="form-control" placeholder="Add a note..." style="flex:1;">
                        <button type="button" class="btn btn-primary" onclick="addNote()">Add Note</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function saveNotes() {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const admin_notes = document.getElementById('admin_notes').value;
        fetch('{{ route("admin.orders.update-notes", $order) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ admin_notes })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) alert('Notes saved successfully.');
        });
    }

    function addNote() {
        const input = document.getElementById('newNote');
        const note = input.value.trim();
        if (!note) return;

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        fetch('{{ route("admin.orders.add-note", $order) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ note })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('noNotes')?.remove();
                const list = document.getElementById('notesList');
                const div = document.createElement('div');
                div.className = 'note-card';
                div.innerHTML = `
                    <div class="note-card-header">
                        <span class="author">${data.note.created_by}</span>
                        <span class="date">${new Date().toLocaleString()}</span>
                    </div>
                    <p>${note}</p>`;
                list.prepend(div);
                input.value = '';
            }
        });
    }
</script>
@endpush
