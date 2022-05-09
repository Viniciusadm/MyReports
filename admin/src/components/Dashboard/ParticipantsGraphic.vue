<template>
    <h2>Participantes</h2>
    <v-chart :option="option" :autoresize="true" />
</template>

<script>
import api from '@/services/api';
import { use } from "echarts/core";
import { CanvasRenderer } from "echarts/renderers";
import { BarChart } from "echarts/charts";
import {
    TitleComponent,
    TooltipComponent,
    LegendComponent,
    GridComponent,
} from "echarts/components";
import VChart from "vue-echarts";

use([
    CanvasRenderer,
    BarChart,
    TitleComponent,
    TooltipComponent,
    LegendComponent,
    GridComponent,
]);

export default {
    methods: {
        getData() {
            api.get('/dashboard')
                .then(response => {
                    if (response.data.success) {
                        const participants = response.data.data.participants;
                        this.option.xAxis.data = Object.keys(participants);
                        this.option.series[0].data = Object.values(participants);
                    }
                })
        },
    },
    mounted() {
        this.getData();
    },
    components: {
        VChart
    },
    data() {
        return {
            option: {
                xAxis: {
                    type: 'category',
                    data: [],
                },
                tooltip: {
                    trigger: "item",
                    formatter: "{b} : {c}"
                },
                emphasis: {
                    itemStyle: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: "rgba(0, 0, 0, 0.5)"
                    }
                },
                yAxis: {},
                series: [
                    {
                        type: 'bar',
                        data: []
                    }
                ]
            },
        }
    }
};
</script>
