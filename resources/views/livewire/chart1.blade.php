<div class="pb-[10px] bg-[#eee] min-h-[calc(50vh-46px)] flex items-center"
x-data="{
    data: $wire.entangle('data'),
    labels: $wire.entangle('labels'),
    chart: null,

    init() {
        this.chart = this.newchart(this.$refs.canvas, this.labels, this.data);
    },

    changechart() {
        this.chart.destroy();
        $wire.chartchange().then(() => {
            this.chart = this.newchart(this.$refs.canvas, this.labels, this.data);
        });
    },

    newchart(element, labels, data) {
        return new Chart(element, {
            type: $wire.chartType,
            data: {
                labels: labels,
                datasets: [{
                    label: $wire.label,
                    data: data,
                    backgroundColor: [...$wire.backgroundColor],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    },
}">

    <div class="w-full">
        <div style="position: relative; width: 100%; padding-top: 85%;">
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; max-height: none;">
                <canvas wire:ignore x-ref="canvas"></canvas>
            </div>
        </div>

        <div class="text-center mt-[10px]">
            <button @click="changechart" class="border-[1px] border-black p-[8px]">
                Update
            </button>
        </div>
    </div>

</div>