<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel 8 Ajax</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet">
</head>

<body>
    <div class="table-auto-responsive">
        <main class="container border my-5">
            <div class="container">
                <h1>List Data</h1>
                <div class="col-md-3">
                    <a class="btn btn-success my-1" href="javascript:void(0)" id="createNewStudent">Tambah Data</a>
                </div>
                <br>
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Thumbnail</th>
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
                    <form id="studentForm" name="studentForm" class="form-horizontal">
                        <input type="hidden" name="student_id" id="student_id">
                        <div class="form-group">
                            <label class="control-label">Nama</label>
                            <input class="form-control" id="name" name="name" placeholder="Masukkan Nama"
                                value="" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Email</label>
                            <input class="form-control" id="email" name="email" placeholder="Masukkan Email"
                                value="" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="saveBtn"
                                onClick="window.location.reload();" value="Create">Save</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal"
                                aria-label="Close">Batal</button>
                        </div>
                    </form>
                </div>
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
                        ajax: "{{ route('students.index') }}",
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'name',
                                name: 'name'
                            },
                            {
                                data: 'email',
                                name: 'email'
                            },
                            {
                                data: 'action',
                                name: 'action'
                            },
                        ]
                    });

                    //menampilkan form create
                    $("#createNewStudent").click(function() {
                        $("#student_id").val('');
                        $("#modalHeading").html('Tambah Data')
                        $("#studentForm").trigger("reset");
                        $("#ajaxModal").modal("show");
                    });

                    //save data
                    $("#saveBtn").click(function(e) {
                        e.preventDefault();
                        $(this).html('Save');

                        $.ajax({
                            data: $("#studentForm").serialize(),
                            url: "{{ route('students.store') }}",
                            type: "POST",
                            dataType: 'json',
                            success: function(data) {
                                $("#studentForm").trigger("reset");
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
                    $('body').on('click', '.deleteStudent', function() {
                            if (confirm("Are you sure want to delete?")) {
                                var student_id = $(this).data("id");
                                $.ajax({
                                    type: "DELETE",
                                    url: "{{ route('students.store') }}" + '/' + student_id,
                                    success: function(data) {
                                        table.draw();
                                    },
                                }).done(function(msg) {
                                    if (msg.error == 0) {
                                        //$('.sucess-status-update').html(msg.message);
                                        alert(msg.message);
                                    } else {
                                        alert(msg.message);
                                        //$('.error-favourite-message').html(msg.message);
                                    }
                                });
                            } else {
                                return false;
                            }
                        });

                        // //delete data without confirm
                        // $('body').on('click','.deleteStudent',function(){
                        //     var student_id = $(this).data("id");
                        // // confirm("Are you sure want to delete?");

                        //     $.ajax({
                        //         type: "DELETE",
                        //         url: "{{ route('students.store') }}"+'/'+student_id,
                        //         success:function(data){
                        //             table.draw();
                        //         },
                        //         error: function(data){
                        //             console.log('Error:', data);
                        //         }
                        //     });
                        // });

                        //edit data
                        $('body').on('click', '.editStudent', function() {
                            var student_id = $(this).data('id');
                            $.get("{{ route('students.index') }}" + "/" + student_id + "/edit", function(
                                data) {
                                $("#modalHeading").html('Edit Data');
                                $("#ajaxModal").modal('show');
                                $("#student_id").val(data.id);
                                $("#name").val(data.name);
                                $("#email").val(data.email);
                            })
                        });
                    });
    </script>
</body>

</html>
