@extends('layouts.template')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Poppins', sans-serif; background-image: url("https://travelspromo.com/wp-content/uploads/2019/03/Patung-di-Museum-Ullen-Sentalu-Jogja.-Foto-Gmap-Edhi-Sutanto.jpg"); background-size: cover; background-position: center; background-attachment: fixed; min-height: 100vh; position: relative; }
    body::before { content: ""; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.45); z-index: -1; transition: 0.4s; }
    body:hover::before { background: rgba(0, 0, 0, 0.6); }
    .navbar-glass { position: fixed; top: 15px; left: 50%; transform: translateX(-50%); width: 90%; z-index: 2000; padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(15px); border-radius: 20px; border: 1px solid rgba(0, 255, 100, 0.2); box-shadow: 0 0 25px rgba(0, 255, 100, 0.15); }
    .nav-menu a { color: #d1fae5; text-decoration: none; margin-right: 30px; font-size: 14px; cursor: pointer; position: relative; }
    .nav-menu a::after { content: ""; position: absolute; left: 0; bottom: -5px; width: 0%; height: 2px; background: #22c55e; transition: 0.3s; }
    .nav-menu a:hover::after { width: 100%; }
    .search-box { display: flex; align-items: center; background: rgba(255, 255, 255, 0.2); border-radius: 30px; border: 1px solid rgba(34, 197, 94, 0.4); overflow: hidden; }
    .search-box input { background: transparent; border: none; color: white; padding: 8px 15px; outline: none; width: 170px; }
    .search-box input::placeholder { color: #d1fae5; }
    .search-box button { background: linear-gradient(90deg, #16a34a, #22c55e); border: none; color: white; padding: 8px 18px; cursor: pointer; }
    .content { margin-top: 120px; padding: 20px; }
    .card { border: none; border-radius: 18px; box-shadow: 0 10px 35px rgba(0, 0, 0, 0.25); background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(5px); }
    .table thead { background: #111827; color: white; }
</style>
@endsection

@section('content')
<div class="content">
    <div class="container">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Tabel Data Wisata Alam Jogja Utara</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Tempat</th>
                                <th>Alamat</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>1</td><td>Kaliurang</td><td>Kaliurang, Hargobinangun, Pakem, Sleman</td></tr>
                            <tr><td>2</td><td>Bukit Klangon</td><td>Glagaharjo, Cangkringan, Sleman</td></tr>
                            <tr><td>3</td><td>Stonehenge Merapi</td><td>Kepuharjo, Cangkringan, Sleman</td></tr>
                            <tr><td>4</td><td>Air Terjun Tlogo Muncar</td><td>Hargobinangun, Pakem, Sleman</td></tr>
                            <tr><td>5</td><td>Embung Kaliaji</td><td>Glagaharjo, Cangkringan, Sleman</td></tr>
                            <tr><td>6</td><td>Lava Tour Merapi</td><td>Kepuharjo, Cangkringan, Sleman</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

