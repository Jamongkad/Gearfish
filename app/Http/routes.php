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
	return View::make('usage');
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
        $fileName = rand(11111,99999).'.'.$extension; // renameing csv
        $file->move('uploads', $fileName);

        $id = DB::table('Uploads')->insertGetId(
            ['companyID' => $companyID, 'name' => $fileName, 'records' => csv_count($fileName)]
        );

        $api_key = hash('sha256', (time() . $companyID . Config::get('app.key') . rand()));
        DB::table('CompanyApiKeys')->insert(
            ['companyID' => $companyID, 'key' => $api_key, 'uploadID' => $id, 'name' => $fileName]
        );
         
        //return 'ready';
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
    
    //var_dump($page_data);
    return Response::json($page_data);
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
