<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; // Assuming you have an Order model
use PDF;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Illuminate\Support\Facades\Log;

class OrderPrintController extends Controller
{
    //
    public function printOrder($orderId)
    {
        Log::info('Printing order: ' . $orderId);
        // Fetch the order and printer name from the database
        $order = Order::findOrFail($orderId);
        $printerName = 'Black Copper BC-85AC'; // Assuming the printer name is stored in the order

        try {
            $connector = new WindowsPrintConnector($printerName);
            $printer = new Printer($connector);
        
            // Set the paper width (80mm)
            $printer->selectPrintMode(Printer::MODE_FONT_B);
            $printer->setJustification(Printer::JUSTIFY_CENTER);
        
            // Print a header
        $printer->text("Grocery Garden\n");
        $printer->text("Tel: 0346-0323336\n");
        $printer->text("Order Number: " . $order->id . "\n");
        $printer->text("--------------------\n");

        // Adjusted for table-like structure
        $printer->setJustification(\Mike42\Escpos\Printer::JUSTIFY_LEFT);
        $printer->text(sprintf("%-12s %8s %12s\n", "Product", "Qty", "Price"));

        $total = 0;
        foreach ($order->items as $item) {
            $itemPrice = floatval($item->price);
            $itemQuantity = intval($item->quantity);
        
            // Calculate the total for each item and add it to the overall total
            $total += $itemPrice * $itemQuantity;
        
            // Print each item line
            $printer->text(sprintf("%-10s %2d    Rs. %s\n", $item->product->name, $itemQuantity, number_format($itemPrice, 2, '.', '')));
        }

        // Print total
        $printer->text("--------------------\n");
        $printer->text(sprintf("%-12s %8s %12.2f\n", "", "Total:", $total));

        // Footer
        $printer->text("--------------------\n");
        $printer->text("Thank you for shopping with us\n");

            $printer->cut();
            $printer->close();
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
