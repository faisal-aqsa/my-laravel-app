@extends('back.layout.pages-layout')

@section('pageTitle', isset($pageTitle) ? $pageTitle : '')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Settings Page</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Settings Page</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <x-form-alerts></x-form-alerts>
                <!-- <div class="card-header">
                    <a href="{{ route('admin.add-setting') }}" class="btn btn-primary waves-effect waves-light">Create Setting</a>
                </div> -->
                <div class="card-body">

                    <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">

                        <div class="row">
                            <div class="col-sm-12">
                                <table id="datatable"
                                    class="table table-bordered dt-responsive nowrap w-100 dataTable no-footer dtr-inline"
                                    aria-describedby="datatable_info" style="width: 1169px;">
                                    <thead>
                                        <tr>
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="datatable"
                                                rowspan="1" colspan="1" style="width: 186.2px;"
                                                aria-sort="ascending"
                                                aria-label="Name: activate to sort column descending">Sr No</th>
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="datatable"
                                                rowspan="1" colspan="1" style="width: 186.2px;"
                                                aria-sort="ascending"
                                                aria-label="Name: activate to sort column descending">Name</th>
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="datatable"
                                                rowspan="1" colspan="1" style="width: 186.2px;"
                                                aria-sort="ascending"
                                                aria-label="Name: activate to sort column descending">Phone Number</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 279.2px;"
                                                aria-label="Position: activate to sort column ascending">Email</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 136.2px;"
                                                aria-label="Office: activate to sort column ascending">Address</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 136.2px;"
                                                aria-label="Office: activate to sort column ascending">Gst No</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 136.2px;"
                                                aria-label="Office: activate to sort column ascending">Website Url</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 136.2px;"
                                                aria-label="Office: activate to sort column ascending">SGST</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 136.2px;"
                                                aria-label="Office: activate to sort column ascending">CGST</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 136.2px;"
                                                aria-label="Office: activate to sort column ascending">IGST</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 136.2px;"
                                                aria-label="Office: activate to sort column ascending">Signature</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                colspan="1" style="width: 129.2px;"
                                                aria-label="Start date: activate to sort column ascending">Action
                                            </th>
                                        </tr>
                                    </thead>


                                    <tbody>
                                        @forelse ($settings as $key => $setting)
                                            <tr class="odd">
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="dtr-control sorting_1" tabindex="0">{{ $setting->name ?? 'N/A' }}</td>
                                                <td>{{ $setting->phone }}</td>
                                                <td>{{ $setting->email }}</td>
                                                <td>{{ $setting->address }}</td>
                                                <td>{{ $setting->gst_no }}</td>
                                                <td>{{ $setting->website_url }}</td>
                                                <td>{{ $setting->sgst }}</td>
                                                <td>{{ $setting->cgst }}</td>
                                                <td>{{ $setting->igst }}</td>
                                                <td>
                                                    @if($setting->signature)
                                                        <a href="{{ asset('storage/' . $setting->signature) }}" target="_blank" data-bs-toggle="tooltip" title="Click to view full size">
                                                            <img src="{{ asset('storage/' . $setting->signature) }}" 
                                                                alt="Signature" 
                                                                style="max-width: 100px; max-height: 60px; border: 1px solid #ddd; padding: 2px; border-radius: 4px; background: #f8f9fa;">
                                                        </a>
                                                        <br>
                                                        <!-- <small class="text-muted">{{ basename($setting->signature) }}</small> -->
                                                    @else
                                                        <span class="badge bg-secondary">No signature</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.edit-setting', ['id' => $setting->id]) }}" class="text-primary">
                                                        <i class="mdi mdi-square-edit-outline" style="font-size: 25px"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty

                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div>

@endsection
