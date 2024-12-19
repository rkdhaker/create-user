<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-template="vertical-menu-template">
<head>
    <!-- Meta Tags for Character Set and Responsive Design -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <!-- Page Title -->
    <title>{{ env('APP_NAME')}}</title>

    <!-- Bootstrap CSS (for styling) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Toastr CSS for Notification Styling -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Custom User Creation Page Styles -->
    <link rel="stylesheet" href="{{ asset('css/user-create.css') }}">
</head>

<body>
    <!-- Main Content Container -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="pull-left fw-bold py-3 mb-4">User Create</h4>

        <!-- Card for User Creation Form -->
        <div class="card">
            <div class="card-body">
                
                <!-- Error Message Section -->
                <p class="error text-danger" id="error_message"></p>

                <!-- User Create Form -->
                <form autocomplete="off" id="userCreate" enctype="multipart/form-data" method="post" class="needs-validation" novalidate>
                    @csrf
                    @method('post')

                    <!-- Form Fields Row -->
                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-md-4 mt-3">
                            <label for="name" class="form-label">Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="name" maxlength="255" value="{{ old('name') }}" placeholder="Please Enter Name" required>
                        </div>

                        <!-- Email Field -->
                        <div class="col-md-4 mt-3">
                            <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" id="email" maxlength="60" value="{{ old('email') }}" placeholder="Please Enter Email" required>
                        </div>

                        <!-- Phone Field -->
                        <div class="col-md-4 mt-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" id="phone" value="{{ old('phone') }}" placeholder="Please Enter Phone Number" pattern="^[789]\d{9}$" maxlength="10">
                            <small class="form-text text-muted">Enter Indian phone number (starts with 7, 8, or 9, followed by 9 digits).</small>
                        </div>

                        <!-- Description Field -->
                        <div class="col-md-4 mt-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="description" required rows="3" placeholder="Enter a brief description">{{ old('description') }}</textarea>
                        </div>

                        <!-- Role ID Field -->
                        <div class="col-md-4 mt-3">
                            <label for="role_id" class="form-label">Role<span class="text-danger">*</span></label>
                            <select class="form-control" name="role_id" id="role_id" required>
                                <option value="">Select Role</option>
                                <!-- Dynamically Populate Roles -->
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Profile Image Field -->
                        <div class="col-md-4 mt-3">
                            <label for="profile_image" class="form-label">Profile Image</label>
                            <input type="file" class="form-control" name="profile_image" id="profile_image" accept="image/*">
                        </div>

                        <!-- Submit Button -->
                        <div class="col-md-12 mt-4">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-reload me-0 me-sm-1 ti-xs"></i> Create
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <!-- Card for User Listing Table -->
        <div class="card mt-5">
            <h5 class="card-header">Users Listing</h5>
            <div class="card-datatable table-responsive">
                <table class="dt-responsive table data-table">
                    <thead>
                        <tr>
                            <th>Basic Detail</th>
                            <th>Role</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- JS Scripts Section -->

    <!-- Core JS Libraries -->
    <script>var route = @json(url('api/submit-user')); </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@1.7.9/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.0/js/toastr.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.js"></script>
    <script src="{{ asset('js/user-create.js') }}"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>

    <script type="text/javascript">
        // Initialize DataTable for users listing
        var dataTable = $('.data-table').DataTable({
            "processing": true,
            "serverSide": true,
            "retrieve": true,
            "searching": false,
            "language": {
                processing: '<div class="page_loader"><div class="loader_img"><div class="spinner-border" role="status"><span class="visually-hidden"></span></div></div></div>'
            },
            "order": [],
            lengthMenu: [20, 50, 100, 500, 1000, 5000],
            ajax: {
                url: "{{ url('api/get-users') }}",
            },
            columns: [
                { data: 'user', name: 'user' },
                { data: 'role', name: 'role' },
                { data: 'date', name: 'date' }
            ],
            "columnDefs": [{
                "targets": [0],
                "visible": true,
                "searchable": false,
                "orderable": false,
                "sorting": false,
            }]
        });
    </script>
</body>
</html>
