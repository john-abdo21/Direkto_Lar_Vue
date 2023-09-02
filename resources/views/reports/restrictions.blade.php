<!DOCTYPE html>
<html>
<head>
    <style>
        /* Estilos del reporte */
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="3"></td>
            <td>REGISTRO</td>
            <td>Revision</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td>GESTION DE PROYECTOS</td>
            <td></td>
        </tr>
        <tr>
            <td colspan="2"></td>
            <td>ANALISIS DE RESTRICCIONES</td>
            <td>Pagina 1</td>
        </tr>
        <tr>
            <td rowspan="2"></td>
            <td rowspan="2"></td>
            <td rowspan="2"></td>
            <td>CODIGO DEL PROYECTO</td>
            <td colspan="10">{{ $qproyecto->codigo }}</td>
        </tr>
        <tr>
            <td>Area:</td>
            <td colspan="10">{{ $qproyecto->area }}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>Ubicacion:</td>
            <td colspan="10">{{ $qproyecto->ubicacion }}</td>
        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td>{{ $qproyecto->nombre }}</td>
            <td>Cliente:</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Fecha:</td>
            <td>Semana:</td>
            <td>NUMERO TOTAL DE NUEVAS RESTRICCIONES:</td>
        </tr>
        <tr>
            <td>{{ date('d/m/Y') }}</td>
            <td></td>
            <td>% DE NUEVAS RESTRICCIONES IDENTIFICADAS POR SEMANA:</td>
        </tr>
        <tr>
            <td>SEMANA</td>
            <td>TIPO</td>
            <td>FRENTE</td>
            <td>SUBFRENTE</td>
            <td>RESPONSABLE DE ASIGNACION</td>
            <td>DESCRIPCION DE LA ACTIVIDAD</td>
            <td>DESCRIPCION DE LA RESTRICCION</td>
            <td>FECHA DE IDENTIFICACION</td>
            <td>FECHA REQUERIDA</td>
            <td>RESPONSABLE DE LEVANTAMIENTO</td>
            <td>FECHA REAL DE FIN LEVANTAMIENTO</td>
            <td>ETAPA</td>
            <td>ESTADO</td>
            <td>DELTA EN DIAS</td>
            <td>OBSERVACION</td>
        </tr>
        @foreach ($qregistros as $registro)
            <tr>
                <td>{{ $registro->nsemana }}</td>
                <td>{{ $registro->ntipo }}</td>
                <td>{{ $registro->nfrente }}</td>
                <td>{{ $registro->nsubfrente }}</td>
                <td>{{ $registro->nresponsableasignacion }}</td>
                <td>{{ $registro->ndescripcionactividad }}</td>
                <td>{{ $registro->ndescripcionrestriccion }}</td>
                <td>{{ $registro->nfechaidentificacion }}</td>
                <td>{{ $registro->nfecharequerida }}</td>
                <td>{{ $registro->nresponsablelevantamiento }}</td>
                <td>{{ $registro->nfecharealfinlevantamiento }}</td>
                <td>{{ $registro->netapa }}</td>
                <td>{{ $registro->nestado }}</td>
                <td>{{ $registro->ndeltadias }}</td>
                <td>{{ $registro->nobservacion }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>
