<div class="row">
    <div class="col">
        <table class="table table-striped table-nowrap">
            <thead class="thead-dark">
                <tr>
                    <td>Table Name</td>
                    <td>Query</td>
                    <td>Spesial Condition</td>
                    <td>Include Data</td>
                    <td>Active</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $d)
                <tr>
                    <td>{{$d->table_name}}</td>
                    <td>{{$d->query}}</td>
                    <td>{{$d->spesial_conditions}}</td>
                    <td>@if($d->flag_include_data == 1) Yes @else No @endif</td>
                    <td>@if($d->flag_active == 1) Active @else Inactive @endif</td>
                    <td>
                        <a href="/modulegendata/update/{{$d->id}}" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>