@extends('layouts.pdf')

@section('style')
    <style>
        ul {
            list-style-type: none;
            margin: 0;
        }
        ul li {
            margin-left: -40px;
        }
    </style>
@endsection

@section('content')
    <h3 class="text-center">REPORTE DE MATRICULAS</h3>
    <table class="table table-response" style="width: 100%">
        <thead>
        <tr>
            <th>N°</th>
            <th>MATRICULA</th>
            <th>CARRERA</th>
            <th>CICLO</th>
            <th>DNI</th>
            <th>NOMBRES Y APELLIDOS</th>
            <th>PAGO</th>
            <th>ESTADO</th>
            <th>REGISTRADO</th>
        </tr>
        </thead>
        <tbody>
        <?php $sum = 0; ?>
        @foreach($matriculas as $m)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $m->tipo_matriculas->nombre }}</td>
                <td>{{ $m->carreras->nombre }}</td>
                <td>{{ $m->ciclos->nombre }}</td>
                <td>{{ $m->alumnos->dni }}</td>
                <td>{{ $m->alumnos->nombres.' '.$m->alumnos->apellidos }}</td>
                <td>S/. {{ $m->total }}</td>
                <td>{{ $m->estado == 0 ? 'RECHAZADO' : $m->estado == 1 ? 'APROVADO' : $m->estado == 2 ? 'PROCESO' : $m->estado == 3 ? 'OBSERVACIÓN' : 'EMITIDO' }}</td>
                <td>{{ \Carbon\Carbon::parse($m->created_at)->format('d/m/Y') }}</td>
            </tr>
            <?php $sum += $m->total; ?>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <td colspan="6">TOTAL</td>
            <td>S/. {{ $sum }}</td>
            <td></td>
            <td></td>
        </tr>
        </tfoot>
    </table>
@endsection
