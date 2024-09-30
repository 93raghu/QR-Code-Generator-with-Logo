<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <title>QR Code Generator with Logo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" >
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" ></script>

      
    </head>
    <body class="antialiased">
       
    <div class="container">
        <div class="card">
            <div class="card-header">
                QR Code Generator with Logo
            </div>
            <div class="card-body">
                

                            @if (session('success'))
                                <p>{{ session('success') }}</p>
                                <a href="{{ asset('storage/generated/' . session('fileName')) }}" class="btn btn-primary" download>Download QR Code</a>
                                <br><br>
                                <img src="{{ asset('storage/generated/' . session('fileName')) }}" alt="Generated QR Code" class="img-thumbnail">
                                <br><br>
                     
                            @endif

                            <form action="{{ route('generate.qr.code') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <label for="text">Text for QR Code:</label>
                                <input type="text" name="text" id="text" class="form-control" required>
                                <br><br>

                                <label for="logo">Upload Logo (Optional):</label>
                                <input type="file" name="logo" id="logo" class="form-control" accept="">
                                <br><br>

                                <button type="submit" class="btn btn-primary">Generate QR Code</button>
                            </form>
                        </div>
            </div>
    </div>
</body>
</html>

        </div>
    </body>
</html>
