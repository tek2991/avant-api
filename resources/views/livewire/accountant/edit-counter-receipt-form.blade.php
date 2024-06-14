<div>
    <form wire:submit.prevent="submit">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="standard_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"> Standard
                </label>
                <input type="text" id="standard_id"
                    class="rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    readonly value="{{ $counterReceipt->standard->name }}">
            </div>

            <div>
                <label for="student_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Student
                </label>
                <input type="text" id="standard_id"
                    class="rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    readonly value="{{ $counterReceipt->student->user->userDetail->name }}">
            </div>

            <div class="md:col-span-3">
                <label for="remarks" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"> Remarks
                </label>
                <textarea id="message" rows="4"
                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    readonly>{{ $counterReceipt->remarks }}</textarea>
            </div>
        </div>
    </form>


    <form wire:submit.prevent="addCounterReceiptItem">
        <h2 class="text-lg font-bold py-3 mt-10">Add item</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="counter_receipt_item_type_id"
                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"> Item
                </label>
                <select id="standard_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    wire:model="state.counter_receipt_item_type_id">
                    <option value="">Select Item Type</option>
                    @foreach ($counterReceiptItemTypes as $counterReceiptItemType)
                        <option value="{{ $counterReceiptItemType->id }}">{{ $counterReceiptItemType->name }}
                        </option>
                    @endforeach
                </select>
                @error('state.counter_receipt_item_type_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="amount" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"> Amount
                </label>
                <input type="number" id="amount" wire:model="state.amount"
                    class="rounded-lg bg-gray-50 border border-gray-300 text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                @error('state.amount')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="md:col-span-3">
                <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Add Item
                </button>
            </div>
        </div>
    </form>


    <div class="mt-10 flex justify-between">
        <h2 class="text-lg font-bold py-3">Items</h2>
    </div>

    <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-white uppercase tracking-wider">
                                    Sl
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-white uppercase tracking-wider">
                                    Item
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-white uppercase tracking-wider">
                                    Amount
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Remove</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800">
                            @foreach ($counterReceiptItems as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $loop->iteration }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $item->counterReceiptItemType->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            {{ $item->amount_in_cents / 100 }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">
                                            <button type="button"
                                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-500 border border-transparent rounded-md hover:bg-red-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-red-500"
                                                wire:click="removeCounterReceiptItem({{ $item->id }})">
                                                Remove
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        {{-- Total  --}}
                        <tfoot class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-white uppercase tracking-wider">
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-white uppercase tracking-wider">
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-white uppercase tracking-wider">
                                    Total: {{ $counterReceiptItems->sum('amount_in_cents') / 100 }}
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Remove</span>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-10 flex justify-between">
        <h2 class="text-lg font-bold py-3">Confirm and Save</h2>
    </div>

    <div class="flex mt-4">
        <form wire:submit.prevent="confirmAndSave">
            <div class="mb-4">
                <div>
                    <input checked id="checkbox-1" type="checkbox" wire:model="confirmation"
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="checkbox-1" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">I confirm
                        that the information provided is correct and I am responsible for the information provided.
                    </label>
                </div>

                @if ($error)
                    <label class="text-red-500 text-sm py-3">{{ $error }}</label>
                @endif

                @error('confirmation')
                    <label class="text-red-500 text-sm py-3">{{ $message }}</label>
                @enderror
            </div>

            <button type="submit"
                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-500 border border-transparent rounded-md hover:bg-green-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-green-500">
                Confirm and Save
            </button>
        </form>
    </div>
</div>
