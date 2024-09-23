<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <p>Scrim Details</p>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                role="alert">
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            @endif

            @if ($scrim_requests && count($scrim_requests))
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="relative">
                            <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">Scrim Requests</h3>

                            @foreach ($scrim_requests as $scrim_request)
                                <div class="w-f rounded-lg bg-dark text-[0.8125rem] leading-5 text-slate-900 shadow-xl shadow-black/5 ring-1 ring-slate-700/10 dark:text-gray-400">
                                    <div class="flex items-center p-4 pb-0">
                                        <div class="flex-auto">
                                            <div class="font-medium">{{ $scrim_request->teamFrom->team_name }}</div>
                                            <div class="mt-1 text-slate-500">
                                                Sent you an scrim request on
                                                {{ Carbon\Carbon::parse($scrim_request->date)->format('d M') }}
                                                {{ Carbon\Carbon::parse($scrim_request->time)->format('H:i A') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex gap-3 p-4">
                                        <a href="{{ route('scrims.accept', $scrim_request->id) }}" class="pointer-events-auto rounded-md bg-indigo-600 px-3 py-2 text-[0.8125rem] font-semibold leading-5 text-white hover:bg-indigo-500">
                                            Accept
                                        </a>
                                        <a href="{{ route('scrims.reject', $scrim_request->id) }}" class="pointer-events-auto rounded-md px-4 py-2 text-center font-medium shadow-sm ring-1 ring-slate-700/10 hover:bg-slate-50">
                                            Reject
                                        </a>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            @endif

            <div class="py-12">
                <div class="max-w-7xl mx-auto">
                    @if ($errors->any())
                    <div class="flex p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                        role="alert">
                        <span class="sr-only">Danger</span>
                        <div>
                            <ul class="mt-1.5 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-4">Scrim
                                Scheduled </h3>
                            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Team Name
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Date & Time
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Status
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($scrim_scheduled as $scheduled)
                                            <tr
                                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                <th scope="row"
                                                    class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                                    <div class="ps-3">
                                                        <div class="text-base font-semibold">
                                                            @if ($scheduled->is_me)
                                                                {{ $scheduled?->teamTo?->team_name }}
                                                            @else
                                                                {{ $scheduled?->teamFrom?->team_name }}
                                                            @endif
                                                        </div>
                                                        <div class="font-normal text-gray-500">
                                                            {{ $scheduled?->teamFrom?->email }}
                                                        </div>
                                                    </div>
                                                </th>
                                                <td class="px-6 py-4">
                                                    {{ Carbon\Carbon::parse($scheduled->date)->format('d M') }}
                                                    {{ Carbon\Carbon::parse($scheduled->time)->format('H:i A') }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if ($scheduled->status == 'accepted')
                                                        <div class="flex items-center">
                                                            <div class="h-2.5 w-2.5 rounded-full bg-yellow-500 me-2"></div>
                                                            Scheduled
                                                        </div>
                                                    @elseif ($scheduled->status == 'finished')
                                                        <div class="flex items-center">
                                                            <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div>
                                                            Finished
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if ($scheduled->status == 'accepted')
                                                    <button data-modal-target="popup-modal_{{ $scheduled->id }}" data-modal-toggle="popup-modal_{{ $scheduled->id }}"
                                                        class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                                        type="button">
                                                        Finish Match
                                                    </button>
                                                    @endif
                                                    @if ($scheduled->status == 'finished')
                                                    <button data-modal-target="details-modal_{{ $scheduled->id }}" data-modal-toggle="details-modal_{{ $scheduled->id }}"
                                                        class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                                        type="button">
                                                        Details Match
                                                    </button>
                                                    @endif

                                                    @if ($scheduled->status == 'accepted')
                                                    <div id="popup-modal_{{ $scheduled->id }}" tabindex="-1"
                                                        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                                        <div class="relative p-4 w-full max-w-md max-h-full">
                                                            <form action="{{ route('scrims.submit') }}" method="post" enctype="multipart/form-data">
                                                                @csrf
                                                                <input type="hidden" name="scrim_id" value="{{ $scheduled->id }}">
                                                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                                    <!-- Modal header -->
                                                                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                                                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                                            Terms of Service
                                                                        </h3>
                                                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
                                                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                                            </svg>
                                                                            <span class="sr-only">Close modal</span>
                                                                        </button>
                                                                    </div>
                                                                    <!-- Modal body -->
                                                                    <div class="p-4 md:p-5 space-y-4">
                                                                        <div class="mb-5">
                                                                            <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                                                Screenshot
                                                                            </label>

                                                                            <input name="image" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="file_input" type="file">
                                                                        </div>
                                                                    </div>
                                                                    <!-- Modal footer -->
                                                                    <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                                                        <button data-modal-hide="default-modal" type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                                                                        <button data-modal-hide="default-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancel</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    @endif
                                                     @if ($scheduled->status == 'finished')
                                                     <div id="details-modal_{{ $scheduled->id }}" tabindex="-1"
                                                         class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                                         <div class="relative p-4 w-full max-w-md max-h-full">
                                                             <form action="{{ route('scrims.submit') }}" method="post" enctype="multipart/form-data">
                                                                 @csrf
                                                                 <input type="hidden" name="scrim_id" value="{{ $scheduled->id }}">
                                                                 <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                                     <!-- Modal header -->
                                                                     <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                                                         <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                                                             Terms of Service
                                                                         </h3>
                                                                         <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
                                                                             <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                                                 <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                                             </svg>
                                                                             <span class="sr-only">Close modal</span>
                                                                         </button>
                                                                     </div>
                                                                     <!-- Modal body -->
                                                                     <div class="p-4 md:p-5 space-y-4">
                                                                         <div class="mb-5">
                                                                             <img height="200px" width="200px" src="{{ asset('images/'. $scheduled->image) }}" />
                                                                         </div>
                                                                     </div>
                                                                     <!-- Modal footer -->
                                                                     <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">

                                                                         <button data-modal-hide="default-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Cancel</button>
                                                                     </div>
                                                                 </div>
                                                             </form>
                                                         </div>
                                                     </div>
                                                    @endif



                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
