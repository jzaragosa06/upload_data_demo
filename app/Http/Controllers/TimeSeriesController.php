<?php

// // app/Http/Controllers/TimeSeriesController.php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Storage;
// use League\Csv\Reader;

// class TimeSeriesController extends Controller
// {

//     public function upload(Request $request)
//     {
//         $request->validate([
//             'csv_file' => 'required|file|mimes:csv,txt',
//             'type' => 'required|string',
//             'description' => 'required|string',
//         ]);

//         $path = $request->file('csv_file')->store('csv_files');

//         $csv = Reader::createFromPath(storage_path('app/' . $path), 'r');
//         $csv->setHeaderOffset(0);
//         $records = iterator_to_array($csv->getRecords());

//         $formattedRecords = array_map(function ($record) {
//             $date = \DateTime::createFromFormat('m/d/Y', $record['Date']);
//             return [
//                 'Date' => $date ? $date->format('c') : $record['Date'], // ISO format
//                 'Value' => $record['Value']
//             ];
//         }, $records);

//         if ($request->type == 'univariate') {
//             return view('univariate', ['data' => $formattedRecords]);
//         } else {
//             $headers = $csv->getHeader();
//             return view('multivariate', ['data' => $formattedRecords, 'headers' => $headers]);
//         }
//     }


//     public function processUnivariate(Request $request)
//     {
//         // Handle processing of univariate data based on selected fill method
//     }

//     public function processMultivariate(Request $request)
//     {
//         // Handle processing of multivariate data based on selected fill method
//     }
// }
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TimeSeriesController extends Controller
{
    public function showUploadForm()
    {
        return view('upload');
    }

    public function uploadCSV(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
            'type' => 'required',
            'description' => 'nullable|string'
        ]);

        $path = $request->file('file')->store('csv');

        if ($request->input('type') === 'multivariate') {
            $data = array_map('str_getcsv', file(storage_path('app/' . $path)));
            $headers = $data[0];
            array_shift($data); // Remove header row

            return view('multivariate', compact('data', 'headers'));
        } else {
            $data = array_map('str_getcsv', file(storage_path('app/' . $path)));
            $headers = $data[0];
            array_shift($data); // Remove header row

            return view('univariate', compact('data', 'headers'));
        }
    }

    public function uploadProcessedCSV(Request $request)
    {
        $file = $request->file('file');
        $path = $file->store('processed_csvs');

        // Process the file or save the path to the database

        return response()->json(['message' => 'Processed CSV uploaded successfully', 'path' => $path]);
    }
}
