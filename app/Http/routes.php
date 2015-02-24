<?php

use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;
use Illuminate\Pagination\Paginator;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('/', 'WelcomeController@index');
//Route::get('home', 'HomeController@index');

Route::get('/', function()
{ 
	return View::make('homepage');
});

Route::get('/getstarted', ['middleware' => 'auth', function()
{	
	return View::make('getstarted');
}]);

Route::get('/apikey', ['middleware' => 'auth', function()
{	
    $keys = DB::Table('CompanyApiKeys')->where('companyID', '=', Auth::user()->id)->get();
	return View::make('apikey', ['keys' => $keys]);
}]);

Route::get('/usage', ['middleware' => 'auth', function()
{	
    $calendar = new Solution10\Calendar\Calendar(new DateTime('now'));
	return View::make('usage', ['calendar' => $calendar]);
}]);

Route::get('/billing', ['middleware' => 'auth', function()
{	
	return View::make('billing');
}]);

Route::get('/docs', ['middleware' => 'auth', function()
{	
	return View::make('docs');
}]);

Route::get('/myapi', ['middleware' => 'auth', function()
{	
    $csv = DB::table('Uploads')->where('companyID', '=', Auth::user()->id)->orderBy('created_at', 'desc')->get();
	return View::make('myapi', ['csv' => $csv]);
}]);

Route::get('/upload', ['middleware' => 'auth', function()
{	
	return View::make('plupload');
}]);

Route::get('/upload_success', ['middleware' => 'auth', function() 
{
   echo "Success";
}]);

Route::post('/plupload', function() {
    return Plupload::receive('file', function ($file) {

        $companyID = Auth::user()->id;

        $extension = $file->getClientOriginalExtension(); // getting csv extension
        $fileNumber = rand(11111, 99999);
        $fileUploadDirectory = 'uploads/'.$fileNumber;
        $fileName = $fileNumber.'.'.$extension; // renameing csv

        if (!file_exists($fileUploadDirectory)) {
            mkdir($fileUploadDirectory, 0777, true);
        }

        $file->move($fileUploadDirectory, $fileName);

        $uploadID = DB::table('Uploads')->insertGetId(
            ['companyID' => $companyID, 'name' => $fileName, 'records' => csv_count($fileUploadDirectory.'/'.$fileName)]
        );

        $api_key = hash('sha256', (time() . $companyID . Config::get('app.key') . rand()));
        DB::table('CompanyApiKeys')->insert(
            ['companyID' => $companyID, 'key' => $api_key, 'uploadID' => $uploadID, 'name' => $fileName]
        );
         
        csv_split_file($fileName, $fileUploadDirectory, $uploadID, $companyID);
        return Response::json(['status' => 'ready']);
    });  
});

Route::post('/createkey', function() 
{
    $companyID = Input::get('companyID');

    $api_key = hash('sha256', (time() . $companyID . Config::get('app.key') . rand()));
    DB::table('CompanyApiKeys')->insert(
        ['companyID' => $companyID, 'key' => $api_key, 'name' => Input::get('name')]
    );

    return Redirect::to('apikey');
});

Route::get('/viewapi/{id}', ['middleware' => 'auth', function($id)
{
    $companyID = Auth::user()->id;
    $upload_data = DB::table('Uploads')->join('CompanyApiKeys', 'Uploads.id', '=', 'CompanyApiKeys.uploadID')
                                       ->where('Uploads.companyID', '=', $companyID)
                                       ->where('Uploads.id', '=', $id)
                                       ->first();    

    return View::make('viewapi', ['upload_data' => $upload_data]);
}]);

Route::get('/createkey', function() 
{
    $companyID = 1;
    return View::make('createkey', ['companyID' => $companyID]);
});

Route::get('/api/{id}', function($id)
{	
    $apikey = Input::get('apikey');
    if($apikey) { 
 
        $csvUploadsPageCount = DB::table('CompanyUploadFiles')->where('uploadID', '=', $id)->count();
        $csvUploads = DB::table('CompanyUploadFiles')
                      ->select('CompanyUploadFiles.name as childFile', 
                               'Uploads.name as parentFile',
                               'Uploads.records')
                      ->join('Uploads', 'Uploads.id', '=', 'CompanyUploadFiles.uploadID') 
                      ->where('CompanyUploadFiles.uploadID', '=', $id)
                      ->where('Uploads.id', '=', $id)
                      ->limit(1)->offset(Input::get('page') - 1)->first();

        $config = new lexerconfig();
        $lexer = new lexer($config);
        $interpreter = new interpreter();
        $interpreter->unstrict(); // ignore row column count consistency

        $collection = array();
        $interpreter->addobserver(function(array $columns) use(&$collection) {
            $collection[] = $columns;
        });
        
        if(Input::get('page') != 0) {
            
            if($csvUploads) {
                $folderName = str_replace('.csv', '', $csvUploads->parentFile);
                $csv = 'uploads/'.$folderName.'/'.$csvUploads->childFile.'.csv';
                $lexer->parse($csv, $interpreter);
                
                /*
                var_dump($csvUploads);
                var_dump($csvUploadsPageCount);
                var_dump($collection);
                */

                $page_data = [
                    'total_pages' => $csvUploadsPageCount,
                    'paging' => [ 
                        'prev_page' => (Input::get('page')) ? (Input::get('page') == 1) ? null : Input::get('page') - 1 : 0,
                        'next_page' => (Input::get('page')) ? ((Input::get('page') == $csvUploadsPageCount) ? null : Input::get('page') + 1) : 1,
                    ],
                    'data' => $collection 
                ];
                return Response::json($page_data);
            }

        } else { 
            return Response::json('Nothing');
        } 
    }
});

function csv_count($fileName) {
    $c = 0;
    $fp = fopen($fileName, "r");
    if($fp){
        while(!feof($fp)) {
          $content = fgets($fp);
          if($content) $c++;
        }
    }
    fclose($fp);
    return $c; 
}

function csv_split_file($fileName, $fileUploadDirectory, $uploadID, $companyID) {
    $outputFile = 'output';
    $splitSize = 10000;
    
    $in = fopen($fileUploadDirectory.'/'.$fileName, 'r');

    $rowCount = 0;
    $fileCount = 1;
    while (!feof($in)) {
        if (($rowCount % $splitSize) == 0) {
            if ($rowCount > 0) {
                fclose($out);
            }
            $outputFileName = $outputFile . $fileCount++;
            DB::table('CompanyUploadFiles')->insert(['uploadID' => $uploadID, 'companyID' => $companyID, 'name' => $outputFileName]);
            $out = fopen($fileUploadDirectory . '/' . $outputFileName . '.csv', 'w');
        }
        $data = fgetcsv($in);
        if ($data)
            fputcsv($out, $data);
        $rowCount++;
    }
    fclose($out);    
}

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
