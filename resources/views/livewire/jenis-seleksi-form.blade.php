<div>
    <form wire:submit.prevent="submit">
        <div>
            <x-filament::select wire:model="tingkatSelected" multiple>
                <option value="">Pilih Tingkat</option>
                @foreach($tingkats as $tingkat)
                    <option value="{{ $tingkat->id }}">{{ $tingkat->nama }}</option>
                @endforeach
            </x-filament::select>
        </div>

        @if(!empty($tingkatSelected))
            <div>
                <x-filament::repeater wire:model="tingkatUrutan">
                    @foreach($tingkatSelected as $tingkatId)
                        <div>
                            <x-filament::text-input
                                wire:model="tingkatUrutan.{{ $tingkatId }}"
                                label="Urutan untuk {{ \App\Models\Tingkat::find($tingkatId)->nama }}"
                                placeholder="Masukkan urutan"
                                :disabled="true"
                            />
                        </div>
                    @endforeach
                </x-filament::repeater>
            </div>
        @endif

        <x-filament::button type="submit">Simpan</x-filament::button>
    </form>
</div>
