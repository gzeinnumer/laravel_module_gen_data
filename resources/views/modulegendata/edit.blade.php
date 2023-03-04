@include('layout.top')
<div class="container">
    <div class="row mt-3">
        <div class="col">
            <div class="row">
                <div class="col-md-6 offset-md-3 text-center">Update Data Module Gendata</div>
            </div>
            <form name="dFormEdit" action="{{ route('modulegendata.updatePerform') }}" method="POST">
                @csrf
                <input type="hidden" class="form-control" id="id" name="id" onchange="validate()" required readonly>
                <br>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="created_at">Created At</label>
                            <input type="text" class="form-control" id="created_at" name="created_at" required readonly>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="updated_at">Updated At</label>
                            <input type="text" class="form-control" id="updated_at" name="updated_at" required readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="table_name">Table Name</label>
                            <input type="text" class="form-control" id="table_name" name="table_name" placeholder="Table Name" readonly required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="flag_include_data">Include Data</label>
                            <select name="flag_include_data" class="form-control" aria-label="Default select example" required>
                                <option disabled selected value="">Select Active</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="flag_active">Active</label>
                            <select name="flag_active" class="form-control" aria-label="Default select example" required>
                                <option disabled selected value="">Select Active</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="form-group">
                    <label for="query">Query</label>
                    <!-- <input type="text" class="form-control" id="query" name="query" placeholder="Query" required> -->
                    <textarea type="text" class="form-control" id="query" name="query" placeholder="Query" required readonly></textarea>
                </div>
                <div class="form-group">
                    <label for="spesial_conditions">Spesial Conditions</label>
                    <!-- <input type="text" class="form-control" id="spesial_conditions" name="spesial_conditions" placeholder="Spesial Conditions" required> -->
                    <textarea type="text" class="form-control" id="spesial_conditions" name="spesial_conditions" placeholder="Spesial Conditions"></textarea>
                </div>
                <div class="row justify-content-end p-2">
                    <a href="{{ route('modulegendata.index') }}" type="submit" class="btn btn-primary btn-sm">Cancel</a>
                    &nbsp;&nbsp;&nbsp;
                    <button onclick="regenerateQuery()" type="button"class="btn btn-info btn-sm">Regenerate Query</button>
                    &nbsp;&nbsp;&nbsp;
                    <button type="submit" name="submit" class="btn btn-warning btn-sm">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        const currentLocation = window.location + "";
        const id = currentLocation.split('/');
        loadData(id[5]);
    });

    function regenerateQuery() {
        const currentLocation = window.location + "";
        const id = currentLocation.split('/');
        $.ajax({
            url: "/modulegendata/regeneratequery/" + id[5],
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                var lines = data.data.split("\n");
                document.getElementById('query').rows = lines.length;
                document.getElementById('query').value = data.data;
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Gagal mendapatkan data');
            }
        });
    }

    function loadData(id) {
        var myForm = document.forms['dFormEdit'];

        for (var i = 0; i < myForm.length; i++) {
            var d = myForm.elements[i];
            if (d.name != "_token") {
                d.value = "";
            }
        }

        $.ajax({
            url: '/modulegendata/json/' + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {

                for (var i = 0; i < myForm.length; i++) {
                    var d = myForm.elements[i];
                    if (d.name != "_token") {
                        if (d.name == "") {

                        } else if (d.name == "query") {
                            var lines = data[d.name].split("\n");
                            document.getElementById('query').rows = lines.length;
                            document.getElementById('query').value = data[d.name];
                        } else {
                            d.value = data[d.name];
                        }
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Gagal mendapatkan data');
            }
        });
    }
</script>
@include('layout.bottom')
