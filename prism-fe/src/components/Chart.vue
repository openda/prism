<template>
  <div ref="chart">

  </div>
</template>

<script>
  import echarts from 'echarts'
  export default {
    data () {
      return {
        chartObj: null
      }
    },
    props: ['chart_options'],
    watch: {
      chart_options: {
        handler (newValue, oldValue) {
          if (!this.chartObj) {
            this.chartObj = echarts.init(this.$refs.chart)
          }
          console.log(newValue)
          let options = {
            title: {
              text: newValue.title || ''
            },
            xAxis: {
              type: newValue.xAxis.type,
              name: newValue.xAxis.name || null
            },
            yAxis: {
              type: newValue.yAxis.type,
              name: newValue.yAxis.name || null
            },
            series: [{
              type: newValue.type,
              data: []
            }]
          }
          this.chartObj.setOption(options)
        },
        deep: true
      }
    }
  }
</script>

<style scoped>
  div{
    width: 100%;
    height: 100%;
  }
</style>
