<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; // Assuming you have an Order model
use Barryvdh\DomPDF\Facade as PDF;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;
use Illuminate\Support\Facades\Log;

class OrderPrintController extends Controller
{
    //
    public function printOrder($orderId)
    {
        // Fetch the order and printer name from the database
        $order = Order::findOrFail($orderId);
        $printerName = $order->printer_name; // Assuming the printer name is stored in the order

        // Generate PDF content using a Blade view
        $pdfContent = PDF::loadView('orders.receipt', ['order' => $order])->output();

        // Save the PDF to a temporary file
        $tempFilePath = tempnam(sys_get_temp_dir(), 'order') . '.pdf';
        file_put_contents($tempFilePath, $pdfContent);

        // Print the PDF (Example for a local printer, adjust for network printers or other scenarios)
        try {
            $connector = new FilePrintConnector($printerName);
            $printer = new Printer($connector);
            $printer->text(file_get_contents($tempFilePath));
            $printer->cut();
            $printer->close();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to print: ' . $e->getMessage()], 500);
        }

        // Clean up the temporary file
        unlink($tempFilePath);

        return response()->json(['success' => 'Order printed successfully']);
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
