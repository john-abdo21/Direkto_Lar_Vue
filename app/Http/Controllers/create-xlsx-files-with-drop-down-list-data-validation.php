<?php

namespace App\Http\Controllers;
use App\Models\RestrictionMember;
use App\Models\Restriction;
use App\Models\ProjectUser;
use App\Models\PhaseActividad;
use App\Models\RestrictionPerson;
use App\Models\RestrictionFront;
use App\Models\RestrictionPhase;
use App\Models\Conf_Estado;
use App\Models\Ana_TipoRestricciones;
use App\Models\Proy_AreaIntegrante;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;
// use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Config;
use Helper; // Important
use Carbon\Carbon;

// Autoload dependencies
require '../../vendor/autoload.php';

// Import the core class of PhpSpreadsheet


// Import the Xlsx writer class
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Create a new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Retrieve the current active worksheet
$sheet = $spreadsheet->getActiveSheet();

/**
 * Set cell B3 with the "Select from the drop down options:"
 * string value, will serve as the 'Select Option Title'.
 */
$sheet->setCellValue('B3', 'Select from the drop down options:');

/**
 * Set the 'drop down list' validation on C3.
 */
$validation = $sheet->getCell('C3')->getDataValidation();

/**
 * Since the validation is for a 'drop down list',
 * set the validation type to 'List'.
 */
$validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);

/**
 * List drop down options.
 */
$validation->setFormula1('"A, B, C, D"');

/**
 * Do not allow empty value.
 */
$validation->setAllowBlank(false);

/**
 * Show drop down.
 */
$validation->setShowDropDown(true);

/**
 * Display a cell 'note' about the
 * 'drop down list' validation.
 */
$validation->setShowInputMessage(true);

/**
 * Set the 'note' title.
 */
$validation->setPromptTitle('Note');

/**
 * Describe the note.
 */
$validation->setPrompt('Must select one from the drop down options.');

/**
 * Show error message if the data entered is invalid.
 */
$validation->setShowErrorMessage(true);

/**
 * Do not allow any other data to be entered
 * by setting the style to 'Stop'.
 */
$validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);

/**
 * Set descriptive error title.
 */
$validation->setErrorTitle('Invalid option');

/**
 * Set the error message.
 */
$validation->setError('Select one from the drop down list.');

// Write a new .xlsx file
$writer = new Xlsx($spreadsheet);

// Save the new .xlsx file
$writer->save('create-xlsx-files-with-drop-down-list-data-validation.xlsx');

