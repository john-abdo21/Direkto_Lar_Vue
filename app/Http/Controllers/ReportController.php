<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use DB;

class ReportController extends Controller
{
    public function generarReporte(Request $request)
    {
        // $data = $request->validate([
        //     'id' => 'required|string'
        // ]);
        // $data = json_decode($_POST['data'], true);
        // $id = $data['id'];
        // $json = json_encode($request);
        $id_restriction = $request->input('id');
        // print_r($request);
        // echo "nadaa";

        $query_proyectos = "

        select
            pp.desNombreProyecto as nproyecto,
            bb.des_Empresa,
            pp.desDireccion as des_Direccion
            from proy_proyecto pp
            inner join anares_analisisrestricciones aa  on pp.codProyecto  = aa.codProyecto
            left join conf_maestro_empresas bb on cast(pp.desEmpresa as int) = bb.cod_Empresa
        where pp.codProyecto = ?

        ";

        $query_restricciones = "

        select
        aa.codAnaResActividad,
        WEEK(aa.dayFechaCreacion) AS nsemana,
        IFNULL(at2.desTipoRestricciones ,'sin elegir') as ntipo,
        af.desAnaResFrente as nfrente ,
        af2.desAnaResFase as nsubfrente,
        concat(u2.name,' ',u2.lastname) as nresponsableasignacion,

        aa.desActividad as ndescripcionactividad ,
        aa.desRestriccion as ndescripcionrestriccion,
        aa.dayFechaCreacion  as nfechaidentificacion,
        case when aa.dayFechaConciliada is null then   aa.dayFechaRequerida else aa.dayFechaConciliada end  as nfecharequerida,
        concat(u2.name,' ',u2.lastname) as nresponsablelevantamiento,
        aa.dayFechaLevantamiento  as nfecharealfinlevantamiento,
        -- pa.desArea as netapa,
        -- ce.desEstado as nestado,
	    CASE
	        WHEN aa.dayFechaCreacion IS NULL THEN ''
	        WHEN
	            CASE
	                WHEN aa.dayFechaConciliada IS NOT NULL THEN DATE(aa.dayFechaConciliada)
	                ELSE DATE(aa.dayFechaRequerida)
	            END >= DATE(aa.dayFechaLevantamiento) AND aa.dayFechaLevantamiento IS NOT NULL AND ce.desEstado = 'Completado' THEN 'CULMINADA EN PLAZO'
	        WHEN
	            CASE
	                WHEN aa.dayFechaConciliada IS NOT NULL THEN DATE(aa.dayFechaConciliada)
	                ELSE DATE(aa.dayFechaRequerida)
	            END < DATE(aa.dayFechaLevantamiento) AND aa.dayFechaLevantamiento IS NOT NULL AND ce.desEstado = 'Completado' THEN 'CULMINADA CON ATRASO'
	        WHEN
	            CASE
	                WHEN aa.dayFechaConciliada IS NOT NULL THEN DATE(aa.dayFechaConciliada)
	                ELSE DATE(aa.dayFechaRequerida)
	            END >= CURDATE() AND DATE(aa.dayFechaCreacion) <
	            CASE
	                WHEN aa.dayFechaConciliada IS NOT NULL THEN DATE(aa.dayFechaConciliada)
	                ELSE DATE(aa.dayFechaRequerida)
	            END THEN 'PROCESO EN PLAZO'
	        WHEN
	            CASE
	                WHEN aa.dayFechaConciliada IS NOT NULL THEN DATE(aa.dayFechaConciliada)
	                ELSE DATE(aa.dayFechaRequerida)
	            END < CURDATE() AND DATE(aa.dayFechaCreacion) <
	            CASE
	                WHEN aa.dayFechaConciliada IS NOT NULL THEN DATE(aa.dayFechaConciliada)
	                ELSE DATE(aa.dayFechaRequerida)
	            END THEN 'PROCESO CON ATRASO'
	        ELSE ''
	    END AS nestado,
        ce.desEstado as netapa,
        -- 0 as ndeltadias,
        CASE
	        WHEN
	            CASE
	                WHEN aa.dayFechaConciliada IS NOT NULL THEN DATE(aa.dayFechaConciliada)
	                ELSE DATE(aa.dayFechaRequerida)
	            END > CURDATE() THEN '-'
	        WHEN
	            CASE
	                WHEN aa.dayFechaConciliada IS NOT NULL THEN DATE(aa.dayFechaConciliada)
	                ELSE DATE(aa.dayFechaRequerida)
	            END <= CURDATE() THEN
	            CASE
	                WHEN aa.dayFechaLevantamiento IS NOT NULL THEN DATEDIFF(CASE WHEN aa.dayFechaConciliada IS NOT NULL THEN DATE(aa.dayFechaConciliada) ELSE DATE(aa.dayFechaRequerida) END, DATE(aa.dayFechaLevantamiento))
	                ELSE DATEDIFF(CASE WHEN aa.dayFechaConciliada IS NOT NULL THEN DATE(aa.dayFechaConciliada) ELSE DATE(aa.dayFechaRequerida) END, CURDATE()) * -1
	            END
	        ELSE ''
   		END AS ndeltadias,
        '' as nobservacion,
        IFNULL(concat(u.name,' '+u.lastname) , pi2.desCorreo) as Usuario_Solicitante,
        aa.dayFechaRequerida,
        aa.numOrden
        from
        anares_actividad aa
        inner join anares_frente af on aa.codAnaResFrente  = af.codAnaResFrente
        inner join anares_fase af2 on aa.codAnaResFase  = af2.codAnaResFase
        left join anares_tiporestricciones at2 on aa.codTipoRestriccion  = at2.codTipoRestricciones
        left join proy_integrantes pi2 on aa.idUsuarioResponsable = pi2.codProyIntegrante
        left join users u on pi2.idIntegrante  = u.id
        left join proy_areaintegrante pa on pi2.codArea  = pa.codArea
        left join conf_estado ce on aa.codEstadoActividad  = ce.codEstado and ce.desModulo = 'ANARES'
        left join users u2 on aa.codUsuarioSolicitante  = u2.id
        where aa.codProyecto = ?
        order by aa.codAnaResFrente,aa.codAnaResFase, aa.numOrden



        ";
        $v1 = array($id_restriction);
        $qregistros  = DB::select($query_restricciones, $v1);
        $qregistros = array_map(function ($value) {
            return (array)$value;
        }, $qregistros);

        $v2 = array($id_restriction);
        $qproyecto  = DB::select($query_proyectos, $v2);
        $qproyecto = array_map(function ($value) {
            return (array)$value;
        }, $qproyecto);


        // Crear el objeto Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

                // Establecer los estilos
        $styleDefault = [
            'font' => [
                'name' => 'Calibri',
                'size' => 10,
            ],
        ];
                // Establecer los estilos
        $styleTitle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'name' => 'Calibri',
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0000FF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'inside' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        $styleVerticalBorders = [
            'borders' => [
                'inside' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
                'outline' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        // Configurar las columnas
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(10);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(10);
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->getColumnDimension('H')->setWidth(10);
        $sheet->getColumnDimension('I')->setWidth(10);
        $sheet->getColumnDimension('J')->setWidth(10);
        $sheet->getColumnDimension('K')->setWidth(10);
        $sheet->getColumnDimension('L')->setWidth(10);
        $sheet->getColumnDimension('M')->setWidth(10);
        $sheet->getColumnDimension('N')->setWidth(10);
        $sheet->getColumnDimension('O')->setWidth(10);

        // Configurar las filas
        $sheet->getRowDimension(1)->setRowHeight(20);
        $sheet->getRowDimension(2)->setRowHeight(20);
        $sheet->getRowDimension(3)->setRowHeight(20);
        $sheet->getRowDimension(4)->setRowHeight(20);
        $sheet->getRowDimension(5)->setRowHeight(20);
        $sheet->getRowDimension(6)->setRowHeight(20);
        $sheet->getRowDimension(7)->setRowHeight(20);
        $sheet->getRowDimension(8)->setRowHeight(20);
        $sheet->getRowDimension(9)->setRowHeight(20);
        $sheet->getRowDimension(10)->setRowHeight(20);

        // Configurar el contenido de las celdas
        $sheet->setCellValue('A1', '')
            ->mergeCells('A1:E1')
            ->setCellValue('F1', 'REGISTRO')
            ->mergeCells('F1:K1')
            ->setCellValue('L1', 'Revision')
            ->mergeCells('L1:O1');
        $sheet->getStyle('F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', '')
            ->mergeCells('A2:E2')
            ->setCellValue('F2', 'GESTION DE PROYECTOS')
            ->mergeCells('F2:K2')
            ->setCellValue('L2', '')
            ->mergeCells('L2:O2');
        $sheet->getStyle('F2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', '')
            ->mergeCells('A3:E3')
            ->setCellValue('F3', 'ANALISIS DE RESTRICCIONES')
            ->mergeCells('F3:K3')
            ->setCellValue('L3', 'Pagina 1')
            ->mergeCells('L3:O3');
        $sheet->getStyle('F3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A4', 'CODIGO DEL PROYECTO')
            ->mergeCells('A4:F4');
        $sheet->setCellValue('G4', 'Area : ')
            ->mergeCells('G4:K4');
        $sheet->setCellValue('L4', 'Ubicacion : ')
            ->mergeCells('L4:O4');

        $sheet->setCellValue('A5', '')
            ->mergeCells('A5:F5');
        $sheet->setCellValue('G5', '')
            ->mergeCells('G5:K5');
        $sheet->setCellValue('L5', $qproyecto[0]['des_Direccion'])
            ->mergeCells('L5:O5');


        $sheet->setCellValue('A6', $qproyecto[0]['nproyecto'])
            ->mergeCells('A6:F6');
        $sheet->setCellValue('G6', 'Cliente : ')
            ->mergeCells('G6:G6');
        $sheet->setCellValue('H6', $qproyecto[0]['des_Empresa'])
            ->mergeCells('H6:O6');

        $sheet->setCellValue('A7', '')
            ->mergeCells('A7:F7');
        $sheet->setCellValue('G7', '')
            ->mergeCells('G7:G7');
        $sheet->setCellValue('H7', '')
            ->mergeCells('H7:O7');

        $sheet->setCellValue('A8', 'Fecha :')
            ->mergeCells('A8:F8');
        $sheet->setCellValue('G8', 'Semana: ')
            ->getStyle('G8:G8')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $sheet->setCellValue('H8', 'Numero Total de nuevas restricciones: ')
            ->mergeCells('H8:O8');

        $sheet->setCellValue('A9', date('d/m/Y'))
            ->mergeCells('A9:F9');
        $sheet->setCellValue('G9', '')
            ->mergeCells('G9:G9');
        $sheet->setCellValue('H9', '% de nuevas retricciones identificadas x semana: ')
            ->mergeCells('H9:O9');

        $sheet->setCellValue('A10', 'SEMANA')
            ->getStyle('A10:A10')->applyFromArray($styleTitle);
        $sheet->setCellValue('B10', 'TIPO')
            ->getStyle('B10:B10')->applyFromArray($styleTitle);
        $sheet->setCellValue('C10', 'FRENTE')
            ->getStyle('C10:C10')->applyFromArray($styleTitle);
        $sheet->setCellValue('D10', 'SUBFRENTE')
            ->getStyle('D10:D10')->applyFromArray($styleTitle);
        $sheet->setCellValue('E10', 'RESPONSABLE DE ASIGNACION')
            ->getStyle('E10:E10')->applyFromArray($styleTitle);
        $sheet->setCellValue('F10', 'DESCRIPCION DE LA ACTIVIDAD')
            ->getStyle('F10:F10')->applyFromArray($styleTitle);
        $sheet->setCellValue('G10', 'DESCRIPCION DE LA RESTRICCION')
            ->getStyle('G10:G10')->applyFromArray($styleTitle);
        $sheet->setCellValue('H10', 'FECHA DE IDENTIFICACION')
            ->getStyle('H10:H10')->applyFromArray($styleTitle);
        $sheet->setCellValue('I10', 'FECHA REQUERIDA')
            ->getStyle('I10:I10')->applyFromArray($styleTitle);
        $sheet->setCellValue('J10', 'RESPONSABLE DE LEVANTAMIENTO')
            ->getStyle('J10:J10')->applyFromArray($styleTitle);
        $sheet->setCellValue('K10', 'FECHA REAL DE FIN LEVANTAMIENTO')
            ->getStyle('K10:K10')->applyFromArray($styleTitle);
        $sheet->setCellValue('L10', 'ETAPA')
            ->getStyle('L10:L10')->applyFromArray($styleTitle);
        $sheet->setCellValue('M10', 'ESTADO')
            ->getStyle('M10:M10')->applyFromArray($styleTitle);
        $sheet->setCellValue('N10', 'DELTA EN DIAS')
            ->getStyle('N10:N10')->applyFromArray($styleTitle);
        $sheet->setCellValue('O10', 'OBSERVACION')
            ->getStyle('O10:O10')->applyFromArray($styleTitle);

        // // Configurar los bordes
        // $sheet->getStyle('A4:O5')->applyFromArray($styleVerticalBorders);
        // $sheet->getStyle('A10:O10')->applyFromArray($styleTitle);

        // Configurar los bordes
        $sheet->getStyle('A1:O9')->applyFromArray($styleVerticalBorders);
        $sheet->getStyle('A10:O10')->applyFromArray($styleTitle);

        // Configurar los valores de las celdas de acuerdo a $qregistros
        $fila = 11;
        foreach ($qregistros as $registro) {
            $sheet->setCellValue('A' . $fila, $registro['nsemana']);
            $sheet->setCellValue('B' . $fila, $registro['ntipo']);
            $sheet->setCellValue('C' . $fila, $registro['nfrente']);
            $sheet->setCellValue('D' . $fila, $registro['nsubfrente']);
            $sheet->setCellValue('E' . $fila, $registro['nresponsableasignacion']);
            $sheet->setCellValue('F' . $fila, $registro['ndescripcionactividad']);
            $sheet->setCellValue('G' . $fila, $registro['ndescripcionrestriccion']);
            $sheet->setCellValue('H' . $fila, $registro['nfechaidentificacion']);
            $sheet->setCellValue('I' . $fila, $registro['nfecharequerida']);
            $sheet->setCellValue('J' . $fila, $registro['nresponsablelevantamiento']);
            $sheet->setCellValue('K' . $fila, $registro['nfecharealfinlevantamiento']);
            $sheet->setCellValue('L' . $fila, $registro['netapa']);
            $sheet->setCellValue('M' . $fila, $registro['nestado']);
            $sheet->setCellValue('N' . $fila, $registro['ndeltadias']);
            $sheet->setCellValue('O' . $fila, $registro['nobservacion']);
            $fila++;
        }

        // // Configurar la autoajuste de ancho de columnas
        // foreach (range('A', 'O') as $columna) {
        //     $sheet->getColumnDimension($columna)->setAutoSize(true);
        // }

        // Aplicar los estilos a todas las celdas
        $sheet->getStyle($sheet->calculateWorksheetDimension())->applyFromArray($styleDefault);
        // Configurar las celdas de la fila 10
        $sheet->getRowDimension(10)->setRowHeight(60);

        // Establecer la altura predeterminada para la fila E
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(15);

        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getColumnDimension('K')->setWidth(18);
        $sheet->getColumnDimension('O')->setWidth(16);

        $sheet->getStyle('A10:O10')->getAlignment()->setWrapText(true);

        // Calcular la mitad del tamaño actual
        $mitadTamanio = $sheet->getDefaultRowDimension()->getRowHeight() / 4;

        // Ajustar la altura de cada fila
        foreach ($sheet->getRowDimensions() as $rowDimension) {
            $rowDimension->setRowHeight($mitadTamanio);
        }

        $sheet->getStyle('A10:O10')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('002d74');


        // Crear el archivo Excel
        $writer = new Xlsx($spreadsheet);
        $nombreArchivo = "reporte_" . $qproyecto[0]['nproyecto'] . '_' . date('Ymd') . '.xlsx';
        $writer->save($nombreArchivo);

        // Descargar el archivo
        return response()->download($nombreArchivo)->deleteFileAfterSend(true);
    }
}
