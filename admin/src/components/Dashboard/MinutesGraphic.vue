<template>
    <h2>Tempo assistido por dia</h2>
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
            api.get('/dashboard/minutes')
                .then(response => {
                    if (response.data.success) {
                        const minutes = response.data.data;
                        this.option.xAxis.data = Object.keys(minutes);
                        this.option.series[0].data = Object.values(minutes);
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
