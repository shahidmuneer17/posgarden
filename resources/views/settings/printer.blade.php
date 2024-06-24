<form action="#" method="POST">
    @csrf
    <label for="printer">Select Printer:</label>
    <select name="printer_name" id="printer">
        @foreach($printers as $printer)
            <option value="{{ $printer['Name'] }}">{{ $printer['Name'] }}</option>
        @endforeach
    </select>
    <button type="submit">Save</button>
</form>