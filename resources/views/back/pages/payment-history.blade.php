@extends('back.layout.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Delivery Challans')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Payment Histories</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Payment Histories</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <x-form-alerts></x-form-alerts>
                <div class="card-body">
                    <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="datatable"
                                    class="table table-bordered dt-responsive nowrap w-100 dataTable no-footer dtr-inline"
                                    aria-describedby="datatable_info">
                                    <thead>
                                        <tr>
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="datatable"
                                                rowspan="1" colspan="1" style="width: 50px;"
                                                aria-sort="ascending"
                                                aria-label="SR No: activate to sort column descending">Sr No</th>
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="datatable"
                                                rowspan="1" colspan="1" style="width: 150px;"
                                                aria-sort="ascending"
                                                aria-label="Client Name: activate to sort column descending">Client Name</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 120px;"
                                                aria-label="Invoice No: activate to sort column ascending">Invoice No</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 100px;"
                                                aria-label="Payment Date: activate to sort column ascending">Payment Date</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 120px;"
                                                aria-label="Payment Amount: activate to sort column ascending">Payment Amount</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 120px;"
                                                aria-label="Payment Type: activate to sort column ascending">Payment Type</th>
                                            <!-- <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 150px;"
                                                aria-label="Previous Balance: activate to sort column ascending">Previous Balance
                                            </th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 150px;"
                                                aria-label="New Balance: activate to sort column ascending">New Balance
                                            </th> -->
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 150px;"
                                                aria-label="Notes: activate to sort column ascending">Notes
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($payments as $key => $payment)
                                            <tr class="odd" data-payment-id="{{ $payment->id }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="dtr-control">
                                                    {{ $payment->invoice->getClient->name ?? 'N/A' }}
                                                </td>
                                                <td>{{ $payment->invoice->invoice_number ?? 'N/A' }}</td>
                                                <td>
                                                    {{ $payment->payment_date->format('d-m-Y') }}
                                                    <br>
                                                    <small class="text-muted">{{ $payment->created_at->format('h:i A') }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success fs-6">
                                                        ₹{{ number_format($payment->amount, 2) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $typeClass = 'badge bg-primary';
                                                        switch($payment->payment_type) {
                                                            case 'cash': $typeClass = 'badge bg-success'; break;
                                                            case 'bank_transfer': $typeClass = 'badge bg-info'; break;
                                                            case 'cheque': $typeClass = 'badge bg-warning'; break;
                                                            case 'online': $typeClass = 'badge bg-purple'; break;
                                                            case 'other': $typeClass = 'badge bg-secondary'; break;
                                                        }
                                                    @endphp
                                                    <span class="{{ $typeClass }}">
                                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}
                                                    </span>
                                                </td>
                                                <!-- <td class="text-danger">
                                                    ₹{{ number_format($payment->previous_balance, 2) }}
                                                </td>
                                                <td class="text-success">
                                                    ₹{{ number_format($payment->new_balance, 2) }}
                                                </td> -->
                                                <td>
                                                    @if($payment->notes)
                                                        <span class="d-inline-block text-truncate" style="max-width: 150px;" 
                                                            title="{{ $payment->notes }}">
                                                            {{ $payment->notes }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">No notes</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">
                                                    No Payment History found
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
