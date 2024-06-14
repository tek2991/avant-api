<div>
    <div>
        {{-- Print --}}
        <button type="button" onclick="printDiv()"
            class="flex items-center px-2 py-1 mb-3 border rounded-md hover:border-blue-300 hover:underline hover:text-blue-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                <circle cx="12" cy="12" r="2" />
                <rect x="2" y="6" width="20" height="12" rx="2" />
            </svg>
            <span class="pl-1">Print</span>
        </button>
        {{-- Download as PDF --}}
        {{-- <button type="button" onclick="downloadPDF()"
            class="flex items-center px-2 py-1 mb-3 border rounded-md hover:border-blue-300 hover:underline hover:text-blue-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <rect x="0" y="0" width="24" height="24" stroke="none"></rect>
                <circle cx="12" cy="12" r="2" />
                <rect x="2" y="6" width="20" height="12" rx="2" />
            </svg>
            <span class="pl-1">Download as PDF</span>
        </button> --}}
    </div>
    <div class="relative overflow-x-auto sm:rounded-lg">
        <div class="pb-4 pl-2">
            <div class="grid gap-6 mb-6 grid-cols-2">
                <div class="max-w-xs">
                    <label for="from_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date
                        from</label>
                    <input type="date" id="from_date"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="John" required wire:model="start_date">
                    @error('start_date')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="max-w-xs">
                    <label for="to_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date
                        to</label>
                    <input type="date" id="to_date"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Doe" required wire:model="end_date">
                    @error('end_date')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div id="printarea">
            @php
                $s_date = date('d-m-Y', strtotime($start_date));
                $e_date = date('d-m-Y', strtotime($end_date));
            @endphp
            <div class="text-center">
                <span class="font-bold">{{ $variables['ADDRESS_LINE_1'] }}</span> <br>
                {{ $variables['ADDRESS_LINE_2'] }}
                {{ $variables['ADDRESS_LINE_3'] }}<br>
                <div class="font-semibold mt-2 mb-4">Counter Receipt Report: {{ $s_date }} - {{ $e_date }}
                </div>
            </div>
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Sl
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Receipt No.
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Class
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Student
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Total
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Date
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                    @endphp
                    @foreach ($counter_receipts as $receipt)
                        <tr
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">

                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $loop->iteration }}
                            </th>

                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $receipt->id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $receipt->standard->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $receipt->student->user->userDetail->name }}
                            </td>
                            <td class="px-6 py-4">
                                ₹ {{ number_format($receipt->totalAmount(), 2) }} /-
                            </td>
                            <td class="px-6 py-4">
                                {{ $receipt->created_at->format('d-m-Y') }}
                            </td>
                        </tr>
                        @php
                            $total += $receipt->totalAmount();
                        @endphp
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-semibold">
                        <td colspan="4"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            Total
                        </td>
                        <td colspan="1"
                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            ₹ {{ number_format($total, 2) }}/-
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <script>
        function printDiv() {
            var printContents = document.getElementById("printarea").innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

        function downloadPDF() {
            
        }
    </script>
</div>
