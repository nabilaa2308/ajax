<!DOCTYPE html>
<html>

<head>
    <title>Laravel dynamic</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css">
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
</head>

<body>
    <div class="table-auto-responsive">
        <main class="container border my-5">
            <div class="container">
                <h1>List Data</h1>
                <br>
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </main>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="ajaxModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalHeading"></h5>
                </div>
                <div class="modal-body">
                    <form id="dynamicForm" name="dynamicForm" class="form-horizontal">
                        <input type="hidden" name="moreFields[0][id]" id="data_id">
                        <div class="form-group">
                            <label class="control-label">Title</label>
                            <input class="form-control" id="title" name="moreFields[0][title]"
                                placeholder="Masukkan Nama" value="" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="Create">Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                aria-label="Close">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="card mt-3">
            <div class="card-header">
                <h2>Tambah Data</h2>
            </div>
            <div class="card-body">
                <form action="{{ url('dynamic-form') }}" method="POST">
                    @csrf
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (Session::has('success'))
                        <div class="alert alert-success text-center">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            <p>{{ Session::get('success') }}</p>
                        </div>
                    @endif
                    <table class="table table-bordered" id="dynamicAddRemove">
                        <tr>
                            <th>Title</th>
                            <th>Action</th>
                        </tr>
                        <tr>
                            <td><input type="text" name="moreFields[0][title]" placeholder="Enter title"
                                    class="form-control" /></td>
                            <td><button type="button" name="add" id="add-btn" class="btn btn-success">Add
                                    More</button></td>
                        </tr>
                    </table>
                    <button type="submit" id="simpanBtn" class="btn btn-success">Save</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.33/dist/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"
        integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous">
    </script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        var i = 0;
        $("#add-btn").click(function() {
            ++i;
            $("#dynamicAddRemove").append('<tr><td><input type="text" name="moreFields[' + i +
                '][title]" placeholder="Enter title" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>'
            );
        });
        $(document).on('click', '.remove-tr', function() {
            $(this).parents('tr').remove();
        });
    </script>
    <script type="text/javascript">
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // menampilkan data
            var table = $(".data-table").DataTable({
                serverSide: true,
                processing: true,
                ajax: "{{ route('dynamic-form.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ]
            });

            //save data input
            $("#saveBtn").click(function(e) {
                e.preventDefault();
                $(this).html('Save');

                $.ajax({
                    data: $("#dynamicForm").serialize(),
                    url: "{{ route('dynamic-form.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $("#dynamicForm").trigger("reset");
                        $('#ajaxModal').modal('hide');
                        table.draw();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $("#saveBtn").html('error');
                    }
                });
            });

            //delete data with confirm
            $('body').on('click', '.deleteDynamic', function() {
                Swal.fire({
                    title: 'Apakah Kamu Yakin?',
                    text: "ingin menghapus data ini!",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: 'TIDAK',
                    confirmButtonText: 'YA, HAPUS!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // if (confirm("Are you sure want to delete?")) {
                        var data_id = $(this).data("id");
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('dynamic-form.store') }}" + '/' + data_id,
                            success: function(data) {
                                Swal.fire(
                                    'Data Berhasil Di Hapus',
                                    '',
                                    'success'
                                )
                                var oTable = $('#data-table');
                                oTable.DataTable().ajax.reload();
                            },
                            error: function(data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                });
            });

            //edit data
            $('body').on('click', '.editDynamic', function() {
                var data_id = $(this).data('id');
                $.get("{{ route('dynamic-form.index') }}" + "/" + data_id + "/edit", function(
                    data) {
                    $("#modalHeading").html('Edit Data');
                    $("#ajaxModal").modal('show');
                    $("#data_id").val(data.id);
                    $("#title").val(data.title);
                });
            });
        });
    </script>
</body>

</html>
