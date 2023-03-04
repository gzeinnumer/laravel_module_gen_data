@include('layout.top')
<div class="container">
    <br>
    <div class="row">
        <div class="col">

        </div>
        <div class="col-auto">
            <a href="{{ route('modulegendata.updatetable') }}" class="btn btn-primary btn-sm">Generate New Table</a>
        </div>

        <div class="col-auto">
            <a href="{{ route('modulegendata.regenerateall') }}" class="btn btn-info btn-sm">Re-Generate All Table</a>
        </div>

        <div class="col-auto">
            <a onclick="generate()" class="btn btn-danger btn-sm">Generate File</a>
        </div>
    </div>
    <div class="row">
        @if (session('sukses'))
            <div class="alert alert-success" role="alert">
                {{ session('sukses') }}
            </div>
        @endif
    </div>
    <br>

    @include('modulegendata.index-tables')

    <script>
        function generate() {
            $.ajax({
                url: '/modulegendata/generatefile',
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Gagal mendapatkan data');
                }
            });
        }
    </script>

</div>
@include('layout.bottom')
