<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Transfer Rows</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
    .custom-select {
        background-color: #f8f9fa;
        border: 2px solid #6c757d;
        color: #495057;
    }
</style>
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <h4>เลือกข้อมูล</h4>
                <div class="mb-3">
                    <label for="province_id" class="form-label">Province</label>
                    <select class="form-select custom-select" id="province_id" name="province_id">
                        <option value="0" selected>Select province</option>
                    </select>
                </div>
                <!-- <button id="addItem" class="btn btn-primary mt-3">เพิ่มไปยังตาราง</button> -->
            </div>
            <div class="col-md-8">
                <h4>ตารางข้อมูล</h4>
                <table id="dataTable" class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Id</th>
                            <th>Province</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const endpoint = "http://localhost/thailand_db/api/";

        function lovProvinces() {
            $.ajax({
                type: "POST",
                url: endpoint + "v1/provinces/get.php",
                dataType: "json",
                success: function(response) {
                    if (response.data.length > 0) {
                        let select = `<option value="0" selected>Select province</option>`;
                        response.data.forEach((province) => {
                            select += `<option value="${province.id}">${province.name_in_thai}</option>`;
                        });
                        $("#province_id").html(select);
                    }
                }
            });
        }

        $(document).ready(function() {
            lovProvinces();
            $('#addItem').click(function() {
                let province_id = $('#province_id').val();
                let province_name = $('#province_id option:selected').text();

                if (province_id && province_id !== "0") {
                    if ($('#row-' + province_id).length === 0) {
                        $('#dataTable tbody').append(`
                            <tr id="row-${province_id}">
                                <td>${province_id}</td>
                                <td>${province_name}</td>
                                <td><button class="btn btn-danger btn-sm removeRow">ลบ</button></td>
                            </tr>
                        `);
                    }
                } else {
                    alert('กรุณาเลือกรายการ');
                }
            });

            $(document).on('click', '.removeRow', function() {
                $(this).closest('tr').remove();
            });

            // event handlers
            $("#province_id").change(function (e) { 
                e.preventDefault();
                
                let province_id = $('#province_id').val();
                let province_name = $('#province_id option:selected').text();
                // province_id เรียก api อื่นๆ เพื่อดึง ฟิลล์ที่ต้องการเพิ่ม
                if (province_id && province_id !== "0") {
                    if ($('#row-' + province_id).length === 0) {
                        $('#dataTable tbody').append(`
                            <tr 
                                id="row-${province_id}"
                                data-province_id="${province_id}"
                                data-province_name="${province_name}"
                            >
                                <td>${province_id}</td>
                                <td>${province_name}</td>
                                <td><button class="btn btn-danger btn-sm removeRow">ลบ</button></td>
                            </tr>
                        `);
                    }
                } else {
                    alert('กรุณาเลือกรายการ');
                }
            });
        });
    </script>
</body>

</html>