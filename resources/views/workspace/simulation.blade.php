<x-student-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $task->module->title }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen" x-data="simulationGame('{{ route('workspace.submit', $task->id) }}', '{{ csrf_token() }}')">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header Tugas --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-2 flex items-center gap-3">
                        <span class="text-3xl">🧑‍💻</span> {{ $task->title }}
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400">
                        {{ $task->instruction }}
                    </p>
                </div>
                <div x-show="isComplete">
                    <button type="button" @click="submitSimulation()" :disabled="isSubmitting"
                        class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition flex items-center gap-2">
                        <span x-text="isSubmitting ? 'Menyimpan...' : 'Selesaikan Latihan'"></span>
                        <svg x-show="!isSubmitting" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- PANEL KIRI: Kontrol --}}
                <div class="lg:col-span-5 space-y-6">
                    
                    {{-- Pilihan Mode --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                        <h3 class="font-bold text-gray-800 dark:text-gray-200 mb-4">Pilih Peran</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <button @click="setMode('programmer')" class="p-4 rounded-xl border-2 text-left transition relative overflow-hidden"
                                :class="mode === 'programmer' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 hover:border-blue-300 dark:border-gray-700 dark:hover:border-blue-800'">
                                <span class="text-2xl mb-2 block">🧠</span>
                                <h4 class="font-bold text-gray-900 dark:text-white text-sm">Saya Programmer</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Menyusun instruksi agar komputer menggambar.</p>
                            </button>
                            <button @click="setMode('komputer')" class="p-4 rounded-xl border-2 text-left transition relative overflow-hidden"
                                :class="mode === 'komputer' ? 'border-orange-500 bg-orange-50 dark:bg-orange-900/20' : 'border-gray-200 hover:border-orange-300 dark:border-gray-700 dark:hover:border-orange-800'">
                                <span class="text-2xl mb-2 block">🤖</span>
                                <h4 class="font-bold text-gray-900 dark:text-white text-sm">Saya Komputer</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Menjalankan instruksi yang diberikan.</p>
                            </button>
                        </div>
                    </div>

                    {{-- Pilihan Bentuk Target --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6" x-show="mode === 'programmer'">
                        <h3 class="font-bold text-gray-800 dark:text-gray-200 mb-4">Pilih Bentuk Target</h3>
                        <div class="flex gap-3">
                            <button @click="targetShape = 'persegi'" class="px-4 py-2 rounded-lg text-sm font-semibold border-2 transition"
                                :class="targetShape === 'persegi' ? 'bg-indigo-50 border-indigo-500 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400' : 'border-gray-200 text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400'">
                                <span class="inline-block w-3 h-3 bg-indigo-500 mr-2"></span> Persegi
                            </button>
                            <button @click="targetShape = 'persegipanjang'" class="px-4 py-2 rounded-lg text-sm font-semibold border-2 transition"
                                :class="targetShape === 'persegipanjang' ? 'bg-indigo-50 border-indigo-500 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400' : 'border-gray-200 text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400'">
                                <span class="inline-block w-4 h-3 bg-indigo-500 mr-2"></span> Persegi Panjang
                            </button>
                        </div>
                    </div>

                    {{-- Tambah Instruksi (Hanya Programmer) --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6" x-show="mode === 'programmer'">
                        <h3 class="font-bold text-gray-800 dark:text-gray-200 mb-4">Tambah Instruksi</h3>
                        <div class="grid grid-cols-2 gap-2">
                            <template x-for="block in instructionBlocks" :key="block.id">
                                <button @click="addInstruction(block)" class="flex items-center p-2.5 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg text-xs font-semibold text-blue-700 transition dark:bg-blue-900/30 dark:border-blue-800 dark:text-blue-300 dark:hover:bg-blue-800/50">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path x-show="block.dir === 'kanan'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        <path x-show="block.dir === 'kiri'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                        <path x-show="block.dir === 'atas'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                        <path x-show="block.dir === 'bawah'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                    </svg>
                                    Gambar garis ke <span x-text="block.dir" class="mx-1"></span> <span x-text="block.val"></span> px
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- Urutan Instruksi --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                        <h3 class="font-bold text-gray-800 dark:text-gray-200 mb-4" x-text="mode === 'programmer' ? 'Urutan Instruksi' : 'Instruksi dari Programmer'"></h3>
                        
                        <div x-show="instructions.length === 0" class="p-4 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-500 text-center dark:bg-gray-700 dark:border-gray-600 dark:text-gray-400">
                            Belum ada instruksi. Tambahkan langkah di atas.
                        </div>

                        <div class="space-y-2 mb-4" x-show="instructions.length > 0">
                            <template x-for="(inst, index) in instructions" :key="index">
                                <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700"
                                    :class="{'ring-2 ring-orange-500': mode === 'komputer' && currentStepIndex === index}">
                                    <div class="flex items-center gap-3">
                                        <span class="w-6 h-6 flex items-center justify-center rounded-full bg-blue-100 text-blue-700 text-xs font-bold dark:bg-blue-900/50 dark:text-blue-400" x-text="index + 1"></span>
                                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                            Gambar garis ke <span x-text="inst.dir"></span> <span x-text="inst.val"></span> px
                                        </span>
                                    </div>
                                    <div class="flex gap-1" x-show="mode === 'programmer' && !isRunning">
                                        <button @click="moveUp(index)" x-show="index > 0" class="px-2 py-1 text-xs text-gray-500 hover:bg-gray-100 rounded">Naik</button>
                                        <button @click="moveDown(index)" x-show="index < instructions.length - 1" class="px-2 py-1 text-xs text-gray-500 hover:bg-gray-100 rounded">Turun</button>
                                        <button @click="removeInstruction(index)" class="px-2 py-1 text-xs text-red-500 hover:bg-red-50 rounded">Hapus</button>
                                    </div>
                                    <div x-show="mode === 'komputer'">
                                        <span x-show="currentStepIndex > index" class="text-emerald-500">✓ Selesai</span>
                                        <span x-show="currentStepIndex === index" class="text-orange-500 text-xs font-bold animate-pulse">Menunggu...</span>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="flex flex-wrap gap-2" x-show="mode === 'programmer'">
                            <button @click="runProgrammer()" :disabled="instructions.length === 0 || isRunning"
                                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-md transition disabled:opacity-50">
                                Jalankan Program
                            </button>
                            <button @click="loadExample()" :disabled="isRunning"
                                class="px-4 py-2.5 bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 text-sm font-semibold rounded-xl transition dark:bg-amber-900/20 dark:border-amber-800 dark:text-amber-400">
                                ✨ Contoh Benar
                            </button>
                            <button @click="clearAll()" :disabled="instructions.length === 0 || isRunning"
                                class="px-4 py-2.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 text-sm font-semibold rounded-xl transition ml-auto dark:bg-red-900/20 dark:border-red-800 dark:text-red-400">
                                Hapus Semua
                            </button>
                        </div>

                        <div class="flex flex-wrap gap-2 mt-4" x-show="mode === 'komputer'">
                            <button @click="startComputerSimulation()" :disabled="isRunning"
                                class="px-5 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-sm font-bold rounded-xl shadow-md transition disabled:opacity-50 w-full flex justify-center items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Mulai Eksekusi
                            </button>
                        </div>
                    </div>
                </div>

                {{-- PANEL KANAN: Kanvas & Log --}}
                <div class="lg:col-span-7 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                        <h3 class="font-bold text-gray-800 dark:text-gray-200 mb-2">Area Simulasi</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4" x-text="mode === 'programmer' ? 'Saat program dijalankan, garis akan digambar secara otomatis.' : 'Tekan Mulai Eksekusi untuk melihat Komputer menggambar instruksi yang diberikan.'"></p>

                        <div class="relative w-full aspect-square md:aspect-video bg-[#f8fbff] dark:bg-gray-900 rounded-xl border-2 border-dashed border-blue-200 dark:border-gray-700 overflow-hidden flex items-center justify-center">
                            {{-- Grid Pattern SVG Background --}}
                            <div class="absolute inset-0 opacity-20 dark:opacity-10 pointer-events-none" style="background-image: linear-gradient(#3b82f6 1px, transparent 1px), linear-gradient(90deg, #3b82f6 1px, transparent 1px); background-size: 25px 25px; background-position: center;"></div>
                            
                            {{-- Canvas for drawing --}}
                            <canvas id="simCanvas" width="600" height="400" class="z-10 bg-transparent"></canvas>
                        </div>

                        <div class="mt-4 p-4 rounded-xl" x-show="feedback.message" x-transition
                            :class="feedback.success ? 'bg-emerald-50 border border-emerald-200 text-emerald-800 dark:bg-emerald-900/30 dark:border-emerald-800 dark:text-emerald-300' : 'bg-red-50 border border-red-200 text-red-800 dark:bg-red-900/30 dark:border-red-800 dark:text-red-300'">
                            <p class="font-bold text-sm" x-text="feedback.message"></p>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                        <h3 class="font-bold text-gray-800 dark:text-gray-200 mb-4">Log Eksekusi</h3>
                        <div class="h-48 overflow-y-auto font-mono text-sm space-y-1 p-4 bg-gray-50 border border-gray-200 rounded-xl dark:bg-gray-900 dark:border-gray-700 text-gray-600 dark:text-gray-300" id="logContainer">
                            <template x-for="(log, idx) in logs" :key="idx">
                                <div x-text="log"></div>
                            </template>
                            <div x-show="logs.length === 0" class="text-gray-400 italic">1. Belum ada program dijalankan.</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function simulationGame(submitUrl, csrfToken) {
            return {
                mode: 'programmer', // programmer or komputer
                targetShape: 'persegi',
                
                instructionBlocks: [
                    { id: 1, dir: 'kanan', val: 50 },
                    { id: 2, dir: 'bawah', val: 50 },
                    { id: 3, dir: 'kiri', val: 50 },
                    { id: 4, dir: 'atas', val: 50 },
                    { id: 5, dir: 'kanan', val: 100 },
                    { id: 6, dir: 'bawah', val: 100 },
                    { id: 7, dir: 'kiri', val: 100 },
                    { id: 8, dir: 'atas', val: 100 },
                ],
                
                instructions: [],
                logs: [],
                isRunning: false,
                currentStepIndex: 0,
                
                feedback: { success: false, message: '' },
                
                // Canvas properties
                ctx: null,
                startX: 300,
                startY: 200,
                currentX: 300,
                currentY: 200,

                // Progress tracking
                programmerCompleted: false,
                komputerCompleted: false,
                isComplete: false,
                isSubmitting: false,

                init() {
                    this.$nextTick(() => {
                        const canvas = document.getElementById('simCanvas');
                        this.ctx = canvas.getContext('2d');
                        this.drawInitialState();
                    });
                },

                setMode(newMode) {
                    if (this.isRunning) return;
                    this.mode = newMode;
                    this.clearAll();

                    if (this.mode === 'komputer') {
                        // Generate bad/good instructions for computer to execute
                        this.instructions = [
                            { dir: 'kanan', val: 100 },
                            { dir: 'bawah', val: 50 },
                            { dir: 'kiri', val: 100 },
                            { dir: 'atas', val: 50 }
                        ];
                        this.addLog("Sistem: Beralih ke mode Komputer. Instruksi telah dimuat.");
                    } else {
                        this.addLog("Sistem: Beralih ke mode Programmer. Silakan susun instruksi.");
                    }
                },

                addInstruction(block) {
                    if (this.isRunning) return;
                    this.instructions.push({ ...block });
                    this.addLog(`+ Ditambahkan: Gambar garis ke ${block.dir} ${block.val} px`);
                },

                removeInstruction(index) {
                    if (this.isRunning) return;
                    this.instructions.splice(index, 1);
                },

                moveUp(index) {
                    if (this.isRunning || index === 0) return;
                    const temp = this.instructions[index - 1];
                    this.instructions[index - 1] = this.instructions[index];
                    this.instructions[index] = temp;
                },

                moveDown(index) {
                    if (this.isRunning || index === this.instructions.length - 1) return;
                    const temp = this.instructions[index + 1];
                    this.instructions[index + 1] = this.instructions[index];
                    this.instructions[index] = temp;
                },

                clearAll() {
                    if (this.isRunning) return;
                    this.instructions = [];
                    this.logs = [];
                    this.feedback = { success: false, message: '' };
                    this.drawInitialState();
                },

                loadExample() {
                    if (this.isRunning) return;
                    this.clearAll();
                    if (this.targetShape === 'persegi') {
                        this.instructions = [
                            { dir: 'kanan', val: 100 },
                            { dir: 'bawah', val: 100 },
                            { dir: 'kiri', val: 100 },
                            { dir: 'atas', val: 100 }
                        ];
                    } else {
                        this.instructions = [
                            { dir: 'kanan', val: 100 },
                            { dir: 'bawah', val: 50 },
                            { dir: 'kiri', val: 100 },
                            { dir: 'atas', val: 50 }
                        ];
                    }
                    this.addLog("Sistem: Contoh instruksi yang benar telah dimuat.");
                },

                addLog(msg) {
                    const line = `${this.logs.length + 1}. ${msg}`;
                    this.logs.push(line);
                    this.$nextTick(() => {
                        const container = document.getElementById('logContainer');
                        container.scrollTop = container.scrollHeight;
                    });
                },

                drawInitialState() {
                    if (!this.ctx) return;
                    this.ctx.clearRect(0, 0, 600, 400);
                    
                    // Draw start point
                    this.ctx.beginPath();
                    this.ctx.arc(this.startX, this.startY, 5, 0, 2 * Math.PI);
                    this.ctx.fillStyle = '#ef4444'; // Red dot
                    this.ctx.fill();
                    
                    this.ctx.fillStyle = '#6b7280';
                    this.ctx.font = '12px sans-serif';
                    this.ctx.fillText("Titik Awal", this.startX + 10, this.startY - 10);

                    this.currentX = this.startX;
                    this.currentY = this.startY;
                },

                async runProgrammer() {
                    this.isRunning = true;
                    this.feedback = { success: false, message: '' };
                    this.logs = [];
                    this.drawInitialState();
                    this.addLog("Sistem: Memulai eksekusi program...");

                    for (let i = 0; i < this.instructions.length; i++) {
                        this.currentStepIndex = i;
                        const inst = this.instructions[i];
                        this.addLog(`Langkah ${i+1}: Gambar garis ke ${inst.dir} ${inst.val} px`);
                        
                        await this.animateLine(inst);
                        await new Promise(r => setTimeout(r, 300)); // Pause between lines
                    }

                    this.verifyShape();
                    this.isRunning = false;
                },

                async startComputerSimulation() {
                    this.isRunning = true;
                    this.feedback = { success: false, message: '' };
                    this.logs = [];
                    this.drawInitialState();
                    this.addLog("Sistem: Komputer mengeksekusi instruksi dari Programmer...");

                    for (let i = 0; i < this.instructions.length; i++) {
                        this.currentStepIndex = i;
                        const inst = this.instructions[i];
                        this.addLog(`[Komputer] Langkah ${i+1}: Menggambar ke ${inst.dir} ${inst.val} px`);
                        
                        await this.animateLine(inst);
                        await new Promise(r => setTimeout(r, 600)); // Slower for computer mode observation
                    }
                    
                    this.currentStepIndex++;
                    this.feedback = { success: true, message: "Simulasi Komputer selesai dijalankan!" };
                    this.komputerCompleted = true;
                    this.checkCompletion();
                    this.isRunning = false;
                },

                animateLine(inst) {
                    return new Promise(resolve => {
                        let targetX = this.currentX;
                        let targetY = this.currentY;

                        if (inst.dir === 'kanan') targetX += inst.val;
                        if (inst.dir === 'kiri') targetX -= inst.val;
                        if (inst.dir === 'bawah') targetY += inst.val;
                        if (inst.dir === 'atas') targetY -= inst.val;

                        // Animation frame approach
                        let steps = 20;
                        let stepX = (targetX - this.currentX) / steps;
                        let stepY = (targetY - this.currentY) / steps;
                        let currentStep = 0;

                        const drawFrame = () => {
                            this.ctx.beginPath();
                            this.ctx.moveTo(this.currentX, this.currentY);
                            this.currentX += stepX;
                            this.currentY += stepY;
                            this.ctx.lineTo(this.currentX, this.currentY);
                            this.ctx.strokeStyle = '#2563eb'; // Blue line
                            this.ctx.lineWidth = 3;
                            this.ctx.lineCap = 'round';
                            this.ctx.stroke();

                            currentStep++;
                            if (currentStep < steps) {
                                requestAnimationFrame(drawFrame);
                            } else {
                                // Snap to exact target to avoid float errors
                                this.currentX = targetX;
                                this.currentY = targetY;
                                resolve();
                            }
                        };
                        requestAnimationFrame(drawFrame);
                    });
                },

                verifyShape() {
                    // Cek apakah tertutup sempurna (kembali ke startX, startY)
                    const isClosed = (Math.abs(this.currentX - this.startX) < 1 && Math.abs(this.currentY - this.startY) < 1);
                    
                    if (!isClosed) {
                        this.feedback = { success: false, message: "Bentuk belum tertutup! Garis terakhir harus kembali ke titik awal." };
                        return;
                    }

                    if (this.instructions.length !== 4) {
                        this.feedback = { success: false, message: `Instruksinya kurang atau berlebih. Bentuk segi empat butuh tepat 4 instruksi.` };
                        return;
                    }

                    // Cek jenis bentuk berdasarkan value yang dipakai
                    const vals = this.instructions.map(i => i.val);
                    const isSquare = vals.every(v => v === vals[0]);
                    
                    if (this.targetShape === 'persegi' && isSquare) {
                        this.feedback = { success: true, message: "Hebat! Kamu berhasil menyusun instruksi untuk membuat Persegi." };
                        this.programmerCompleted = true;
                    } else if (this.targetShape === 'persegipanjang' && !isSquare) {
                        // Very simple validation for rectangle (just needs non-uniform sides)
                        this.feedback = { success: true, message: "Luar biasa! Kamu berhasil menyusun instruksi untuk membuat Persegi Panjang." };
                        this.programmerCompleted = true;
                    } else {
                        this.feedback = { success: false, message: `Bentuk yang dihasilkan tidak sesuai dengan target (${this.targetShape}).` };
                    }

                    this.checkCompletion();
                },

                checkCompletion() {
                    if (this.programmerCompleted && this.komputerCompleted) {
                        this.isComplete = true;
                    }
                },

                submitSimulation() {
                    if (this.isSubmitting) return;
                    this.isSubmitting = true;

                    fetch(submitUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            answer_data: { completed: true, programmer: true, komputer: true }
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        window.location.reload(); // Will redirect to index or success page
                    })
                    .catch(e => {
                        console.error(e);
                        alert('Gagal menyimpan progress.');
                        this.isSubmitting = false;
                    });
                }
            };
        }
    </script>
</x-student-layout>
