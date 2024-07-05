<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; // Assuming you have an Order model
use PDF;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Illuminate\Support\Facades\Log;
use Imagick;

class OrderPrintController extends Controller
{
    //
    public function printOrder($orderId)
    {
        Log::info('Printing order: ' . $orderId);
        // Fetch the order and printer name from the database
        $order = Order::findOrFail($orderId);
        $printerName = 'Black Copper BC-85AC'; // Assuming the printer name is stored in the order

        $pdf = Pdf::loadView('orders.printOrder', compact('order'))->setPaper([0, 0, 226.77, 1000], 'portrait'); // 80mm in points (96 dpi)
        $pdfContent = $pdf->output();

        // Save the PDF to a temporary file
        $tempFilePath = tempnam(sys_get_temp_dir(), 'order') . '.pdf';
        file_put_contents($tempFilePath, $pdfContent);

        try {
            $connector = new WindowsPrintConnector($printerName);
            $printer = new Printer($connector);
        
            // Convert the first page of the PDF to an image
            $imagick = new Imagick();
            $imagick->readImage($tempFilePath . '[0]'); // '[0]' to specify the first page
            $imagick->setImageFormat('png');
            $tempImageFilePath = tempnam(sys_get_temp_dir(), 'order') . '.png';
            $imagick->writeImage($tempImageFilePath);
        
            // Load the image as EscposImage
            $escposImage = EscposImage::load($tempImageFilePath, false);
        
            // Print the image
            $printer->graphics($escposImage);
            $printer->cut();
            $printer->close();
        
            // Cleanup: Delete the temporary image file
            unlink($tempImageFilePath);
        } catch (\Exception $e) {
            Log::error("Error printing: " . $e->getMessage());
            return response()->json(['error' => 'Failed to print'], 500);
        }
        
        return response()->json(['success' => 'Print successful']);

        // View PDF Order

        // Log::info('Viewing order PDF: ' . $orderId);
        // // Fetch the order from the database
        // $order = Order::findOrFail($orderId);

        // Log::info('order: ' . $order);
        
    
        // // Generate PDF content using a Blade view
        // $pdfContent = PDF::loadView('orders.printOrder', ['order' => $order])
        //         ->setPaper([0, 0, 226.77, 1000], 'portrait') // Set custom paper size, width = 302px (80mm in 96dpi), height arbitrarily set to 1000px for a roll
        //         ->output();
    
        // // Save the PDF to a temporary file
        // $tempFilePath = tempnam(sys_get_temp_dir(), 'order') . '.pdf';
        // file_put_contents($tempFilePath, $pdfContent);
    
        // // Return the PDF file as a response to be viewed in the browser
        // return response()->file($tempFilePath, [
        //     'Content-Type' => 'application/pdf',
        //     'Content-Disposition' => 'inline; filename="' . $order->id . '_order.pdf"'
        // ]);

        // // Print the order Direct Printer PDF

        // Log::info('Printing order: ' . $orderId);
        // // Fetch the order and printer name from the database
        // $order = Order::findOrFail($orderId);
        // $printerName = 'Black copper BC-85AC'; 

        // // Generate PDF content using a Blade view
        // $pdfContent = PDF::loadView('orders.printOrder', ['order' => $order])->output();

        // // Save the PDF to a temporary file
        // $tempFilePath = tempnam(sys_get_temp_dir(), 'order') . '.pdf';
        // file_put_contents($tempFilePath, $pdfContent);

        // // Print the PDF (Example for a local printer, adjust for network printers or other scenarios)
        // try {
        //     $connector = new FilePrintConnector($printerName);
        //     $printer = new Printer($connector);
        //     $printer->text(file_get_contents($tempFilePath));
        //     $printer->cut();
        //     $printer->close();
        // } catch (\Exception $e) {
        //     return response()->json(['error' => 'Failed to print: ' . $e->getMessage()], 500);
        // }

        // // Clean up the temporary file
        // unlink($tempFilePath);

        // return response()->json(['success' => 'Order printed successfully']);
    }

    public function showPrinterSettingsByIP()
{
    $ipAddress = '192.168.1.100';
    // PowerShell script to get printer details by IP address
    $script = <<<PS
\$ports = Get-WmiObject -Query "SELECT * FROM Win32_TCPIPPrinterPort WHERE HostAddress = '$ipAddress'"
\$ports | ForEach-Object {
    Get-Printer -Filter "PortName = '\$($_.Name)'" | Select-Object Name | ConvertTo-Json
}
PS;

    $command = "powershell -Command \"{$script}\"";
    $output = shell_exec($command);
    Log::info('Output: ' . $output);

    $printers = [];
    if ($output) {
        $printers = json_decode($output, true);
    }

    Log::info('Printers by IP [' . $ipAddress . ']: ' . print_r($printers, true));

    return view('settings.printer', compact('printers'));
}
}
