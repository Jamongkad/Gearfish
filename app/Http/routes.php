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
	return View::make('getstarted');
});

Route::get('/getstarted', function()
{	
	return View::make('getstarted');
});

Route::get('/apikey', function()
{	
    $companyID = 1;
    $keys = DB::Table('CompanyApiKeys')->where('companyID', '=', $companyID)->get();
	return View::make('apikey', ['keys' => $keys]);
});

Route::get('/usage', function()
{	
	return View::make('usage');
});

Route::get('/billing', function()
{	
	return View::make('billing');
});

Route::get('/docs', function()
{	
	return View::make('docs');
});

Route::get('/myapi', ['middleware' => 'auth', function()
{	
    $companyID = 1;
    $csv = DB::table('Uploads')->where('companyID', '=', $companyID)->get();
	return View::make('myapi', ['csv' => $csv]);
}]);

Route::get('/upload', function()
{	
	return View::make('plupload');
});

Route::post('/upload', function()
{	
    // getting all of the post data
    $file = array('csv' => Input::file('csv'));
    // setting up rules
    $rules = array('csv' => 'required');
    // doing the validation, passing post data, rules and the messages
    $validator = Validator::make($file, $rules);
    if ($validator->fails()) {
        // send back to the page with the input data and errors
        return Redirect::to('upload')->withInput()->withErrors($validator);
    } else {
        // checking file is valid.
        $validCSV = in_array(Input::file('csv')->getMimeType(), array(
            'text/csv',
            'text/plain',
            'application/csv',
            'text/comma-separated-values',
            'application/excel',
            'application/vnd.ms-excel',
            'application/vnd.msexcel',
            'text/anytext',
            'application/octet-stream',
            'application/txt',
        ));

        if ($validCSV) {
            $destinationPath = 'uploads'; // upload path
            $extension = Input::file('csv')->getClientOriginalExtension(); // getting csv extension
            $fileName = rand(11111,99999).'.'.$extension; // renameing csv
            Input::file('csv')->move($destinationPath, $fileName); // uploading file to given path
            // sending back with message
            Session::flash('success', 'Upload successful!'); 
            return Redirect::to('upload');
        } else {
            // sending back with error message.
            Session::flash('error', 'uploaded file is not valid.');
            return Redirect::to('upload');
        }

   }
});

Route::post('/plupload', function() {
    return Plupload::receive('file', function ($file) {

        $companyID = 1;

        $extension = $file->getClientOriginalExtension(); // getting csv extension
        $fileName = rand(11111,99999).'.'.$extension; // renameing csv
        $file->move('uploads', $fileName);

        $id = DB::table('Uploads')->insertGetId(
            ['companyID' => $companyID, 'name' => $fileName, 'records' => csv_count($fileName)]
        );

        $api_key = hash('sha256', (time() . $companyID . Config::get('app.key') . rand()));
        DB::table('CompanyApiKeys')->insert(
            ['companyID' => $companyID, 'key' => $api_key, 'uploadID' => $id, 'name' => $fileName]
        );

        return 'ready';
    });  
});

Route::post('/add_csv', function() { 
    $companyID = 1;
    $file = array('csv' => Input::get('filename'));

    $config = new lexerconfig();
    $lexer = new lexer($config);

    $interpreter = new interpreter();
    $interpreter->unstrict(); // ignore row column count consistency
    $collection = array();

    $interpreter->addobserver(function(array $columns) use(&$collection) {
        $collection[] = $columns;
    });

    $csv = 'uploads/'.Input::get('filename');
    $lexer->parse($csv, $interpreter);

    $id = DB::table('Uploads')->insertGetId(
        ['companyID' => $companyID, 'name' => Input::get('filename'), 'records' => count($collection)]
    );

    $api_key = hash('sha256', (time() . $companyID . Config::get('app.key') . rand()));
    DB::table('CompanyApiKeys')->insert(
        ['companyID' => $companyID, 'key' => $api_key, 'uploadID' => $id, 'name' => Input::get('filename')]
    );
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

Route::get('/viewapi/{id}', function($id)
{
    $companyID = 1;
    $upload_data = DB::table('Uploads')->join('CompanyApiKeys', 'Uploads.id', '=', 'CompanyApiKeys.uploadID')
                                       ->where('Uploads.companyID', '=', $companyID)
                                       ->where('Uploads.id', '=', $id)
                                       ->first();
 
    return View::make('viewapi', ['upload_data' => $upload_data]);
});

Route::get('/createkey', function() 
{
    $companyID = 1;
    return View::make('createkey', ['companyID' => $companyID]);
});

Route::get('/api/{id}', function($id)
{	
    $limit = (Input::get('limit')) ? (int)Input::get('limit') : 100;
    $data = DB::table('Uploads')->where('id', '=', $id)->first();
    
    $config = new lexerconfig();
    $lexer = new lexer($config);

    $interpreter = new interpreter();
    $interpreter->unstrict(); // ignore row column count consistency
    $collection = array();

    $interpreter->addobserver(function(array $columns) use(&$collection) {
        $collection[] = $columns;
    });

    $csv = 'uploads/'.$data->name;
    $lexer->parse($csv, $interpreter);

    $paginator = new Paginator($collection, $limit, Input::get('page'));
    $data = $paginator->toArray();

    $offset = (Input::get('page')) ? $data['to'] : 0;
    $page_data = [
        'limit' => $limit,
        'total_pages' => ceil(count($collection) / $limit) - 1,
        'paging' => [ 
            'per_page' => $data['per_page'],
            'prev_page' => (Input::get('page')) ? $paginator->currentPage() - 1 : 0,
            'next_page' => (Input::get('page')) ? $paginator->currentPage() + 1 : 1,
        ],
        'from' => $data['from'],
        'to' => $data['to'],
        'data' => array_slice($collection, $offset, $limit)//$paginator->paginate($limit)->toArray()['data']
    ];
    
    var_dump($page_data);
    //return Response::json($page_data);
});

function csv_count($fileName) {
    
    $config = new lexerconfig();
    $lexer = new lexer($config);

    $interpreter = new interpreter();
    $interpreter->unstrict(); // ignore row column count consistency

    $collection = Array();
    $interpreter->addobserver(function(array $columns) use(&$collection) {
        $collection[] = $columns;
    });

    $csv = 'uploads/'.$fileName;
    $lexer->parse($csv, $interpreter);

    return count($collection);
}


Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
