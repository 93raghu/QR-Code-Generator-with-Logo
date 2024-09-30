<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Intervention\Image\Image;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeController extends Controller
{
    public function index()
    {

        try {
    

            // return QrCode::size(200)
            // ->backgroundColor(255, 255, 0)
            // ->color(0, 0, 255)
            // ->margin(1)
            // ->generate(
            //     'Hello, World!',
            // );

            return view('qr-code-generator');
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
       
    }

    public function generate(Request $request)
    {
        // Validate the request inputs
        $request->validate([
            'text' => 'required|string',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);
    
        try {
            // Generate the QR code
            $qrCode = QrCode::format('png')
                ->size(300)
                //->color(75, 0, 130)
                ->margin(2)
                ->errorCorrection('Q')
                ->generate($request->input('text'));
    
            // Define the file name and path
            $fileName = 'qrcode-' . Str::random(10) . '.png';
            $qrCodePath = 'qrcodes/' . $fileName;
    
            // Store the QR code image file
            Storage::disk('public')->put($qrCodePath, $qrCode);
    
            // Define the destination folder and ensure it exists
            $destinationFolder = 'generated';
            Storage::disk('public')->makeDirectory($destinationFolder, 0777, true, true);
    
            // Move the QR code to the destination folder
            $movedQrCodePath = $destinationFolder . '/' . $fileName;
            Storage::disk('public')->move($qrCodePath, $movedQrCodePath);
    
            // If a logo is uploaded, merge it with the QR code
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('logos', 'public');
                $this->addLogoToQrCode($movedQrCodePath, $logoPath);
            }

            //dd(Storage::disk('public')->exists('qrcodes/' . session('fileName')));

    
            // Return the view with the generated QR code
            return back()->with('success', 'QR code generated successfully!')->with('fileName', $fileName);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => "Error: " . $e->getMessage()]);
        }
    }

    
        private function addLogoToQrCode($qrCodePath, $logoPath)
{
    // Load QR code and logo images
    $qrCodeImage = imagecreatefrompng(storage_path('app/public/' . $qrCodePath));
    $logoImage = imagecreatefrompng(storage_path('app/public/' . $logoPath));

    // Get dimensions of both images
    $qrWidth = imagesx($qrCodeImage);
    $qrHeight = imagesy($qrCodeImage);
    $logoWidth = imagesx($logoImage);
    $logoHeight = imagesy($logoImage);

    // Resize logo to fit inside the QR code
    $logoSize = $qrWidth / 5;
    $resizedLogo = imagecreatetruecolor($logoSize, $logoSize);
    //dd($resizedLogo);
    // Maintain logo transparency
    imagealphablending($resizedLogo, false);
    imagesavealpha($resizedLogo, true);
    $transparent = imagecolorallocatealpha($resizedLogo, 0, 0, 0, 127);
    imagefill($resizedLogo, 0, 0, $transparent);

    // Resize the logo to fit inside the QR code
    imagecopyresampled($resizedLogo, $logoImage, 0, 0, 0, 0, $logoSize, $logoSize, $logoWidth, $logoHeight);

    // Merge logo onto the QR code
    $xPos = ($qrWidth - $logoSize) / 2;
    $yPos = ($qrHeight - $logoSize) / 2;
    imagecopy($qrCodeImage, $resizedLogo, $xPos, $yPos, 0, 0, $logoSize, $logoSize);

    // Save the final QR code with the logo
    imagepng($qrCodeImage, storage_path('app/public/' . $qrCodePath));

    // Free up memory
    imagedestroy($qrCodeImage);
    imagedestroy($logoImage);
    imagedestroy($resizedLogo);
}

    



}
