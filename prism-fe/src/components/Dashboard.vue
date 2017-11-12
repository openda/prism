<template>
  <Row>
    <Col span="24" id="container">
    <div class="item" v-for="item in list">
      <Card  style="width:400px" >
        <div class="chartArea">
          <chart v-if="ready" :chart_options="chart_options[item.report_id]"></chart>
        </div>
        <p>
          {{ item.report_brief }}
        </p>
      </Card>
    </div>
    </Col>
  </Row>
</template>

<script>
  import axios from 'axios'
  import inter from '../utils/interface'
  import Chart from './Chart.vue'
  export default {
    data: () => {
      return {
        list: [],
        chart_options: {},
        ready: false
      }
    },
    components: {
      'chart': Chart
    },
    methods: {
      preview: function (reportId, chartInstance, dataOption) {
        return new Promise(
          (resolve, reject) => {
            axios.get(inter.chartinstance + '?chart_info=' + JSON.stringify(dataOption))
              .then((res) => {
                let data = res.data.data
                let expressions = dataOption.expressions
                let group = dataOption.group
                let dataObj = {}
                for (let key in expressions) {
                  dataObj[expressions[key]] = []
                }
                for (let i = 0; i < data.length; i++) {
                  let item = data[i]
                  for (let k in item) {
                    dataObj[k].push(item[k])
                  }
                }
                for (let c = 0; c < group.length; c++) {
                  chartInstance.xAxis.data = dataObj[group[c]]
                }
                for (let n = 0; n < expressions.length; n++) {
                  if (group.indexOf(expressions[n]) < 0) {
                    chartInstance.data[expressions[n]] = dataObj[expressions[n]]
                  }
                }
                this.chart_options[reportId] = chartInstance
                resolve()
              })
          }
        )
      }
    },
    mounted: function () {
      axios.get(inter.report).then(
        (res) => {
          this.list = res.data.data
          this.chart_options = {}
          let array = []
          for (let k in this.list) {
            let instance = this.list[k]
            let dataOptions = JSON.parse(instance['data_options'])
            let reportInfo = JSON.parse(instance['report_info'])
            array.push(this.preview(instance['report_id'], reportInfo, dataOptions))
          }
          Promise.all(array).then(
            () => {
              this.ready = true
            }
          )
        }
      )
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  #container{
    display: flex;
    border: 15px;
    flex-wrap: wrap;
  }
  .item{
    margin: 10px;
  }
  .chartArea{
    height: 200px;
  }
</style>
