<template>
  <div id="chartForm">
    <div :hidden="isShow" >
      <h1>图表配置</h1>
      <Form :label-width="120">
        <Col span="12">
        <Form-item label="图表标题">
          <Input type="text" v-model="chart_instance.title" placeholder="请输入图表标题"></Input>
        </Form-item>
        <Form-item label="横轴名称">
          <Input type="text" v-model="chart_instance.xAxis.name" placeholder="请输入横轴名称"></Input>
        </Form-item>
        </Col>
        <Col span="12">
        <Form-item label="图表类型">
          <Select v-model="chart_instance.type" placeholder="请选择图表类型">
            <Option value="line">折线图</Option>
            <Option value="bar">柱状/条形图</Option>
          </Select>
        </Form-item>
        <Form-item label="纵轴名称">
          <Input type="text" v-model="chart_instance.yAxis.name" placeholder="请输入纵轴名称"></Input>
        </Form-item>
        <Form-item>
          <Button type="info" @click="handlePreview">预览</Button>
        </Form-item>
        </Col>
      </Form>
    </div>
    <Col span="24">
      <div id="chartArea">
        <chart :chart_options="chart_options"></chart>
      </div>
    </Col>
  </div>
</template>

<script>
  import axios from 'axios'
  import inter from '../utils/interface'
  import Chart from './Chart.vue'

  export default {
    data: () => {
      return {
        chart_instance: {
          title: '',
          xAxis: {
            name: '',
            type: 'category',
            data: ''
          },
          yAxis: {
            name: '',
            type: 'value',
            data: ''
          },
          type: ''
        },
        chart_options: null
      }
    },
    props: ['isShow', 'dataOption'],
    components: {
      'chart': Chart
    },
    methods: {
      handlePreview: function () {
        axios.get(inter.chartinstance + '?chart_info=' + JSON.stringify(this.dataOption))
          .then((res) => {
            if (res.data) {
              let head = ''
              let body = ''
              let max = (res.data.length > 10) ? 10 : res.data.length
              for (let count = 0; count < max; count++) {
                if (count === 0) head += res.data[count].keys().join(' | ') + '<br/>'
                body += res.data[count].values().join(' | ') + '<br/>'
              }
              this.result = head + body
            } else {
              this.result = '查询有误'
            }
          })
      }
    }
  }
</script>

<style scoped>
  #chartForm{
    display: flex;
    flex-direction: column;
  }
h1{
  text-align: center;
  margin-bottom: 0.5em;
}
  #chartArea{
    height: 300px;
  }
</style>
