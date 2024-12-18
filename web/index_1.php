<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>thailand_db</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function() {
            let endpoint = "http://localhost/thailand_db/api/";
            let loading = false;
            let offset = 0;
            const limit = 10;

            function loadProvinces() {
                if (loading) return;
                loading = true;

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
                            $("#provinces").html(select);
                            offset += limit;
                        }
                        loading = false;
                    }
                });
            }

            function loadDistricts(provinceId) {
                $.ajax({
                    type: "POST",
                    url: endpoint + "v1/districts/get.php",
                    data: {
                        province_id: provinceId
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.data.length > 0) {
                            let select = `<option value="0" selected>Select district</option>`;
                            response.data.forEach((district) => {
                                select += `<option value="${district.id}">${district.name_in_thai}</option>`;
                            });
                            $("#districts").html(select);
                        } else {
                            $("#districts").html('<option value="0" selected>Select district</option>');
                        }
                    }
                });
            }

            function loadSubdistricts(districtId) {
                $.ajax({
                    type: "POST",
                    url: endpoint + "v1/subdistricts/get.php",
                    data: {
                        district_id: districtId
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.data.length > 0) {
                            let select = `<option value="0" selected>Select subdistrict</option>`;
                            response.data.forEach((subdistrict) => {
                                select += `<option value="${subdistrict.id}">${subdistrict.name_in_thai}</option>`;
                            });
                            $("#subdistricts").html(select);
                        } else {
                            $("#subdistricts").html('<option value="0" selected>Select subdistrict</option>');
                        }
                    }
                });
            }

            $(window).on("scroll", function() {
                if ($(window).scrollTop() + $(window).height() == $(document).height()) {
                    loadProvinces();
                }
            });

            loadProvinces();

            $("#provinces").change(function() {
                let provinceId = $(this).val();
                if (provinceId > 0) {
                    loadDistricts(provinceId);
                    $("#districts").html('<option value="0" selected>Select district</option>');
                    $("#subdistricts").html('<option value="0" selected>Select subdistrict</option>');
                } else {
                    $("#districts").html('<option value="0" selected>Select district</option>');
                    $("#subdistricts").html('<option value="0" selected>Select subdistrict</option>');
                }
            });

            $("#districts").change(function() {
                let districtId = $(this).val();
                if (districtId > 0) {
                    loadSubdistricts(districtId);
                } else {
                    $("#subdistricts").html('<option value="0" selected>Select subdistrict</option>');
                }
            });

            $("#submitForm").submit(function(e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: endpoint + "v1/all/get.php",
                    data: {
                        province_id: $("#provinces").val(),
                        district_id: $("#districts").val(),
                        subdistrict_id: $("#subdistricts").val(),
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response?.data.length > 0) {
                            $("#show").html(`
                                <tr>
                                    <td>${response.data[0].row_number}</td>
                                    <td>${response.data[0].province_name}</td>
                                    <td>${response.data[0].district_name}</td>
                                    <td>${response.data[0].subdistrict_name}</td>
                                </tr>
                            `);
                        }
                    }
                });
            });
        });
    </script>

    <style>
        .scrollable-select {
            max-height: 200px;
            overflow-y: auto;
        }
    </style>

</head>

<body>
    <div class="container mt-4">
        <form id="submitForm">
            <div class="mb-3">
                <label for="provinces" class="form-label">Province</label>
                <select class="form-select scrollable-select" id="provinces" name="province_id">
                    <option value="0" selected>Select province</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="districts" class="form-label">District</label>
                <select class="form-select" id="districts" name="district_id">
                    <option value="0" selected>Select district</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="subdistricts" class="form-label">Subdistrict</label>
                <select class="form-select" id="subdistricts" name="subdistrict_id">
                    <option value="0" selected>Select subdistrict</option>
                </select>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>

        <br>

        <table id="dataTable" class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Province</th>
                    <th>District</th>
                    <th>Subdistrict</th>
                </tr>
            </thead>
            <tbody id="show">

            </tbody>
        </table>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>