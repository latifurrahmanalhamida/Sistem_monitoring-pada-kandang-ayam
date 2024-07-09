<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\FeedingHistoryExport;
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
        // Ambil data dari Controlling_makan
        $makanReference = $this->database->getReference('Controlling_makan');
        $makanSnapshot = $makanReference->getSnapshot();

        $feedVolume1 = null;
        $prestarter = null;

        if ($makanSnapshot->exists()) {
            $makanData = $makanSnapshot->getValue();
            $feedVolume1 = isset($makanData['feedVolume1']['volume']) ? round($makanData['feedVolume1']['volume'], 2) : null;
            $prestarter = isset($makanData['pre-startter']) ? $makanData['pre-startter'] : null;
        } else {
            return response()->json(['status' => 'fail', 'message' => 'Tidak ada data ditemukan di Controlling_makan']);
        }

        // Ambil semua data dari FeedingHistory/Servo1
        $feedingHistoryReference = $this->database->getReference('FeedingHistory/Servo1');
        $feedingHistorySnapshot = $feedingHistoryReference->getSnapshot();

        $feeds = [];

        if ($feedingHistorySnapshot->exists()) {
            $feeds = $feedingHistorySnapshot->getValue();
        }

        return view('monitoring.makan.monitor-satu', [
            'feedVolume1' => $feedVolume1,
            'prestarter' => $prestarter,
            'feeds' => $feeds
        ]);
    }




    public function showMonitoringMakan2()
    {
        // Ambil data dari Controlling_makan
        $makanReference = $this->database->getReference('Controlling_makan');
        $makanSnapshot = $makanReference->getSnapshot();

        $feedVolume2 = null;
        $prestarter = null;

        if ($makanSnapshot->exists()) {
            $makanData = $makanSnapshot->getValue();
            $feedVolume2 = isset($makanData['feedVolume2']['volume']) ? round($makanData['feedVolume2']['volume'], 2) : null;
            $prestarter = isset($makanData['starter']) ? $makanData['starter'] : null;
        } else {
            return response()->json(['status' => 'fail', 'message' => 'Tidak ada data ditemukan di Controlling_makan']);
        }

        // Ambil semua data dari FeedingHistory/Servo1
        $feedingHistoryReference = $this->database->getReference('FeedingHistory/Servo2');
        $feedingHistorySnapshot = $feedingHistoryReference->getSnapshot();

        $feeds = [];

        if ($feedingHistorySnapshot->exists()) {
            $feeds = $feedingHistorySnapshot->getValue();
        }

        return view('monitoring.makan.monitor-dua', [
            'feedVolume2' => $feedVolume2,
            'prestarter' => $prestarter,
            'feeds' => $feeds
        ]);
    }
    public function showMonitoringMakan3()
    {
        // Ambil data dari Controlling_makan
        $makanReference = $this->database->getReference('Controlling_makan');
        $makanSnapshot = $makanReference->getSnapshot();

        $feedVolume3 = null;
        $prestarter = null;

        if ($makanSnapshot->exists()) {
            $makanData = $makanSnapshot->getValue();
            $feedVolume3 = isset($makanData['feedVolume3']['volume']) ? round($makanData['feedVolume3']['volume'], 3) : null;
            $prestarter = isset($makanData['finisher']) ? $makanData['finisher'] : null;
        } else {
            return response()->json(['status' => 'fail', 'message' => 'Tidak ada data ditemukan di Controlling_makan']);
        }

        // Ambil semua data dari FeedingHistory/Servo1
        $feedingHistoryReference = $this->database->getReference('FeedingHistory/Servo3');
        $feedingHistorySnapshot = $feedingHistoryReference->getSnapshot();

        $feeds = [];

        if ($feedingHistorySnapshot->exists()) {
            $feeds = $feedingHistorySnapshot->getValue();
        }

        return view('monitoring.makan.monitor-tiga', [
            'feedVolume3' => $feedVolume3,
            'prestarter' => $prestarter,
            'feeds' => $feeds
        ]);
    }


    public function exportFeedingHistory2(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        // Initialize variables for total feed volume grams and data array
        $totalFeedVolumeGrams = 0;
        $data = [];

        // Retrieve data from Firebase
        $reference = $this->database->getReference('FeedingHistory/Servo2');
        $snapshot = $reference->getSnapshot();

        if ($snapshot->exists()) {
            foreach ($snapshot->getValue() as $key => $value) {
                // Check if the timestamp matches the selected month and year
                $timestamp = strtotime($value['timestamp']);
                if (date('m', $timestamp) == $month && date('Y', $timestamp) == $year) {
                    $feedVolumeGrams = $value['feed_volume_grams'] ?? 0;
                    $totalFeedVolumeGrams += $feedVolumeGrams;

                    $data[] = [
                        'feed_volume_grams' => $feedVolumeGrams,
                        'time_of_day' => $value['time_of_day'] ?? 'N/A',
                        'timestamp' => $value['timestamp'] ?? 'N/A'
                    ];
                }
            }
        }

        // Add total feed volume row to the data array
        $data[] = [
            'Total Feed Volume (grams)' =>  $totalFeedVolumeGrams,

        ];

        // Generate CSV file
        $fileName = 'feeding_history_' . $month . '_' . $year . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Feed Volume (grams)', 'Time of Day', 'Timestamp']);

            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function exportFeedingHistory1(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        // Initialize variables for total feed volume grams and data array
        $totalFeedVolumeGrams = 0;
        $data = [];

        // Retrieve data from Firebase
        $reference = $this->database->getReference('FeedingHistory/Servo1');
        $snapshot = $reference->getSnapshot();

        if ($snapshot->exists()) {
            foreach ($snapshot->getValue() as $key => $value) {

                $timestamp = strtotime($value['timestamp']);
                if (date('m', $timestamp) == $month && date('Y', $timestamp) == $year) {
                    $feedVolumeGrams = $value['feed_volume_grams'] ?? 0;
                    $totalFeedVolumeGrams += $feedVolumeGrams;

                    $data[] = [
                        'feed_volume_grams' => $feedVolumeGrams,

                    ];
                }
            }
        }

        // Add total feed volume row to the data array
        $data[] = [
            'Total Feed Volume (grams)' =>  $totalFeedVolumeGrams,

        ];

        // Generate CSV file
        $fileName = 'feeding_history_' . $month . '_' . $year . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Feed Volume (grams)', 'Time of Day', 'Timestamp']);

            foreach ($data as $row) {
                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function showMonitoringMinum(Request $request)
    {
        $selectedYear = $request->input('selected_year');
        $selectedMonth = $request->input('selected_month');
        $selectedDay = $request->input('selected_day');

        // Ambil referensi untuk History_volume dari Firebase
        $historyReference = $this->database->getReference('History_volume');
        $historySnapshot = $historyReference->getSnapshot();
        $historyData = $historySnapshot->getValue();

        $latestSensorData = [];

        // Iterasi melalui Sensor1 dan Sensor2
        foreach (['Sensor1', 'Sensor2'] as $sensorId) {
            if (isset($historyData[$sensorId]) && is_array($historyData[$sensorId])) {
                // Filter data berdasarkan tahun, bulan, dan hari yang dipilih
                $filteredData = collect($historyData[$sensorId])->filter(function ($item) use ($selectedYear, $selectedMonth, $selectedDay) {
                    $date = \DateTime::createFromFormat('d-m-Y H:i:s', $item['timestamp']);
                    return (
                        (!$selectedYear || $date->format('Y') == $selectedYear) &&
                        (!$selectedMonth || $date->format('m') == $selectedMonth) &&
                        (!$selectedDay || $date->format('d') == $selectedDay)
                    );
                })->values()->all();

                // Masukkan data yang difilter ke dalam array $latestSensorData
                $latestSensorData[$sensorId] = $filteredData;
            }
        }

        // Ambil data dari 'monitoring minum' untuk Minum1 dan Minum2
        $reference = $this->database->getReference('monitoring minum');
        $snapshot = $reference->getSnapshot();

        $minum1 = null;
        $minum2 = null;

        if ($snapshot->exists()) {
            $dataMinum = $snapshot->getValue();
            $minum1 = isset($dataMinum['minum1']) ? $dataMinum['minum1'] : null;
            $minum2 = isset($dataMinum['minum2']) ? $dataMinum['minum2'] : null;
        }

        return view('monitoring.monitorminum', [
            'minum1' => $minum1,
            'minum2' => $minum2,
            'latestSensorData' => $latestSensorData,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
            'selectedDay' => $selectedDay,
        ]);
    }





    public function getDataDashboards()
{
    // Initialize variables
    $makan1 = $makan2 = $makan3 = $minum1 = $minum2 = $suhu1 = $suhu2 = $suhu3 = null;

    // Retrieve and process feed data
    $reference = $this->database->getReference('Controlling_makan');
    $snapshot = $reference->getSnapshot();
    if ($snapshot->exists()) {
        $data = $snapshot->getValue();
        $makan1 = $data['feedVolume1'] ?? null;
        $makan2 = $data['feedVolume2'] ?? null;
        $makan3 = $data['feedVolume3'] ?? null;
    }

    // Retrieve and process drinking data
    $reference = $this->database->getReference('monitoring minum');
    $snapshot = $reference->getSnapshot();
    if ($snapshot->exists()) {
        $data = $snapshot->getValue();
        $minum1 = $data['minum1'] ?? null;
        $minum2 = $data['minum2'] ?? null;
    }

    // Retrieve and process temperature data
    $reference = $this->database->getReference('Controlling_suhu');
    $snapshot = $reference->getSnapshot();
    if ($snapshot->exists()) {
        $data = $snapshot->getValue();
        $suhu1 = $data['suhu']['sensor1']['suhu'] ?? null;
        $suhu2 = $data['suhu']['sensor2']['suhu'] ?? null;
        $suhu3 = $data['suhu']['sensor3']['suhu'] ?? null;
    } else {
        return response()->json(['status' => 'fail', 'message' => 'No data found']);
    }

    // Return the view with the data
    return view('monitoring/dashboard', [
        'makan1' => $makan1,
        'makan2' => $makan2,
        'makan3' => $makan3,
        'minum1' => $minum1,
        'minum2' => $minum2,
        'suhu1' => $suhu1,
        'suhu2' => $suhu2,
        'suhu3' => $suhu3
    ]);
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
    // Ambil data filter dari request
    $selectedYear = $request->input('selected_year');
    $selectedMonth = $request->input('selected_month');
    $selectedDay = $request->input('selected_day');

    // Ambil data dari Controlling_suhu
    $reference = $this->database->getReference('Controlling_suhu');
    $snapshot = $reference->getSnapshot();
    $data = $snapshot->getValue();

    $controllingData = [
        'kipas' => $data['kipas'] ?? 'off',
        'lampu' => $data['lampu'] ?? 'off',
        'mode' => $data['mode'] ?? 'manual'
    ];

    $sensorData = [];

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

    // Ambil data dari History_suhu
    $historyReference = $this->database->getReference('History_suhu');
    $historySnapshot = $historyReference->getSnapshot();
    $historyData = $historySnapshot->getValue();

    $latestSensorData = [];

    if ($historyData && is_array($historyData)) {
        foreach (['Sensor1', 'Sensor2', 'Sensor3'] as $sensorId) {
            if (isset($historyData[$sensorId]) && is_array($historyData[$sensorId])) {
                $filteredData = collect($historyData[$sensorId])->filter(function ($item) use ($selectedYear, $selectedMonth, $selectedDay) {
                    $date = \DateTime::createFromFormat('d-m-Y H:i:s', $item['timestamp']);
                    return (
                        (!$selectedYear || $date->format('Y') == $selectedYear) &&
                        (!$selectedMonth || $date->format('m') == $selectedMonth) &&
                        (!$selectedDay || $date->format('d') == $selectedDay)
                    );
                })->values()->all();

                $latestSensorData[$sensorId] = $filteredData;
            }
        }
    }

    return view('monitoring.suhumonitor', [
        'sensorData' => $sensorData,
        'latestSensorData' => $latestSensorData,
        'controllingData' => $controllingData,
        'selectedYear' => $selectedYear,
        'selectedMonth' => $selectedMonth,
        'selectedDay' => $selectedDay,
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
