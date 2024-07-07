<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class FirebaseController extends Controller
{
    protected $database;
    protected $auth;

    public function __construct()
    {
        $firebaseCredentialsPath = env('FIREBASE_CREDENTIALS');
        $firebaseDatabaseUrl = env('FIREBASE_DATABASE_URL');

        // Validate Firebase credentials path
        if (!$firebaseCredentialsPath) {
            throw new \Exception("Firebase credentials file path is not defined in .env file");
        }

        // Validate if Firebase credentials file exists
        $credentialsFullPath = base_path($firebaseCredentialsPath);
        if (!file_exists($credentialsFullPath)) {
            throw new \Exception("Firebase credentials file not found at path: " . $credentialsFullPath);
        }

        // Initialize Firebase Factory with service account credentials
        $firebase = (new Factory)
            ->withServiceAccount($credentialsFullPath)
            ->withDatabaseUri($firebaseDatabaseUrl);

        // Initialize Firebase Database and Auth instances
        $this->database = $firebase->createDatabase();
        $this->auth = $firebase->createAuth();
    }

    public function showMonitoringMakan()
    {
        $reference = $this->database->getReference('Controlling_makan');
        $snapshot = $reference->getSnapshot();

        if ($snapshot->exists()) {
            $data = $snapshot->getValue();
            $feedVolume1 = isset($data['feedVolume1']['volume']) ? round($data['feedVolume1']['volume'], 2) : null;

            return view('monitoring/makan/monitor-satu', ['feedVolume1' => $feedVolume1]);
        } else {
            return response()->json(['status' => 'fail', 'message' => 'No data found at Controlling_makan']);
        }
    }

    public function showMonitoringMakan2()
    {
        $reference = $this->database->getReference('Controlling_makan');
        $snapshot = $reference->getSnapshot();

        if ($snapshot->exists()) {
            $data = $snapshot->getValue();
            $feedVolume2 = isset($data['feedVolume2']['volume']) ? round($data['feedVolume2']['volume'], 2) : null;

            return view('monitoring/makan/monitor-dua', ['feedVolume2' => $feedVolume2]);
        } else {
            return response()->json(['status' => 'fail', 'message' => 'No data found at Controlling_makan']);
        }
    }

    public function showMonitoringMakan3()
    {
        $reference = $this->database->getReference('Controlling_makan');
        $snapshot = $reference->getSnapshot();

        if ($snapshot->exists()) {
            $data = $snapshot->getValue();
            $feedVolume3 = isset($data['feedVolume3']['volume']) ? round($data['feedVolume3']['volume'], 2) : null;

            return view('monitoring/makan/monitor-tiga', ['feedVolume3' => $feedVolume3]);
        } else {
            return response()->json(['status' => 'fail', 'message' => 'No data found at Controlling_makan']);
        }
    }

    public function showMonitoringMinum()
    {
        $reference = $this->database->getReference('monitoring minum');
        $snapshot = $reference->getSnapshot();

        if ($snapshot->exists()) {
            $data = $snapshot->getValue();

            $minum1 = isset($data['minum1']) ? $data['minum1'] : null;
            $minum2 = isset($data['minum2']) ? $data['minum2'] : null;

            return view('monitoring.monitorminum', ['minum1' => $minum1, 'minum2' => $minum2]);
        } else {
            return response()->json(['status' => 'fail', 'message' => 'No data found for monitoring minum']);
        }
    }

    public function getDataDashboards()
    {
        return view('monitoring/dashboard', []);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        $userRef = $this->database->getReference('tb_user')->orderByChild('email')->equalTo($email)->getValue();
        if ($userRef) {
            foreach ($userRef as $user) {
                if ($user['password'] === $password) {
                    // Store user information in session
                    session(['user' => $user]);

                    if ($user['roles'] == "admin") {
                        return redirect()->route('dashboard');
                    }

                    return redirect()->route('dashboard');
                }
            }
        }

        return redirect()->back()->withErrors(['email' => 'Invalid email or password.']);
    }


// Data Suhu
public function getDataSuhu(Request $request)
{
    // Mengambil data dari Controlling_suhu
    $reference = $this->database->getReference('Controlling_suhu');
    $snapshot = $reference->getSnapshot();
    $data = $snapshot->getValue();

    $sensorData = [];
    $controllingData = [];

    if ($data) {
        $controllingData = [
            'kipas' => $data['kipas'] ?? 'off',
            'lampu' => $data['lampu'] ?? 'off',
            'mode' => $data['mode'] ?? 'manual'
        ];

        if (isset($data['suhu'])) {
            foreach ($data['suhu'] as $sensorId => $sensorDetails) {
                $sensorData[] = [
                    'sensor' => $sensorId,
                    'kelembaban' => $sensorDetails['kelembaban'] ?? null,
                    'suhu' => $sensorDetails['suhu'] ?? null,
                    'timestamp' => $sensorDetails['timestamp'] ?? null
                ];
            }
        }
    }

    // Mengambil data dari History_suhu
    $historyReference = $this->database->getReference('History_suhu');
    $historySnapshot = $historyReference->getSnapshot();
    $historyData = $historySnapshot->getValue();

    $latestSensorData = [];

    if ($historyData && is_array($historyData)) {
        foreach (['Sensor1', 'Sensor2', 'Sensor3'] as $sensorId) {
            if (isset($historyData[$sensorId]) && is_array($historyData[$sensorId])) {
                $sensorHistory = array_slice(array_reverse($historyData[$sensorId]), 0, 5);
                foreach ($sensorHistory as $timestamp => $details) {
                    $latestSensorData[] = [
                        'sensor' => $sensorId,
                        'timestamp' => $timestamp,
                        'suhu' => $details['suhu'] ?? null,
                        'kelembaban' => $details['kelembaban'] ?? null,
                    ];
                }
            }
        }
    }


    return view('monitoring.suhumonitor', [
        'sensorData' => $sensorData,
        'latestSensorData' => $latestSensorData,
        'controllingData' => $controllingData
    ]);
}
// logout
public function logout()
{
    session()->forget('user');
    return redirect('/');
}

    public function createUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
            'name' => 'required',
            'roles' => 'required',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');
        $name = $request->input('name');
        $roles = $request->input('roles');

        try {
            // Generate a unique ID for the new user
            $newUserId = $this->database->getReference('tb_user')->push()->getKey();

            // Save user info in Realtime Database
            $this->database->getReference('tb_user/' . $newUserId)->set([
                'email' => $email,
                'password' => $password,
                'name' => $name,
                'roles' => $roles,
            ]);

            return redirect('/pegawai')->with('status', 'User created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Data Pegawai
    public function getDataUser(Request $request)

    {

        $user = $this->database->getReference('tb_user');
        $snapshot = $user->getSnapshot()   ;

        if($snapshot->exists()) {
            $users = $snapshot->getValue() ;
            return view('pegawai.index', ['users'=>$users]);
        }
    }

    // Delete User
    public function editUser($id)
    {
        $user = $this->database->getReference('tb_user/' . $id)->getValue();

        return view('pegawai.edit-pegawai', ['user' => $user, 'id' => $id]);
    }

    public function updateDataUser(Request $request, $id)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:8',
        'name' => 'required',
        'roles' => 'required',
    ]);

    $email = $request->input('email');
    $password = $request->input('password');
    $name = $request->input('name');
    $roles = $request->input('roles');

    try {
        // Update user info in Realtime Database
        $this->database->getReference('tb_user/' . $id)->update([
            'email' => $email,
            'password' => $password,
            'name' => $name,
            'roles' => $roles,
        ]);

        return redirect('/pegawai')->with('status', 'User updated successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => $e->getMessage()]);
    }
}

    public function deleteDataUser($id)
    {
        try {
            $this->database->getReference('tb_user/' . $id)->remove();

            return redirect('/pegawai')->with('status', 'User deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

}
}
