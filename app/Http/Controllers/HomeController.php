<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use App\User;
// use GuzzleHttp\Client;

class HomeController extends Controller
{
    /**
     * Home Index
     * @return Response
     * @throws ReflectionException
     */
    public function index(Request $request)
    {
    	if(!isset($_COOKIE['cookie_token'])) {
    		return redirect('/login');
    	}
		return view('dashboard/index');
	}

    /**
     * Home Login
     * @return Response
     * @throws ReflectionException
     */
    public function login(Request $request)
    {
		return view('login');
	}

    /**
     * Home Register
     * @return Response
     * @throws ReflectionException
     */
    public function register(Request $request)
    {
        return view('register');
    }

    /**
     * Home Authentication
     * @return Response
     * @throws ReflectionException
     */
    public function auth(Request $request)
    {
    	$data = ['email' => $request->email, 'password' => $request->password];

        $client = new \GuzzleHttp\Client;

		$response = $client->post('http://localhost:8000/api/login', [
		    // 'headers' => ['Authorization' => "Bearer ".$token],
		    'form_params' => $data
		]);

		$response = json_decode($response->getBody(), true);

        if(!setcookie('cookie_token', $response['access_token'], time() + (86400 * 30), "/")) {
            throw new \Exception();
        }

        return redirect('/');

	}

    /**
     * Home Add
     * @return Response
     * @throws ReflectionException
     */
    public function add(Request $request)
    {
        $data = ['email' => $request->email, 'password' => $request->password];

        try {
            $client = new \GuzzleHttp\Client;

            if(!$response = $client->post('http://localhost:8000/api/register', [
                'headers' => [],
                'form_params' => $data
            ]))
                throw new \Exception();
        } catch (\Exception $e) {
            return redirect('/register');
            // return response()->json('E-mail já cadastrado', 401);
        }

        $response = json_decode($response->getBody(), true);

        if(!$response) {
            throw new \Exception();
        }

        return redirect('/login');

    }

    /**
     * Show Sales
     * @return Response
     * @throws ReflectionException
     */
    public function showSales(Request $request)
    {
    	if(!isset($_COOKIE['cookie_token'])) {
    		return redirect('/login');
    	}
    	$token = $_COOKIE['cookie_token'];

        $filePath = (string)$request->file('file');
        $data = ['file' => $filePath];

		if(isset($token)) {
			$authorization = "Authorization: Bearer ".$token;
		}

        try {
            $client = new \GuzzleHttp\Client;

            if(!$response = $client->post('http://localhost:8000/api/sales', [
                'headers' => ['Authorization' => "Bearer ".$token],
                'form_params' => $data
            ]))
                throw new \Exception();
        } catch (\Exception $e) {
            return redirect('/login');
            // return response()->json('Não Autorizado', 401);
        }

		$response = $response->getBody()->getContents();

		return view('dashboard/sales', ['sales' => $response]);
	}
}