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
          if (!newValue.type) {
            return
          }
          if (!this.chartObj) {
            this.chartObj = echarts.init(this.$refs.chart)
          }
          let options = {
            title: {
              text: newValue.title || ''
            },
            xAxis: {
              type: newValue.xAxis.type,
              name: newValue.xAxis.name || null,
              data: newValue.xAxis.data
            },
            yAxis: {
              type: newValue.yAxis.type,
              name: newValue.yAxis.name || null
            },
            series: []
          }
          for (let key in newValue.data) {
            let serie = {
              id: key,
              type: newValue.type,
              data: newValue.data[key]
            }
            options.series.push(serie)
          }
          this.chartObj.setOption(options, {notMerge: true})
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
