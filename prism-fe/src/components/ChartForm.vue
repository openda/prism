<template>
  <div id="chartForm">
    <div :hidden="!isShow" >
      <h1>图表配置</h1>
      <Form :label-width="120">
        <Col span="12">
        <Form-item label="图表标题">
          <Input type="text" v-model="chart_instance.title" placeholder="请输入图表标题"></Input>
        </Form-item>
        <Form-item label="横轴名称">
          <Input type="text" v-model="chart_instance.xAxis.name" placeholder="请输入横轴名称"></Input>
        </Form-item>
        <Form-item label="报表简介">
          <Input type="text" v-model="chart_instance.brief" placeholder="请输入报表简介"></Input>
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
          <Button type="primary" @click="handleSave">保存报表</Button>
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
            type: 'category'
          },
          yAxis: {
            name: '',
            type: 'value'
          },
          type: '',
          data: {},
          brief: '无'
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
        if (this.chart_instance.type === '') {
          return
        }
        axios.get(inter.chartinstance + '?chart_info=' + JSON.stringify(this.dataOption))
          .then((res) => {
            let data = res.data.data
            let expressions = this.dataOption.expressions
            let group = this.dataOption.group
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
              this.chart_instance.xAxis.data = dataObj[group[c]]
            }
            for (let n = 0; n < expressions.length; n++) {
              if (group.indexOf(expressions[n]) < 0) {
                this.chart_instance.data[expressions[n]] = dataObj[expressions[n]]
              }
            }
            this.chart_options = this.chart_instance
          })
      },
      handleSave: function () {
        let instance = JSON.parse(JSON.stringify(this.chart_instance))
        delete instance['xAxis']['data']
        delete instance['yAxis']['data']
        let search = '?' + 'db_link_id=' + this.dataOption.dblink_id + '&chart_type=histogram' + '&chart_info=' + JSON.stringify(instance) + '&report_brief=' + this.chart_instance.brief + '&data_options=' + JSON.stringify(this.dataOption)
        axios.put(inter.report + search).then(
          (res) => {
            if (res.data.code === 0) {
              if (res.data.msg === 'success') {
                alert('保存成功！')
              } else {
                alert(res.data.msg)
              }
              window.location.reload()
            }
          }
        )
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
