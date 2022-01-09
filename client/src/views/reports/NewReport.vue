<template>
    <div class="new_report">
    </div>
</template>

<style lang="scss" scoped>

</style>

<script>

export default {
    data() {
        return {
        }
    },
    methods: {
        sendReport() {
            const formData = new FormData();
            formData.append('title', 'Júlia');
            formData.append('report', 'A júlia me mordeu');
            formData.append('humor', 'Triste');
            formData.append('type', 'daily');
            formData.append('persons_ids', [1]);

            fetch('http://localhost:8000/api/reports/', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(json => {
                console.log(json)
            })
        }
    },
    mounted() {
        this.sendReport();
    }
}

</script>