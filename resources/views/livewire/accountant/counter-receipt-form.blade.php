<div>
    <form wire:submit.prevent="submit">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="standard_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"> Standard
                </label>
                <select id="standard_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    wire:model="state.standard_id">
                    <option value="">Select Standard</option>
                    @foreach ($standards as $standard)
                        <option value="{{ $standard->id }}">{{ $standard->name }}</option>
                    @endforeach
                </select>
                @error('state.standard_id') <span class="text-red-500">{{ $message }}</span>@enderror
            </div>

            <div>
                <label for="student_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Student
                </label>
                <select id="student_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    wire:model="state.student_id">
                    <option value="">Select Student</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}">{{ $student->user->userDetail->name }} (Roll:
                            {{ $student->roll_no }})</option>
                    @endforeach
                </select>
                @error('state.student_id') <span class="text-red-500">{{ $message }}</span>@enderror
            </div>

            <div class="md:col-span-3">
                <label for="remarks" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"> Remarks
                </label>
                <textarea id="message" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a comment..." wire:model="state.remarks"></textarea>
                @error('state.remarks') <span class="text-red-500">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="flex justify-end mt-4">
            <button type="submit"
                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500">
                Next
            </button>
        </div>
    </form>
</div>
