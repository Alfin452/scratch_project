<x-student-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="text-center mb-10">
                <h1 class="text-3xl font-extrabold text-gray-900">Papan Peringkat</h1>
                <p class="mt-2 text-gray-500">Siapa Juara Koding Minggu Ini?</p>
            </div>

            @if($students->count() >= 3)
            <div class="flex justify-center items-end gap-4 mb-12">
                <div class="flex flex-col items-center">
                    <div class="w-20 h-20 rounded-full border-4 border-gray-300 overflow-hidden mb-2 shadow-lg">
                        <div class="w-full h-full bg-gray-200 flex items-center justify-center font-bold text-2xl text-gray-500">
                            {{ substr($students[1]->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="text-sm font-bold text-gray-700">{{ $students[1]->name }}</div>
                    <div class="text-xs text-gray-500 font-bold">{{ $students[1]->submissions_sum_score ?? 0 }} XP</div>
                    <div class="w-24 h-32 bg-gray-300 rounded-t-lg mt-2 flex items-end justify-center pb-4 shadow-md">
                        <span class="text-4xl font-black text-gray-400 opacity-50">2</span>
                    </div>
                </div>

                <div class="flex flex-col items-center z-10 -mb-4">
                    <div class="relative">
                        <span class="absolute -top-6 left-1/2 transform -translate-x-1/2 text-3xl">👑</span>
                        <div class="w-24 h-24 rounded-full border-4 border-yellow-400 overflow-hidden mb-2 shadow-xl bg-white">
                            <div class="w-full h-full bg-yellow-100 flex items-center justify-center font-bold text-3xl text-yellow-600">
                                {{ substr($students[0]->name, 0, 1) }}
                            </div>
                        </div>
                    </div>
                    <div class="text-base font-bold text-gray-900">{{ $students[0]->name }}</div>
                    <div class="text-sm text-indigo-600 font-extrabold">{{ $students[0]->submissions_sum_score ?? 0 }} XP</div>
                    <div class="w-28 h-40 bg-gradient-to-b from-yellow-300 to-yellow-500 rounded-t-lg mt-2 flex items-end justify-center pb-4 shadow-lg">
                        <span class="text-5xl font-black text-white opacity-80">1</span>
                    </div>
                </div>

                <div class="flex flex-col items-center">
                    <div class="w-20 h-20 rounded-full border-4 border-orange-300 overflow-hidden mb-2 shadow-lg">
                        <div class="w-full h-full bg-orange-100 flex items-center justify-center font-bold text-2xl text-orange-500">
                            {{ substr($students[2]->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="text-sm font-bold text-gray-700">{{ $students[2]->name }}</div>
                    <div class="text-xs text-gray-500 font-bold">{{ $students[2]->submissions_sum_score ?? 0 }} XP</div>
                    <div class="w-24 h-24 bg-orange-300 rounded-t-lg mt-2 flex items-end justify-center pb-4 shadow-md">
                        <span class="text-4xl font-black text-orange-100 opacity-60">3</span>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white shadow-lg rounded-2xl overflow-hidden border border-gray-100">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Peringkat</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Siswa</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-right">Total XP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($students as $index => $student)
                        <tr class="hover:bg-indigo-50/30 transition {{ Auth::id() == $student->id ? 'bg-indigo-50 border-l-4 border-indigo-500' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-gray-500 w-8 inline-block text-center">#{{ $index + 1 }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold mr-4 text-sm">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $student->name }}
                                        @if(Auth::id() == $student->id)
                                        <span class="ml-2 text-[10px] bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-bold">Kamu</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <span class="text-sm font-extrabold text-indigo-600">{{ $student->submissions_sum_score ?? 0 }} pts</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-student-layout>